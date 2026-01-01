<?php

namespace App\Http\Controllers\backend;

use App\Contracts\Common\LookupContract;

use App\Http\Controllers\Controller;
use App\Models\GL\LGlType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class JournalVoucherController extends Controller
{
    private $lookupManager;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;

    }


    public function index ()
    {
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $acc_type = LGlType::all();


        return view('backend.general-ledger.journal.index',compact('fiscalYear','acc_type'));
    }

public function store(Request $request){
        dd($request);
}

}
