$(document).ready(function () {
    $('#editNodeModal').on('show.bs.modal', function (event) { // id of the modal with event
      var button = $(event.relatedTarget); // Button that triggered the modal
      var documentId = button.data('document-id'); // Extract info from data-* attributes
      var documentName = button.data('document-name');
      var documentNote = button.data('document-note');
      
      var title = 'Modifica Nota - ' + documentName;

      // Update the modal's content.
      var modal = $(this);
      modal.find('.modal-title').text(title);
      modal.find('#note').text(documentNote);

      // And if you wish to pass the productid to modal's 'Yes' button for further processing
      modal.find('button.btn-success').val(documentId);
    });
});