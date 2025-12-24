document.addEventListener('DOMContentLoaded', function () {
    postingDatePicker = flatpickr(".datepicker", {
        dateFormat: "d-m-Y",
        allowInput: true,
        animate: true,
        maxDate: "today"   // 🔥 today-এর পরে date যাবে না
    });
});


let postingDatePicker;

document.addEventListener('DOMContentLoaded', function () {
    // Initialize Flatpickr without any default or maxDate to keep field empty
    postingDatePicker = flatpickr("#posting_date", {
        dateFormat: "d-m-Y",
        allowInput: true,
        animate: true,

    });


    $("#fiscal_year").trigger('change');
});


function getPostingPeriod(calenderId, url, callback, preselected = null) {
    $.ajax({
        url: url,
        method: 'GET',
        data: {

            calenderId: calenderId,
            preselected: preselected
        },
        success: function (response) {
            callback(response.period);
        },
        error: function (jqXHR) {
            let msg = jqXHR.responseJSON?.message || 'Failed to load posting periods';
            Swal.fire({
                icon: 'warning',
                text: msg
            });
        }
    });
}
