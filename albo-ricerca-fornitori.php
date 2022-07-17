<?php

// Gestione eventuale modifica Stato Fornitore
if(isset($_GET['edit-state-submit'])) {
    $supplier = new Supplier();
    $supplier->setId($_GET['supplier-id']);
    $supplier->setStato($_GET['edit-state']);
    $supplier->editStateSupplier();
}

// Managing searches
if(isset($_GET['rag_sociale'])) {

    $supplierToSearch = new Supplier();
    $supplierToSearch->setRagSociale(strtolower($_GET['rag_sociale']));
    $supplierToSearch->setIva(strtolower($_GET['iva']));
    $supplierToSearch->setCf(strtolower($_GET['cf']));

    // Stati selezionati "Stato Fornitore"
    $statiFornitore = array();
    $statoMonitorato = isset($_GET['stato-monitorato']) ? 2 : 0;
    $statoLavorazione = isset($_GET['stato-lavorazione']) ? 1 : 0;
    array_push($statiFornitore, $statoMonitorato);
    array_push($statiFornitore, $statoLavorazione);

    // Stati selezionati "Stato Art. 80"
    $statiArt80 = array();
    $statoArt80Monitorato = isset($_GET['stato-art80-monitorato']) ? 2 : 0;
    $statoArt80Art80Lavorazione = isset($_GET['stato-art80-lavorazione']) ? 1 : 0;
    $statoArt80Anomalie = isset($_GET['stato-art80-anomalie']) ? -1 : 0;
    array_push($statiArt80, $statoArt80Monitorato);
    array_push($statiArt80, $statoArt80Art80Lavorazione);
    array_push($statiArt80, $statoArt80Anomalie);

    // Stati selezionati "Stato Assetto Societario"
    $statiAssetto = array();
    $statoAssettoMonitorato = isset($_GET['stato-assetto-monitorato']) ? 2 : 0;
    $statoAssettoLavorazione = isset($_GET['stato-assetto-lavorazione']) ? 1 : 0;
    $statoAssettoAnomalie = isset($_GET['stato-assetto-anomalie']) ? -1 : 0;
    array_push($statiAssetto, $statoAssettoMonitorato);
    array_push($statiAssetto, $statoAssettoLavorazione);
    array_push($statiAssetto, $statoAssettoAnomalie);

    // Stati selezionati "Stato Liste Riferimento"
    $statiListe = array();
    $statoListeMonitorato = isset($_GET['stato-liste-monitorato']) ? 2 : 0;
    $statoListeLavorazione = isset($_GET['stato-liste-lavorazione']) ? 1 : 0;
    $statoListeAnomalie = isset($_GET['stato-liste-anomalie']) ? -1 : 0;
    array_push($statiListe, $statoListeMonitorato);
    array_push($statiListe, $statoListeLavorazione);
    array_push($statiListe, $statoListeAnomalie);

    // Stati selezionati "Stato Scadenza DOC SCA"
    $statiDocSca = array();
    $statoDocScaRegola = isset($_GET['stato-docsca-regola']) ? 2 : -1;
    $statoDocScaScadenza = isset($_GET['stato-docsca-scadenza']) ? 1 : -1;
    $statoDocScaScaduti = isset($_GET['stato-docsca-scaduti']) ? 0 : -1;
    $statoDocScaMancanti = isset($_GET['stato-docsca-mancanti']) ? 3 : -1;
    array_push($statiDocSca, $statoDocScaRegola);
    array_push($statiDocSca, $statoDocScaScadenza);
    array_push($statiDocSca, $statoDocScaScaduti);
    array_push($statiDocSca, $statoDocScaMancanti);

    $dateInserimento = convertDateRangeToSeconds($_GET['datefilter']);

    $searchResults = $supplierToSearch->searchSuppliers($statiFornitore, 
            $statiArt80, $statiAssetto, $statiListe, $statiDocSca, $dateInserimento);
    if($searchResults == -1) {
        $supplierTemp = new Supplier();
        $searchResults = $supplierTemp->getAll();
        $code = 1;
    } else {
        if(count($searchResults) == 0) {
            $code = -1;
        } else {
            $code = 1;
        }
    }
}

?>

