<!--CONTENUTO PRINCIPALE-->
<section class="">
<div class="row">
<div class="col-md-12">

<?php
if(count($_GET) > 1) {
    $db = (new Database())->dbConnection();

    $queryPart1 = 'SELECT DISTINCT cv_esterni.id_cv_esterni, nome, cognome, cf, ruolo, citta, eta, anno_cv FROM cv_esterni';
    $queryPart2 = 'WHERE';
    $fields = array('nome', 'cognome', 'citta', 'ruolo', 'eta', 'annoCV', 'competenze');
    foreach($fields as $field) {
        $count = 1;
        while(isset($_GET[$field . $count])) {
            $currentValue = trim($_GET[$field . $count]);

            switch($field) {
                case 'nome':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' nome LIKE "%' . $currentValue . '%"';
                    } else {
                        $queryPart2 .= ' OR nome LIKE "%' . $currentValue . '%"';
                    }
                    break;

                case 'cognome':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' cognome LIKE "%' . $currentValue . '%"';
                    } else {
                        $queryPart2 .= ' OR cognome LIKE "%' . $currentValue . '%"';
                    }
                    break;

                case 'ruolo':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' ruolo LIKE "%' . $currentValue . '%"';
                    } else {
                        $queryPart2 .= ' OR ruolo LIKE "%' . $currentValue . '%"';
                    }
                    break;

                case 'citta':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' citta LIKE "%' . $currentValue . '%"';
                    } else {
                        $queryPart2 .= ' OR citta LIKE "%' . $currentValue . '%"';
                    }
                    break;

                case 'eta':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' eta = "%' . $currentValue . '%"';
                    } else {
                        $queryPart2 .= ' OR eta = "%' . $currentValue . '%"';
                    }
                    break;

                case 'annoCV':
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' anno_cv LIKE "%' . $currentValue . '%"';
                    } else {
                        $queryPart2 .= ' OR anno_cv LIKE "%' . $currentValue . '%"';
                    }
                    break;

                case 'competenze':
                    $currentCompetenza = explode("-", $currentValue);
                    if ($currentCompetenza[1] == '') {
                        $currentCompetenza[1] = 0;
                    }

                    if(!strrpos($queryPart1, 'INNER JOIN')) {
                        $queryPart1 .= ' INNER JOIN cv_esterni_competenze ON cv_esterni.id_cv_esterni = cv_esterni_competenze.id_cv_esterni';
                    }
                    if($queryPart2 == 'WHERE') {
                        $queryPart2 .= ' (competenza = "' . trim($currentCompetenza[0]) . '" AND anni >= ' . trim($currentCompetenza[1]) . ')';
                    } else {
                        $queryPart2 .= ' OR (competenza = "' . trim($currentCompetenza[0]) . '" AND anni >= ' . trim($currentCompetenza[1]) . ')';
                    }
                    break;
            }

            $count++;
        }
    }

    $query = $queryPart1 . ' ' . $queryPart2;
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
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Codice Fiscale</th>
                        <th>Ruolo</th>
                        <th>Città</th>
                        <th>Età</th>
                        <th>Anno CV</th>
                        <th>Competenze</th>
                    </tr>
                </thead>
                <tbody>';

        while($row = $result->fetch_assoc()) {
            $queryCompetence = "SELECT `id_competenze`, `competenza`, `anni` FROM cv_esterni_competenze WHERE id_cv_esterni = " . $row["id_cv_esterni"];
            $resultCompetence = $db->query($queryCompetence);
            $rowspan = $db->affected_rows + 1;

            echo '<tr>
                    <td style="vertical-align: middle;" rowspan="' . $rowspan . '">' . $row["nome"] . '</td>
                    <td style="vertical-align: middle;" rowspan="' . $rowspan . '">' . $row["cognome"] . '</td>
                    <td style="vertical-align: middle;" rowspan="' . $rowspan . '">' . $row["cf"] . '</td>
                    <td style="vertical-align: middle;" rowspan="' . $rowspan . '">' . $row["ruolo"] . '</td>
                    <td style="vertical-align: middle;" rowspan="' . $rowspan . '">' . $row["citta"] . '</td>
                    <td style="vertical-align: middle;" rowspan="' . $rowspan . '">' . $row["eta"] . '</td>
                    <td style="vertical-align: middle;" rowspan="' . $rowspan . '">' . $row["anno_cv"] . '</td>
                  </tr>';

            while($rowCompetence = $resultCompetence->fetch_assoc()) {
                $anniString = ($rowCompetence["anni"] == 1) ? ' Anno' : ' Anni';
                echo '<tr>
                    <td>' . $rowCompetence["competenza"] . ' - ' . $rowCompetence["anni"] . $anniString . '</td>
                  </tr>';
            }
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
    <div class="row" style="padding-left:15%;">
        <div class="col-md-4">
            <strong>Nome:</strong>
        </div>
        <div class="col-md-4">
            <input style="width:65%;" class="form-control" type="text" id="input-nome" value="" autocomplete="off">
        </div>
        <div class="col-md-4">
            <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Nome', 'nome');"></button>
        </div>
    </div>
    <hr>
    <div class="row" style="padding-left:15%;">
        <div class="col-md-4">
            <strong>Cognome:</strong>
        </div>
        <div class="col-md-4">
            <input style="width:65%;" class="form-control" type="text" id="input-cognome" value="" autocomplete="off">
        </div>
        <div class="col-md-4">
            <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Cognome', 'cognome');"></button>
        </div>
    </div>
    <hr>
    <div class="row" style="padding-left:15%;">
        <div class="col-md-4">
            <strong>Ruolo:</strong>
        </div>
        <div class="col-md-4">
            <input style="width:65%;" class="form-control" type="text" id="input-ruolo" value="" autocomplete="off">
        </div>
        <div class="col-md-4">
            <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Ruolo', 'ruolo');"></button>
        </div>
    </div>
    <hr>
    <div class="row" style="padding-left:15%;">
        <div class="col-md-4">
            <strong>Città:</strong>
        </div>
        <div class="col-md-4">
            <input style="width:65%;" class="form-control" type="text" id="input-citta" value="" autocomplete="off">
        </div>
        <div class="col-md-4">
            <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Città', 'citta');"></button>
        </div>
    </div>
    <hr>
    <div class="row" style="padding-left:15%;">
        <div class="col-md-4">
            <strong>Età:</strong>
        </div>
        <div class="col-md-4">
            <input style="width:65%;" class="form-control" type="text" id="input-eta" value="" autocomplete="off">
        </div>
        <div class="col-md-4">
            <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Età', 'eta');"></button>
        </div>
    </div>
    <hr>
    <div class="row" style="padding-left:15%;">
        <div class="col-md-4">
            <strong>Anno CV:</strong>
        </div>
        <div class="col-md-4">
            <input style="width:65%;" class="form-control" type="number" id="input-annoCV" value="" autocomplete="off">
        </div>
        <div class="col-md-4">
            <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Anno CV', 'annoCV');"></button>
        </div>
    </div>
    <hr style="margin-bottom: 0px !important;">
    <div class="row center" style="padding: 7px;">
        <div class="col-md-12">
            <strong>Competenze Informatiche:</strong>
        </div>
    </div>
    <hr style="margin-top: 0px !important;">
    <div class="row" style="padding-left:15%;">
        <div class="col-md-1">
            <strong>Competenza:</strong>
        </div>
        <div class="col-md-3">
            <input style="width:65%;" class="form-control" type="text" id="input-competenza" value="" autocomplete="off">
        </div>
        <div class="col-md-1">
            <strong>Anni di Esperienza:</strong>
        </div>
        <div class="col-md-3">
            <input style="width:65%;" class="form-control" type="number" id="input-anni-esperienza" value="" autocomplete="off">
        </div>
        <div class="col-md-2">
            <button class="fa fa-plus-circle fa-2x button-clear" onclick="addFilter('Competenze Informatiche', 'competenze');"></button>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-2 col-md-offset-5">
            <a href="hr-ricerca-statistiche.php?action=ricerca-cv-esterno" id="start-search" class="btn btn-success"
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
var nomeClicks = 0;
var cognomeClicks = 0;
var ruoloClicks = 0;
var cittaClicks = 0;
var etaClicks = 0;
var annoCVClicks = 0;
var competenzeClicks = 0;

function addFilter(filterType, filterInput) {
    // Aumento contatore
    window[filterInput + 'Clicks']++;

    // Aggiunta bottone filtro
    var button = document.createElement('button');
    button.setAttribute('id', filterInput + window[filterInput + 'Clicks']);
    var input = document.getElementById("input-" + filterInput);
    var buttonInput;
    var yearString;
    if(filterType === 'Competenze Informatiche') {
        if (document.getElementById("input-anni-esperienza").value === '') {
            buttonInput = document.getElementById("input-competenza").value;
            yearString = '';
        } else if (document.getElementById("input-anni-esperienza").value === '1') {
            buttonInput = document.getElementById("input-competenza").value + " - " +
                document.getElementById("input-anni-esperienza").value;
            yearString = ' Anno';
        } else {
            buttonInput = document.getElementById("input-competenza").value + " - " +
                document.getElementById("input-anni-esperienza").value;
            yearString = ' Anni';
        }
        button.innerHTML = filterType + ': ' + buttonInput + yearString +
            ' <span class="glyphicon glyphicon glyphicon-remove-circle"></span>';
        document.getElementById("input-competenza").value = '';
        document.getElementById("input-anni-esperienza").value = '';
    } else {
        buttonInput = document.getElementById("input-" + filterInput).value;
        document.getElementById("input-" + filterInput).value = '';
        button.innerHTML = filterType + ': ' + buttonInput + ' ' +
            ' <span class="glyphicon glyphicon glyphicon-remove-circle"></span>';
    }
    button.className = 'btn btn-primary btn-xs btn-filter';
    button.setAttribute('onclick', "removeFilter('" + filterInput + "');");
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
    if(indexEnd === -1) {
        indexEnd = link.length;
    }
    var partToRemove = link.substring(indexStart - 1, indexEnd);
    link = link.replace(partToRemove, '');
    document.getElementById("start-search").setAttribute('href', link);
    return false;
}
</script>
