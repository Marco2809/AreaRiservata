<?php

require_once('class/dbconn.php');

$database = new Database();
$dbconn = $database->dbConnection();
$mysqli = $dbconn;

$currentEmail = '';
$currentName = '';
$currentSurname = '';
$currentPhone = '';
$currentCF = '';

$userArray = array();
$query = "SELECT user_idd, commessa FROM temp_users INNER JOIN login ON " . 
        "temp_users.email = login.email";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {
    array_push($userArray, $row);
}

foreach($userArray as $user) {
    echo 'FORNITORE n. ' . $user['user_idd'] . '<br>';
    
    $user['commessa'] = str_replace(" -    ", " - ", $user['commessa']);
    $user['commessa'] = str_replace(" -   ", " - ", $user['commessa']);
    $user['commessa'] = str_replace(" -  ", " - ", $user['commessa']);
    $commesseArray = explode(" - ", $user['commessa']);
    print_r($commesseArray);
    echo '<br><br>';
    
    foreach($commesseArray as $commessa) {
        if($commessa != '') {
            
            $querySelect = "SELECT id_commessa FROM commesse WHERE commessa = '" . $commessa . "' LIMIT 1";
            $resultSelect = $mysqli->query($querySelect);
            $idCommessa = $resultSelect->fetch_assoc()['id_commessa'];
            
            $queryInsert = "INSERT INTO user_commesse (id_commessa, id_user, id_role) " . 
                    "VALUES ('" . $idCommessa . "', '" . $user['user_idd'] . "', '4')";
            $resultInsert = $mysqli->query($queryInsert);
            if($resultInsert) {
                echo 'Ok<br>';
            } else {
                echo 'Errore: ' . $mysqli->error . '<br>';
            }
        }
    }
    
    /*
    $queryInsert = "INSERT INTO user_cc (id_user, id_cc) " . 
                    "VALUES ('" . $user['user_idd'] . "', '" . $user['centro_costo'] . "')";
    $resultInsert = $mysqli->query($queryInsert);
    if($resultInsert) {
        echo 'Ok';
    } else {
        echo 'No: ' . $mysqli->error;
    }
    echo '<br>';
    */
}

/*
foreach($userArray as $user) {
    echo $user['email'] . ' --> ';
    $query = "SELECT user_idd FROM login INNER JOIN anagrafica "
            . "ON login.user_idd = anagrafica.user_id WHERE email = '" . $user['email'] . "' LIMIT 1";
    $result = $mysqli->query($query);
    if($result->num_rows == 0) {
        $queryFind = "SELECT user_idd FROM login WHERE email = '" . $user['email'] . "' LIMIT 1";
        $resultFind = $mysqli->query($queryFind);
        if($resultFind->num_rows > 0) {
            $row = $resultFind->fetch_assoc();
            $user['cognome'] = str_replace("'", "''", $user['cognome']);
            $queryInsert = "INSERT INTO anagrafica (user_id, nome, cognome, phone, codice_fiscale) " . 
                    "VALUES ('" . $row['user_idd'] . "', '" . $user['nome'] . "', '" . $user['cognome']
                     . "', '" . $user['telefono'] . "', '" . $user['cf'] . "')";
            $resultInsert = $mysqli->query($queryInsert);
            if($resultInsert) {
                echo 'Inserito!';
            } else {
                echo 'Errore: ' . $mysqli->error;
            }
            
        }
    }
    
}
 */

?>