<?php

function sec_session_start() {
        $session_name = 'sec_session_id'; // Imposta un nome di sessione
        $secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
        $httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
        ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
        $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
        session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
        session_start(); // Avvia la sessione php.
        session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
}

function login($username, $password, $mysqli) {
    if($stmt = $mysqli->prepare("SELECT username, password, user_idd, is_admin FROM login WHERE username = ? LIMIT 1")) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($db_user, $db_password, $db_user_id, $db_is_admin);
        $stmt->fetch();
        $password = hash('sha512', $password);
        if($stmt->num_rows == 1) {

            if(checkbrute($db_user_id, $mysqli) == true) {
               return false;
            } else {
            if($db_password == $password) {
                $user_browser = $_SERVER['HTTP_USER_AGENT'];

                $user_id = preg_replace("/[^0-9]+/", "", $db_user_id);
                $_SESSION['user_idd'] = $db_user_id;
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
                $_SESSION['username'] = $username;
                $_SESSION['logged'] = 1;

                if($db_is_admin == 1) {
                    $_SESSION['is_admin'] = 1;
                } else if($db_is_admin == 2) {
                    $_SESSION['is_admin'] = 2;
                } else {
                    $_SESSION['is_admin'] = 0;
                }
                // $_SESSION['login_string'] = hash('sha512', $password.$user_browser);

                $stmt = $mysqli->prepare("SELECT nome, cognome FROM anagrafica WHERE user_id = ? LIMIT 1");
                $stmt->bind_param('i', $_SESSION['user_idd']);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($db_name, $db_surname);
                $stmt->fetch();

                $_SESSION['name'] = $db_name;
                $_SESSION['surname'] = $db_surname;

                /*
                $stmt = $mysqli->prepare("SELECT * FROM commesse WHERE id_responsabile = ? LIMIT 1");
                $stmt->bind_param('i', $_SESSION['user_idd']);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($idCommessa, $idResp, $commessa, $cliente, $nome_comm);
                $stmt->fetch();

                if($stmt->num_rows > 0){
                    $_SESSION['responsabile'] = 1;
                } else {
                    $_SESSION['responsabile'] = 0;
                }
                 */

              return true;
           } else {
              // Password incorretta.
              // Registriamo il tentativo fallito nel database.
              $now = time();
              $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
              return false;
           }
        }
        } else {
           // L'utente inserito non esiste.
           return false;
        }
    }
}

function supplier_login($username, $password, $mysqli) {
    if($stmt = $mysqli->prepare("SELECT login.username, login.password, login.user_idd, "
            . "user_supplier.supplier_id, suppliers.ragione_sociale "
            . "FROM user_supplier INNER JOIN login ON login.user_idd = user_supplier.user_id "
            . "INNER JOIN suppliers ON user_supplier.supplier_id = suppliers.id "
            . "WHERE username = ? LIMIT 1")) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($db_user, $db_password, $db_user_id, $db_supplier_id, $db_rag_sociale);
        $stmt->fetch();
        $password = hash('sha512', $password);
        if($stmt->num_rows == 1) {

            if(checkbrute($db_user_id, $mysqli) == true) {
               return false;
            } else {
                if($db_password == $password) {
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];

                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_idd'] = $db_user_id;
                    $_SESSION['supplier_id'] = $db_supplier_id;
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // Protezione da un attacco XSS
                    $_SESSION['supplier_rag_sociale'] = $db_rag_sociale;
                    $_SESSION['logged'] = 1;
                    $_SESSION['is_supplier'] = 1;

                  return true;
               } else {
                  // Password incorretta.
                  // Registriamo il tentativo fallito nel database.
                  $now = time();
                  $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
                  return false;
               }
            }
        } else {
           // L'utente inserito non esiste.
           return false;
        }
    }
}

function checkbrute($user_id, $mysqli) {
   // Recupero il timestamp
   $now = time();
   // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
   $valid_attempts = $now - (2 * 60 * 60);
   if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) {
      $stmt->bind_param('i', $user_id);
      // Eseguo la query creata.
      $stmt->execute();
      $stmt->store_result();
      // Verifico l'esistenza di più di 5 tentativi di login falliti.
      if($stmt->num_rows > 5) {
         return true;
      } else {
         return false;
      }
   }
}

