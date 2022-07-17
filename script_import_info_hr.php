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
$query = "SELECT * FROM loading_data";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {
    array_push($userArray, $row);
}

foreach($userArray as $user) {
    //echo 'CF n. ' . $user['HiringDate'] . '<br>';
    $usercf=$user['CF'];
    //echo '<br><br>';
    $queryInsert = "SELECT user_id FROM anagrafica WHERE codice_fiscale = '".$usercf."'";
    $resultInsert = $mysqli->query($queryInsert);

    //INSERT INFO HR
    /*while($rowIn = $resultInsert->fetch_assoc()) {
      $ore = explode("*", $user['CTR']);
      $queryInsert2 = "INSERT INTO info_hr (user_id,ore_giorno,giorni_settimana,scad_visita_medica,scad_contratto,data_assunzione) VALUES('" . $rowIn['user_id'] . "', '" . $ore[0] . "','" . $ore[1] . "','" . $user['ExpiryVisit'] . "', '" . $user['ExpiryDate'] . "', '" . $user['HiringDate'] . "')";
      $resultInsert2 = $mysqli->query($queryInsert2);
    }*/

    //INSERT ANAGRAFICA HR
    while($rowIn = $resultInsert->fetch_assoc()) {
      $queryInsert2 = "INSERT INTO hr_anagrafica (user_id,data_assunzione,mansione,qualifica,livello_contratto,commessa) VALUES('" . $rowIn['user_id'] . "', '" . $user['HiringDate'] . "','" . $user['Task'] . "','" . $user['Qualify'] . "', '" . $user['level'] . "', '" . $user['commessa'] . "')";
      $resultInsert2 = $mysqli->query($queryInsert2);
      echo $queryInsert2."<br>";
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
