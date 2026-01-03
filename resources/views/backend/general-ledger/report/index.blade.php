@extends('backend.layout.master')

@section('content')
    <div class="px-3">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="py-3 py-lg-4">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="page-title mb-0">Report</h4>
                    </div>
                    <div class="col-lg-6">
                        <ol class="breadcrumb m-0 float-end d-none d-lg-flex">
                            <li class="breadcrumb-item">General Ledger</li>
                            <li class="breadcrumb-item active">Report</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                          <div class="row mt-4">
                                <div class="col-md-6">
                                    <label>Report Name</label>
                                    <select class="form-control" id="gl_report">
                                        <option value="">Select</option>
                                        <option value="1">Gl01.Chart of Account</option>
                                    </select>
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

  </script>

@endsection

