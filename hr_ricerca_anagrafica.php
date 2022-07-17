<!--CONTENUTO PRINCIPALE-->
<section class="">
<div class="row">
<div class="col-md-12">

<?php
if(count($_GET) > 1) {
    $db = (new Database())->dbConnection();

    $queryPart1 = 'SELECT DISTINCT anagrafica.user_id, anagrafica.nome, anagrafica.cognome, '
            . 'anagrafica.codice_fiscale, anagrafica.data_nascita FROM anagrafica '
            . 'INNER JOIN login ON anagrafica.user_id = login.user_idd';
    $queryPart2 = 'WHERE';
    $fields = array('commessa', 'data', 'domicilio', 'catprotetta', 'sesso', 'macchina',
        'pc', 'telefono', 'livello', 'distacco', 'subappalto');
    foreach($fields as $field) {
        $count = 1;
        while(isset($_GET[$field . $count])) {
            $currentValue = $_GET[$field . $count];

            switch($field) {
                case 'commessa':
                    if(!strrpos($queryPart1, 'user_commesse')) {
                        $queryPart1 .= ' INNER JOIN user_commesse ON user_commesse.id_user = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' user_commesse.id_commessa = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND user_commesse.id_commessa = "' . $currentValue . '"';
                    }
                    break;

                case 'data':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' anagrafica.data_nascita = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND anagrafica.data_nascita = "' . $currentValue . '"';
                    }
                    break;

                case 'domicilio':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' anagrafica.citta_domicilio = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND anagrafica.citta_domicilio = "' . $currentValue . '"';
                    }
                    break;

                case 'catprotetta':
                    if(!strrpos($queryPart1, 'info_hr')) {
                        $queryPart1 .= ' INNER JOIN info_hr ON info_hr.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' info_hr.cat_protetta = "1"';
                    } else {
                        $queryPart2 .= ' AND info_hr.cat_protetta = "1"';
                    }
                    break;

                case 'sesso':
                    if(!strrpos($queryPart1, 'info_hr')) {
                        $queryPart1 .= ' INNER JOIN info_hr ON info_hr.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' info_hr.d_u = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND info_hr.d_u = "' . $currentValue . '"';
                    }
                    break;

                case 'macchina':
                    if(!strrpos($queryPart1, 'beni_hr')) {
                        $queryPart1 .= ' INNER JOIN beni_hr ON beni_hr.id_user = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' beni_hr.car_text = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND beni_hr.car_text = "' . $currentValue . '"';
                    }
                    break;

                    case 'macchina':
                        if(!strrpos($queryPart1, 'beni_hr')) {
                            $queryPart1 .= ' INNER JOIN beni_hr ON beni_hr.id_user = anagrafica.user_id';
                        }
                        if($queryPart2 == 'WHERE') {
                            $queryPart2 .= ' beni_hr.pc_text = "' . $currentValue . '"';
                        } else {
                            $queryPart2 .= ' AND beni_hr.pc_text = "' . $currentValue . '"';
                        }
                        break;

                        case 'telefono':
                            if(!strrpos($queryPart1, 'beni_hr')) {
                                $queryPart1 .= ' INNER JOIN beni_hr ON beni_hr.id_user = anagrafica.user_id';
                            }
                            if($queryPart2 == 'WHERE') {
                                $queryPart2 .= ' beni_hr.telefono_text = "' . $currentValue . '"';
                            } else {
                                $queryPart2 .= ' AND beni_hr.telefono_text = "' . $currentValue . '"';
                            }
                            break;

                case 'livello':
                    if(!strrpos($queryPart1, 'info_hr')) {
                        $queryPart1 .= ' INNER JOIN info_hr ON info_hr.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' info_hr.livello = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND info_hr.livello = "' . $currentValue . '"';
                    }
                    break;

                case 'distacco':
                    if(!strrpos($queryPart1, 'info_hr')) {
                        $queryPart1 .= ' INNER JOIN info_hr ON info_hr.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' info_hr.distacco = "1"';
                    } else {
                        $queryPart2 .= ' AND info_hr.distacco = "1"';
                    }
                    break;

                    case 'subappalto':
                        if(!strrpos($queryPart1, 'info_hr')) {
                            $queryPart1 .= ' INNER JOIN info_hr ON info_hr.user_id = anagrafica.user_id';
                        }
                        if($queryPart2 == 'WHERE') {
                            $queryPart2 .= ' info_hr.sub = "1"';
                        } else {
                            $queryPart2 .= ' AND info_hr.sub = "1"';
                        }
                        break;

            }

            $count++;
        }
    }

    if($queryPart2 == 'WHERE') {
        $queryPart2 .= ' login.is_active = 1';
    } else {
        $queryPart2 .= ' AND login.is_active = 1';
    }
    $query = $queryPart1 . ' ' . $queryPart2 . ' ORDER BY anagrafica.cognome';
    $result = $db->query($query);

