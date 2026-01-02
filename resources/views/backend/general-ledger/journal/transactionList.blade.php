@extends('backend.layout.master')

@section('content')
    <div class="px-3">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="py-3 py-lg-4">
                <div class="row">
                    <div class="col-lg-6">
                                                <h4 class="page-title mb-0">Transaction List</h4>

                    </div>
                    <div class="col-lg-6">
                        <ol class="breadcrumb m-0 float-end d-none d-lg-flex">
                            <li class="breadcrumb-item">General Ledger</li>
                            <li class="breadcrumb-item active">transaction List</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- Calendar Setup -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

{{--                            <!-- Header + Button -->--}}
{{--                            <div class="d-flex justify-content-between align-items-center mb-3">--}}
{{--                                <h4 class="header-title mb-0">Calendar Setup</h4>--}}
{{--                                <button id="toggleBtn" class="btn btn-primary">--}}
{{--                                    <i id="iconPlus" class="mdi mdi-plus"></i>--}}
{{--                                    <i id="iconCheck" class="mdi mdi-close d-none"></i>--}}
{{--                                    <span id="btnText">Add New Setup</span>--}}
{{--                                </button>--}}

{{--                            </div>--}}


                            <!-- Data Table -->

                            <table id="calender-table" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Document No</th>
                                    <th>Fiscal Year</th>
                                    <th>Posting Period</th>
                                    <th>Posting Date</th>
                                    <th>Approval Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{-- Dynamic Data --}}
                                </tbody>
                            </table>
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
                url: "{{ route('journal-voucher.datatable') }}",
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

