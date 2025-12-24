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

        // AJAX function to get posting periods

    </script>
@endsection
