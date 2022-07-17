<?php

// Getting the supplier ID
if(!$_SESSION['is_supplier']) {
    $id = $_GET['id'];
} else {
    $id = $_SESSION['supplier_id'];
}

$supplier = new Supplier();
$supplierDoc = new SupplierDocuments();
$supplierDoc->setSupplierId($id);
$supplierDocuments = array();
$listRequests = array();
$listDocuments = array();

// Managing the possible date emission edit
if(isset($_POST['edit-date-submit'])) {
    $newEmissionDate = getTimestampFromDate($_POST['modifica-data-emissione']);
    $supplierDoc->editEmissionDate($_POST['edit-date-submit'], $newEmissionDate);

    $newExpiryDate = sumMonthsToDate($newEmissionDate, 6);
    $supplierDoc->editExpiryDate($_POST['edit-date-submit'], $newEmissionDate);
}

// Managing the possible document state edit
if(isset($_POST['edit-doc-state-submit'])) {
    $supplierDoc->editState($_POST['edit-doc-state-submit'], $_POST['edit-state']);
}

// Managing the possible note edit
if(isset($_POST['edit-note-submit'])) {
    $supplierDoc->editNote($_POST['edit-note-submit'], $_POST['note']);
}

// Getting the supplier and supplier status
if(isset($id)) {
    $supplier->getSupplier($id);
    $supplierDocuments = $supplierDoc->getSupplierDocuments();
}

// Verifying the documents' states (Art. 80 state)
if(isset($_POST['edit-doc-state-submit'])) {
    if($_POST['edit-state'] == 0) { // 0 = Anomalie
        $supplier->setStatoArt80(-1);
        $supplier->editArt80State();
    } else if($_POST['edit-state'] == 1) { // 1 = In Lavorazione
        $checkAnomalies = 0;
        $currentState = 0;
        for($i=0; $i<11; $i++) {
            if($i != ($_POST['edit-doc-state-submit'] - 1)) {
                $currentState = getDocumentState($supplierDocuments[$i]);
                if($currentState == 0) {
                    $checkAnomalies++;
                }
            }
        }
        if($checkAnomalies == 0) {
            $supplier->setStatoArt80(1);
            $supplier->editArt80State();
        }
    } else if($_POST['edit-state'] == 2) { // 2 = No Anomalie
        $check = array(0, 0);
        for($i=0; $i<11; $i++) {
            if($i != ($_POST['edit-doc-state-submit'] - 1)) {
                $currentState = getDocumentState($supplierDocuments[$i]);
                if($currentState == 1 || $currentState == 0) {
                    $check[$currentState]++;
                }
            }
        }
        if($check[0] == 0 && $check[1] == 0) {
            $supplier->setStatoArt80(2);
            $supplier->editArt80State();
        } else if($check[0] == 0 && $check[1] > 0) {
            $supplier->setStatoArt80(1);
            $supplier->editArt80State();
        }
    }
}

// Retrieving documents' list
$supplierPath = 'documents/' . $id;
$pathToScan = '';

for($i = 0; $i < 11; $i++) {
    $pathToScan = $supplierPath . '/' . ($i+1) . '/';
    array_push($listRequests, scandir($pathToScan . 'richieste', SCANDIR_SORT_DESCENDING));
    array_push($listDocuments, scandir($pathToScan . 'documenti', SCANDIR_SORT_DESCENDING));
}

?>

<section class="">
    
    <div class="col-md-12">
        
<?php   printSupplierTop($id, 'documenti'); ?>
        
        <div class="spacing"></div>
        
<?php   if($code == -1) { ?>
            <div class="error-message">
                <div class="error-message">
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <span class="glyphicon glyphicon-exclamation-sign"></span> 
                        <strong>Errore!</strong> Caricamento del file non completato.
                    </div>
                </div>
            </div>
<?php   } else if($code == 1) { ?>
            <div class="alert alert-warning alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-exclamation-sign"></span> 
                <strong>Attenzione!</strong> Estensione file non supportata.
            </div>
<?php   } else if($code == 2) { ?>
            <div class="alert alert-success alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-ok"></span> 
                <strong>Caricamento completato!</strong> Documento caricato correttamente.
            </div>
<?php   } else if($code == 3) { ?>
            <div class="alert alert-success alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-ok"></span> 
                <strong>Eliminazione completata!</strong> Documento eliminato correttamente.
            </div>
<?php   } ?>

<?php   printSupplierDocumentsTable($supplier->getId(), $supplierDocuments); ?>
        
        <div class="spacing"></div>
        <div class="spacing"></div>
        <div class="spacing"></div>
    
    </div>
</section>

