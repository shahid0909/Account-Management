<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 09/12/2021
 * Time: 2:27 PM
 */

namespace App\Managers;


use App\Contracts\Common\LookupContract;
use App\Models\GL\LCalenderMaster;
use Illuminate\Support\Facades\DB;

class LookupManager implements LookupContract
{
    public function getCurrentFinancialYear()
    {
        $data = LCalenderMaster::where('calender_status', 'O')->get();
        return $data;
    }
}
