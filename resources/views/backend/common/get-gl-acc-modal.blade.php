<div class="modal fade" id="glAccModal" tabindex="-1" role="dialog" aria-labelledby="glAccModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-soft-info">
                <h4 class="modal-title  px-2" id="glAccModal">Account Search
                </h4>
                <button type="button" class="btn-close  py-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body  px-5 py-4">

                <!-- Search Area -->
                <div class="row g-3 align-items-end mb-3">

                    <!-- Account Type -->
                    <div class="col-md-4">
                        <label class="form-label">Account Type</label>
                        <select class="form-select" name="acc_type_id" id="acc_type_id">
                            <option value="">Select</option>
                          @foreach($acc_type as $val)
                              <option value="{{$val->id}}">{{$val->gl_type_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Account Name or Code -->
                    <div class="col-md-4">
                        <label class="form-label">Account</label>
                        <input type="text"
                               name="acc_name_or_id"
                               id="acc_name_or_id"
                               class="form-control"
                               placeholder="Account name or code">
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-4 text-end">
                        <button type="button" id="btnSearch" class="btn btn-primary me-2">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <button type="reset" id="btnReset" class="btn btn-secondary">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                    </div>

                </div>

                <hr>

                <!-- Result Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle"
                           id="account_list">
                        <thead class="table-light">
                        <tr>
                            <th>Account ID</th>
                            <th>Account Name</th>
                            <th>Account Code</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>


            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
