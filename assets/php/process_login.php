<?php

require_once('../../class/dbconn.php');
require_once ('functions.php');

$database = new Database();
$db = $database->dbConnection();

session_start();
if(isset($_POST['username'], $_POST['p'])) {

    $username = $_POST['username'];
    $password = $_POST['p'];
    $queryResult = login($username, $password, $db);
    if($queryResult == true) {
        $stmt = $db->prepare("SELECT user_id FROM anagrafica WHERE user_id = ?");
        $stmt->bind_param('i', $_SESSION['user_idd']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($db_anag_user_id);
        $stmt->fetch();

        if($stmt->num_rows > 0){
            if($_SESSION['is_admin'] == 1) {
                header("location: ../../hr-gestione-personale.php?action=dipendenti");
            }
            else {
                header("location: ../../dashboard.php");
            }
        } else {
            header("location: ../../anagrafica.php?id=" . $_SESSION['user_idd']);
        }
    } else {
        // Login fallito
        if($queryResult == false) {
            $queryResult = 'false';
        } else {
            $queryResult = 'true';
        }

        header('Location: ../../login.php?error=1');
    }
} else {
   // Le variabili corrette non sono state inviate a questa pagina dal metodo POST.
   header('Location: ../../login.php?error=2');
}

?>