//echo $query;
    if($result && $db->affected_rows > 0) {

        echo '<div class="panel panel-primary filterable" style="margin-bottom:5%;">
                    <div class="legenda-heading">
                        <div style="padding-left:2%;padding-right:2%;">
                                <span class="legenda-title">Risultati Ricerca</span>
                        </div>
                    </div>
              <div class="panel-body" style="padding-left:2%;padding-right:2%;">
                <table class="table">
                <thead>
                    <tr class="filters">
                        <th>Dipendente</th>
                        <th>Codice Fiscale</th>
                        <th>Data di Nascita</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';

        while($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td><a href="https://www.service-tech.it/new_area_riservata/riepilogo.php?id='.$row["user_id"].'">' . $row["cognome"] . ' ' . $row["nome"] . '</a></td>
                    <td>' . $row["codice_fiscale"] . '</td>
                    <td>' . $row["data_nascita"] . '</td>
                    <td><a target="_blank" href="tcpdf/examples/PDF_create.php?id=' .
                        $row["user_id"] . '">
                            <i class="fa fa-file-pdf-o fa-2x" style="color:#fd6c6e;"></i>
                        </a>
                    </td>
                  </tr>';
        }

        echo '</tbody>
            </table>
            </div>
        </div>';

    } else {
        echo '<div class="panel panel-default">
                <div class="panel-body center">Nessun risultato trovato</div>
              </div>';
    }
}
?>

<div id="filter-list" class="filters">

</div>

<div class="panel panel-primary filterable" style="margin-bottom:5%;padding-bottom:2%;">
  <div class="calendar-heading" style="min-height:40px;margin-bottom:2%;">
      <div style="padding-left:2%;padding-right:2%;">
        <span class="legenda-title">Selezione Filtri di Ricerca</span>
    </div>
  </div>
    <div class="row center" style="padding-bottom:7px;padding-right:7px;padding-left:7px;">
        <div class="col-md-12">
            <strong>Commesse:</strong>
        </div>
    </div>
    <hr style="margin-top: 0px !important;">
              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Selezione Commessa:</strong>
              </div>
              <div class="col-md-4">
              <select id="input-commessa" class="form-control" name="commessa" style="width:25%;margin-top:1%;">
                  <option>Seleziona una commessa...</option>
<?php
$commessa = new Commessa();
$allJobs = $commessa->getAll();