function login_check($mysqli) {
   // Verifica che tutte le variabili di sessione siano impostate correttamente
   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
     $user_id = $_SESSION['user_id'];
     $login_string = $_SESSION['login_string'];
     $username = $_SESSION['username'];
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
     if ($stmt = $mysqli->prepare("SELECT password FROM User WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // esegue il bind del parametro '$user_id'.
        $stmt->execute(); // Esegue la query creata.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // se l'utente esiste
           $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
           $stmt->fetch();
           $login_check = hash('sha512', $password.$user_browser);
           if($login_check == $login_string) {
              // Login eseguito!!!!
              return true;
           } else {
              //  Login non eseguito
              return false;
           }
        } else {
            // Login non eseguito
            return false;
        }
     } else {
        // Login non eseguito
        return false;
     }
   } else {
     // Login non eseguito
     return false;
   }

}

function presenzeautomatiche($user_id) {
    if($stmt = $mysqli->prepare("SELECT * FROM user_commesse WHERE id_user = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id);
        // Eseguo la query creata.
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($db_name, $db_surname);
        $stmt->fetch();
    }
}


function filterActivities($idUser, $month, $year, $jobName, $activity, $jobIds, $mysqli) {
    $month = getMonthTwoDigits($month);
    // $stato = 'Validato'; // AND stato = ?
    $activitiesOutput = array();
    $currentActivity = array("", "", "");
    $query = "SELECT data, ore, commesse.id_commessa, commesse.commessa
              FROM attivita
              INNER JOIN commesse ON commesse.id_commessa = attivita.id_commessa
              WHERE id_utente = ?
                AND SUBSTRING(data, 4, 2) = ?
                AND SUBSTRING(data, 7, 4) = ?
                AND LOWER(commessa) LIKE LOWER(CONCAT('%', ?, '%'))
                AND ";
    if ($activity == 'Presente') {
        $query .= "(tipo = ? OR tipo = ?)";
    } else {
        $query .= "tipo = ?";
    }

    if ($jobIds != null) {
        $query .= " AND attivita.id_commessa IN (" . $jobIds . ")";
    }
    $stmt = $mysqli->prepare($query);
    if ($activity == 'Presente') {
        $activityLA = 'Lavoro Agile';
        $stmt->bind_param('isssss', $idUser, $month, $year, $jobName, $activity, $activityLA);
    } else {
        $stmt->bind_param('issss', $idUser, $month, $year, $jobName, $activity);
    }
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($dbData, $dbOre, $dbIDCommessa, $dbNomeCommessa);
    while($stmt->fetch()) {
        $currentActivity[0] = getDayFromDate($dbData);
        $currentActivity[1] = $dbOre;
        $currentActivity[2] = $dbIDCommessa;
        $currentActivity[3] = $dbNomeCommessa;
        array_push($activitiesOutput, $currentActivity);
    }
    return $activitiesOutput;
}

function consoleLog($message) {
    echo '<script>console.log("' . $message . '");</script>';
}

function getTimestamp($day, $month, $year) {
    $tempDate = strptime($day . '-' . $month . '-' . $year, '%d-%m-%Y');
    return mktime(0, 0, 0, $tempDate['tm_mon']+1, $tempDate['tm_mday'], $tempDate['tm_year']+1900);
}

function getDayFromDate($date) {
    $day = substr($date, 0, 2);
    if(substr($day, 0, 1) == '0') {
        $day = substr($day, 1, 1);
    }
    return $day;
}

function getPosDayFromDate($timestamp) {
    return date("N", $timestamp);
}

function getColumnFromIndex($index) {
    return PHPExcel_Cell::stringFromColumnIndex($index + 6);
}

function getColumnFromIndexMassive($index) {
    return PHPExcel_Cell::stringFromColumnIndex($index);
}

function getJobNameFromId($jobId, $mysqli) {
    $stmt = $mysqli->prepare("SELECT cliente FROM commesse WHERE id_commessa = ? LIMIT 1");
    $stmt->bind_param('i', $jobId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($jobName);
    $stmt->fetch();
    return $jobName;
}

function getMonthOneDigit($month) {
    switch($month) {
        case '01':
            $month = 1;
            break;
        case '02':
            $month = 2;
            break;
        case '03':
            $month = 3;
            break;
        case '04':
            $month = 4;
            break;
        case '05':
            $month = 5;
            break;
        case '06':
            $month = 6;
            break;
        case '07':
            $month = 7;
            break;
        case '08':
            $month = 8;
            break;
        case '09':
            $month = 9;
            break;
    }

    return $month;
}

function getMonthTwoDigits($month) {
    if($month < 10) {
        $month = '0' . $month;
    }
    return $month;
}

function getMonthName($month) {
    $monthName = '';
    switch($month) {
        case '01':
            $monthName = 'Gennaio';
            break;

        case '02':
            $monthName = 'Febbraio';
            break;

        case '03':
            $monthName = 'Marzo';
            break;

        case '04':
            $monthName = 'Aprile';
            break;

        case '05':
            $monthName = 'Maggio';
            break;

        case '06':
            $monthName = 'Giugno';
            break;

        case '07':
            $monthName = 'Luglio';
            break;

        case '08':
            $monthName = 'Agosto';
            break;

        case '09':
            $monthName = 'Settembre';
            break;

        case '10':
            $monthName = 'Ottobre';
            break;

        case '11':
            $monthName = 'Novembre';
            break;

        case '12':
            $monthName = 'Dicembre';
            break;
    }

    return $monthName;
}

function checkCommessaInAttivita($idCommessa, $attivita) {

    if($idCommessa == $attivita[7]) {
        return true;
    } else {
        return false;
    }

}

function printSuppliersTable($supplierList, $role) {

    echo '<table id="suppliers-list" class="tablesorter">

        <thead>
            <tr>
                <th style="padding-top:1%;padding-bottom:1%;"><span style="color:#fff;font-size:14px;font-weight:800;">Ragione Sociale</span></th>
                <th style="padding-top:1%;padding-bottom:1%;"><span style="color:#fff;font-size:14px;font-weight:800;">Partita IVA</span></th>
                <th style="padding-top:1%;padding-bottom:1%;"><span style="color:#fff;font-size:14px;font-weight:800;">Codice Fiscale</span></th>
                <th style="padding-top:1%;padding-bottom:1%;"><span style="color:#fff;font-size:14px;font-weight:800;">Stato Operatore</span></th>
                <th style="padding-top:1%;padding-bottom:1%;"><span style="color:#fff;font-size:14px;font-weight:800;">Data Inserimento OE</span></th>
                <th style="padding-top:1%;padding-bottom:1%;"><span style="color:#fff;font-size:14px;font-weight:800;">Art. 80</span></th>
                <th style="padding-top:1%;padding-bottom:1%;"><span style="color:#fff;font-size:14px;font-weight:800;">Stato Scadenza DOC</span></th>
                <th style="padding-top:1%;padding-bottom:1%;" class="actions"><span style="color:#fff;font-size:14px;font-weight:800;">Azioni</span></th>';

    echo    '</tr>
        </thead>
        <tfoot class="supplier-table-foot">
            <tr>
              <td colspan="10">
                <div class="pager">
                  <div class="pager-center">
                    <span class="prev">
                        <img src="assets/img/prev.png" />Precedente
                    </span>
                    <span class="pagecount"></span>
                    &nbsp;<span class="next">Successivo
                        <img src="assets/img/next.png">
                    </span>
                    <div class="pager-right pull-right">
                    # per pagina:
                    <a href="#" class="current">25</a> |
                    <a href="#">50</a> |
                    <a href="#">100</a>
                  </div>
                  </div>
                </div>
              </td>
            </tr>
          </tfoot>
        <tbody>';

    for($i=0; $i<count($supplierList); $i++) {
        echo '<tr>';
        echo '<td class="ragione-sociale"><a href="./albo-fornitori.php?action=anagrafica&id=' . $supplierList[$i]->getId() . '">' . $supplierList[$i]->getRagSociale() . '</a></td>';
        echo '<td>' . $supplierList[$i]->getIva() . '</td>';
        echo '<td>' . $supplierList[$i]->getCf() . '</td>';
        echo '<td class="stato-fornitore">' . getImageStatusBig($supplierList[$i]->getStato()) .
                ' ' . getTextStatus($supplierList[$i]->getStato()) . '</td>';
        echo '<td>' . getFinalDate($supplierList[$i]->getDataInsOE()) . '</td>';
        echo '<td>' . getImageStatus($supplierList[$i]->getStatoArt80()) . '</td>';
        echo '<td class="doc-sca">' . getDocScaLabel($supplierList[$i]->getStatoDocScad()) . '</td>';
        echo '<td class="azioni"><button type="button" class="btn btn-primary
            btn-xs" data-toggle="modal" data-target="#editStateModal"
            data-supplier-id="' . $supplierList[$i]->getId() .
            '" data-supplier-state="' . $supplierList[$i]->getStato() .
            '" data-supplier-name="' . $supplierList[$i]->getRagSociale() . '">
            <span class="glyphicon glyphicon-pencil"></span> Modifica Stato</button></td>';

        echo '</tr>';
    }

    echo '</tbody>
        </table>';

}

function printSupplierTop($id, $page) {
    $linkAnagraphic = '#';
    $linkDocuments = '#';

    $btnAnagraphic = 'btn-default';
    $btnDocuments = 'btn-default';

    if($page === 'anagrafica') {
        $linkDocuments = 'albo-fornitori.php?action=documenti&id=' . $id;
        $btnAnagraphic = 'btn-primary';
    }

    if($page === 'documenti') {
        $linkAnagraphic = 'albo-fornitori.php?action=anagrafica&id=' . $id;
        $btnDocuments = 'btn-primary';
    }

    if(!$_SESSION['is_supplier']) {
        echo '<div class="btn-group btn-group-justified">';
        echo '<a href="' . $linkAnagraphic . '" class="btn ' . $btnAnagraphic . '">Anagrafica</a>';
        echo '<a href="' . $linkDocuments . '" class="btn ' . $btnDocuments . '">Documenti</a>';
        echo '</div>';
    }
}

function printSupplierDocumentsTable($supplierId, $supplierDocuments) {

    $documentsNames = array('Informativa sulla privacy',
        'Bilanci (ultimi due)',
        'Documento Identità del Legale Rappresentante',
        'Casellario giudiziale di ciascuno dei soggetti indicati sulla camera di commercio',
        'Carichi pendenti di ciascuno dei soggetti indicati sulla camera di commercio',
        'Casellario Anagrafe sanzioni amministrative dipendenti da reato',
        'Certificato carichi pendenti risultanti al SI dell’Anagrafe tributaria',
        'Dichiarazione sostitutiva di certificazione ai sensi del d.lgs 81/2008',
        'DURC',
        'Visura Camerale',
        'ISO 9001');

    $documentsNamesModal = array('Inf. Privacy',
        'Bilanci',
        'Doc. Identità Legale Rappr.',
        'Cas. Giudiziale Sogg. Camera Comm.',
        'Carichi pendenti',
        'Cas. Anagrafe Sanzioni Amm.',
        'Cert. Carichi Pendenti',
        'Dic. sostitutiva cert.',
        'DURC',
        'Visura Camerale',
        'ISO 9001');

    echo '<table class="table table-bordered table-art80">
            <thead>
                <tr>
                    <th style="background-color:#fff;">Documento</th>
                    <th style="background-color:#fff;">Date</th>
                    <th style="background-color:#fff;">Anomalie</th>';

//    if($_SESSION['role'] == 'admin') {
        echo '<th style="background-color:#fff;">Operazioni</th>';
//    }

    echo '<th style="background-color:#fff;">Note</th>
                </tr>
            </thead>
            <tbody style="background-color:#eeeded;">';

    $rowspan = 3;

    for($i=0; $i<count($supplierDocuments); $i++) {

        if($i == 0) {
            $rowspan = 2;
        } else {
            $rowspan = 3;
        }

    echo '<tr>
            <td id="art80-title" rowspan="' . $rowspan . '">' . $documentsNames[$i] . '</td>
            <td id="art80-date">
                Data Richiesta: ' . getDocumentRequestDate($supplierDocuments[$i]) .
            '</td>
            <td id="art80-anomaly" rowspan="' . $rowspan . '">
                <h4>' . getDocumentStateLabel($supplierDocuments[$i]) . '</h4>
            </td>';
        echo '<td id="art80-operations" rowspan="' . $rowspan . '">';

        if($i == 0 || $i == 7) {
            echo '<div class="row-art80-operations" style="text-align:center;">';

            if($i == 0) {
                echo '<a href="documents/standard/Informativa privacy.pdf" target="_blank"
                            class="btn btn-primary btn-s btn-scarica">
                        <span class="glyphicon glyphicon-download-alt"></span> Scarica
                    </a>';
            } else if($i == 7) {
                echo '<a href="documents/standard/Autocertificazione D.lgs. 812008.pdf" target="_blank"
                            class="btn btn-primary btn-s btn-scarica">
                        <span class="glyphicon glyphicon-download-alt"></span> Scarica
                    </a>';
            }

            echo '<button type="button" class="btn btn-default btn-s"
                        data-toggle="modal"
                        data-target="#uploadDocumentModal" data-document-id="'
                            . ($i + 1) . '"
                        data-document-name="' . $documentsNamesModal[$i] . '"
                        data-is-supplier="' . $_SESSION['is_supplier'] . '">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Carica
                    </button>
                </div>';
        } else {
            echo '<div class="row-art80-operations" style="text-align:center;">
                    <button type="button" class="btn btn-default btn-s btn-art80-operations"
                        data-toggle="modal"
                        data-target="#uploadDocumentModal" data-document-id="'
                            . ($i + 1) . '"
                        data-document-name="' . $documentsNamesModal[$i] . '"
                        data-is-supplier="' . $_SESSION['is_supplier'] . '">
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                    <button type="button" class="btn btn-default btn-s btn-art80-operations"
                        data-toggle="modal" data-target="#downloadDocumentModal" data-document-id="'
                            . ($i + 1) . '"
                        data-document-name="' . $documentsNamesModal[$i] . '"
                        data-supplier-id="' . $supplierId . '">
                        <span class="glyphicon glyphicon-download-alt"></span>
                    </button>
                </div>';
        }

        if(!$_SESSION['is_supplier']) {
            echo '<div class="row-art80-operations" style="text-align:center;">
                    <button type="button" class="btn btn-default
                        btn-s btn-art80-operations" data-toggle="modal" data-target="#deleteDateModal"
                        data-document-id="' . ($i + 1) . '" data-supplier-id="' . $supplierId .
                        '" data-document-name="' . $documentsNamesModal[$i] .
                        '" data-emission-date="' . getDocumentEmissionDate($supplierDocuments[$i]) . '">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </button>
                    <button type="button" class="btn btn-default btn-art80-operations
                            btn-s btn-art80-operations" data-toggle="modal" data-target="#editDocumentStateModal"
                            data-document-id="' . ($i + 1) . '" data-supplier-id="' . $supplierId .
                            '" data-document-state="' . getDocumentState($supplierDocuments[$i]) .
                            '" data-document-name="' . $documentsNamesModal[$i] . '">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                </div>';
        }

    echo '</td>
            <td id="art80-note" rowspan="' . $rowspan . '">
                <div class="panel panel-default panel-note">
                    <div class="panel-body panel-content">'
                        . getDocumentNote($supplierDocuments[$i]) .
                    '</div>
                </div>';
    if(!$_SESSION['is_supplier']) {
        echo '<button type="button" class="btn btn-primary btn-supplier btn-xs"
                    data-toggle="modal" data-target="#editNodeModal"
                    data-document-id="' . ($i + 1) . '"
                    data-document-name="' . $documentsNamesModal[$i] . '"
                    data-document-note="' . getDocumentNote($supplierDocuments[$i]) . '">
                    <span class="glyphicon glyphicon-pencil"></span> Modifica Nota
                </button>
            </td>
            </tr>';
    }
        echo '<tr>
                <td id="art80-date">Data Emissione: ' . getDocumentEmissionDate($supplierDocuments[$i]) .
                '</td>
             </tr>';
        if($i != 0) {
            echo '<tr>
                    <td id="art80-date">Data Scadenza: ' . getDocumentExpiryDate($supplierDocuments[$i]) .
                    '</td>
                 </tr>';
        }
    }

    echo '</tbody>
       </table>';

}

function getDocumentStateLabel($string) {
    $state = substr($string, 0, 1);
    $output = '';
    if($state === '0') {
        $output = '<label class="label label-danger">Riscontrate Anomalie</label>';
    } else if ($state === '1') {
        $output = '<label class="label label-warning label-doc-state1">In Lavorazione</label>';
    } else if ($state === '2') {
        $output = '<label class="label label-success label-doc-state2">Convalidato</label>';
    }
    return $output;
}

function getDocumentSilenceDate($string) {
    $date = substr($string, 32, 10);
    if($date != '0000000000') {
        $date = getFinalDate($date);
    } else {
        $date = '-';
    }
    return $date;
}

function getDocumentRequestDate($string) {
    $date = substr($string, 2, 10);
    if($date != '0000000000') {
        $date = getFinalDate($date);
    } else {
        $date = '-';
    }
    return $date;
}

function getDocumentEmissionDate($string) {
    $date = substr($string, 22, 10);
    if($date != '0000000000') {
        $date = getFinalDate($date);
    } else {
        $date = '-';
    }
    return $date;
}

function getDocumentExpiryDate($string) {
    $date = substr($string, 12, 10);
    if($date != '0000000000') {
        $date = getFinalDate($date);
    } else {
        $date = '-';
    }
    return $date;
}

function getDocumentNote($string) {
    if(strlen($string) > 32) {
        return substr($string, 32);
    } else {
        return '';
    }
}

function getDocumentState($string) {
    return substr($string, 0, 1);
}

function getImageStatus($value) {
    if($value == -1) {
        return '<img src="assets/img/red_light.png" class="semaphore-light"
        alt="Anomalie">';
    } else if($value == 1) {
        return '<img src="assets/img/yellow_light.png" class="semaphore-light"
        alt="In Lavorazione">';
    } else if($value == 2) {
        return '<img src="assets/img/green_light.png" class="semaphore-light"
        alt="Monitorato">';
    } else {
        return '';
    }
}

function getImageStatusBig($value) {
    if($value == -1) {
        return '<img src="assets/img/red_light_big.png" class="semaphore-light-big"
        alt="Anomalie">';
    } else if($value == 1) {
        return '<img src="assets/img/yellow_light_big.png" class="semaphore-light-big"
        alt="In Lavorazione">';
    } else if($value == 2) {
        return '<img src="assets/img/green_light_big.png" class="semaphore-light-big"
        alt="Monitorato">';
    } else {
        return '';
    }
}

function getTextStatus($value) {
    if($value == -1) {
        return 'Anomalie';
    } else if($value == 1) {
        return 'In Lavorazione';
    } else if($value == 2) {
        return 'Monitorato';
    } else {
        return '';
    }
}

function getFinalDate($seconds) {
    if($seconds != NULL) {
        $seconds = date('d/m/Y', $seconds);
    } else {
        $seconds = '-';
    }
    return $seconds;
}

function getDocScaLabel($status) {
    if($status == 0) {
        return '<h5><label class="label label-danger label-doc-sca">Scaduti</label></h5>';
    } else if($status == 1) {
        return '<h5><label class="label label-warning label-doc-sca">In Scadenza</label></h5>';
    } else if($status == 2) {
        return '<h5><label class="label label-success label-doc-sca">In Regola</label></h5>';
    } else if($status == 3) {
        return '<h5><label class="label label-default label-doc-sca">Mancanti</label></h5>';
    } else {
        return '';
    }
}

function getTimestampFromDate($date) {
    list($day, $month, $year) = explode('/', $date);
    return mktime(0, 0, 0, $month, $day, $year);
}

function sumMonthsToDate($timestamp, $numMonths) {
    $date = date('d/n/Y', $timestamp);
    list($day, $month, $year) = explode('/', $date);
    if($month > (12 - $numMonths)) {
        $month += $numMonths - 12;
        $year++;
    } else {
        $month += $numMonths;
    }
    return mktime(0, 0, 0, $month, $day, $year);
}

function getMySQLDate($timestamp) {
    return date('Y-m-d', $timestamp);
}

function subtractDaysFromDate($timestamp, $numDays) {
    return ($timestamp - ($numDays * 86400));
}

function convertDateRangeToSeconds($date) {
    if($date != NULL) {
        $dates = explode(" - ", $date);

        $dateTemp1 = explode("/", $dates[0]);
        $dateTemp2 = explode("/", $dates[1]);

        $dates[0] = $dateTemp1[1] . '/' . $dateTemp1[0] . '/' . $dateTemp1[2];
        $dates[1] = $dateTemp2[1] . '/' . $dateTemp2[0] . '/' . $dateTemp2[2];

        $dates[0] = strtotime($dates[0]);
        $dates[1] = strtotime($dates[1]);

        $dates[1] += 86400;
        return $dates;
    } else {
        return '';
    }
}

function getDocScaStatus($status) {
    if($status == 0) {
        return 'Scaduti';
    } else if($status == 1) {
        return 'In Scadenza';
    } else if($status == 2) {
        return 'In Regola';
    } else if($status == 3) {
        return 'Mancanti';
    } else {
        return '';
    }
}

function printExpiryStates($supplier) {

    echo '{
        "supplier": "' . substr($supplier['supplierName'], 0, 10);

    if(strlen($supplier['supplierName']) > 10) {
        echo '…';
    }

    echo '",
        "valid": ' . $supplier['valid'] . ',
        "expiring": ' . $supplier['expiring'] . ',
        "expired": ' . $supplier['expired'] . ',
        "missing": ' . $supplier['missing'] . '
        }';
}

function printApprovalStates($supplier) {

    echo '{
        "supplier": "' . substr($supplier['supplierName'], 0, 10);

    if(strlen($supplier['supplierName']) > 10) {
        echo '…';
    }

    echo '",
        "valid": ' . $supplier['valid'] . ',
        "working": ' . $supplier['working'] . ',
        "anomaly": ' . $supplier['anomaly'] . '
        }';
}

