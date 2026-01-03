<?php

/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */


namespace App\Managers;


use App\Contracts\Common\LookupContract;

use App\Models\Authorize_process;
use App\Models\GL\GL_trans_master;
use App\Models\GL\GlAccMaster;
use App\Models\GL\LCalenderDetails;

use App\Models\GL\LCalenderMaster;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LookupManager implements LookupContract

{

    public function getCurrentFinancialYear()

    {

        $data = LCalenderMaster::where('calender_status', 'O')->get();

        return $data;

    }


    public function findPostingPeriod($year_id)

    {

        $data = LCalenderDetails::query()
            ->select([

                DB::raw('ROW_NUMBER() OVER (ORDER BY id DESC) AS sl_no'),

                DB::raw('id AS posting_period_id'),

                DB::raw('posting_period_display_name AS posting_period_name'),

                'posting_period_beg_date',

                'posting_period_end_date',

                DB::raw("

                CASE

                    WHEN DATE(posting_period_beg_date) = CURDATE()

                    THEN NOW()

                    ELSE posting_period_beg_date

                END AS current_posting_date

            "),

                'posting_period_status'

            ])
            ->where('calender_master_id', $year_id)
            ->whereIn('posting_period_status', ['O', 'S'])
            ->whereDate('posting_period_beg_date', '<=', now())
            ->get();


        return $data;

    }

    public function glGetAccountInfo($accId)
    {
        $data = GlAccMaster::join('gl_coa as b', 'b.id', '=', 'gl_acc_master.gl_coa_id')
            ->join('l_gl_type as t', 't.id', '=', 'b.gl_type_id')
            ->select(
                'b.gl_acc_id',
                'b.gl_acc_name',
                'b.gl_acc_code',
                'b.gl_type_id',
                't.gl_type_name',
                't.type_flag',
                'b.currency',

                // exchange_rate
                DB::raw("
                CASE
                    WHEN b.currency = 'BDT' THEN 1
                    ELSE 0
                END AS exchange_rate
            "),

                // account_balance_type
                DB::raw("
                CASE
                    WHEN gl_acc_master.current_balance_lcy < 0
                        THEN 'debit'
                    ELSE 'credit'
                END AS account_balance_type
            "),

                // ✅ account_balance (FIXED)
                DB::raw("
                CASE
                    WHEN b.gl_dr_cr_flag = 'D'
                        THEN (gl_acc_master.current_balance_lcy * -1)
                    WHEN b.gl_dr_cr_flag = 'C'
                        THEN gl_acc_master.current_balance_lcy
                    ELSE NULL
                END AS account_balance
            "),

                // authorize_balance
                DB::raw("
                CASE
                    WHEN b.gl_dr_cr_flag = 'D'
                        THEN (gl_acc_master.current_balance_lcy + gl_acc_master.unauth_dr_amount_lcy) * -1
                    WHEN b.gl_dr_cr_flag = 'C'
                        THEN gl_acc_master.current_balance_lcy - gl_acc_master.unauth_cr_amount_lcy
                    ELSE NULL
                END AS authorize_balance
            "),

                // authorize_balance_type
                DB::raw("
                CASE
                    WHEN (gl_acc_master.current_balance_lcy - gl_acc_master.unauth_cr_amount_lcy) < 0
                        THEN 'debit'
                    ELSE 'credit'
                END AS authorize_balance_type
            "),

                // contra_gl_acc_id
                DB::raw("NULL AS contra_gl_acc_id")
            )
            ->where('b.gl_acc_id', $accId)
            ->where('b.active_yn', 'Y')
            ->where('b.postable_yn', 'Y')
            ->first();   // single row

        return $data;
    }


    public function getAuthorizeDate($module)
    {

        $data = Authorize_process::where('auth_process.module_id', $module)
            ->where('auth_user_id', Auth::user()->id)
            ->where('approval_status', 'P')
            ->where('process_yn', 'Y')
            ->join('gl_trans_master as m', 'm.id', '=', 'auth_process.trans_master_id')
            ->join('l_calender_master as cm', 'cm.id', '=', 'm.fiscal_year_id')
            ->join('l_calender_details as cd', 'cd.id', '=', 'm.trans_period_id')
            ->select('auth_process.id as approval_id', 'm.id as trans_gl_id', 'cm.fiscal_year', 'cd.posting_period_display_name as posting_period',
                'm.trans_date', 'm.trans_batch_id', 'm.trans_date'
                , 'm.document_date', 'm.document_no', 'm.authorize_status')
            ->get();
        return $data;

    }

    public static function getWorkflowStep($approvalId, $module)
    {
        $findApproval = Authorize_process::with('authuser:id,name') // createuser na, authuser
        ->whereRaw('MD5(id) = ?', [$approvalId])
            ->firstOrFail();

        // সব approval steps + তাদের authuser
        $findSteps = Authorize_process::with('authuser:id,name')
            ->where('module_id', $findApproval->module_id)
            ->where('trans_master_id', $findApproval->trans_master_id)
            ->orderBy('auth_step', 'asc')
            ->get();

        // যে user transaction টা initiate করেছে
        $initiator = GL_trans_master::with('createuser:id,name')
            ->findOrFail($findApproval->trans_master_id);

        $initiatorName = optional($initiator->createuser)->name ?? 'Unknown';

        // Initiator step (সবসময় প্রথমে)
        $workflowSteps = collect([
            [
                'step_no' => 1,
                'title'   => 'Initiator',
                'status'  => 'completed',
                'user'    => $initiatorName,
            ]
        ]);

        // এবার approval steps গুলো process করি
        $workflowSteps = $workflowSteps->concat(
            $findSteps->map(function ($step, $index) use ($initiatorName) {
                // Status determine
                if ($step->approval_status === 'R') {
                    $status = 'rejected';
                } elseif ($step->approval_status === 'A') {
                    $status = 'completed';
                } elseif ($step->approval_status === 'P' && $step->process_yn === 'Y') {
                    $status = 'pending';
                } else {
                    $status = 'upcoming';
                }

                // User: প্রথম step হলে initiator এর নাম, বাকিগুলোতে authuser এর নাম
                $user = ($step->auth_step == 1)
                    ? $initiatorName
                    : optional($step->authuser)->name ?? 'System';

                return [
                    'step_no' => $index + 2, // initiator পরে 2,3,4...
                    'title'   => $step->step_name ?? 'Step ' . $step->auth_step,
                    'status'  => $status,
                    'user'    => $user,
                ];
            })
        );

         dd($workflowSteps->toArray(), $initiator, $findApproval, $findSteps);

        return $workflowSteps->toArray();
    }
    public function getTransDetilsData($approval_id, $module)
    {
        $findApproval = Authorize_process::whereRaw('MD5(id) = ?', [$approval_id])->first();
        $data = '';
//        $data = Authorize_process::where('auth_process.module_id', $module)
//            ->where('auth_user_id', Auth::user()->id)
//            ->where('approval_status', 'P')
//            ->where('process_yn', 'Y')
//            ->join('gl_trans_master as m', 'm.id', '=', 'auth_process.trans_master_id')
//            ->join('l_calender_master as cm', 'cm.id', '=', 'm.fiscal_year_id')
//            ->join('l_calender_details as cd', 'cd.id', '=', 'm.trans_period_id')
//            ->select('auth_process.id as approval_id', 'm.id as trans_gl_id', 'cm.fiscal_year', 'cd.posting_period_display_name as posting_period',
//                'm.trans_date', 'm.trans_batch_id', 'm.trans_date'
//                , 'm.document_date', 'm.document_no', 'm.authorize_status')
//            ->get();
        return $data;

    }


}

