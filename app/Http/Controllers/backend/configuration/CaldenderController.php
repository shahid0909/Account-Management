<?php

namespace App\Http\Controllers\backend\configuration;

use App\Http\Controllers\Controller;
use App\Models\GL\GlAccMaster;
use App\Models\GL\LCalenderMaster;
use App\Models\l_category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Yajra\DataTables\Facades\DataTables;

class CaldenderController extends Controller
{
    public function index ()
    {

        return view('backend.configuration.calender');
    }

    public function datatable()
    {

        $data = LCalenderMaster::with('fiscalPeriod')->orderby('id','desc')->get();  // Fetch the data

        return DataTables::of($data)
            ->editColumn('fiscalPeriod', function ($query) {
                return $query->fiscalPeriod->fiscal_period_nm;
            })
            ->editColumn('status', function ($query) {

                switch ($query->calender_status) {
                    case 'O':
                        return '<span class="badge bg-success  font-size-14">Open</span>';

                    case 'I':
                        return '<span class="badge bg-secondary  font-size-14">Inactive</span>';

                    case 'C':
                        return '<span class="badge bg-danger font-size-14">Close</span>';

                    default:
                        return '<span class="badge bg-dark  font-size-14">Unknown</span>';
                }
            })

            ->editColumn('action', function ($query) {
                $editButton = '<a href="#" class="edit-btn" data-id="' . $query->id . '">
        <button class="btn btn-info btn-sm rounded">Setting</button>
    </a>';

                return $editButton;
            })

            ->addIndexColumn()
            ->rawColumns(['status','action'])
            ->make(true);
    }
}
