<?php

namespace App\Http\Controllers\backend;

use App\Contracts\Common\LookupContract;

use App\Helpers\HelperClass;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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

}
