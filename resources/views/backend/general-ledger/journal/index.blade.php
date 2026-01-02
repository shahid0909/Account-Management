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
            @include('backend.general-ledger.journal.form')
        </div>
    </div>

    @include('backend.common.get-gl-acc-modal')
@endsection

@section('script')
    <script>

        // When Fiscal Year changes → load Posting Periods
        $("#fiscal_year").on('change', function () {
            let calenderId = $(this).val();
            if (!calenderId) {
                $("#posting_period").html('<option value="">&lt;Select&gt;</option>');
                $("#posting_period").trigger('change');
                return;
            }

            getPostingPeriod(
                calenderId,
                '{{ route("ajax.get-current-posting-period") }}',
                setPostingPeriod
            );
        });

        function setPostingPeriod(periodHtml) {
            $("#posting_period").html(periodHtml);
            $("#posting_period").trigger('change');
        }

        // When Posting Period changes → set date picker range and default date
        $("#posting_period").on('change', function () {
            let selected = $(this).find(':selected');
            let val = selected.val();

            // Always clear the date first
            postingDatePicker.clear();

            if (!val) {
                // No period selected → disable date picker range
                postingDatePicker.set({
                    minDate: null,
                    maxDate: null
                });
                return;
            }

            let minDate = selected.data('mindate');         // DD-MM-YYYY
            let maxDate = selected.data('maxdate');         // DD-MM-YYYY
            let currentDate = selected.data('currentdate'); // DD-MM-YYYY or undefined

            // Calculate today's date in DD-MM-YYYY format
            let today = new Date();
            let todayStr = flatpickr.formatDate(today, "d-m-Y");

            // Effective max date = earlier of period max or today
            let effectiveMaxDate = maxDate;
            if (maxDate) {
                let maxDateObj = new Date(maxDate.split('-').reverse().join('-'));
                if (maxDateObj > today) {
                    effectiveMaxDate = todayStr;
                }
            } else {
                effectiveMaxDate = todayStr;
            }

            // Set the allowed range
            postingDatePicker.set({
                minDate: minDate,
                maxDate: effectiveMaxDate
            });

            // Set default date: current_posting_date if exists, otherwise period start date
            let dateToSet = currentDate || minDate;
            if (dateToSet) {
                postingDatePicker.setDate(dateToSet, true);
            }
        });




        $('#glAccModal').on('shown.bs.modal', function () {

            if (!$.fn.DataTable.isDataTable('#account_list')) {

                accountTable = $('#account_list').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    ordering: true,
                    autoWidth: false,
                    pageLength: 5,
                    lengthMenu: [[5, 10, 25, 50,100], [5, 10, 25, 50,100]],
                    ajax: {
                        url: '{{ route('ajax.get-account-datatable') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: function (d) {
                            d.glType = $('#acc_type_id').val();
                            d.accNameCode = $('#acc_name_or_id').val();
                        }
                    },
                    columns: [
                        { data: 'gl_acc_id' },
                        { data: 'gl_acc_name' },
                        { data: 'gl_acc_code' },
                        { data: 'action', orderable: false, searchable: false }
                    ]
                });

            } else {
                accountTable.columns.adjust().draw();
            }

        });

        $("#acc_search_form").on('submit', function (e) {
            e.preventDefault();
            accountTable.draw();
        });

        $("#btnSearch").on('click', function () {
            accountTable.draw();
        });

        $("#btnReset").on('click', function () {
            $('#acc_type').val('');
            $('#acc_name_or_id').val('');
            accountTable.draw();
        });

        function getAccountDetail (accId) {

            var request = $.ajax({
                url: '{{route('ajax.get-account-details')}}',
                method: 'POST',
                data: {accId: accId},
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            request.done(function (d) {
                resetField(['#account_name', '#account_type', '#account_balance', '#authorized_balance', '#budget_head', '#currency', '#exchange_rate']);

                if ($.isEmptyObject(d.account_info)) {
                    $("#account_id").notify("Account id not found", "error");
                } else {
                    $("#account_id").val(d.account_info.gl_acc_id);
                    $("#account_id").data("flag", d.account_info.gl_type_flag);
                    $("#account_name").val(d.account_info.gl_acc_name);
                    $("#account_type").val(d.account_info.gl_type_name);
                    $("#account_balance").val(getCommaSeparatedValue(d.account_info.account_balance));
                    $("#account_balance_type").text(d.account_info.account_balance_type);
                    $("#authorized_balance").val(getCommaSeparatedValue(d.account_info.authorize_balance));
                    $("#authorized_balance_type").text(d.account_info.authorize_balance_type);

                    $("#budget_head").val(d.account_info.budget_head_line_name);
                    $("#currency").val(d.account_info.currency_code);
                    $("#exchange_rate").val(d.account_info.exchange_rate);
                    if (nullEmptyUndefinedChecked(d.account_info.cost_center_dept_name)) {
                        $("#department_cost_center").html('');
                    } else {
                        $("#department_cost_center").html('<option value="' + d.cost_center_dept_id + '">' + d.cost_center_dept_name + '</option>');
                    }
                    $("#module_id").val(d.account_info.module_id);

                    {{--if (!nullEmptyUndefinedChecked(d.account_info.module_id)) {--}}
                    {{--    if (d.account_info.module_id == '{{\App\Enums\Common\LGlInteModules::ACCOUNT_RECEIVABLE}}') {--}}
                    {{--        $(".receivableArea").removeClass('hidden');--}}
                    {{--        $("#ar_party_sub_ledger").html(d.sub_ledgers);--}}
                    {{--    } else if (d.account_info.module_id == '{{\App\Enums\Common\LGlInteModules::ACC_PAY_VENDOR}}') {--}}
                    {{--        $(".payableArea").removeClass('hidden');--}}
                    {{--        $("#ap_party_sub_ledger").html(d.sub_ledgers);--}}

                    {{--        if (!nullEmptyUndefinedChecked(d.party_info)) {--}}
                    {{--            $("#ap_vendor_id").val(d.party_info.party_id).addClass('make-readonly-bg').attr("tabindex", "-1");--}}
                    {{--            $("#ap_vendor_search").attr('disabled', 'disabled');--}}
                    {{--            $("#ap_vendor_name").val(d.party_info.party_name);--}}
                    {{--            $("#ap_vendor_category").val(d.party_info.party_category);--}}
                    {{--            $("#ap_account_balance").val(d.party_info.account_balance);--}}
                    {{--            $("#ap_authorized_balance").val(d.party_info.authorized_balance);--}}
                    {{--        } else {--}}
                    {{--            $("#ap_vendor_id").removeClass('make-readonly-bg').removeAttr("tabindex");--}}
                    {{--            $("#ap_vendor_search").removeAttr('disabled');--}}
                    {{--        }--}}
                    {{--    }--}}
                    {{--}--}}

                    $("#addNewLineBtn").removeAttr('disabled');
                    /**0002588:End logic for provision journal**/
                    openCloseRateLcy(d.account_info.currency_code);

                    $("#accountListModal").modal('hide');

                    /*$("#amount_ccy").focus();
                    $('html, body').animate({scrollTop: ($("#amount_ccy").offset().top - 400)}, 2000);
                */
                }
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(jqXHR);
            });
        }
        function openCloseRateLcy(currency) {
            if (currency == 'USD') {
                $("#exchange_rate").removeAttr('readonly');
                $("#amount_lcy").removeAttr('readonly');
            } else {
                $("#exchange_rate").attr('readonly', 'readonly');
                $("#amount_lcy").attr('readonly', 'readonly');
            }
        }

        let accountTable;

        $("#search_gl_acc").on("click", function () {

            let accId = $("#acc_id").val();

            if (accId) {
                getAccountDetail(accId);
            } else {
                $("#glAccModal").modal('show');
            }
        });

        /* ===============================
           DataTable Init on Modal Show
        ================================ */




    </script>

@endsection
