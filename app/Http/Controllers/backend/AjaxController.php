<?php

namespace App\Http\Controllers\backend;

use App\Contracts\Common\LookupContract;

use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use App\Models\GL\GLCoa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    private $lookupManager;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;

    }


    public function getCurrentPostingPeriod(Request $request)
    {

        $periods = $this->lookupManager->findPostingPeriod($request->get("calenderId"));
        $preSelected = $request->post('preselected', null);
        $periodHtml = '<option value="">&lt;Select&gt;</option>';

        if (isset($periods)) {
            foreach ($periods as $period) {

                $periodHtml .= "<option " . (isset($preSelected) ? (($period->posting_period_id == $preSelected) ? 'selected' : '') : '' /*(($period->posting_period_status == 'O') ? 'selected' : '')*/) . "
                                        data-currentdate=" . HelperClass::dateConvert($period->current_posting_date) . "
                                        data-postingname=" . $period->posting_period_name . "
                                        data-mindate='" . HelperClass::dateConvert($period->posting_period_beg_date) . "'
                                        data-maxdate='" . HelperClass::dateConvert($period->posting_period_end_date) . "'
                                         value='" . $period->posting_period_id . "'>" . $period->posting_period_name . "</option>";
            }
        }
        return response()->json(['period' => $periodHtml]);
    }


    public function glAccDatatable(Request $request)
    {

        $value = GLCoa::query()
            ->where('active_yn', 'Y')
            ->where('postable_yn', 'Y')
            ->when($request->glType, function ($q) use ($request) {
                $q->where('gl_type_id', $request->glType);
            })
            ->when($request->accNameCode, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('gl_acc_name', 'like', '%' . $request->accNameCode . '%')
                        ->orWhere('gl_acc_code', 'like', '%' . $request->accNameCode . '%')
                        ->orWhere('gl_acc_id',   'like', '%' . $request->accNameCode . '%');
                });
            })
            ->get();

        return datatables()->of($value)

            ->editColumn('gl_acc_code', function ($data) {
                return $data->gl_acc_code;
            })
            ->editColumn('gl_acc_id', function ($data) {
                return $data->gl_acc_id;
            })
            ->editColumn('gl_acc_name', function ($data) {
                return $data->gl_acc_name;
            })
            ->editColumn('action', function ($data)  {
                return "<button class='btn btn-sm btn-primary' onclick='getAccountDetail($data->gl_acc_id)' >Select</button>";
            })
            ->addIndexColumn()
            ->make(true);
    }



    public function glAccDetails(Request $request){

        dd($request);
        $value = GLCoa::where('active_yn', 'Y')->where('postable_yn','Y')->get();

        dd($value,$request);
//
//
//        SELECT A.*
//		FROM FAS.FAS_GL_COA       a
//		WHERE a.INACTIVE_YN = FAS.getConst('no')
//        AND	 A.POSTABLE_YN = FAS.getConst('YES')
//        AND (a.COST_CENTER_ID IS NULL OR a.COST_CENTER_ID = @p_cost_center_id)
//		and (a.SERVICE_CENTER_ID IS NULL OR a.SERVICE_CENTER_ID=@P_service_center_id)
//		and (@p_gl_type_id IS NULL OR a.GL_TYPE_ID = @p_gl_type_id)
//		AND (@p_gl_acc_name IS NULL OR upper(a.gl_acc_name) LIKE '%' + upper(@p_gl_acc_name) + '%')
//		AND ISNULL(a.GL_SUB_CATEGORY_ID,0) <> FAS.getConst('gl_sub_cat_inter_office_liabilities_ac');

        $accountId = $request->post('accId');
        $accountInfo = $this->lookupManager->findPostingPeriod($accountId);
        $accountInfo = DB::selectOne("select * from FAS.glGetAccountInfo(:p_gl_acc_id)", ['p_gl_acc_id' => $accountId]);


        $partySubLedgers = '';
        $partyInfo = null;
        if (isset($accountInfo)) {
            $ledgers = DB::select("select * from FAS.glGetSubsidiaryLedger (:p_cost_center_id,:p_module_id,:p_gl_acc_id)", ['p_cost_center_id' => $costCenter, 'p_module_id' => $accountInfo->module_id, 'p_gl_acc_id' => $accountId]);

            foreach ($ledgers as $ledger) {
                $partySubLedgers .= '<option value="' . $ledger->gl_subsidiary_id . '" data-partyparams="' . $ledger->vendor_type_id . '#' . $ledger->vendor_category_id . '#' . $ledger->gl_subsidiary_type . '">' . $ledger->gl_subsidiary_name . '</option>';
                if ($accountId == GlAccountID::TDS_Tax_Deduction_At_Source_Payable || $accountId == GlAccountID::VDS_Vat_Deduction_At_Source_Payable) {
                    $partyInfo = DB::selectOne("select * from  FAS.glGetPartyAccountInfo (:p_gl_subsidiary_id,:p_vendor_id,:p_customer_id, :p_cost_center_id)",
                        ['p_gl_subsidiary_id' => $ledger->gl_subsidiary_id, 'p_vendor_id' => $ledger->vendor_id, 'p_customer_id' => $ledger->customer_id, 'p_cost_center_id' => $costCenter]);
                }
            }
        }
        return response()->json(['account_info' => $accountInfo, 'sub_ledgers' => $partySubLedgers, 'party_info' => $partyInfo]);
    }

}
