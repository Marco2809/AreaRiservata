$(document).ready(function () {
    $('#deleteDateModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var documentId = button.data('document-id');
      var documentName = button.data('document-name');
      var emissionDate = button.data('emission-date');
      var supplierId = button.data('supplier-id');
      
      var title = 'Modifica Data - ' + documentName;

      var modal = $(this);
      modal.find('.modal-title').text(title);
      document.getElementById("btn-delete-date").href = 
              "assets/php/albo-elimina-data.php?id=" + supplierId +
              "&document=" + documentId;
      modal.find('.modal-body').css({
          height: 230
      });
      modal.find('#modifica-data-emissione').val(emissionDate);
      modal.find('button.btn-success').val(documentId);
    });
    
});