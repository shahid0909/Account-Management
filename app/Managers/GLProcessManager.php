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
use App\Models\GL\GlAccMaster;
use App\Models\GL\LCalenderDetails;

use App\Models\GL\LCalenderMaster;

use Illuminate\Support\Facades\DB;


class GLProcessManager implements GLProcessContract

{

    public function journalVoucherEntry($request)

    {
        $master = $this->glTransMake($request);

        dd($master,$request);

        $data = LCalenderMaster::where('calender_status', 'O')->get();

        return $data;

    }

    public  function glTransMake($request){
        dd('dasdasd',$request);
    }





}

