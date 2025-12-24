<div class="card">
    <div class="card-body">
        <form>
            <!-- ================= Journal Voucher ================= -->
            <h5 class="text-primary mb-3">Journal Voucher</h5>
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Function Type <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select">
                                <option>Journal Voucher (GL)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Fiscal Year <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select" name="fiscal_year" id="fiscal_year">
                                @foreach($fiscalYear as $val)
                                    <option value="{{$val->id}}">{{$val->fiscal_year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Posting Period <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select" name="posting_period" id="posting_period">
                                <option value="">&lt;Select&gt;</option>
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
                                       class="form-control "
                                       placeholder="DD-MM-YYYY"
                                       autocomplete="off">
                                <span class="input-group-text" id="datepicker-btn" style="cursor:pointer;">
                                                <i class="mdi mdi-calendar text-primary"></i>
                                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Document Date</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control ps-5 datepicker" placeholder="Select date">
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
                            <button type="button"  id="search_gl_acc" class="btn btn-primary"><i class="mdi mdi-search-web"></i>Search</button>
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