<section class="">
    <div class="col-md-12">

        <div class="panel panel-primary filterable" style="margin-bottom:2%;">
            <div class="calendar-heading">
                <div style="padding-left:1%;padding-right:1%;">
                    <span class="legenda-title">Ricerca Fornitori</span>
                </div>
            </div>

            <div class="panel-body">

                <form action="albo-fornitori.php#risultati-ricerca" method="GET"
                      name="search_supplier" id="search-supplier">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rag_sociale">Ragione Sociale:</label>
                                <input type="text" class="form-control" id="rag_sociale" name="rag_sociale"
                                       value="<?php if(isset($_GET['rag_sociale'])) {
                                            echo $_GET['rag_sociale'];
                                        } ?>">
                            </div>

                            <div class="form-group">
                                <label for="cf">Codice Fiscale:</label>
                                <input type="text" class="form-control" id="cf" name="cf"
                                       value="<?php if(isset($_GET['cf'])) {
                                            echo $_GET['cf'];
                                        }?>">
                            </div>
                            <div class="form-group">
                                <label for="stato">Stato Operatore:</label>
                                <div class="dropdown" data-control="checkbox-dropdown">
                                    <label class="dropdown-label">Seleziona</label>

                                    <div class="dropdown-list">
                                      <a href="#" data-toggle="check-all" class="dropdown-check btn btn-success btn-xs">
                                        Seleziona tutti
                                      </a>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-monitorato" value="true"
        <?php                                       if(isset($_GET['stato-monitorato'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                        <img src="assets/img/green_light_big.png" class="semaphore-light" alt="Monitorato">
                                         Monitorato
                                      </label>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-lavorazione" value="true"
        <?php                                       if(isset($_GET['stato-lavorazione'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                        <img src="assets/img/yellow_light_big.png" class="semaphore-light" alt="In Lavorazione">
                                         In Lavorazione
                                      </label>

                                    </div>
                                  </div>
                            </div>
                            <div class="form-group">
                                <label for="stato-assetto">Stato Assetto Societario:</label>
                                <div class="dropdown" data-control="checkbox-dropdown">
                                    <label class="dropdown-label">Seleziona</label>

                                    <div class="dropdown-list">
                                      <a href="#" data-toggle="check-all" class="dropdown-check btn btn-success btn-xs">
                                        Seleziona tutti
                                      </a>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-assetto-monitorato" value="true"
        <?php                                       if(isset($_GET['stato-assetto-monitorato'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                        <img src="assets/img/green_light.png" class="semaphore-light" alt="Monitorato">
                                         Monitorato
                                      </label>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-assetto-lavorazione" value="true"
        <?php                                       if(isset($_GET['stato-assetto-lavorazione'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                        <img src="assets/img/yellow_light.png" class="semaphore-light" alt="In Lavorazione">
                                         In Lavorazione
                                      </label>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-assetto-anomalie" value="true"
        <?php                                       if(isset($_GET['stato-assetto-anomalie'])) {
                                                    echo "checked";
                                                }
                                        ?>
                                        <img src="assets/img/red_light.png" class="semaphore-light" alt="Anomalie">
                                         Anomalie
                                      </label>

                                    </div>
                                  </div>
                            </div>
                            <div class="form-group">
                                <label for="stato-docsca">Stato Scadenza DOC SCA:</label>
                                <div class="dropdown" data-control="checkbox-dropdown">
                                    <label class="dropdown-label">Seleziona</label>

                                    <div class="dropdown-list">
                                      <a href="#" data-toggle="check-all" class="dropdown-check btn btn-success btn-xs">
                                        Seleziona tutti
                                      </a>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-docsca-regola" value="true"
        <?php                                       if(isset($_GET['stato-docsca-regola'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                         Documenti in Regola
                                      </label>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-docsca-scadenza" value="true"
        <?php                                       if(isset($_GET['stato-docsca-scadenza'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                         Documenti in Scadenza
                                      </label>

                                      <label class="dropdown-option">
                                        <input type="checkbox" name="stato-docsca-scaduti" value="true"
        <?php                                       if(isset($_GET['stato-docsca-scaduti'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                         Documenti Scaduti
                                      </label>

                                    <label class="dropdown-option">
                                        <input type="checkbox" name="stato-docsca-mancanti" value="true"
        <?php                                       if(isset($_GET['stato-docsca-mancanti'])) {
                                                    echo "checked";
                                                }
                                        ?>>
                                         Documenti Mancanti
                                    </label>

                                    </div>
                                  </div>
                            </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="iva">Partita IVA:</label>
                                    <input type="text" class="form-control" id="iva" name="iva">
                                </div>
                                <label for="data-appr">Data Inserimento:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="datefilter" id="data-appr" 
                                        value="<?php if(isset($_GET['datefilter'])) {
                                            echo $_GET['datefilter'];
                                        } ?>" />
                                    <div class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stato-art80">Stato Art. 80:</label>
                                    <div class="dropdown" data-control="checkbox-dropdown">
                                        <label class="dropdown-label">Seleziona</label>

                                        <div class="dropdown-list">
                                          <a href="#" data-toggle="check-all" class="dropdown-check btn btn-success btn-xs">
                                            Seleziona tutti
                                          </a>

                                          <label class="dropdown-option">
                                            <input type="checkbox" name="stato-art80-monitorato" value="true"
        <?php                                       if(isset($_GET['stato-art80-monitorato'])) {
                                                    echo "checked";
                                                }
                                            ?>>
                                            <img src="assets/img/green_light.png" class="semaphore-light" alt="Monitorato">
                                             Monitorato
                                          </label>

                                          <label class="dropdown-option">
                                            <input type="checkbox" name="stato-art80-lavorazione" value="true"
        <?php                                       if(isset($_GET['stato-art80-lavorazione'])) {
                                                    echo "checked";
                                                }
                                            ?>>
                                            <img src="assets/img/yellow_light.png" class="semaphore-light" alt="In Lavorazione">
                                             In Lavorazione
                                          </label>

                                          <label class="dropdown-option">
                                            <input type="checkbox" name="stato-art80-anomalie" value="true"
        <?php                                       if(isset($_GET['stato-art80-anomalie'])) {
                                                    echo "checked";
                                                }
                                            ?>>
                                            <img src="assets/img/red_light.png" class="semaphore-light" alt="Anomalie">
                                             Anomalie
                                          </label>

                                        </div>
                                      </div>
                                </div>
                                <div class="form-group">
                                    <label for="stato-liste">Stato Liste Riferimento:</label>
                                    <div class="dropdown" data-control="checkbox-dropdown">
                                        <label class="dropdown-label">Seleziona</label>

                                        <div class="dropdown-list">
                                          <a href="#" data-toggle="check-all" class="dropdown-check btn btn-success btn-xs">
                                            Seleziona tutti
                                          </a>

                                          <label class="dropdown-option">
                                            <input type="checkbox" name="stato-liste-monitorato" value="true"
        <?php                                       if(isset($_GET['stato-liste-monitorato'])) {
                                                    echo "checked";
                                                }
                                            ?>>
                                            <img src="assets/img/green_light.png" class="semaphore-light" alt="Monitorato">
                                             Monitorato
                                          </label>

                                          <label class="dropdown-option">
                                            <input type="checkbox" name="stato-liste-lavorazione" value="true"
        <?php                                       if(isset($_GET['stato-liste-lavorazione'])) {
                                                    echo "checked";
                                                }
                                            ?>>
                                            <img src="assets/img/yellow_light.png" class="semaphore-light" alt="In Lavorazione">
                                             In Lavorazione
                                          </label>

                                          <label class="dropdown-option">
                                            <input type="checkbox" name="stato-liste-anomalie" value="true"
        <?php                                       if(isset($_GET['stato-liste-anomalie'])) {
                                                    echo "checked";
                                                }
                                            ?>>
                                            <img src="assets/img/red_light.png" class="semaphore-light" alt="Anomalie">
                                             Anomalie
                                          </label>

                                        </div>
                                      </div>
                                </div>

                        </div>
                    </div>

                    <div class="spacing" id="spaceAfterRole"></div>
                    
                    <input type="hidden" value="ricerca-fornitori" name="action">

                    <button type="submit" class="btn btn-primary" name="submit">
                        <span class="glyphicon glyphicon-search"></span> Cerca Operatori
                    </button>
                    <button class="btn btn-warning" id="reset" type="reset">
                        <span class="glyphicon glyphicon-erase"></span> Reset Campi
                    </button>
            </form>
        </div>
    </div>
    <div class="spacing"></div>

<?php
    if($code == -1) {
?>
        <div class="error-message" id="risultati-ricerca">
            <div class="alert alert-info alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="glyphicon glyphicon-exclamation-sign"></span>
                <strong>Nessun operatore trovato.</strong>
            </div>
        </div>

<?php   } else if($code == 1) {

    $_SESSION['searchResults'] = serialize($searchResults);
?>

    <h3><a id="risultati-ricerca"></a>Risultato Ricerca</h3>

        <div class="spacing"></div>

<?php       printSuppliersTable($searchResults, $role); ?>
        
        <div class="spacing"></div>
        <div class="center">
            <a href="albo-download-file.php" class="btn btn-info">
                <span class="glyphicon glyphicon-download-alt"></span> Download Risultati Ricerca
            </a>
            <a href="albo-download-dettaglio-doc.php" class="btn btn-info">
                <span class="glyphicon glyphicon-download-alt"></span> Download Dettaglio Documenti
            </a>
        </div>

        <div class="spacing"></div>
        <div class="spacing"></div>

    </div>
</section>

<div id="editStateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modifica Stato Operatore</h4>
        </div>
          <form id='edit-state'>
              <div class="modal-body"></div>
              <div class="modal-footer">
                <input type="button" class="btn btn-success" id="edit-state-submit"
                   name="edit-state-submit" value="Modifica"
                   onclick="submitForms('search-supplier', 'edit-state',
                      'albo-fornitori.php#risultati-ricerca', 'GET')">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span> Annulla
                </button>
              </div>
          </form>
      </div>

    </div>
  </div>

<?php } ?>

<!-- JS Scripts -->
<script type="text/javascript">
    $(document).ready(function() {
        $("#suppliers-list").tablesorter({
            textExtraction:function(s){
                if($(s).find('img').length == 0) return $(s).text();
                return $(s).find('img').attr('alt');
            }
        });
    });
</script>

<script type="text/javascript" src="assets/js/albo-reset-button.js"></script>
<script type="text/javascript" src='assets/js/albo-modifica-stato.js'></script>
<script type="text/javascript" src="assets/js/dropdown-checkbox.js"></script>
<script type="text/javascript" src="assets/js/daterangepicker-in.js"></script>
<script type="text/javascript" src="assets/js/submitForms.js"></script>