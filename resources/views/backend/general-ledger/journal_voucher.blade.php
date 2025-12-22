@extends('backend.layout.master')
@section('content')

    <div class="px-3">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="py-3 py-lg-4">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="page-title mb-0">Journal Voucher</h4>
                    </div>
                    <div class="col-lg-6">
                        <ol class="breadcrumb m-0 float-end d-none d-lg-flex">
                            <li class="breadcrumb-item">General Ledger</li>
                            <li class="breadcrumb-item active">Journal Voucher</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Journal Voucher Form -->
            <div class="card">
                <div class="card-body">

                    <form>

                        <!-- ================= Journal Voucher ================= -->
                        <h5 class="text-primary mb-3">Journal Voucher</h5>

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label text-dark">Function Type <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-select">
                                            <option>Journal Voucher (GL)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label text-dark">Fiscal Year <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-select" name="fiscal_year" id="fiscal_year">
                                            <option value="">Select</option>
                                            @foreach($fiscalYear as $val)
                                                <option value="{{$val->id}}">{{$val->fiscal_year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label text-dark">Posting Period <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="form-select">
                                            <option><Select></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label text-dark">
                                        Posting Date <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text"
                                                   id="posting_date"
                                                   name="posting_date"
                                                   class="form-control datepicker"
                                                   placeholder="DD-MM-YYYY"
                                                   autocomplete="off">
                                            <span class="input-group-text " id="datepicker-btn"
                                                  style="cursor:pointer;">
                                                <i class="mdi mdi-calendar text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label text-dark">Document Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control ps-5 datepicker"
                                               placeholder="Select date">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label text-dark">Document No</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<Auto>" disabled>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-4 col-form-label text-dark">Document Reference</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-dark">Narration <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>

                        <!-- ================= Transaction Detail ================= -->
                        <h5 class="text-primary mt-4 mb-3">Transaction Detail</h5>

                        <div class="border p-3 rounded">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Account ID <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control">
                                        <button class="btn btn-primary">Search</button>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Type</label>
                                    <input type="text" class="form-control" disabled>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Account Balance</label>
                                    <input type="text" class="form-control" disabled>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Authorized Balance</label>
                                    <input type="text" class="form-control" disabled>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Dr / Cr <span class="text-danger">*</span></label>
                                    <select class="form-select">
                                        <option>Debit</option>
                                        <option>Credit</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Amount CCY <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Amount LCY <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control">
                                </div>

                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-info mt-2">+ ADD</button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
@section('script')

@endsection
