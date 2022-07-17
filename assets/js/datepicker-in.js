$(function() {
    $('input[name="data-ins-oe"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        drops: 'up',
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
});

$(function() {
    $('input[name="data-emissione"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        drops: 'up',
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
});

$(function() {
    $('input[name="modifica-data-emissione"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        drops: 'down',
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
});

$(function() {
    $('input[name="data-scadenza"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        drops: 'up',
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
});