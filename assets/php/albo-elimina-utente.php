<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/class/dbconn.php');
require_once('functions.php');

$db = (new Database())->dbConnection();

if(isset($_GET['id'])) {
    $stmtInsert = $db->prepare("DELETE FROM login WHERE user_idd = ?");
    $stmtInsert->bind_param("i", $_GET['id']);
    if(!$stmtInsert->execute()) {
        trigger_error("Errore! " . $db->error);
    }
}

header('Location: /../albo-fornitori.php?action=utenti&code=3');

?>