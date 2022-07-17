<?php

    session_start();
   
    require_once ('assets/php/PHPExcel.php');
    require_once ('assets/php/functions.php');
    require_once ('class/supplier.class.php');
    
    date_default_timezone_set('Europe/Rome');
    
    if(isset($_SESSION['searchResults'])) {
    
        $title = 'Ricerca Operatori ' . time();
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
                ->getStyle("A1:I1")
                ->getFont()->setBold(true);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Ragione Sociale')
                ->setCellValue('B1', 'Partita IVA')
                ->setCellValue('C1', 'Codice Fiscale')
                ->setCellValue('D1', 'Stato del Fornitore')
                ->setCellValue('E1', 'Data Inserimento OE')
                ->setCellValue('F1', 'Stato Art. 80')
                ->setCellValue('G1', 'Stato Scadenza DOC SCA');

        $lett_arr = array('A'=>30, 'B'=>16, 'C'=>16, 'D'=>20, 'E'=>20, 'F'=>18, 'G'=>24);

        foreach($lett_arr as $key => $value){
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($key)
                ->setWidth($value);
        }

        $objPHPExcel->getActiveSheet()
                ->getStyle('A1:G1')
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

        $objPHPExcel->getActiveSheet()
                ->getStyle('A1:G1')
                ->getFill()
                ->getStartColor()
                ->setRGB('c6c6c6');

        for($j=2; $j<count($searchResults)+2; $j++) {
            if($j % 2 == 0){
                $objPHPExcel->getActiveSheet()
                    ->getStyle('A'.$j.':G'.$j)
                        ->getFill()
                        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $objPHPExcel->getActiveSheet()
                        ->getStyle('A'.$j.':G'.$j)
                        ->getFill()
                        ->getStartColor()
                        ->setRGB('f2f2f2');
            }
        }

        for($i=0; $i<count($searchResults); $i++) {
            $temp = $searchResults[$i];
            $data = getFinalDate($temp->getDataInsOE());
            $dataSep = explode('/', $data);
            $dataFinal = PHPExcel_Shared_Date::FormattedPHPToExcel($dataSep[2], 
                    $dataSep[1], $dataSep[0]);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.($i+2), $temp->getRagSociale())
                ->setCellValue('B'.($i+2), $temp->getIva())
                ->setCellValue('C'.($i+2), $temp->getCf())
                ->setCellValue('D'.($i+2), getTextStatus($temp->getStato()))
                ->setCellValue('E'.($i+2), $dataFinal)
                ->setCellValue('F'.($i+2), getTextStatus($temp->getStatoArt80()))
                ->setCellValue('G'.($i+2), getDocScaStatus($temp->getStatoDocScad()));

            $objPHPExcel->getActiveSheet()
                ->getStyle('E'.($i+2))
                ->getNumberFormat()
                ->setFormatCode('dd/mm/yyyy');
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