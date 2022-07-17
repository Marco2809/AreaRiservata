<?php
   
    session_start();

    require_once('class/dbconn.php');
    require_once ('assets/php/PHPExcel.php');
    require_once ('assets/php/functions.php');
    require_once ('class/supplier.class.php');
    require_once ('class/supplier_documents.class.php');
    
    date_default_timezone_set('Europe/Rome');
    $title = 'Dettaglio Documenti ' . time();
    
    if(isset($_SESSION['searchResults'])) {
    
        $searchResults = unserialize($_SESSION['searchResults']);

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription($title);

        $objPHPExcel->getDefaultStyle()
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()
                ->getStyle("A1:BC1")
                ->getFont()->setBold(true);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Ragione Sociale')
                ->setCellValue('B1', 'Partita IVA')
                ->setCellValue('C1', 'Codice Fiscale')
                ->setCellValue('D1', 'Stato del Fornitore')
                ->setCellValue('E1', 'Data Inserimento OE')
                ->setCellValue('F1', 'Stato Art. 80')
                ->setCellValue('G1', 'Informativa sulla privacy Data Inserimento')
                ->setCellValue('H1', 'Informativa sulla privacy Data Emissione')
                ->setCellValue('I1', 'Bilanci Data Inserimento')
                ->setCellValue('J1', 'Bilanci Data Emissione')
                ->setCellValue('K1', 'Bilanci Data Scadenza')
                ->setCellValue('L1', 'Documento Identità del Legale Rappresentante Data Inserimento')
                ->setCellValue('M1', 'Documento Identità del Legale Rappresentante Data Emissione')
                ->setCellValue('N1', 'Documento Identità del Legale Rappresentante Data Scadenza')
                ->setCellValue('O1', 'Cas. Giudiziale Soggetti Cam. Commercio Data Inserimento')
                ->setCellValue('P1', 'Cas. Giudiziale Soggetti Cam. Commercio Data Emissione')
                ->setCellValue('Q1', 'Cas. Giudiziale Soggetti Cam. Commercio  Data Scadenza')
                ->setCellValue('R1', 'Carichi Pendenti Soggetti Cam. Commercio Data Inserimento')
                ->setCellValue('S1', 'Carichi Pendenti Soggetti Cam. Commercio Data Emissione')
                ->setCellValue('T1', 'Carichi Pendenti Soggetti Cam. Commercio Data Scadenza')
                ->setCellValue('U1', 'Casellario Anagrafe Data Inserimento')
                ->setCellValue('V1', 'Casellario Anagrafe Data Emissione')
                ->setCellValue('W1', 'Casellario Anagrafe Data Scadenza')
                ->setCellValue('X1', 'Certificato Carichi Pendenti Data Inserimento')
                ->setCellValue('Y1', 'Certificato Carichi Pendenti Data Emissione')
                ->setCellValue('Z1', 'Certificato Carichi Pendenti Data Scadenza')
                ->setCellValue('AA1', 'Dichiarazione Sostitutiva di Cert. Data Inserimento')
                ->setCellValue('AB1', 'Dichiarazione Sostitutiva di Cert. Data Emissione')
                ->setCellValue('AC1', 'Dichiarazione Sostitutiva di Cert. Data Scadenza')
                ->setCellValue('AD1', 'DURC Data Inserimento')
                ->setCellValue('AE1', 'DURC Data Emissione')
                ->setCellValue('AF1', 'DURC Data Scadenza')
                ->setCellValue('AG1', 'Visura Camerale Data Inserimento')
                ->setCellValue('AH1', 'Visura Camerale Data Emissione')
                ->setCellValue('AI1', 'Visura Camerale Data Scadenza')
                ->setCellValue('AJ1', 'ISO 9001 Data Inserimento')
                ->setCellValue('AK1', 'ISO 9001 Data Inserimento')
                ->setCellValue('AL1', 'ISO 9001 Data Inserimento');

        $lett_arr = array('A'=>30, 'B'=>16, 'C'=>16, 'D'=>20, 'E'=>20, 'F'=>18, 
            'G'=>40, 'H'=>40, 'I'=>38, 'J'=>43, 'K'=>35, 'L'=>35, 'M'=>34, 'N'=>44, 
            'O'=>44, 'P'=>44, 'Q'=>44, 'R'=>40, 'S'=>40, 'T'=>42, 'U'=>40, 'V'=>40, 
            'W'=>42, 'X'=>40, 'Y'=>40, 'Z'=>38, 'AA'=>37, 'AB'=>36, 'AC'=>36, 'AD'=>36, 
            'AE'=>36, 'AF'=>30, 'AG'=>30, 'AH'=>30, 'AI'=>42, 'AJ'=>40, 'AK'=>40, 'AL'=>36);

        foreach($lett_arr as $key => $value){
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($key)
                ->setWidth($value);
        }

        $objPHPExcel->getActiveSheet()
                ->getStyle('A1:AL1')
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

        $objPHPExcel->getActiveSheet()
                ->getStyle('A1:AL1')
                ->getFill()
                ->getStartColor()
                ->setRGB('c6c6c6');

        for($j=2; $j<count($searchResults)+2; $j++) {
            if($j % 2 == 0){
                $objPHPExcel->getActiveSheet()
                    ->getStyle('A'.$j.':AL'.$j)
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()
                        ->getStyle('A'.$j.':AL'.$j)
                        ->getFill()
                        ->getStartColor()
                        ->setRGB('f2f2f2');
            }
        }

        $supplierIds = array();
        foreach($searchResults as $supplier) {
            array_push($supplierIds, $supplier->getId());
        }

        $suppDocs = new SupplierDocuments();
        $searchResultsDocs = $suppDocs->getSupplierDocumentsFromIds($supplierIds);

        for($i=0; $i<count($searchResultsDocs); $i++) {
            // Generic supplier part
            $temp = $searchResults[$i];

            $dataInsOE = getFinalDate($temp->getDataInsOE());
            $dataInsOESep = explode('/', $dataInsOE);
            $dataInsOEFinal = PHPExcel_Shared_Date::FormattedPHPToExcel($dataInsOESep[2], 
                    $dataInsOESep[1], $dataInsOESep[0]);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.($i+2), $temp->getRagSociale())
                ->setCellValue('B'.($i+2), $temp->getIva())
                ->setCellValue('C'.($i+2), $temp->getCf())
                ->setCellValue('D'.($i+2), getTextStatus($temp->getStato()))
                ->setCellValue('E'.($i+2), $dataInsOEFinal)
                ->setCellValue('F'.($i+2), getTextStatus($temp->getStatoArt80()));

            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.($i+2))
                ->getNumberFormat()
                ->setFormatCode('dd/mm/yyyy');

            // Document details part
            $tempDocs = $searchResultsDocs[$i];

            $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');

            $documentIndexes = array(array('G','H'), array('I','J','K'), 
                array('L','M','N'), array('O','P','Q'), array('R','S','T'), 
                array('U','V','W'), array('X','Y','Z'), array('AA','AB','AC'), 
                array('AD','AE','AF'), array('AG','AH','AI'), array('AJ','AK','AL'));

            for($j=0; $j<count($documentNames); $j++) {
                $dataIns = substr($tempDocs[$documentNames[$j]], 2, 10);
                $dataEm = substr($tempDocs[$documentNames[$j]], 22, 10);
                $dataReq = substr($tempDocs[$documentNames[$j]], 12, 10);

                if($dataIns == '0000000000') {
                    $dataInsFinal = '-';
                } else {
                    $dataIns = getFinalDate($dataIns);
                    $dataInsSep = explode('/', $dataIns);
                    $dataInsFinal = PHPExcel_Shared_Date::FormattedPHPToExcel($dataInsSep[2], 
                        $dataInsSep[1], $dataInsSep[0]);
                }

                if($dataEm == '0000000000') {
                    $dataEmFinal = '-';
                } else {
                    $dataEm = getFinalDate($dataEm);
                    $dataEmSep = explode('/', $dataEm);
                    $dataEmFinal = PHPExcel_Shared_Date::FormattedPHPToExcel($dataEmSep[2], 
                        $dataEmSep[1], $dataEmSep[0]);
                }

                

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($documentIndexes[$j][0].($i+2), $dataInsFinal)
                    ->setCellValue($documentIndexes[$j][1].($i+2), $dataEmFinal);
                
                if($j != 0) {
                    if($dataReq == '0000000000') {
                        $dataReqFinal = '-';
                    } else {
                        $dataReq = getFinalDate($dataReq);
                        $dataReqSep = explode('/', $dataReq);
                        $dataReqFinal = PHPExcel_Shared_Date::FormattedPHPToExcel($dataReqSep[2], 
                            $dataReqSep[1], $dataReqSep[0]);
                    }
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($documentIndexes[$j][2].($i+2), $dataReqFinal);
                }

                $objPHPExcel->getActiveSheet()
                    ->getStyle('G'.($i+2).':AL'.($i+2))
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy');
            }
        }

        $objPHPExcel->getActiveSheet()
                ->setTitle($title);

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$title.'.xls"');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        
    }
    
?>