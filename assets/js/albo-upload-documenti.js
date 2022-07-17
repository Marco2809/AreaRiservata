$(document).ready(function () {
    $('#uploadDocumentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var documentId = button.data('document-id');
        var documentName = button.data('document-name');
        var isSupplier = button.data('is-supplier');
        var modal = $(this);

        var title = 'Caricamento ';
        var datePick = '<div class="spacing"></div>\n\
                        <div class="col-md-12"><div class="col-md-8 col-md-offset-2">\n\
                            <label for="data-emissione">Data Emissione Documento:</label>\n\
                            <div class="input-group">\n\
                                <input type="text" class="form-control" name="data-emissione" \n\
                                       id="data-emissione" value="" />\n\
                                <div class="input-group-addon">\n\
                                  <span class="glyphicon glyphicon-calendar"></span>\n\
                                </div>\n\
                            </div></div></div>\n\
                            <script type="text/javascript" src="/assets/js/datepicker-in.js"></script>';
        
        if(isSupplier) {
            title += 'Documento ' + documentName;
            modal.find('#emission-date').html(datePick);
            modal.find('.modal-body').css({
                    height: 160
            });
        } else {
            title += 'Richiesta ' + documentName;
        }

        modal.find('.modal-title').text(title);
        modal.find('button.btn-success').val(documentId);
    });
    
    $('#uploadDocumentModal').on('hidden.bs.modal', function() {
        var modal = $(this);
        modal.find('#emission-date').html('');
    });
});