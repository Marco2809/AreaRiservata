<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('class/dbconn.php');

$database = new Database();
$db = $database->dbConnection();

$arrayUsers = array();
$stmtSelectEmails = $db->prepare("SELECT login.user_idd, temp_users.commessa FROM login "
        . "INNER JOIN temp_users ON login.email = temp_users.email");
$stmtSelectEmails->execute();
$stmtSelectEmails->store_result();
$stmtSelectEmails->bind_result($currentId, $currentCommessa);
while($stmtSelectEmails->fetch()) {
    $currentUser = array(
        'id'=>$currentId, 
        'commesse'=>$currentCommessa
    );
    array_push($arrayUsers, $currentUser);
}

foreach($arrayUsers as $user) {
    
    /* COPIA COMMESSE
    echo 'UTENTE ' . $user['id'] . '<br>';
    $currentCommesse = explode(" - ", $user['commesse']);
    
    foreach($currentCommesse as $commessa) {
        $commessa = str_replace(" ", "", $commessa);
        $stmtSelect = $db->prepare("SELECT id_commessa FROM commesse WHERE commessa = ? LIMIT 1");
        $stmtSelect->bind_param('s', $commessa);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($currIdCommessa);
        $stmtSelect->fetch();
        
        $result = '';
        $stmtSelectUserCC = $db->prepare("SELECT rowid FROM user_commesse WHERE id_user = ? "
                . "AND id_commessa = ? LIMIT 1");
        $stmtSelectUserCC->bind_param('ii', $user['id'], $currIdCommessa);
        $stmtSelectUserCC->execute();
        $stmtSelectUserCC->store_result();
        $stmtSelectUserCC->bind_result($result);
        $stmtSelectUserCC->fetch();

        if($stmtSelectUserCC->num_rows == 0) {
            echo $user['id'] . ' | ' . $currIdCommessa . '<br>';
            $stmtInsert = $db->prepare("INSERT INTO user_commesse (id_user, id_commessa) VALUES (?, ?)");
            $stmtInsert->bind_param('ii', $user['id'], $currIdCommessa);
            if(!$stmtInsert->execute()) {
                 trigger_error("Errore! " . $db->error, E_USER_WARNING);
            }
            $stmtInsert->close();
        }
    }
    */
    
    /* COPIA CENTRI DI COSTO
    $result = '';
    $stmtSelect = $db->prepare("SELECT id_user FROM user_cc WHERE id_user = ? LIMIT 1");
    $stmtSelect->bind_param('i', $user['id']);
    $stmtSelect->execute();
    $stmtSelect->store_result();
    $stmtSelect->bind_result($result);
    $stmtSelect->fetch();

    if($stmtSelect->num_rows == 0) {
        if($user['centro_costo'] == '1.000') {
            $user['centro_costo'] = 1;
        } else if($user['centro_costo'] == '2.000') {
            $user['centro_costo'] = 2;
        } else if($user['centro_costo'] == '3.000') {
            $user['centro_costo'] = 3;
        } else if($user['centro_costo'] == '4.000') {
            $user['centro_costo'] = 4;
        } else if($user['centro_costo'] == '5.000') {
            $user['centro_costo'] = 5;
        } else if($user['centro_costo'] == '5.001') {
            $user['centro_costo'] = 6;
        } else if($user['centro_costo'] == '5.002') {
            $user['centro_costo'] = 7;
        } else if($user['centro_costo'] == '5.003') {
            $user['centro_costo'] = 8;
        } else if($user['centro_costo'] == '5.004') {
            $user['centro_costo'] = 9;
        } else if($user['centro_costo'] == '6.000') {
            $user['centro_costo'] = 10;
        } else if($user['centro_costo'] == '7.000') {
            $user['centro_costo'] = 11;
        } else if($user['centro_costo'] == '8.000') {
            $user['centro_costo'] = 12;
        } else if($user['centro_costo'] == '9.000') {
            $user['centro_costo'] = 13;
        } else if($user['centro_costo'] == '10.000') {
            $user['centro_costo'] = 14;
        }
        
        echo $user['id'] . ' | ' . $user['centro_costo'] . '<br>';
        
        $stmtInsert = $db->prepare("INSERT INTO user_cc (id_user, id_cc) VALUES (?, ?)");
        $stmtInsert->bind_param('ii', $user['id'], $user['centro_costo']);
        if(!$stmtInsert->execute()) {
             trigger_error("Errore! " . $db->error, E_USER_WARNING);
        }
        $stmtInsert->close();  
    }
    */
    
    /* COPIA INFO_HR
    
    $result = '';
    $stmtSelect = $db->prepare("SELECT user_id FROM info_hr WHERE user_id = ? LIMIT 1");
    $stmtSelect->bind_param('i', $user['id']);
    $stmtSelect->execute();
    $stmtSelect->store_result();
    $stmtSelect->bind_result($result);
    $stmtSelect->fetch();
    
    if($stmtSelect->num_rows == 0) {
        if($user['contratto'] != 0) {
            $oreGiorniContratto = str_replace(",", ".", explode("*", $user['contratto']));
        } else {
            $oreGiorniContratto = array('', '');
        }
        
        if($user['scad_visita'] != '') {
            $dataScadVisita = explode("-", $user['scad_visita']);
            $dataScadVisita[2] = '20' . $dataScadVisita[2];
            if($dataScadVisita[1] == 'gen') {
                $dataScadVisita[1] = '01';
            } else if($dataScadVisita[1] == 'feb') {
                $dataScadVisita[1] = '02';
            } else if($dataScadVisita[1] == 'mar') {
                $dataScadVisita[1] = '03';
            } else if($dataScadVisita[1] == 'apr') {
                $dataScadVisita[1] = '04';
            } else if($dataScadVisita[1] == 'mag') {
                $dataScadVisita[1] = '05';
            } else if($dataScadVisita[1] == 'giu') {
                $dataScadVisita[1] = '06';
            } else if($dataScadVisita[1] == 'lug') {
                $dataScadVisita[1] = '07';
            } else if($dataScadVisita[1] == 'ago') {
                $dataScadVisita[1] = '08';
            } else if($dataScadVisita[1] == 'set') {
                $dataScadVisita[1] = '09';
            } else if($dataScadVisita[1] == 'ott') {
                $dataScadVisita[1] = '10';
            } else if($dataScadVisita[1] == 'nov') {
                $dataScadVisita[1] = '11';
            } else if($dataScadVisita[1] == 'dic') {
                $dataScadVisita[1] = '12';
            }

            if($dataScadVisita[0] < 10) {
                $dataScadVisita[0] = '0' . $dataScadVisita[0];
            }

            $user['scad_visita'] = implode("/", $dataScadVisita);
        }
        
        if($user['scad_contratto'] != '' && $user['scad_contratto'] != 'indeterminato' && 
                $user['scad_contratto'] != 'indeterminato distacco INFORDATA' && 
                $user['scad_contratto'] != 'indeterminato sub.ENGINNERING') {
            $dataScadContratto = explode("/", $user['scad_contratto']);
            $dataScadContratto[2] = '20' . $dataScadContratto[2];
            $user['scad_contratto'] = implode("/", $dataScadContratto);
        }
        
        echo $user['id'] . ' | ' . $oreGiorniContratto[0] . ' | ' . 
                $oreGiorniContratto[1] . ' | ' . $user['scad_visita'] . ' | ' . $user['scad_contratto'] . '<br>';
        
        $stmtInsert = $db->prepare("INSERT INTO info_hr (user_id, ore_giorno, "
                . "giorni_settimana, scad_visita_medica, scad_contratto) VALUES (?, ?, ?, ?, ?)");
        $stmtInsert->bind_param('issss', $user['id'], $oreGiorniContratto[0], 
                $oreGiorniContratto[1], $user['scad_visita'], $user['scad_contratto']);
        if(!$stmtInsert->execute()) {
             trigger_error("Errore! " . $db->error, E_USER_WARNING);
        }
        $stmtInsert->close();
    }
    */
    
    /* COPIA ANAGRAFICA
    $result = '';
    $stmtSelect = $db->prepare("SELECT user_id FROM anagrafica WHERE user_id = ? LIMIT 1");
    $stmtSelect->bind_param('i', $user['id']);
    $stmtSelect->execute();
    $stmtSelect->store_result();
    $stmtSelect->bind_result($result);
    $stmtSelect->fetch();

    if($stmtSelect->num_rows == 0) {
         $stmtInsert = $db->prepare("INSERT INTO anagrafica (user_id, nome, cognome, "
                 . "phone, codice_fiscale) VALUES (?, ?, ?, ?, ?)");
         $stmtInsert->bind_param('issss', $user['id'], $user['nome'], $user['cognome'], 
                 $user['telefono'], $user['cf']);
         if(!$stmtInsert->execute()) {
              trigger_error("Errore! " . $db->error, E_USER_WARNING);
         }
         $stmtInsert->close();
    }
    */
}

?>