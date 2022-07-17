$(document).ready(function () {
    $('#editStateModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var supplierId = button.data('supplier-id');
        var supplierState = button.data('supplier-state');
        var supplierName = button.data('supplier-name');

        var title = 'Modifica Stato Fornitore - ' + supplierName;
        var body = '<div class="center">\n\
                      <label class="radio-inline"><input type="radio" name="edit-state"\
                          value="2"';

        if(supplierState === 2) {
            body += ' checked';
        }

        body += '> <img src="assets/img/green_light_big.png"\
                  class="semaphore-light-big" alt="Monitorato"> Monitorato</label>\n\
                <label class="radio-inline"><input type="radio" name="edit-state"\
                      value="1"';
                
        if(supplierState === 1) {
            body += ' checked';
        }
                
        body += '> <img src="assets/img/yellow_light_big.png"\
                      class="semaphore-light-big" alt="In Lavorazione"> In Lavorazione</label>\n\
                </div>';
        
        body += '<input type="hidden" name="supplier-id" value="' + supplierId + '">';

        var modal = $(this);
        modal.find('.modal-title').text(title);
        modal.find('.modal-body').html(body);

        modal.find('button.btn-success').val(supplierId);
    });
});