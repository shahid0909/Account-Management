<?php

/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */


namespace App\Managers;


use App\Contracts\Common\LookupContract;

use App\Models\GL\GlAccMaster;
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



}