<div class="modal fade" id="downloadDocumentModal" tabindex="-1" role="dialog" aria-labelledby="downloadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="downloadDocumentModalLabel">Gestione Documenti Fornitore</h4>
        </div>
          <div class="modal-body"></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                <span class="glyphicon glyphicon-menu-left"></span> Indietro
            </button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="uploadDocumentModalLabel">Caricamento Documento Operatore</h4>
        </div>
          
        <form enctype="multipart/form-data" action="assets/php/albo-upload-documento.php?id=<?php echo $id;?>" 
              method="POST" name="form-insert-doc">
            <input type="hidden" name="MAX_FILE_SIZE" value="50000000">
          
        <div class="modal-body">
            <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Sfoglia</span>
                  <input name="uploaded-document" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file" required>
                </span>
                <span class="form-control"></span>
            </div>
            <div id="emission-date"></div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="upload-confirm"
                    name="upload-submit" value="">
                <span class="glyphicon glyphicon-open"></span> Carica
            </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">
                <span class="glyphicon glyphicon-remove"></span> Annulla
            </button>
        </div>
            
        </form>
          
      </div>
    </div>
</div>

<?php if(!$_SESSION['is_supplier']) { ?>

<div class="modal fade" id="deleteDateModal" tabindex="-1" role="dialog" aria-labelledby="deleteDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="deleteDateModalLabel">Eliminazione Data</h4>
        </div>
        <form action="albo-fornitori.php?action=documenti&id=<?php echo $supplier->getId();?>"
              method="POST" name="edit-date-form">
        <div class="modal-body">
            <div class="col-md-8 col-md-offset-2">
                <div class="delete-date-content well">
                    <label for="modifica-data-emissione">Data Emissione Documento:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="modifica-data-emissione" 
                               id="modifica-data-emissione" value="" />
                        <div class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>
                    <div class="center">
                        <a href="" class="btn btn-danger btn-sm" id="btn-delete-date">
                            <span class="glyphicon glyphicon-trash"></span> 
                            Elimina Data
                        </a>
                    </div>
                </div>
             </div>
        </div>
        
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="edit-date-submit"
                    name="edit-date-submit" value="">
                <span class="glyphicon glyphicon-save"></span> Salva
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
                <span class="glyphicon glyphicon-menu-left"></span> Annulla
            </button>
        </div>
        </form>
      </div>
    </div>
</div>

<div id="editDocumentStateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modifica Stato Documento</h4>
      </div>
        <form action='albo-fornitori.php?action=documenti&id=<?php echo $supplier->getId();?>' 
              method='POST' id='edit-document-state'>
            <div class="modal-body">
                <div class="center edit-document-state-modal">
                    <label class="radio-inline no-anomalie"><input type="radio" name="edit-state"
                        id="no-anomalie" value="2"> 
                        <span class="glyphicon glyphicon-ok"></span> Nessuna Anomalia</label>
                    <label class="radio-inline in-lavorazione"><input type="radio" name="edit-state"
                        id="in-lavorazione" value="1"> 
                        <span class="glyphicon glyphicon-exclamation-sign"></span> In Lavorazione</label>
                    <label class="radio-inline anomalie"><input type="radio" name="edit-state"
                        id="anomalie" value="0"> 
                        <span class="glyphicon glyphicon-remove"></span> Riscontrate Anomalie</label>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" id="edit-doc-state-submit"
                 name="edit-doc-state-submit" value="">
                  <span class="glyphicon glyphicon-pencil"></span> Modifica
              </button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                  <span class="glyphicon glyphicon-remove"></span> Annulla
              </button>
            </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editNodeModal" tabindex="-1" role="dialog" aria-labelledby="editNodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="editNodeModalLabel">Modifica Nota</h4>
        </div>
          
        <form action="albo-fornitori.php?action=documenti&id=<?php echo $supplier->getId();?>" method="POST">
        
        <div class="modal-body">
            <div class="form-group">
                <textarea class="form-control" rows="5" id="note" name="note" autofocus></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="edit-note-submit"
                    name="edit-note-submit" value="">
                <span class="glyphicon glyphicon-floppy-disk"></span> Salva
            </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">
                <span class="glyphicon glyphicon-remove"></span> Annulla
            </button>
        </div>
            
        </form>
          
      </div>
    </div>
</div>

<?php } ?>

