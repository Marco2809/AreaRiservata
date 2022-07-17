<?php
require_once("../../class/dbconn.php");
require_once ( '../../assets/php/functions.php');

// require_once("../../class/dbconn.php");
// require_once("functions.php");

$database = new Database();
$db = $database->dbConnection();
session_start();
if(isset($_POST['username'], $_POST['p'])) {
    $username = $_POST['username'];
    $password = $_POST['p'];
    $queryResult = supplier_login($username, $password, $db);
    if($queryResult == true) {
        header("location: ../../albo-fornitori.php?action=anagrafica");
    } else {
        // Login fallito
        if($queryResult == false) {
            $queryResult = 'false';
        } else {
            $queryResult = 'true';
        }
        
        header('Location: ../../login-albo-fornitori.php?error=1');
    }
} else { 
   // Le variabili corrette non sono state inviate a questa pagina dal metodo POST.
   header('Location: ../../login-albo-fornitori.php?error=2');
}

?>