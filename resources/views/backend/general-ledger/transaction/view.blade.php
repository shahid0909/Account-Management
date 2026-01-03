@extends('backend.layout.master')

@section('content')
    <div class="px-3">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="py-3 py-lg-4">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="page-title mb-0">Transaction Details</h4>
                    </div>
                    <div class="col-lg-6">
                        <ol class="breadcrumb m-0 float-end d-none d-lg-flex">
                            <li class="breadcrumb-item">General Ledger</li>
                            <li class="breadcrumb-item active">Transaction Details</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- ================= Workflow Steps ================= -->
                           @include('backend.common.workflow_step')
                            <!-- ================= End Workflow ================= -->

                            <!-- ================= Transaction Info (Example) ================= -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th width="40%">Document No</th>
                                            <td>JV-02012026-001</td>
                                        </tr>
                                        <tr>
                                            <th>Batch No</th>
                                            <td>02012026001</td>
                                        </tr>
                                        <tr>
                                            <th>Posting Date</th>
                                            <td>02-01-2026</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th width="40%">Status</th>
                                            <td><span class="badge bg-primary">Approved</span></td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>Admin</td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td>Journal Voucher Entry</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- ================= End Transaction Info ================= -->

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection




@section('script')
    <script>
        $('#toggleBtn, #cancelBtn').on('click', function () {


            $('#calendarForm').slideToggle(300);


            $('#iconPlus').toggleClass('d-none');

            $('#iconCheck').toggleClass('d-none');


            let text = $('#btnText').text();

            $('#btnText').text(text === 'Add New Setup' ? 'Close Form' : 'Add New Setup');


            $('#toggleBtn').toggleClass('btn-primary btn-success');

        });

        let table = $('#calender-table').DataTable({ // Change `.dataTable()` to `.DataTable()
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: {
                url: "{{ route('transaction.datatable') }}",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: "document_no"},
                {data: "fiscal_year"},
                {data: "posting_period"},
                {data: "posting_date"},
                {data: "approval_status"},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],


        });

    </script>

@endsection

