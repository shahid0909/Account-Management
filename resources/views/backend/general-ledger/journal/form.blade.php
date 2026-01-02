<div class="card">
    <div class="card-body">
        <form id="journalVoucherForm">
            <!-- ================= Journal Voucher ================= -->
            <h5 class="text-primary mb-3">Journal Voucher</h5>
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Fiscal Year <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select" name="fiscal_year" id="fiscal_year" required>
                                @foreach($fiscalYear as $val)
                                    <option value="{{$val->id}}">{{$val->fiscal_year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Document Date</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text"
                                       id="document_date"
                                       name="document_date"
                                       class="form-control datepicker"
                                       placeholder="DD-MM-YYYY"
                                       autocomplete="off">
                                <span class="input-group-text" style="cursor:pointer;">
                                    <i class="mdi mdi-calendar text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Document Reference</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="document_reference">
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Posting Period <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select" name="posting_period" id="posting_period" required>
                                <option value="">&lt;Select&gt;</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Posting Date <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text"
                                       id="posting_date"
                                       name="posting_date"
                                       class="form-control datepicker"
                                       placeholder="DD-MM-YYYY"
                                       autocomplete="off" required>
                                <span class="input-group-text" style="cursor:pointer;">
                                    <i class="mdi mdi-calendar text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-dark">Document No</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="<Auto>" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-dark">Narration <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="3" name="narration" id="narration" required></textarea>
            </div>

            <!-- ================= Transaction Detail ================= -->
            <h5 class="text-primary mt-4 mb-3">Transaction Detail</h5>
            <div class="border p-3 rounded mb-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Account ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="acc_id" id="acc_id"
                                   data-flag=""
                                   onfocusout="addZerosInAccountId(this)"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '');" >
                            <button type="button" id="search_gl_acc" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Account Name</label>
                        <input type="text" class="form-control" name="acc_name" id="acc_name" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Account Balance</label>
                        <input type="text" class="form-control" name="acc_balance" id="acc_balance" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Authorized Balance</label>
                        <input type="text" class="form-control" name="acc_auth_balance" id="acc_auth_balance" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <input type="text" class="form-control" name="acc_type" id="acc_type" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dr / Cr <span class="text-danger">*</span></label>
                        <select class="form-select" name="dr_cr" id="dr_cr" required>
                            <option value="D">Debit</option>
                            <option value="C">Credit</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Amount CCY <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="amount_ccy" id="amount_ccy" >
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Amount LCY <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" readonly name="amount_lcy" id="amount_lcy">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="button" id="addRow" class="btn btn-info mt-2">+ ADD</button>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="border p-3 rounded">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="glTable">
                        <thead>
                        <tr>
                            <th>GL Account ID</th>
                            <th>GL Account Name</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- rows added dynamically -->
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th id="totalDebit">0</th>
                            <th id="totalCredit">0</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary me-2" >Submit</button>
                <button type="button" id="resetForm" class="btn btn-danger">Reset</button>
            </div>
        </form>
    </div>
</div>
