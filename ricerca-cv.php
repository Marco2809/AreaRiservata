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
    $fields = array('area', 'ruolo', 'mansione', 'istituto', 'laurea', 'studi',
        'tipologia', 'livello', 'titolo', 'ente', 'corso');
    foreach($fields as $field) {
        $count = 1;
        while(isset($_GET[$field . $count])) {
            $currentValue = $_GET[$field . $count];

            switch($field) {
                case 'area':
                    if(!strrpos($queryPart1, 'esperienza')) {
                        $queryPart1 .= ' INNER JOIN esperienza ON esperienza.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' esperienza.area = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND esperienza.area = "' . $currentValue . '"';
                    }
                    break;

                case 'ruolo':
                    if(!strrpos($queryPart1, 'esperienza')) {
                        $queryPart1 .= ' INNER JOIN esperienza ON esperienza.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' esperienza.ruolo = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND esperienza.ruolo = "' . $currentValue . '"';
                    }
                    break;

                case 'mansione':
                    if(!strrpos($queryPart1, 'esperienza')) {
                        $queryPart1 .= ' INNER JOIN esperienza ON esperienza.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' esperienza.mansione = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND esperienza.mansione = "' . $currentValue . '"';
                    }
                    break;

                case 'istituto':
                    if(!strrpos($queryPart1, 'formazione')) {
                        $queryPart1 .= ' INNER JOIN formazione ON formazione.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' formazione.ateneo = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND formazione.ateneo = "' . $currentValue . '"';
                    }
                    break;

                case 'laurea':
                    if(!strrpos($queryPart1, 'formazione')) {
                        $queryPart1 .= ' INNER JOIN formazione ON formazione.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' formazione.laurea = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND formazione.laurea = "' . $currentValue . '"';
                    }
                    break;

                case 'studi':
                    if(!strrpos($queryPart1, 'formazione')) {
                        $queryPart1 .= ' INNER JOIN formazione ON formazione.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' formazione.corso = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND formazione.corso = "' . $currentValue . '"';
                    }
                    break;

                case 'tipologia':
                    if(!strrpos($queryPart1, 'skill')) {
                        $queryPart1 .= ' INNER JOIN skill ON skill.skill_user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' skill.skill = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND skill.skill = "' . $currentValue . '"';
                    }
                    break;

                case 'livello':
                    if(!strrpos($queryPart1, 'skill')) {
                        $queryPart1 .= ' INNER JOIN skill ON skill.skill_user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' skill.livello_skill = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND skill.livello_skill = "' . $currentValue . '"';
                    }
                    break;

                case 'titolo':
                    if(!strrpos($queryPart1, 'certificazione')) {
                        $queryPart1 .= ' INNER JOIN certificazione ON certificazione.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' certificazione.titolo_certificazione = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND certificazione.titolo_certificazione = "' . $currentValue . '"';
                    }
                    break;

                case 'ente':
                    if(!strrpos($queryPart1, 'certificazione')) {
                        $queryPart1 .= ' INNER JOIN certificazione ON certificazione.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' certificazione.ente_certificante = "' . $currentValue . '"';
                    } else {
                        $queryPart2 .= ' AND certificazione.ente_certificante = "' . $currentValue . '"';
                    }
                    break;

                case 'corso':
                    if($queryPart1.indexOf('corsi') == -1) {
                        $queryPart1 .= ' INNER JOIN corsi ON corsi.user_id = anagrafica.user_id';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' corsi.id_corso = ' . $currentValue;
                    } else {
                        $queryPart2 .= ' AND corsi.id_corso = ' . $currentValue;
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
                    <td>' . $row["cognome"] . ' ' . $row["nome"] . '</td>
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
            <strong>Esperienza lavorativa:</strong>
        </div>
    </div>
    <hr style="margin-top: 0px !important;">
              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Area di Competenza:</strong>
              </div>
              <div class="col-md-4">
              <select name="input-area" id="input-area" style="height:30px;width:65%;" class="form-control">
                  <option value="Sys Admin">Sys Admin</option>
                  <option value="Tecnico Hardware">Tecnico Hardware</option>
                  <option value="Phisical Network Developer">Phisical Network Developer</option>
                  <option value="Network Admin">Network Admin</option>
                  <option value="Consulente Direzionale">Consulente Direzionale</option>
                  <option value="Developer - IT Solutions">Developer - IT Solutions</option>
                  <option value="Web Design - Grafica 3D">Web Design - Grafica 3D</option>
                  <option value="Altro">Altro</option>
              </select>
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Area', 'area');""></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Ruolo:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-ruolo" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Ruolo', 'ruolo');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Mansione:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-mansione" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Mansione', 'mansione');"></button>
              </div>
              </div>
              <hr style="margin-bottom: 0px !important;">

                <div class="row center" style="padding:7px;">
                    <div class="col-md-12">
                        <strong>Formazione:</strong>
                    </div>
                </div>
                <hr style="margin-top: 0px !important;">

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Nome Istituto:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-istituto" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Istituto', 'istituto');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Corso di Laurea:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-laurea" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Corso di Laurea', 'laurea');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Corso di Studi:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-studi" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Corso di Studi', 'studi');"></button>
              </div>
              </div>
              <hr style="margin-bottom: 0px !important;">

                <div class="row center" style="padding:7px;">
                    <div class="col-md-12">
                        <strong>Skill:</strong>
                    </div>
                </div>
                <hr style="margin-top: 0px !important;">

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Tipologia:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-tipologia" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Tipologia', 'tipologia');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Livello:</strong>
              </div>
              <div class="col-md-4">
                <select name="input-livello" id="input-livello" style="height:30px;width:65%;" class="form-control">
                    <option value="Principiante"> Principiante </option>
                    <option value="Intermedio"> Intermedio </option>
                    <option value="Avanzato"> Avanzato </option>
                </select>
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Livello', 'livello');"></button>
              </div>
              </div>
              <hr style="margin-bottom: 0px !important; background-color: LightGray; color: white;">

                <div class="row center" style="padding:7px; ">
                    <div class="col-md-12">
                        <strong>Certificazione:</strong>
                    </div>
                </div>
                <hr style="margin-top: 0px !important;">

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Titolo:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-titolo" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Titolo', 'titolo');"></button>
              </div>
              </div>
              <hr>

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Ente:</strong>
              </div>
              <div class="col-md-4">
              <input style="width:65%;" class="form-control" type="text" id="input-ente" name="" value="">
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Ente', 'ente');"></button>
              </div>
              </div>
              <hr style="margin-bottom: 0px !important;">

                <div class="row center" style="padding:7px;">
                    <div class="col-md-12">
                        <strong>Corsi:</strong>
                    </div>
                </div>
                <hr style="margin-top: 0px !important;">

              <div class="row" style="padding-left:15%;">
              <div class="col-md-4">
              <strong>Tipologia:</strong>
              </div>
              <div class="col-md-4">
                <select name="input-corso" id="input-corso" style="height:30px;width:65%;" class="form-control">
                    <option value="Corso Base"> Corso Base </option>
                    <option value="Primo Soccorso"> Primo Soccorso </option>
                    <option value="Anti Incendio"> Anti Incendio </option>
                    <option value="Capocantiere"> Capocantiere </option>
                    <option value="RLS"> RLS </option>
                    <option value="RSSP"> RSSP </option>
                </select>
              </div>
              <div class="col-md-4">
              <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Tipo Corso', 'corso');"></button>
              </div>
              </div>



</div>

    <div class="row">
        <div class="col-md-2 col-md-offset-5">
            <a href="hr-ricerca-statistiche.php?action=ricerca-cv" id="start-search" class="btn btn-success"
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
var areaClicks = 0;
var ruoloClicks = 0;
var mansioneClicks = 0;
var istitutoClicks = 0;
var laureaClicks = 0;
var studiClicks = 0;
var tipologiaClicks = 0;
var livelloClicks = 0;
var titoloClicks = 0;
var enteClicks = 0;
var corsoClicks = 0;

function addFilter(filterType, filterInput) {
    // Aumento contatore
    window[filterInput + 'Clicks']++;

    // Aggiunta bottone filtro
    var button = document.createElement('button');
    button.setAttribute('id', filterInput + window[filterInput + 'Clicks']);
    var input = document.getElementById("input-" + filterInput);
    var buttonInput;
    if(filterType == 'Area' || filterType == 'Livello' || filterType == 'Tipo Corso') {
        buttonInput = input.options[input.selectedIndex].text;
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
