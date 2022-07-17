$('#reset').click(function() {
    $('form[name="search_supplier"]')
        .find(':checkbox').removeAttr('checked')
        .find(':text, select').val('');
    $('label.dropdown-label').html('Seleziona Stato');
    $('a[data-toggle="check-all"]').removeClass();
    $('a[data-toggle="check-all"]').addClass('dropdown-check btn btn-success btn-xs');
    $('a[data-toggle="check-all"]').html('<span class="glyphicon glyphicon-ok"></span> Seleziona tutti');
});