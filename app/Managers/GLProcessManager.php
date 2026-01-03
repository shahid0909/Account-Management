<?php

/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */


namespace App\Managers;


use App\Contracts\Common\LookupContract;

use App\Contracts\GLProcessContract;
use App\Enum\common;
use App\Exceptions\MakeException;
use App\Models\Auth_key;
use App\Models\Authorize_process;
use App\Models\Authorize_user;
use App\Models\GL\GL_trans_details;
use App\Models\GL\GL_trans_master;
use App\Models\GL\GlAccMaster;
use App\Models\GL\LCalenderDetails;

use App\Models\GL\LCalenderMaster;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GLProcessManager implements GLProcessContract

{
    public function journalVoucherEntry($request)
    {
        try {
            DB::beginTransaction();

            $param = [
                'p_fiscal_year' => $request->fiscal_year,
                'p_posting_period' => $request->posting_period,
                'p_document_date' => $request->document_date,
                'p_posting_date' => $request->posting_date,
                'p_document_reference' => $request->document_reference,
                'p_narration' => $request->narration,
            ];

            $master = $this->glTransMasterMake($param);

            $details = [
                'transactions' => $request->transactions,
                'master_id' => $master['id'],
            ];
            $details = $this->glTransDetailMake($details);
            $authorize = [
                'key' => 'JOURNAL_VOUCHER_AUTHORIZE',
                'master_id' => $master['id'],
            ];
            $auth = $this->glTransAuth($authorize);


            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction save successfully',
                'data' => $master
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function glTransMasterMake($params)
    {
        try {
            // Validation
            if (empty($params['p_fiscal_year'])) {
                throw new MakeException('Please select fiscal year');
            }

            if (empty($params['p_document_reference'])) {
                throw new MakeException('Please provide document reference');
            }

            // Generate today's batch and document numbers
            $today = now()->format('dmY');

            // ===== Batch ID =====
            $lastBatch = GL_trans_master::where('trans_batch_id', 'like', $today . '%')
                ->lockForUpdate()
                ->orderBy('trans_batch_id', 'desc')
                ->value('trans_batch_id');

            $batchNo = $lastBatch ? ((int) substr($lastBatch, -3) + 1) : 1;
            $transBatchId = $today . str_pad($batchNo, 3, '0', STR_PAD_LEFT);

            // ===== Document No =====
            $lastDoc = GL_trans_master::where('document_no', 'like', 'JV-' . $today . '%')
                ->lockForUpdate()
                ->orderBy('document_no', 'desc')
                ->value('document_no');

            $docNo = $lastDoc ? ((int) substr($lastDoc, -3) + 1) : 1;
            $documentNo = 'JV-' . $today . '-' . str_pad($docNo, 3, '0', STR_PAD_LEFT);

            // Save the master record
            $input = new GL_trans_master();
            $input->module_id = Common::GL_MODULE;
            $input->function_id = Common::GL_FUNCTION;
            $input->fiscal_year_id = $params['p_fiscal_year'];
            $input->trans_period_id = $params['p_posting_period'];
            $input->trans_batch_id = $transBatchId;
            $input->trans_date = !empty($params['p_posting_date']) ? date('Y-m-d', strtotime($params['p_posting_date'])) : null;
            $input->document_date = !empty($params['p_document_date']) ? date('Y-m-d', strtotime($params['p_document_date'])) : null;
            $input->document_no = $documentNo;
            $input->document_ref = $params['p_document_reference'];
            $input->narration = $params['p_narration'] ?? null;
            $input->created_by = Auth::id();

            if (!$input->save()) {
                throw new MakeException('Failed to save journal voucher master');
            }

            return $input;

        } catch (MakeException $e) {
            // Re-throw custom exceptions to be caught in the controller
            throw $e;

        } catch (\Exception $e) {
            // Catch any other unexpected exception
            throw new MakeException('Unexpected error: ' . $e->getMessage());
        }
    }

    public function glTransDetailMake($details)
    {
        try {
            $insertedIds = [];

            if(empty($details['transactions']) || empty($details['master_id'])){
                return [
                    'status' => 99,
                    'message' => 'No transactions provided or master ID missing',
                    'ids' => []
                ];
            }

            foreach ($details['transactions'] as $val) {
                $input = new GL_trans_details();
                $input->trans_master_id = $details['master_id'];
                $input->gl_acc_id = $val['acc_id'];
                $input->dr_cr = $val['dr_cr'];
                $input->amount_ccy = $val['amount_ccy'];
                $input->amount_lcy = $val['amount_lcy'];
                $input->created_by = Auth::id();
                $input->save();

                $insertedIds[] = $input->id;
            }

            return [
                'status' => 1,
                'message' => 'Transaction details saved successfully',
                'ids' => $insertedIds
            ];

        } catch (\Exception $e) {
            return [
                'status' => 99,
                'message' => 'Something went wrong while saving details: ' . $e->getMessage(),
                'ids' => []
            ];
        }
    }

    public function glTransAuth($authorize)
    {
        try {
            // Find the auth key
            $findKey = Auth_key::where('auth_key', $authorize['key'])->first();

            if (!$findKey) {
                throw new MakeException('Invalid authorization key');
            }

            // Get authorization users
            $findAuthUsers = Authorize_user::where('auth_key_id', $findKey->id)
                ->orderBy('auth_step', 'asc')
                ->get();

            if ($findAuthUsers->isEmpty()) {
                throw new MakeException('No approval user found for this key');
            }

            $insertedIds = [];

            foreach ($findAuthUsers as $val) {
                $input = new Authorize_process();
                $input->trans_master_id = $authorize['master_id'];
                $input->module_id = Common::GL_MODULE;
                $input->auth_key_id = $val->auth_key_id;
                $input->auth_user_id = $val->auth_user_id;
                $input->auth_step = $val->auth_step;
                $input->approval_status = 'P';
                $input->process_yn = $val->auth_step == 1 ? 'Y' : 'N';

                $input->save();

                $insertedIds[] = $input->id;
            }

            return [
                'status' => 1,
                'message' => 'Workflow saved successfully',
                'ids' => $insertedIds
            ];

        } catch (MakeException $e) {
            // Re-throw custom exceptions to controller
            throw $e;

        } catch (\Exception $e) {
            // Wrap unexpected exceptions
            throw new MakeException('Something went wrong while saving workflow: ' . $e->getMessage());
        }
    }




}

