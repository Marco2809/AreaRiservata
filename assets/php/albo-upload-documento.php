<?php

    session_start();
    date_default_timezone_set('Europe/Rome');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/class/dbconn.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/class/supplier.class.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/class/supplier_documents.class.php');
    require_once ('functions.php');

    $documentId = $_POST['upload-submit'];
    $code = 0;
    $id = $_GET['id'];

    $uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/documents/' . $id . '/'
             . $documentId . '/';
    $userfileTmp = $_FILES['uploaded-document']['tmp_name'];
    $extension = pathinfo($_FILES['uploaded-document']['name'], PATHINFO_EXTENSION);

    if($extension != 'pdf' && $extension != 'xls' && $extension != 'xlsx'
            && $extension != 'ods' && $extension != 'jpg' && $extension != 'jpeg') {
        $code = 1;
    }

    if($code == 0) {
        $supplier = new SupplierDocuments();
        $supplier->setSupplierId($id);

        if(!$_SESSION['is_supplier']) {
            $uploadDir .= 'richieste/';
            $userfileName = 'Richiesta' . time() . '.' . $extension;
        } else if($_SESSION['is_supplier']) {
            $uploadDir .= 'documenti/';
            $userfileName = 'Documento' . time() . '.' . $extension;
        }

        if (!file_exists($uploadDir)) {
            $oldmask = umask(0);
            mkdir($uploadDir, 0777, true);
            umask($oldmask);
        }

        if(move_uploaded_file($userfileTmp, $uploadDir.$userfileName)) {
            $code = 2;
            if(!$_SESSION['is_supplier']) {
                $supplier->editRequestDate($documentId, time());
            } else {
                if($documentId == 9) {
                    $dateExpiry = getTimestampFromDate($_POST['data-scadenza']);
                } else {
                    $emissionTimestamp = getTimestampFromDate($_POST['data-emissione']);
                    $dateExpiry = sumMonthsToDate($emissionTimestamp, 6);
                }
                
                $supplier->editExpiryDate($documentId, $dateExpiry); 
                $supplier->editDocScaState($documentId, 2);
                
                $dateEmission = getTimestampFromDate($_POST['data-emissione']);
                $supplier->editEmissionDate($documentId, $dateEmission);
                
                $documents = $supplier->getSupplierDocuments();
                $checkExpiry = array(0, 0, 0, 0);
                foreach($documents as $document) {
                    $docScadState = substr($document, 1, 1);
                    if($docScadState == 3) { // Documento mancante
                        $checkExpiry[3]++;
                        break;
                    } else if($docScadState == 2) { // Documento in regola
                        $checkExpiry[2]++;
                    } else if($docScadState == 1) { // Documento in scadenza
                        $checkExpiry[1]++;
                    } else if($docScadState == 0) { // Documento scaduto
                        $checkExpiry[0]++;
                    }
                }
                
                $supplierToEdit = new Supplier();
                $supplierToEdit->setId($id);
                if($checkExpiry[3] > 0) { // Se c'è almeno un documento mancante, setto Mancanti
                    $supplierToEdit->setStatoDocScad(3);
                } else if($checkExpiry[0] > 0) { // Se c'è almeno un documento scaduto, setto Scaduti
                    $supplierToEdit->setStatoDocScad(0);
                } else if($checkExpiry[1] > 0) { // Se c'è almeno un documento in scadenza, setto In Scadenza
                    $supplierToEdit->setStatoDocScad(1);
                } else { // Altrimenti setto In Regola
                    $supplierToEdit->setStatoDocScad(2);
                }
                $supplierToEdit->editDocScaState();
            }
        } else {
            $code = -1;
        }
    }
    if(!$_SESSION['is_supplier']) {
        header('Location: /../albo-fornitori.php?action=documenti&id=' . $id . '&code=' . $code);
    } else if($_SESSION['is_supplier']) {
        $uploadDir .= 'documenti/';
        header('Location: /../albo-fornitori.php?action=documenti&code=' . $code);
    }
?>