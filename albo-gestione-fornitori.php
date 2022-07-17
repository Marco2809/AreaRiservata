<?php
// Gestione eventuale inserimento nuovo fornitore
if(isset($_POST['rag_sociale'])) {

    $supplier = new Supplier();
    $supplierDocuments = new SupplierDocuments();

    $currentDate = date('d/m/Y');
    $currentYear = substr($currentDate, 6);
    $dateExpiryYear = substr($currentDate, 0, 6) . ($currentYear + 1);

    $supplier->setRagSociale($_POST['rag_sociale']);
    $supplier->setIva($_POST['iva']);
    $supplier->setCf($_POST['cf']);
    $supplier->setDataInsOE(time());
    $supplier->setIndirizzo($_POST['indirizzo']);
    $supplier->setCap($_POST['cap']);
    $supplier->setCittaProv($_POST['citta']);
    $supplier->setNazione($_POST['nazione']);
    $supplier->setEmail($_POST['email']);
    $supplier->setPEC($_POST['pec']);
    $supplier->setStato(1);
    $supplier->setStatoArt80(1);
    $supplier->setStatoAssettoSoc(1);
    $supplier->setStatoListe(1);
    $supplier->setStatoDocScad(3);
    $supplier->setScadAnn(getTimestampFromDate($dateExpiryYear));

    $idCreated = $code = $supplier->createSupplier();
    $supplierDocuments->createSupplierDocuments($code);

    // Creation of files' directory
    $path = BASE_PATH . '/files/supplier_documents/' . $idCreated . '/';
    if(!file_exists($path)) {
        $oldmask = umask(0);
        for($i=0; $i<11; $i++) {
            mkdir($path.($i+1).'/richieste', 0777, true);
            mkdir($path.($i+1).'/documenti', 0777, true);
        }
        umask($oldmask);
    }
}

// Gestione eventuale modifica Stato Fornitore
if(isset($_POST['edit-state-submit'])) {
    $supplier = new Supplier();
    $supplier->setId($_POST['edit-state-submit']);
    $supplier->setStato($_POST['edit-state']);
    $supplier->editStateSupplier();
}

// Ricavo fornitori presenti nel DB
$supplierTemp = new Supplier();
$supplierList = $supplierTemp->getAll();
?>

<section class="">
    
    <div class="col-md-12">
    
<?php   if($code == -1) { ?>
            <div class="error-message">
                <div class="error-message">
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Errore!</strong> Creazione dell'operatore non completata.
                    </div>
                </div>
            </div>
<?php   } else if($code > 0) { ?>
            <div class="alert alert-success alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-ok"></span> <strong>Creazione effettuata!</strong> Operatore creato correttamente.
            </div>
<?php   } ?>

    <?php printSuppliersTable($supplierList, $role); ?>

        <div class="spacing"></div>
        <div class="spacing"></div>
        <div class="spacing"></div>

    </div>

    <div id="editStateModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modifica Stato Operatore</h4>
          </div>
            <form action='albo-fornitori.php?action=fornitori' method='POST' id='edit-state'>
                <div class="modal-body"></div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success" id="edit-state-submit"
                     name="edit-state-submit" value="">
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
    
</section>

<script type="text/javascript" src='assets/js/albo-modifica-stato.js'></script>
