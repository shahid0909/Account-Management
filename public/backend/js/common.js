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
function resetField(selectors) {
    selectors.forEach(function (element) {
        if ($(element).hasClass('select2')) {
            /*
            * Select2 show options inside <span>. That's why select2 resetting using it's own method.
            * */
            $(element).val(null).trigger('change');
        } else {
            $(element).val('');
        }
    });
}
function getCommaSeparatedValue(amountParam, americanBritish = 'B') {

    if (amountParam === null || amountParam === undefined || amountParam === '') {
        return false;
    }

    // ✅ force string
    amountParam = amountParam.toString();

    let amount = '';

    /** Comma exist in amount remove them **/
    if (amountParam.match(/,/)) {
        amount = removeCommaFromValue(amountParam);
    } else {
        amount = (parseFloat(amountParam) < 0)
            ? (parseFloat(amountParam) * -1).toString()
            : amountParam;
    }

    if (nullEmptyUndefinedChecked(amount.length)) {
        return false;
    }

    let array = amount.split('.');
    let decimal = array[0];
    let fraction = array[1];

    let decimalArray = decimal.split('');
    let length = decimalArray.length;

    if ((decimal != 0 || !nullEmptyUndefinedChecked(decimal)) && length > 3) {

        let steps = Math.floor(length / 2);
        let position = length;

        for (let i = 0; i < steps; i++) {
            if (americanBritish.toUpperCase() === 'B') {
                position -= (i === 0 ? 3 : 2);
            } else {
                position -= 3;
            }

            if (position > 0) {
                decimalArray.splice(position, 0, ',');
            }
        }

        let newDecimal = decimalArray.join('');

        if (amount.indexOf('.') !== -1) {
            return (parseFloat(amountParam) < 0 ? '-' : '') + newDecimal + '.' + fraction;
        } else {
            return (parseFloat(amountParam) < 0 ? '-' : '') + newDecimal;
        }

    } else {
        return (parseFloat(amountParam) < 0 ? '-' : '') + amount;
    }
}


function removeCommaFromValue(amount) {

    let array = amount.split('.');
    let decimal = array[0].split(',');
    let fraction = array[1];
    let newDecimal = decimal.join('');

    if (amount.indexOf('.') != -1) {
        return newDecimal + '.' + fraction;
    } else {
        return newDecimal;
    }


    /*if (!nullEmptyUndefinedChecked(fraction)) {
        return decimal.join('') + '.' + fraction;
    } else {
        if (amount.indexOf('.') != -1) {
            return decimal.join('') + '.'+fraction;
        } else {
            return decimal.join('');
        }
    }*/
}

function nullEmptyUndefinedChecked(value) {
    let trueCounter = 0;
    if (typeof (value) === 'undefined') {
        trueCounter++;  //null
    }

    if (value === undefined) {
        trueCounter++;  //null
    }

    if (value == null) {
        trueCounter++;  //null
    }

    if ($.trim(value) === "") {
        trueCounter++;  //null
    }

    if ($.trim(value) === '') {
        trueCounter++;  //null
    }

    if (value === 0) {
        trueCounter++;  //null
    }

    if (value === "0") {
        trueCounter++;  //null
    }

    /*
    * True = given variable/array/object is null/Empty/Undefined.
    * False = Given variable/array/object not null/Empty/Undefined.
    */
    return trueCounter > 0;
}