function findIdInArray($id, $array) {
    $idOutput = -1;
    for($i=0; $i<count($array); $i++) {
        if($array[$i][0] == $id) {
            $idOutput = $i;
            break;
        }
    }
    return $idOutput;
}

function parseSpecialChar($string) {
    return str_replace(array( '\'', '"', ',' , ';', '<', '>', ' ' ), '', $string);
}

function parseAccentedCharacters($text) {
	$text = str_replace("à", "a'", $text);
	$text = str_replace("è", "e'", $text);
	$text = str_replace("é", "e'", $text);
	$text = str_replace("ì", "i'", $text);
	$text = str_replace("ò", "o'", $text);
	$text = str_replace("ù", "u'", $text);
	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	return $text;
}

function getCfAndNameFromBP($text) {
    $cf = '';
	$name = '';
    $text = str_replace("\t", " ", $text);
	$text = str_replace("\n", " ", $text);

	// Manipolazione iniziale del testo
	$indexStart = strpos($text, "7053179727");
	$text = substr($text, $indexStart + 12, 200);
    if (substr($text, 0, 4) == 'ata ') {
        $text = substr($text, 9, 200);
        $indexLastName = strpos($text, " ");
        $text = substr($text, $indexLastName + 1);
    } else {
        $indexLastName = strpos($text, " ");
        $text = substr($text, $indexLastName + 2);
    }
    $foundCF = false;

    while(!$foundCF && $text != '') {
        $index = strpos($text, " ");

        if($index == 16 && !is_numeric(substr($text, 0, 1)) && !is_numeric(substr($text, 1, 1)) &&
                !is_numeric(substr($text, 2, 1)) && !is_numeric(substr($text, 3, 1)) &&
                !is_numeric(substr($text, 4, 1)) && !is_numeric(substr($text, 5, 1)) &&
                is_numeric(substr($text, 6, 1)) && is_numeric(substr($text, 7, 1)) &&
                !is_numeric(substr($text, 8, 1)) && is_numeric(substr($text, 9, 1)) &&
                is_numeric(substr($text, 10, 1)) && !is_numeric(substr($text, 11, 1)) &&
                is_numeric(substr($text, 12, 1)) && is_numeric(substr($text, 13, 1)) &&
                is_numeric(substr($text, 14, 1)) && !is_numeric(substr($text, 15, 1))) {

            $cf = substr($text, 0, 16);
            $foundCF = true;
        } else {
			$name .= substr($text, 0, strpos($text, " ") + 1);
            $text = substr($text, strpos($text, " ") + 1);
        }
    }

    return array(trim($cf), trim($name));

}

function getViewed($idBP, $stato) {
    if($stato == 0) {
          return '<span class="label label-danger" id="bp-view-' . $idBP . '">Non Visualizzato</span>';
    } else if($stato == 1) {
          return '<span class="label label-success" id="bp-view-' . $idBP . '">Visualizzato</span>';
    }
}

function printBlockMessage() {
    echo '<div class="col-md-12" style="text-align:center; margin-top:40px;">'
            . 'Non sei autorizzato ad accedere a questa pagina'
        . '</div>';
}

function printArray($text) {
    print '<pre>';
    print_r($text);
    print '</pre>';
}

function convertUTF($text) {
    return iconv('UTF-8', 'windows-1252//TRANSLIT', $text);
}

function alert($message) {
    echo '<script>window.alert("' . $message . '")</script>';
}

?>