foreach($allJobs as $job) {
    echo '<option value="' . $job->id_commessa . '">' .
         $job->nome_commessa . '</option>';
}
?>
          </select>
        </div>
              <div class="col-md-2">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Commessa', 'commessa');""></button>
              </div>
              </div>
              <hr>
              <div class="row center" style="padding-bottom:7px;padding-right:7px;padding-left:7px;">
                  <div class="col-md-12">
                      <strong>Anagrafica:</strong>
                  </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Data di Nascita:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="date" id="input-data" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Data', 'data');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Domiclio:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-domicilio" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Domicilio', 'domicilio');"></button>
              </div>
              </div>
              <hr style="margin-bottom: 0px !important;">

                <div class="row center" style="padding:7px;">
                    <div class="col-md-12">
                        <strong>Info HR:</strong>
                    </div>
                </div>
                <hr style="margin-top: 0px !important;">

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Categoria Protetta:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="checkbox" id="input-catprotetta" name="" value="si">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('CatProtetta', 'catprotetta');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Sesso (D/U):</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-sesso" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Sesso', 'sesso');"></button>
              </div>
              </div>
              <hr>
              <div class="row center" style="padding:7px;">
                  <div class="col-md-12">
                      <strong>Beni HR:</strong>
                  </div>
              </div>
              <hr style="margin-top: 0px !important;">
              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Macchina:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-macchina" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Macchina', 'macchina');"></button>
              </div>
              </div>

              <hr>
              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>PC:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-pc" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('PC', 'pc');"></button>
              </div>
              </div>
              <hr>
              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Telefono:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-telefono" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Telefono', 'telefono');"></button>
              </div>
              </div>

              <hr style="margin-bottom: 0px !important; background-color: LightGray; color: white;">

                <div class="row center" style="padding:7px; ">
                    <div class="col-md-12">
                        <strong>Contratto:</strong>
                    </div>
                </div>
                <hr style="margin-top: 0px !important;">

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Livello:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-livello" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Livello', 'livello');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Subappalto:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="checkbox" id="input-subappalto" name="" value="si">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Subappalto', 'subappalto');"></button>
              </div>
              </div>
              <hr style="margin-bottom: 0px !important;">

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Distacco:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="checkbox" id="input-ente" name="" value="si">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Distacco', 'distacco');"></button>
              </div>
              </div>



</div>

    <div class="row">
        <div class="col-md-2 col-md-offset-5">
            <a href="hr-ricerca-statistiche.php?action=ricerca-anagrafica" id="start-search" class="btn btn-success"
               style="margin-bottom:60px;">
                <span class="glyphicon glyphicon-search"></span> Avvia Ricerca
            </a>
        </div>
    </div>

</div>

</div>
</section>
<!--FINE CONTENUTO PRINCIPALE-->

<script type="text/javascript">
var commessaClicks = 0;
var dataClicks = 0;
var domicilioClicks = 0;
var catprotettaClicks = 0;
var sessoClicks = 0;
var macchinaClicks = 0;
var pcClicks = 0;
var livelloClicks = 0;
var telefonoClicks = 0;
var subappaltoClicks = 0;
var distaccoClicks = 0;

function addFilter(filterType, filterInput) {
    // Aumento contatore
    window[filterInput + 'Clicks']++;

    // Aggiunta bottone filtro
    var button = document.createElement('button');
    button.setAttribute('id', filterInput + window[filterInput + 'Clicks']);
    var input = document.getElementById("input-" + filterInput);
    var buttonInput;
    if(filterType == 'Commessa') {
        buttonInput = input.options[input.selectedIndex].value;
    } else {
        buttonInput = document.getElementById("input-" + filterInput).value;
        document.getElementById("input-" + filterInput).value = '';
    }
    button.className = 'btn btn-primary btn-xs btn-filter';
    button.setAttribute('onclick', "removeFilter('" + filterInput + "');");
    button.innerHTML = filterType + ': ' + buttonInput + ' ' +
            '<span class="glyphicon glyphicon glyphicon-remove-circle"></span>';
    document.getElementById("filter-list").appendChild(button);

    // Modifica link per la ricerca
    var link = document.getElementById("start-search").getAttribute('href');
    link += '&' + filterInput + window[filterInput + 'Clicks'] + '=' + buttonInput;
    document.getElementById("start-search").setAttribute('href', link);
    return false;
}

function removeFilter(filterInput) {
    // Rimozione bottone filtro
    var id = filterInput + window[filterInput + 'Clicks'];
    var button = document.getElementById(id);
    button.parentNode.removeChild(button);
    window[filterInput + 'Clicks']--;

    // Rimozione input GET
    var link = document.getElementById("start-search").getAttribute('href');
    var indexStart = link.indexOf(id);
    var indexEnd = link.indexOf('&', indexStart + 1);
    if(indexEnd == -1) {
        indexEnd = link.length;
    }
    var partToRemove = link.substring(indexStart - 1, indexEnd);
    link = link.replace(partToRemove, '');
    document.getElementById("start-search").setAttribute('href', link);
    return false;
}
</script>