<script type="text/javascript" src="./assets/js/albo-upload-documenti.js"></script>
<script type="text/javascript" src="./assets/js/albo-modifica-data-doc.js"></script>
<script type="text/javascript" src="./assets/js/datepicker-in.js"></script>
<script type="text/javascript" src="./assets/js/albo-modifica-nota.js"></script>
<script type="text/javascript" src="./assets/js/albo-modifica-stato-doc.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#downloadDocumentModal').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var supplierId = button.data('supplier-id');
            var documentId = button.data('document-id');
            var documentName = button.data('document-name');
            var title = 'Gestione Documenti ' + documentName;
            var userRole = '<?php echo $role; ?>';
            
            
            var listRequests = <?php echo json_encode($listRequests); ?>[documentId-1];
            var listDocuments = <?php echo json_encode($listDocuments); ?>[documentId-1];
            var maxLength = 0;
            if(listRequests.length >= listDocuments.length) {
                maxLength = listRequests.length - 2;
            } else {
                maxLength = listDocuments.length - 2;
            }
            
            modal.find('.modal-title').text(title);
            
            if(maxLength > 0) {
                var table = '<div class="col-md-10 col-md-offset-1">\n\
                    <table class="table table-bordered table-download-doc">\n\
                        <thead>\n\
                            <tr>\n\
                                <th>Richieste</th>\n\
                                <th>Documenti</th>\n\
                            </tr>\n\
                        </thead>\n\
                        <tbody>';

                var currReqElement = '';
                var currDocElement = '';
                var currReqPath = '';
                var currDocPath = '';
                var currReqTime = '';
                var currDocTime = '';

                for(var i=0; i<maxLength; i++) {
                    table += '<tr>\n';

                    currReqElement = listRequests[i];

                    if(currReqElement == undefined || currReqElement == '..' ||
                            currReqElement == '.') {
                        table += '<td></td>\n';
                    } else {
                        currReqPath = 'documents/' + supplierId + '/' + documentId + 
                                '/richieste/' + currReqElement;
                        currReqElement = currReqElement.split('.');
                        currReqTime = currReqElement[0].substr(currReqElement[0].length - 10);
                        currReqTime = timestampToDate(currReqTime);

                        table += '<td>' + 
                            printFileIcon(currReqElement[1]) + 'Richiesta ' + currReqTime +  
                            '<div style="float: right">\n\
                                <a href="' + currReqPath + '" target="_blank" \n\
                                    class="btn btn-default btn-sm btn-download">\n\
                                    <span class="glyphicon glyphicon-download-alt"></span>\n\
                                </a>';
                         
                        if(userRole == 'admin') {
                            table += '<a href="resources/library/php/delete-document.php?id=' + 
                                    supplierId + '&action=delete&docid=' + documentId + 
                                    '&type=richieste&filename=' + currReqElement[0] + 
                                    '&ext=' + currReqElement[1] + '" class="btn btn-danger btn-sm">\n\
                                    <span class="glyphicon glyphicon-trash"></span>\n\
                                </a>';
                        }
                        table += '</div></td>';
                    }

                    currDocElement = listDocuments[i];

                    if(currDocElement == undefined || currDocElement == '..' ||
                            currDocElement == '.') {
                        table += '<td></td>\n';
                    } else {
                        currDocPath = 'documents/' + supplierId + '/' + documentId + 
                                '/documenti/' + currDocElement;
                        currDocElement = currDocElement.split('.');
                        currDocTime = currDocElement[0].substr(currDocElement[0].length - 10);
                        currDocTime = timestampToDate(currDocTime);
                        table += '<td>' + printFileIcon(currDocElement[1]) + 
                                'Documento ' + currDocTime + 
                                '<div style="float: right">\n\
                                    <a href="' + currDocPath + '" target="_blank"\n\
                                    class="btn btn-default btn-sm btn-download">\n\
                                    <span class="glyphicon glyphicon-download-alt"></span>\n\
                                </a>';
                        if(userRole == 'admin') {
                            table += '<a href="resources/library/php/delete-document.php?id=' + 
                                    supplierId + '&action=delete&docid=' + documentId + 
                                    '&type=documenti&filename=' + currDocElement[0] + 
                                    '&ext=' + currDocElement[1] + '" class="btn btn-danger btn-sm">\n\
                                    <span class="glyphicon glyphicon-trash"></span>\n\
                                </a>';
                        }
                        table += '</div></td>';
                    }
                    table += '</tr>';
                }

                table += '</tbody>\n\
                        </table>\n\
                    </div>';

                modal.find('.modal-body').html(table);
                var tableHeight = (maxLength * 41) + 70;
                modal.find('.modal-body').css({
                  height: tableHeight
                });
            } else {
                modal.find('.modal-body').html('<div class="download-empty"><center>Nessun documento caricato</center></div>');
                modal.find('.modal-body').css({
                  height: 100
                });
            }
            
        });
        
        var timestampToDate = function(timestamp) {
            var date = new Date(timestamp * 1000);
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            
            return day + '/' + month + '/' + year;
        };
        
        var printFileIcon = function(extension) {
            if(extension == 'jpg') {
                extension = 'jpeg';
            }
            var imagePath = '<img src="/assets/img/' + extension + 
                    '-icon.png" class="doc-icon">';
            return imagePath;
        };
    });
</script>