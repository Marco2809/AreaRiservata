<?php

    require_once ('./class/dbconn.php');
    require_once ('./assets/php/PHPExcel.php');
    require_once ('./assets/php/functions.php');

    session_start();
    date_default_timezone_set('Europe/Rome');

    $database = new Database();
    $db = $database->dbConnection();

    $idUser = $_GET['id'];
    $idMonth = getMonthOneDigit($_GET['mese']);
    $monthTwoDigits = getMonthTwoDigits($idMonth);
    $idYear = $_GET['anno'];
    $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $idMonth, $idYear);

    $stmt = $db->prepare("SELECT nome, cognome FROM anagrafica WHERE user_id = ? LIMIT 1");
    $stmt->bind_param('i', $idUser);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userName, $userSurname);
    $stmt->fetch();

    $userSurnameParsed = substr(parseSpecialChar($userSurname), 0, 13);
    $title = 'DettaglioOre' . $userSurnameParsed . $monthTwoDigits . $idYear;
    $objPHPExcel = new PHPExcel();

    // Seleziono le commesse a cui ha lavorato l'utente il mese in questione
    $commesseUser = array();
    $commesseNomiUser = array();
    $tipoPresente = 'Presente';
    $tipoLavoroAgile = 'Lavoro Agile';
    $stmt = $db->prepare("SELECT DISTINCT id_commessa FROM attivita WHERE "
            . "id_utente = ? AND SUBSTRING(data, 4, 2) = ? AND SUBSTRING(data, 7, 4) = ? AND "
            . "(tipo = ? OR tipo = ?)");
    $stmt->bind_param('issss', $idUser, $monthTwoDigits, $idYear, $tipoPresente, $tipoLavoroAgile);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($currentIdCommessa);

    while($stmt->fetch()) {
        $stmtClient = $db->prepare("SELECT cliente FROM commesse WHERE id_commessa = ?");
        $stmtClient->bind_param('i', $currentIdCommessa);
        $stmtClient->execute();
        $stmtClient->store_result();
        $stmtClient->bind_result($currentNameJob);
        $stmtClient->fetch();
        $currentNameJob = ($currentNameJob != '') ? $currentNameJob : 'Service-Tech';

        if(!in_array($currentNameJob, $commesseNomiUser, true)) {
            array_push($commesseUser, $currentIdCommessa);
            array_push($commesseNomiUser, $currentNameJob);
        }
    }

    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('Service-Tech Logo');
    $objDrawing->setDescription('Service-Tech Logo');
    $logo = './assets/img/logo_timesheet.png';
    $objDrawing->setPath($logo);
    $objDrawing->setOffsetX(8);
    $objDrawing->setOffsetY(300);
    $objDrawing->setCoordinates('B2');
    $objDrawing->setWidth(300);
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

    for($i=0; $i<count($commesseUser); $i++) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . ($i+15), $commesseNomiUser[$i]);
    }

    $objPHPExcel->getProperties()
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription($title);

    $objPHPExcel->getActiveSheet()
        ->getStyle('F4')
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    // Settaggio celle con testo in grassetto
    $objPHPExcel->getActiveSheet()
            ->getStyle("F4:AH4")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("F5:AK5")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("B6:AL6")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("B14:AL14")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("B43:G43")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("AF3")
            ->getFont()->setBold(true);

    // Settaggio valori celle statici
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F4', 'TS Excel di')
            ->setCellValue('H4', strtoupper($userSurname . ' ' . $userName))
            ->setCellValue('H5', 'Cognome')
            ->setCellValue('K5', 'Nome')
            ->setCellValue('AF2', date('d-m-Y H:i'))
            ->setCellValue('AF3', 'COPIA PER GESTIONE')
            ->setCellValue('AG4', 'MANDATI')
            ->setCellValue('AI5', 'Definitiva')
            ->setCellValue('B6', 'Cod. Collaboratore')
            ->setCellValue('G6', 'TEMPI')
            ->setCellValue('B7', 'Mese')
            ->setCellValue('C7', $idMonth)
            ->setCellValue('D7', 'Anno')
            ->setCellValue('E7', substr($idYear, 2))
            ->setCellValue('F7', 'Permessi da trattenere, congedo matrimoniale')
            ->setCellValue('F8', 'Ferie')
            ->setCellValue('F9', 'Maternit??, infort., cong. parent., visite med.')
            ->setCellValue('F10', 'Malattia')
            ->setCellValue('F11', 'Permessi retrib. (fest. abolite / ROL)')
            ->setCellValue('F12', 'Totale ore non lavorate')
            ->setCellValue('F13', 'Ore straordinarie')
            ->setCellValue('B14', 'CLIENTE')
            ->setCellValue('B43', 'Totale ore lavorate');


    for($i=0; $i<$numberOfDays; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(PHPExcel_Cell::stringFromColumnIndex($i+7) . '6', strval($i+1))
            ->setCellValue(PHPExcel_Cell::stringFromColumnIndex($i+7) . '14', strval($i+1));

        $currentDate = getTimestamp($i+1, $idMonth, $idYear);
        if(getPosDayFromDate($currentDate) == 6 || getPosDayFromDate($currentDate) == 7) {
            $objPHPExcel->getActiveSheet()
                ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i+7) . '7:' .
                        PHPExcel_Cell::stringFromColumnIndex($i+7) . '11')
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i+7) . '7:' .
                        PHPExcel_Cell::stringFromColumnIndex($i+7) . '11')
                ->getFill()
                ->getStartColor()
                ->setRGB('2f3caa');

            if(count($commesseUser) > 0) {

                $objPHPExcel->getActiveSheet()
                    ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i+7) . '15:' .
                            PHPExcel_Cell::stringFromColumnIndex($i+7) . '42')
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

                $objPHPExcel->getActiveSheet()
                    ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i+7) . '15:' .
                            PHPExcel_Cell::stringFromColumnIndex($i+7) . '42')
                    ->getFill()
                    ->getStartColor()
                    ->setRGB('ffff23');
            }
        }
    }

    //
    $outlineStyleArray = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array('rgb' => '000000'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
        ->getStyle('B6:' . getColumnFromIndex($numberOfDays) . '42')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('B6:E6')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('B14:F14')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('B8:E13')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('F7:F11')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('B43:F43')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('G15:G42')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('G43')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H7:' . getColumnFromIndex($numberOfDays) . '11')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H12:' . getColumnFromIndex($numberOfDays) . '12')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H43:' . getColumnFromIndex($numberOfDays) . '43')
        ->applyFromArray($outlineStyleArray);

    $allBordersStyleArray = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array('rgb' => '000000'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
        ->getStyle('F6:' . getColumnFromIndex($numberOfDays) . '6')
        ->applyFromArray($allBordersStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('G14:' . getColumnFromIndex($numberOfDays) . '14')
        ->applyFromArray($allBordersStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('F12:G13')
        ->applyFromArray($allBordersStyleArray);

    $insideStyleArray = array(
        'borders' => array(
            'inside' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => 'bababa'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
        ->getStyle('G7:G11')
        ->applyFromArray($insideStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H7:' . getColumnFromIndex($numberOfDays) . '11')
        ->applyFromArray($insideStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H13:' . getColumnFromIndex($numberOfDays) . '13')
        ->applyFromArray($insideStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H15:AK42')
        ->applyFromArray($insideStyleArray);

    $horizontalStyleArray = array(
        'borders' => array(
            'horizontal' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => 'bababa'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
        ->getStyle('B15:G42')
        ->applyFromArray($horizontalStyleArray);

    // Settaggio larghezze colonne
    $lett_arr = array('A'=>5, 'B'=>7, 'C'=>5, 'D'=>7, 'E'=>5, 'F'=>40,
        'G'=>8, 'H'=>3, 'I'=>3, 'J'=>3, 'K'=>3, 'L'=>3, 'M'=>3, 'N'=>3,
        'O'=>3, 'P'=>3, 'Q'=>3, 'R'=>3, 'S'=>3, 'T'=>3, 'U'=>3, 'V'=>3,
        'W'=>3, 'X'=>3, 'Y'=>3, 'Z'=>3, 'AA'=>3, 'AB'=>3, 'AC'=>3, 'AD'=>3,
        'AE'=>3, 'AF'=>3, 'AG'=>3, 'AH'=>3, 'AI'=>3, 'AJ'=>3, 'AK'=>3, 'AL'=>3);

    foreach($lett_arr as $key => $value) {
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension($key)
            ->setWidth($value);
    }

    $permessoOre = 0;
    $permessoArray = filterActivities($idUser, $idMonth, $idYear, 'Permesso', $db);
    $permessoStudiArray = filterActivities($idUser, $idMonth, $idYear, 'Permesso Studi', $db);
    $permessoMergeArray = array_merge($permessoArray, $permessoStudiArray);
    for($i=0; $i<count($permessoMergeArray); $i++) {
        $permessoOre += $permessoMergeArray[$i][1];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndex($permessoMergeArray[$i][0]) . '7', $permessoMergeArray[$i][1]);
    }
    if($permessoOre > 0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G7', $permessoOre);
    }

    $ferieOre = 0;
    $ferieArray = filterActivities($idUser, $idMonth, $idYear, 'Ferie', $db);
    for($i=0; $i<count($ferieArray); $i++) {
        $ferieOre += $ferieArray[$i][1];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndex($ferieArray[$i][0]) . '8', $ferieArray[$i][1]);
    }
    if($ferieOre > 0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G8', $ferieOre);
    }

    $matCongOre = 0;
    $maternitaArray = filterActivities($idUser, $idMonth, $idYear, 'Maternita', $db);
    $congedoParArray = filterActivities($idUser, $idMonth, $idYear, 'Congedo Parentale', $db);
    $matCongArray = array_merge($maternitaArray, $congedoParArray);
    for($i=0; $i<count($matCongArray); $i++) {
        $matCongOre += $matCongArray[$i][1];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndex($matCongArray[$i][0]) . '9', $matCongArray[$i][1]);
    }
    if($matCongOre > 0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G9', $matCongOre);
    }

    $malattiaOre = 0;
    $malattiaArray = filterActivities($idUser, $idMonth, $idYear, 'Malattia', $db);
    for($i=0; $i<count($malattiaArray); $i++) {
        $malattiaOre += $malattiaArray[$i][1];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndex($malattiaArray[$i][0]) . '10', $malattiaArray[$i][1]);
    }
    if($malattiaOre > 0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G10', $malattiaOre);
    }

    $oreNonLavorate = $permessoOre + $ferieOre + $matCongOre + $malattiaOre;
    if($oreNonLavorate > 0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G12', $oreNonLavorate);
    }

    $straordinarioOre = 0;
    $straordinarioArray = filterActivities($idUser, $idMonth, $idYear, 'Straordinario', $db);
    for($i=0; $i<count($straordinarioArray); $i++) {
        $straordinarioOre += $straordinarioArray[$i][1];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndex($straordinarioArray[$i][0]) . '13', $straordinarioArray[$i][1]);
    }
    if($straordinarioOre > 0) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G13', $straordinarioOre);
    }

    $commesseOre = array_fill(0, count($commesseUser), "");
    $presenzeArray = filterActivities($idUser, $idMonth, $idYear, 'Presente', $db);
    for($i=0; $i<count($presenzeArray); $i++) {
        $currentJob = $presenzeArray[$i][2];
        $currentHours = $presenzeArray[$i][1];
        $currentJobName = getJobNameFromId($currentJob, $db);
        $currentJobIndex = array_search($currentJobName, $commesseNomiUser);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndex($presenzeArray[$i][0]) .
                ($currentJobIndex + 15), $currentHours);
                $commesseOre[$currentJobIndex] += $currentHours;
    }

    $commesseOreTot = 0;
    for($i=0; $i<count($commesseOre); $i++) {
        $commesseOreTot += $commesseOre[$i];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G' . ($i + 15), $commesseOre[$i]);
    }

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G43', $commesseOreTot);

    $objPHPExcel->getActiveSheet()
            ->setTitle($title);

    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');

?>
