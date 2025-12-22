document.addEventListener('DOMContentLoaded', function () {
    flatpickr(".datepicker", {
        dateFormat: "d-m-Y",
        allowInput: true,
        // defaultDate: "today",
        animate: true
    });
});
