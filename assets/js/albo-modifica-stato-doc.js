$(document).ready(function () {
    $('#editDocumentStateModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var documentId = button.data('document-id');
        var documentState = button.data('document-state');
        var documentName = button.data('document-name');

        var title = 'Modifica Stato Documento - ' + documentName;
        if(documentState == 2) {
            $("#in-lavorazione").attr('checked', false);
            $("#anomalie").attr('checked', false);
            $("#no-anomalie").attr('checked', true);
        } else if(documentState == 1) {
            $("#anomalie").attr('checked', false);
            $("#no-anomalie").attr('checked', false);
            $("#in-lavorazione").attr('checked', true);
        } else if(documentState == 0) {
            $("#no-anomalie").attr('checked', false);
            $("#in-lavorazione").attr('checked', false);
            $("#anomalie").attr('checked', true);
        }

        var modal = $(this);
        modal.find('.modal-title').text(title);
        modal.find('button.btn-success').val(documentId);
    });
});