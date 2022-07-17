<?php
    session_start();

    require_once($_SERVER['DOCUMENT_ROOT'] . '/class/dbconn.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/class/supplier.class.php');
    require_once('functions.php');

    $db = (new Database())->dbConnection();

    $supplierToDelete = new Supplier();
    $supplierToDelete->setId($_GET['id']);
    $supplierToDelete->deleteSupplier();

    header('Location: /../albo-fornitori.php?action=fornitori');

?>