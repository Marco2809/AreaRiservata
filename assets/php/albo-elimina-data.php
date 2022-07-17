<?php

    session_start();

    require_once($_SERVER['DOCUMENT_ROOT'] . '/class/dbconn.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/class/supplier_documents.class.php');
    require_once('functions.php');

    $db = (new Database())->dbConnection();

    $supplierToDelete = new SupplierDocuments();
    $supplierToDelete->setSupplierId($_GET['id']);
    $supplierToDelete->deleteEmissionDate($_GET['document']);
    $supplierToDelete->deleteExpiryDate($_GET['document']);

    header('Location: /../albo-fornitori.php?action=documenti&id=' . $_GET['id']);

?>