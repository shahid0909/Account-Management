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
                resetField(['#acc_id', '#account_type', '#acc_type', '#acc_balance', '#acc_auth_balance', '#amount_ccy', '#amount_lcy']);

                if ($.isEmptyObject(d.account_info)) {
                    Swal.fire({
                        title: 'Account Not Found',
                        text: 'Do you want to search another account?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // User clicked "Yes"
                            $("#glAccModal").modal('show');
                        } else {
                            // User clicked "No"
                            console.log('User chose not to search again');
                        }
                    });
                }
                else {

                    $("#acc_id").val(d.account_info.gl_acc_id);
                    $("#account_id").data("flag", d.account_info.type_flag);
                    $("#acc_name").val(d.account_info.gl_acc_name);
                    $("#acc_type").val(d.account_info.gl_type_name);
                    $("#acc_balance").val(getCommaSeparatedValue(d.account_info.account_balance));
                    $("#acc_auth_balance").val(getCommaSeparatedValue(d.account_info.authorize_balance));
                    $("#currency").val(d.account_info.currency);
                    $("#exchange_rate").val(d.account_info.exchange_rate);


                    $("#glAccModal").modal('hide');

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

        let glTableData = [];

        // Example exchange rate
        const exchangeRate = 1;

        // Auto-fill LCY when CCY changes
        document.getElementById('amount_ccy').addEventListener('input', function() {
            const ccy = parseFloat(this.value) || 0;
            const lcy = ccy * exchangeRate;
            document.getElementById('amount_lcy').value = lcy.toFixed(2);
        });

        // Calculate totals
        function calculateTotals() {
            let totalDebit = 0;
            let totalCredit = 0;

            glTableData.forEach(row => {
                totalDebit += row.dr_cr === 'D' ? parseFloat(row.amount_lcy) : 0;
                totalCredit += row.dr_cr === 'C' ? parseFloat(row.amount_lcy) : 0;
            });

            document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
            document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);

            checkDebitCreditBalance();
        }

        // Render table
        function renderTable() {
            const tbody = document.querySelector('#glTable tbody');
            tbody.innerHTML = '';
            glTableData.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>${row.acc_id}</td>
            <td>${row.acc_name}</td>
            <td>${row.dr_cr === 'D' ? parseFloat(row.amount_lcy).toFixed(2) : '0'}</td>
            <td>${row.dr_cr === 'C' ? parseFloat(row.amount_lcy).toFixed(2) : '0'}</td>
            <td>
                <button type="button" class="btn btn-sm btn-warning" onclick="editRow(${index})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteRow(${index})">Delete</button>
            </td>
        `;
                tbody.appendChild(tr);
            });
            calculateTotals();
        }

        // Add row
        document.querySelector('.btn-info').addEventListener('click', function() {
            const acc_id = document.getElementById('acc_id').value.trim();
            const acc_name = document.getElementById('acc_name').value.trim();
            const dr_cr = document.getElementById('dr_cr').value;
            const amount_ccy = parseFloat(document.getElementById('amount_ccy').value) || 0;
            const amount_lcy = parseFloat(document.getElementById('amount_lcy').value) || 0;

            if (!acc_id || !acc_name || amount_ccy <= 0) {
                alert('Please fill all required fields with valid values!');
                return;
            }

            // Add to data array
            glTableData.push({ acc_id, acc_name, dr_cr, amount_ccy, amount_lcy });

            // Render table
            renderTable();

            // Reset input fields
            document.getElementById('acc_id').value = '';
            document.getElementById('acc_name').value = '';
            document.getElementById('amount_ccy').value = '';
            document.getElementById('acc_balance').value = '';
            document.getElementById('acc_auth_balance').value = '';
            document.getElementById('acc_type').value = '';
        });

        // Delete row
        function deleteRow(index) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This row will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6', // optional: blue button
                cancelButtonColor: '#d33',     // optional: red cancel
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    glTableData.splice(index, 1);
                    renderTable();

                    Swal.fire(
                        'Deleted!',
                        'The row has been deleted.',
                        'success'
                    );
                }
            });
        }


        function editRow(index) {
                const row = glTableData[index];

                // Only populate the fields that should remain as user input
                // Do NOT overwrite amount_ccy and amount_lcy
                document.getElementById('acc_id').value = row.acc_id;
                document.getElementById('dr_cr').value = row.dr_cr;

                // Make AJAX request to get full account details
                $.ajax({
                    url: '{{ route('ajax.get-account-details') }}',
                    method: 'POST',
                    data: { accId: row.acc_id },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(d) {
                        // Check if account info exists
                        // if ($.isEmptyObject(d.account_info)) {
                        //     Swal.fire({
                        //         title: 'Account Not Found',
                        //         text: 'Do you want to search another account?',
                        //         icon: 'warning',
                        //         showCancelButton: true,
                        //         confirmButtonText: 'Yes',
                        //         cancelButtonText: 'No'
                        //     }).then((result) => {
                        //         if (result.isConfirmed) {
                        //             $("#glAccModal").modal('show');
                        //         }
                        //     });
                        //     return;
                        // }

                        // Reset only fields related to account info
                        resetField(['#acc_id', '#account_type', '#acc_type', '#acc_balance', '#acc_auth_balance']);

                        // Populate account info
                        $("#acc_id").val(d.account_info.gl_acc_id);
                        $("#acc_id").data("flag", d.account_info.type_flag);
                        $("#acc_name").val(d.account_info.gl_acc_name);
                        $("#acc_type").val(d.account_info.gl_type_name);
                        $("#acc_balance").val(getCommaSeparatedValue(d.account_info.account_balance));
                        $("#acc_auth_balance").val(getCommaSeparatedValue(d.account_info.authorize_balance));
                        $("#currency").val(d.account_info.currency);
                        $("#exchange_rate").val(d.account_info.exchange_rate);
                        document.getElementById('amount_ccy').value = row.amount_ccy;
                        document.getElementById('amount_lcy').value = row.amount_lcy;
                        // Remove row from array so it can be updated on next ADD
                        glTableData.splice(index, 1);
                        renderTable();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }

        const submitBtn = document.querySelector('#journalVoucherForm button[type="submit"]');

        function checkDebitCreditBalance() {
            const totalDebit = parseFloat(document.getElementById('totalDebit').textContent) || 0;
            const totalCredit = parseFloat(document.getElementById('totalCredit').textContent) || 0;

            // Enable submit only if Debit = Credit and total > 0
            if (totalDebit > 0 && totalDebit === totalCredit) {
                submitBtn.removeAttribute('disabled');
            } else {
                submitBtn.setAttribute('disabled', 'disabled');
            }
        }

        document.getElementById('journalVoucherForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Final validation
            if (glTableData.length === 0) {
                Swal.fire('Error', 'Please add at least one transaction row!', 'error');
                return;
            }

            const totalDebit = parseFloat(document.getElementById('totalDebit').textContent);
            const totalCredit = parseFloat(document.getElementById('totalCredit').textContent);

            if (totalDebit !== totalCredit) {
                Swal.fire('Error', 'Total Debit and Credit must be equal!', 'error');
                return;
            }

            // Gather form data
            const formData = {
                fiscal_year: $('#fiscal_year').val(),
                posting_period: $('#posting_period').val(),
                document_date: $('#document_date').val(),
                posting_date: $('#posting_date').val(),
                document_reference: $('input[name="document_reference"]').val(),
                narration: $('#narration').val(),
                transactions: glTableData
            };

            $.ajax({
                url: '{{ route("journal-voucher.store") }}', // Update route
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.textContent = 'Submitting...';
                },
                success: function(res) {
                    Swal.fire('Success', res.message || 'Journal Voucher saved successfully!', 'success');
                    // Reset form and table
                    glTableData = [];
                    renderTable();
                    document.getElementById('journalVoucherForm').reset();
                    submitBtn.textContent = 'Submit';
                },
                error: function(xhr) {
                    console.error(xhr);
                    Swal.fire('Error', 'An error occurred while saving the voucher.', 'error');
                    submitBtn.textContent = 'Submit';
                    checkDebitCreditBalance(); // Recheck balance after error
                }
            });
        });
    </script>

@endsection
