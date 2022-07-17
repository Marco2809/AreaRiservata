<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require_once('assets/php/PHPMailer/PHPMailerAutoload.php');

// Getting the supplier ID
if(!$_SESSION['is_supplier']) {
    $id = $_GET['id'];
} else {
    $id = $_SESSION['supplier_id'];
}

// Getting the user
$supplier = new Supplier();
$supplier->setId($id);

$code = 0;

// Managing eventual supplier edit
if(isset($_POST['edit-supplier'])) {

    $supplierToUpdate = new Supplier();
    $supplierToUpdate->setID($id);
    $supplierToUpdate->setRagSociale($_POST['rag_sociale']);
    $supplierToUpdate->setEmail($_POST['email']);
    $supplierToUpdate->setIva($_POST['iva']);
    $supplierToUpdate->setCf($_POST['cf']);
    $supplierToUpdate->setIndirizzo($_POST['indirizzo']);
    $supplierToUpdate->setCap($_POST['cap']);
    $supplierToUpdate->setCittaProv($_POST['citta']);
    $supplierToUpdate->setNazione($_POST['nazione']);
    $supplierToUpdate->setDataInsOE(getTimestampFromDate($_POST['data-ins-oe']));

    $code = $supplierToUpdate->editSupplier();
}

if(isset($id)) {
    $supplier->getSupplier($id);
}

// Managing eventual note edit
if(isset($_POST['edit-note-submit'])) {
    $supplier->setNote($_POST['note']);
    if($supplier->editNote()) {
        $mail = new PHPMailer;
        $mail->setFrom('ufficio.acquisti@service-tech.org', 'Ufficio Acquisti');
        $mail->addAddress('luca.dipietromartinelli@gmail.com');
        $mail->Subject  = 'Service-Tech - Inserimento nuova nota fornitore';
        $mail->Body = 'E\' stata inserita la seguente nota sul portale Albo Fornitori di Service-Tech: "' . 
                $_POST['note'] . '"';
        if(!$mail->send()) {
          $code = -2;
        } else {
          $code = 2;
        }
    } else {
        $code = -3;
    }
}

?>

<?php   
        printSupplierTop($id, 'anagrafica'); ?>
        
        <div class="spacing"></div>
        
<?php
        if($code == -3) { ?>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Attenzione!</strong> Nota non salvata.
            </div>
    
<?php   } else if($code == -2) { ?>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Attenzione!</strong> La nota è stata salvata ma non è stato possibile inviare l'e-mail al fornitore.
            </div>
    
<?php   } else if($code == -1) { ?>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Attenzione!</strong> Ragione Sociale già in uso.
            </div>
    
<?php   } else if($code == 1) { ?>
            
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-ok"></span> <strong>Modifica effettuata! </strong>I dati sono stati modificati con successo.
            </div>
<?php   } else if($code == 2) { ?>
            
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-ok"></span> <strong>Nota salvata! </strong>La nota è stata salvata correttamente e una e-mail è stata inviata al fornitore.
            </div>
<?php   } ?>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table-supplier-profile">
                    <tr>
                        <th>Ragione Sociale</th>
                        <td><?php echo $supplier->getRagSociale();?></td>
                    </tr>
                    <tr>
                        <th>Data di Inserimento</th>
                        <td><?php echo getFinalDate($supplier->getDataInsOE());?></td>
                    </tr>
                    <tr>
                        <th>Scadenza Annuale</th>
                        <td><?php echo getFinalDate($supplier->getScadAnn());?></td>
                    </tr>
                    <tr>
                        <th>PEC</th>
                        <td><?php echo $supplier->getPec();?></td>
                    </tr>
                    <tr>
                        <th>Indirizzo</th>
                        <td><?php echo $supplier->getIndirizzo();?></td>
                    </tr>
                    <tr>
                        <th>Città (Provincia)</th>
                        <td><?php echo $supplier->getCittaProv();?></td>
                    </tr>
<?php           if(!$_SESSION['is_supplier']) { ?>
                    <tr>
                        <th>Azioni</th>
                        <td class="td-no-padding">
                            <a href="albo-fornitori.php?action=modifica-anagrafica&id=<?php echo $supplier->getId(); ?>"
                                class="btn btn-primary btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Modifica</a>
                            <button class="btn btn-danger btn-xs"
                                    data-toggle="modal" data-target="#deleteSupplierModal">
                                <span class="glyphicon glyphicon-pencil"></span> Elimina
                            </button>
                        </td>
                    </tr>
<?php           } ?>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered table-supplier-profile">
                    <tr>
                        <th>Stato del Fornitore</th>
                        <td class="td-no-padding"><?php echo getImageStatusBig($supplier->getStato()) . " " .
                                getTextStatus($supplier->getStato());?></td>
                    </tr>
                    <tr>
                        <th>Codice Fiscale</th>
                        <td><?php echo $supplier->getCf();?></td>
                    </tr>
                    <tr>
                        <th>Partita IVA</th>
                        <td><?php echo $supplier->getIva();?></td>
                    </tr>
                    <tr>
                        <th>CAP</th>
                        <td><?php echo $supplier->getCap();?></td>
                    </tr>
                    <tr>
                        <th>Nazione</th>
                        <td><?php echo $supplier->getNazione();?></td>
                    </tr>
                    <tr>
                        <th>E-mail</th>
                        <td>
                            <a href="mailto:<?php echo $supplier->getEmail();?>">
                                <?php echo $supplier->getEmail();?>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class='panel panel-default'>
            <div class='panel-heading panel-heading-note'>Note</div>
            <div class='panel-body'>
                <div class="panel panel-default panel-note-body">
                    <div class="panel-body">
                        <?php echo $supplier->getNote(); ?>
                    </div>
                </div>
                
<?php           if(!$_SESSION['is_supplier']) { ?>
                    <button class='btn btn-success btn-sm pull-right'
                        data-toggle="modal" data-target="#editNoteModal">
                        <span class="glyphicon glyphicon-pencil"></span> Modifica Note
                    </button>
<?php           } ?>
            </div>
        </div>
        
        <div class="spacing"></div>
        <div class="spacing"></div>
    </div>
</div>

<?php if(!$_SESSION['is_supplier']) { ?>
<div class="modal fade" id="editNoteModal" tabindex="-1" role="dialog" aria-labelledby="editNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="editNoteModalLabel">Modifica Note</h4>
        </div>
          
        <form action="albo-fornitori.php?action=anagrafica&id=<?php echo $supplier->getId();?>" method="POST">

            <div class="modal-body">
                <div class="form-group">
                    <textarea class="form-control" rows="5" id="note" name="note"><?php echo $supplier->getNote();?></textarea>
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

<div class="modal fade" id="deleteSupplierModal" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="deleteSupplierModalLabel">Conferma Eliminazione Fornitore</h4>
        </div>

        <div class="modal-body">
            Sei sicuro di voler eliminare il fornitore "<?php echo $supplier->getRagSociale();?>"?
        </div>
        <div class="modal-footer">
            <a href="assets/php/albo-elimina-fornitore.php?id=<?php echo $supplier->getId(); ?>"
             class="btn btn-danger">
              <span class="glyphicon glyphicon-trash"></span> Elimina
          </a>
          <button type="button" class="btn btn-default" data-dismiss="modal">
              <span class="glyphicon glyphicon-remove"></span> Annulla
          </button>
        </div>
            
        </form>
          
      </div>
    </div>
</div>

<?php } ?>