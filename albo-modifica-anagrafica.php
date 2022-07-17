<?php

$supplier = new Supplier();

if(isset($_GET['id'])) {
    $supplier->getSupplier($_GET['id']);
}
        
?>

<section class="">
    <div class="col-md-12">
        
        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Modifica Anagrafica Fornitore</span>
                </div>
            </div>

            <div class="panel-body">
        
                <form action="albo-fornitori.php?action=anagrafica&id=<?php echo $supplier->getId(); ?>" method="POST" name="edit_supplier">

                    <div class="spacing"></div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rag_sociale">Ragione sociale:</label>
                                <input type="text" class="form-control" id="rag_sociale" name="rag_sociale" 
                                       value="<?php echo $supplier->getRagSociale(); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="p_iva">Partita IVA:</label>
                                <input type="text" class="form-control" id="iva" name="iva"
                                       value="<?php echo $supplier->getIva(); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="indirizzo">Indirizzo:</label>
                                <input type="text" class="form-control" id="indirizzo" name="indirizzo"
                                       value="<?php echo $supplier->getIndirizzo(); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="citta">Città (Provincia):</label>
                                <input type="text" class="form-control" id="citta" name="citta"
                                       value="<?php echo $supplier->getCittaProv(); ?>" required>
                            </div>
                            <label for="data-ins-oe">Data Inserimento OE:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="data-ins-oe" 
                                       id="data-ins-oe" value="<?php echo getFinalDate($supplier->getDataInsOE()); ?>" />
                                <div class="input-group-addon">
                                  <span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?php echo $supplier->getEmail(); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="cf">Codice Fiscale:</label>
                                <input type="text" class="form-control" id="cf" name="cf"
                                       value="<?php echo $supplier->getCf(); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="cap">CAP:</label>
                                <input type="text" class="form-control" id="cap" name="cap"
                                       value="<?php echo $supplier->getCap(); ?>"required>
                            </div>
                            <div class="form-group">
                                <label for="nazione">Nazione:</label>
                                <input type="text" class="form-control" id="nazione" name="nazione"
                                       value="<?php echo $supplier->getNazione(); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="edit-user-buttons">
                        <button type="submit" class="btn btn-info" name="edit-supplier">
                            <span class="glyphicon glyphicon-pencil"></span> Modifica Dati
                        </button>

                        <a href="javascript:history.back()" class="btn btn-default">
                            <span class="glyphicon glyphicon-chevron-left"></span> Indietro
                        </a>
                    </div>

                </form>
                
            </div>
        </div>
        
        <div class="spacing"></div>
        
    </div>
</section>

<script type="text/javascript" src="/resources/library/js/datepicker-in.js"></script>