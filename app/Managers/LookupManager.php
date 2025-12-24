<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers;


use App\Contracts\Common\LookupContract;
use App\Models\GL\LCalenderDetails;
use App\Models\GL\LCalenderMaster;
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

}
