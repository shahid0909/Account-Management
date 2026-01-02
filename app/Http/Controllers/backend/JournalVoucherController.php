<?php

namespace App\Http\Controllers\backend;

use App\Contracts\Common\LookupContract;

use App\Contracts\GLProcessContract;
use App\Http\Controllers\Controller;
use App\Models\GL\GL_trans_master;
use App\Models\GL\LGlType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Yajra\DataTables\Facades\DataTables;

class JournalVoucherController extends Controller
{
    private $lookupManager;
    private $glProcessManager;

    public function __construct(LookupContract $lookupManager, GLProcessContract $glProcessManager)
    {
        $this->lookupManager = $lookupManager;
        $this->glProcessManager = $glProcessManager;

    }


    public function index()
    {
        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $acc_type = LGlType::all();


        return view('backend.general-ledger.journal.index', compact('fiscalYear', 'acc_type'));
    }

    public function store(Request $request)
    {
        $val = $this->glProcessManager->journalVoucherEntry($request);

        return $val;
    }
    public function listIndex()
    {

        return view('backend.general-ledger.journal.transactionList');
    }

    public function datatable()
    {

        $data = GL_trans_master::with('fiscal_year','posting_period')->orderby('id','desc')->get();  // Fetch the data

        return DataTables::of($data)
            ->editColumn('fiscal_year', function ($query) {
                return $query->fiscal_year->fiscal_year;
            })
            ->editColumn('posting_period', function ($query) {
                return $query->posting_period->posting_period_display_name;
            })
            ->editColumn('posting_date', function ($query) {
                return date('d-M-Y', strtotime($query->trans_date)) ?? null;
            })
            ->editColumn('approval_status', function ($query) {

                switch ($query->authorize_status) {
                    case 'P':
                        return '<span class="badge bg-primary  font-size-14">Pending</span>';

                    case 'A':
                        return '<span class="badge bg-success  font-size-14">Approve</span>';

                    case 'R':
                        return '<span class="badge bg-danger font-size-14">Reject</span>';

                    default:
                        return '<span class="badge bg-dark  font-size-14">Unknown</span>';
                }
            })

            ->editColumn('action', function ($query) {
                $editButton = '<a href="#" class="edit-btn" data-id="' . $query->id . '">
        <button class="btn btn-info btn-sm rounded">View</button>
    </a>';

                return $editButton;
            })

            ->addIndexColumn()
            ->rawColumns(['approval_status','action'])
            ->make(true);
    }

}
