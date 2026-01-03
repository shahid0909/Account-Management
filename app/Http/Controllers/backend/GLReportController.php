<?php

namespace App\Http\Controllers\backend;

use App\Contracts\Common\LookupContract;

use App\Contracts\GLProcessContract;
use App\Enum\common;
use App\Http\Controllers\Controller;
use App\Models\Authorize_process;
use App\Models\GL\GL_trans_master;
use App\Models\GL\LGlType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GLReportController extends Controller
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

        return view('backend.general-ledger.report.index');
    }


    public function datatable()
    {

         $data = $this->lookupManager->getAuthorizeDate(common::GL_MODULE);

        return DataTables::of($data)
            ->editColumn('fiscal_year', function ($query) {
                return $query->fiscal_year;
            })
            ->editColumn('posting_period', function ($query) {
                return $query->posting_period;
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

                $url = route('transaction.details', [
                    md5($query->approval_id),
                    md5(common::GL_MODULE),
                ]);

                return '
        <a href="'.$url.'" class="edit-btn" data-id="'.$query->trans_master_id.'">
            <button class="btn btn-info btn-sm rounded">View</button>
        </a>
    ';
            })

            ->addIndexColumn()
            ->rawColumns(['approval_status','action'])
            ->make(true);
    }

    public function details($approvalId, $moduleId){

        $data = $this->lookupManager->getTransDetilsData($approvalId,$moduleId);

       return view('backend.general-ledger.transaction.view',compact('approvalId','moduleId'));
    }

}
