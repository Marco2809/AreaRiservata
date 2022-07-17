<?php
/*
   echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

echo ini_get('display_errors');
*/
    require_once ('./class/dbconn.php');
    require_once ('./assets/php/PHPExcel.php');
    require_once ('./assets/php/functions.php');

    session_start();
    date_default_timezone_set('Europe/Rome');

    $database = new Database();
    $db = $database->dbConnection();

    $idMonth = getMonthOneDigit($_GET['mese']);
    $nameMonth = getMonthName($idMonth);
    $idYear = $_GET['anno'];
    $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $idMonth, $idYear);
    $title = 'Timesheet' . $idMonth . $idYear;

    $objPHPExcel = new PHPExcel();

    // Inserting names and infos of hours and days of work
    $userIds = array();
    $userHours = array();
    $activeUserState = 1;

    $stmt = $db->prepare(
        "SELECT anagrafica.user_id, anagrafica.nome, anagrafica.cognome,
            info_hr.ore_giorno, info_hr.giorni_settimana
        FROM anagrafica
        INNER JOIN info_hr ON anagrafica.user_id = info_hr.user_id
        INNER JOIN login ON anagrafica.user_id = login.user_idd
        WHERE login.is_active = ?
        ORDER BY anagrafica.cognome");
    $stmt->bind_param('i', $activeUserState);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId, $userName, $userSurname, $userInfoHours, $userInfoDays);

    $i = 10;

    while($stmt->fetch()) {
        array_push($userIds, $userId);
        $userHours[$userId] = $userInfoHours;

        $objPHPExcel->getActiveSheet()
            ->getStyle('A' . $i)
            ->getFont()->setBold(true);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, strtoupper($userSurname . ' ' . $userName))
            ->setCellValue('A' . ($i+1), $userInfoHours . '*' . $userInfoDays);

        // Settaggio colore sfondo nomi
        $objPHPExcel->getActiveSheet()
            ->getStyle('A' . $i)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

        $objPHPExcel->getActiveSheet()
            ->getStyle('A' . $i)
            ->getFill()
            ->getStartColor()
            ->setRGB('bfbfbf');

        $i += 2;
    }

    $objPHPExcel->getProperties()
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription($title);

    $objPHPExcel->getActiveSheet()
        ->getStyle('F4')
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    // Settaggio larghezze colonne
    $lett_arr = array('A'=>30, 'B'=>5, 'C'=>5, 'D'=>5, 'E'=>5, 'F'=>5,
        'G'=>5, 'H'=>5, 'I'=>5, 'J'=>5, 'K'=>5, 'L'=>5, 'M'=>5, 'N'=>5,
        'O'=>5, 'P'=>5, 'Q'=>5, 'R'=>5, 'S'=>5, 'T'=>5, 'U'=>5, 'V'=>5,
        'W'=>5, 'X'=>5, 'Y'=>5, 'Z'=>5, 'AA'=>5, 'AB'=>5, 'AC'=>5, 'AD'=>5,
        'AE'=>5, 'AF'=>5, 'AG'=>5, 'AH'=>8, 'AI'=>12, 'AJ'=>8, 'AK'=>12, 'AL'=>13,
        'AM'=>13, 'AN'=>12, 'AO'=>16, 'AP'=>14);

    foreach($lett_arr as $key => $value) {
        $objPHPExcel->getActiveSheet()
            ->getColumnDimension($key)
            ->setWidth($value);
    }

    // Settaggio celle con testo in grassetto
    $objPHPExcel->getActiveSheet()
            ->getStyle("C1:Q1")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("C3:AG3")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("C5:AA5")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("A8:AQ8")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("D9:AH9")
            ->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()
            ->getStyle("A" . (10 + count($userIds)*2) . ":" .
                getColumnFromIndexMassive($numberOfDays + 10) . (11 + count($userIds)*2))
            ->getFont()->setBold(true);

    // Settaggio allineamento centrale celle interne
    $objPHPExcel->getActiveSheet()
        ->getStyle("B8:AN" . (9 + count($userIds)*2))
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    // Settaggio valori celle statici
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C1', 'FOGLIO PRESENZE DEL MESE DI:')
            ->setCellValue('K1', strtoupper($nameMonth))
            ->setCellValue('P1', $idYear)
            ->setCellValue('C3', 'P = Presente   M = Malattia  MA = Maternità  CP= Congedo parentale  F = Ferie  PR = Permesso  PS = Permesso Studi  EX = Ex Festività  FE = Festività')
            ->setCellValue('D5', 'PRESENTE')
            ->setCellValue('G5', 'FERIE')
            ->setCellValue('I5', 'MALATTIA')
            ->setCellValue('L5', 'MATERNITÀ')
            ->setCellValue('O5', 'CONGEDO PARENTALE')
            ->setCellValue('T5', 'PERMESSO')
            ->setCellValue('W5', 'PERMESSO STUDI')
            ->setCellValue('AA5', 'STRAORDINARIO')
            ->setCellValue('A8', 'NOMINATIVI')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 1) . '8', 'Note')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 2) . '8', 'Tot. Ore')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 3) . '8', 'Ore Lavorate')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 4) . '8', 'Ore Ferie')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 5) . '8', 'Ore Malattia')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 6) . '8', 'Ore Maternità')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 7) . '8', 'Ore Congedo P.')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 8) . '8', 'Ore Permesso')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 9) . '8', 'Ore Permesso Studi')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + 10) . '8', 'Ore Straordinari')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays) .
                (10 + count($userIds)*2), 'TOTALE')
            ->setCellValue(getColumnFromIndexMassive($numberOfDays) .
                (11 + count($userIds)*2), '%');

    // Settaggio colori di sfondo celle

    // Cella Titolo
    $objPHPExcel->getActiveSheet()
        ->getStyle('C1:Q1')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('C1:Q1')
        ->getFill()
        ->getStartColor()
        ->setRGB('fffd38');

    // Cella Legenda Presente
    $objPHPExcel->getActiveSheet()
        ->getStyle('C5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('C5')
        ->getFill()
        ->getStartColor()
        ->setRGB('2faf26');

    // Cella Legenda Ferie
    $objPHPExcel->getActiveSheet()
        ->getStyle('F5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('F5')
        ->getFill()
        ->getStartColor()
        ->setRGB('fdbf2d');

    // Cella Legenda Malattia
    $objPHPExcel->getActiveSheet()
        ->getStyle('H5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H5')
        ->getFill()
        ->getStartColor()
        ->setRGB('fffd38');

    // Cella Legenda Maternità
    $objPHPExcel->getActiveSheet()
        ->getStyle('K5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('K5')
        ->getFill()
        ->getStartColor()
        ->setRGB('8faad9');

    // Cella Legenda Congedo Parentale
    $objPHPExcel->getActiveSheet()
        ->getStyle('N5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('N5')
        ->getFill()
        ->getStartColor()
        ->setRGB('bdbfbd');

    // Cella Legenda Permesso
    $objPHPExcel->getActiveSheet()
        ->getStyle('S5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('S5')
        ->getFill()
        ->getStartColor()
        ->setRGB('f2b087');

    // Cella Legenda Permesso Studio
    $objPHPExcel->getActiveSheet()
        ->getStyle('V5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('V5')
        ->getFill()
        ->getStartColor()
        ->setRGB('db7173');

    // Cella Legenda Straordinario
    $objPHPExcel->getActiveSheet()
        ->getStyle('Z5')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('Z5')
        ->getFill()
        ->getStartColor()
        ->setRGB('5667ff');

    // Celle titoli
    $objPHPExcel->getActiveSheet()
        ->getStyle('A8:' . getColumnFromIndexMassive($numberOfDays + 10) . '8')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

    $objPHPExcel->getActiveSheet()
        ->getStyle('A8:' . getColumnFromIndexMassive($numberOfDays + 10) . '8')
        ->getFill()
        ->getStartColor()
        ->setRGB('bfbfbf');


    // Colonne dei giorni festivi
    for($i=0; $i<$numberOfDays; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(PHPExcel_Cell::stringFromColumnIndex($i+1) . '8', strval($i+1));

        $currentDate = getTimestamp($i+1, $idMonth, $idYear);
        if(getPosDayFromDate($currentDate) == 6 || getPosDayFromDate($currentDate) == 7) {
            $objPHPExcel->getActiveSheet()
                ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i+1) . '8:' .
                        PHPExcel_Cell::stringFromColumnIndex($i+1) . (9 + count($userIds)*2))
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(PHPExcel_Cell::stringFromColumnIndex($i+1) . '8:' .
                        PHPExcel_Cell::stringFromColumnIndex($i+1) . (9 + count($userIds)*2))
                ->getFill()
                ->getStartColor()
                ->setRGB('94ce58');
        }
    }

    //Settaggio bordi

    $allBordersStyleArray = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array('rgb' => '000000'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
        ->getStyle('A8:' . getColumnFromIndexMassive($numberOfDays + 10) . '8')
        ->applyFromArray($allBordersStyleArray);

    $outlineStyleArray = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array('rgb' => '000000'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
        ->getStyle('A9:' . getColumnFromIndexMassive($numberOfDays + 10) . '9')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('A' . (10 + count($userIds)*2) . ':' .
            getColumnFromIndexMassive($numberOfDays + 10) . (10 + count($userIds)*2))
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('A' . (11 + count($userIds)*2) . ':' .
            getColumnFromIndexMassive($numberOfDays + 10) . (11 + count($userIds)*2))
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle(getColumnFromIndexMassive($numberOfDays - 1) . (10 + count($userIds)*2) . ':' .
            getColumnFromIndexMassive($numberOfDays + 1) . (11 + count($userIds)*2))
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('C1:Q1')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('C5')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('F5')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('H5')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('K5')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('N5')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('S5')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('V5')
        ->applyFromArray($outlineStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('Z5')
        ->applyFromArray($outlineStyleArray);

    $rightStyleArray = array(
        'borders' => array(
            'right' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
        ->getStyle('I1')
        ->applyFromArray($rightStyleArray);

    $objPHPExcel->getActiveSheet()
        ->getStyle('N1')
        ->applyFromArray($rightStyleArray);


    $insideStyleArray = array(
        'borders' => array(
            'inside' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'),
            ),
        ),
    );

    $verticalStyleArray = array(
        'borders' => array(
            'vertical' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000'),
            ),
        ),
    );

    $objPHPExcel->getActiveSheet()
            ->getStyle(getColumnFromIndexMassive($numberOfDays + 2) . (10 + count($userIds)*2) . ':' .
                getColumnFromIndexMassive($numberOfDays + 10) . (11 + count($userIds)*2))
            ->applyFromArray($verticalStyleArray);

    $row = 10;

    foreach($userIds as $currentUserId) {

        // Bordi
        $objPHPExcel->getActiveSheet()
            ->getStyle('A' . $row . ':A' . ($row+1))
            ->applyFromArray($outlineStyleArray);

        $objPHPExcel->getActiveSheet()
            ->getStyle('B' . $row . ':' . getColumnFromIndexMassive($numberOfDays) . ($row+1))
            ->applyFromArray($outlineStyleArray);

        $objPHPExcel->getActiveSheet()
            ->getStyle('B' . $row . ':' . getColumnFromIndexMassive($numberOfDays) . ($row+1))
            ->applyFromArray($insideStyleArray);

        $objPHPExcel->getActiveSheet()
            ->getStyle(getColumnFromIndexMassive($numberOfDays + 1) . $row)
            ->applyFromArray($outlineStyleArray);

        $objPHPExcel->getActiveSheet()
            ->getStyle(getColumnFromIndexMassive($numberOfDays + 1) . ($row+1))
            ->applyFromArray($outlineStyleArray);

        $objPHPExcel->getActiveSheet()
            ->getStyle(getColumnFromIndexMassive($numberOfDays + 2) . $row . ':' .
                getColumnFromIndexMassive($numberOfDays + 10) . ($row+1))
            ->applyFromArray($verticalStyleArray);

        $objPHPExcel->getActiveSheet()
            ->getStyle(getColumnFromIndexMassive($numberOfDays + 2) . $row . ':' .
                getColumnFromIndexMassive($numberOfDays + 10) . ($row+1))
            ->applyFromArray($outlineStyleArray);


        // Sfondi
        $objPHPExcel->getActiveSheet()
            ->getStyle(getColumnFromIndexMassive($numberOfDays + 1) . $row)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

        $objPHPExcel->getActiveSheet()
            ->getStyle(getColumnFromIndexMassive($numberOfDays + 1) . $row)
            ->getFill()
            ->getStartColor()
            ->setRGB('bdbfbd');

        // Ferie
        $ferieOre = 0;
        $ferieArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Ferie', $_GET['id_commesse'], $db);
        for($i=0; $i<count($ferieArray); $i++) {
            $ferieOre += $ferieArray[$i][1];

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($ferieArray[$i][0]) . $row)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($ferieArray[$i][0]) . $row)
                ->getFill()
                ->getStartColor()
                ->setRGB('fdbf2d');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($ferieArray[$i][0]) . $row, 'F');
        }
        if($ferieOre > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 4) . $row, $ferieOre);
        }

        // Congedo Parentale
        $congedoOre = 0;
        $congedoArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Congedo Parentale', $_GET['id_commesse'], $db);
        for($i=0; $i<count($congedoArray); $i++) {
            $congedoOre += $congedoArray[$i][1];

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($congedoArray[$i][0]) . $row)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($congedoArray[$i][0]) . $row)
                ->getFill()
                ->getStartColor()
                ->setRGB('bdbfbd');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($congedoArray[$i][0]) . $row, 'CP');
        }
        if(($congedoOre) > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 7) . $row, $congedoOre);
        }

        // Maternità
        $maternitaOre = 0;
        $maternitaArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Maternita', $_GET['id_commesse'], $db);
        for($i=0; $i<count($maternitaArray); $i++) {
            $maternitaOre += $maternitaArray[$i][1];

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($maternitaArray[$i][0]) . $row)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($maternitaArray[$i][0]) . $row)
                ->getFill()
                ->getStartColor()
                ->setRGB('8faad9');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($maternitaArray[$i][0]) . $row, 'MA');
        }
        if(($maternitaOre) > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 6) . $row, $maternitaOre);
        }

        // Malattia
        $malattiaOre = 0;
        $malattiaArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Malattia', $_GET['id_commesse'], $db);
        for($i=0; $i<count($malattiaArray); $i++) {
            $malattiaOre += $malattiaArray[$i][1];

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($malattiaArray[$i][0]) . $row)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($malattiaArray[$i][0]) . $row)
                ->getFill()
                ->getStartColor()
                ->setRGB('fffd38');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($malattiaArray[$i][0]) . $row, 'M');
        }
        if($malattiaOre > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 5) . $row, $malattiaOre);
        }

        // Permesso
        $permessoOre = 0;
        $permessoArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Permesso', $_GET['id_commesse'], $db);
        for($i=0; $i<count($permessoArray); $i++) {
            $permessoOre += $permessoArray[$i][1];

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($permessoArray[$i][0]) . ($row+1))
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($permessoArray[$i][0]) . ($row+1))
                ->getFill()
                ->getStartColor()
                ->setRGB('f2b087');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($permessoArray[$i][0]) . ($row+1), $permessoArray[$i][1]);

            if($permessoArray[$i][1] == $userHours[$currentUserId]) {
                $objPHPExcel->getActiveSheet()
                    ->getStyle(getColumnFromIndexMassive($permessoArray[$i][0]) . $row)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

                $objPHPExcel->getActiveSheet()
                    ->getStyle(getColumnFromIndexMassive($permessoArray[$i][0]) . $row)
                    ->getFill()
                    ->getStartColor()
                    ->setRGB('f2b087');

                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($permessoArray[$i][0]) . $row, 'PR');
            }
        }
        if($permessoOre > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 8) . $row, $permessoOre);
        }

        // Permesso Studio
        $permessoStudiOre = 0;
        $permessoStudiArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Permesso Studi', $_GET['id_commesse'], $db);
        for($i=0; $i<count($permessoStudiArray); $i++) {
            $permessoStudiOre += $permessoStudiArray[$i][1];

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($permessoStudiArray[$i][0]) . ($row+1))
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($permessoStudiArray[$i][0]) . ($row+1))
                ->getFill()
                ->getStartColor()
                ->setRGB('db7173');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($permessoStudiArray[$i][0]) . ($row+1), $permessoStudiArray[$i][1]);

            if($permessoStudiArray[$i][1] == $userHours[$currentUserId]) {
                $objPHPExcel->getActiveSheet()
                    ->getStyle(getColumnFromIndexMassive($permessoStudiArray[$i][0]) . $row)
                    ->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

                $objPHPExcel->getActiveSheet()
                    ->getStyle(getColumnFromIndexMassive($permessoStudiArray[$i][0]) . $row)
                    ->getFill()
                    ->getStartColor()
                    ->setRGB('db7173');

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue(getColumnFromIndexMassive($permessoStudiArray[$i][0]) . $row, 'PS');
            }
        }
        if($permessoStudiOre > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 9) . $row, $permessoStudiOre);
        }

        // Straordinario
        $straordinarioOre = 0;
        $straordinarioArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Straordinario', $_GET['id_commesse'], $db);
        for($i=0; $i<count($straordinarioArray); $i++) {
            $straordinarioOre += $straordinarioArray[$i][1];

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($straordinarioArray[$i][0]) . ($row+1))
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($straordinarioArray[$i][0]) . ($row+1))
                ->getFill()
                ->getStartColor()
                ->setRGB('5667ff');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($straordinarioArray[$i][0]) . ($row+1), $straordinarioArray[$i][1]);
        }
        if($straordinarioOre > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 10) . $row, $straordinarioOre);
        }

        // Presenza
        $oreGiornaliere = array();
        for($i = 0; $i < $numberOfDays; $i++) {
            array_push($oreGiornaliere, 0);
        }

        $presenzaOre = 0;
        $presenzaArray = filterActivities($currentUserId, $idMonth, $idYear,
            $_GET['commessa'], 'Presente', $_GET['id_commesse'], $db);
        for($i=0; $i<count($presenzaArray); $i++) {
            $presenzaOre += $presenzaArray[$i][1];
            $oreGiornaliere[$presenzaArray[$i][0]] += $presenzaArray[$i][1];
            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($presenzaArray[$i][0]) . $row)
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

            $objPHPExcel->getActiveSheet()
                ->getStyle(getColumnFromIndexMassive($presenzaArray[$i][0]) . $row)
                ->getFill()
                ->getStartColor()
                ->setRGB('2faf26');

            $permessoOrStraordinario = $objPHPExcel->getActiveSheet()
                ->getCell(getColumnFromIndexMassive($presenzaArray[$i][0]) . ($row+1))
                ->getValue();

            if($permessoOrStraordinario != '') {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue(getColumnFromIndexMassive($presenzaArray[$i][0]) . $row, $oreGiornaliere[$presenzaArray[$i][0]]);
            } else {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue(getColumnFromIndexMassive($presenzaArray[$i][0]) . $row, 'P');
            }

        }
        if($presenzaOre > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(getColumnFromIndexMassive($numberOfDays + 3) . $row, $presenzaOre);
        }

        // Scrittura totale ore
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue(getColumnFromIndexMassive($numberOfDays + 2) . $row,
                '=SUM(' . getColumnFromIndexMassive($numberOfDays + 3) . $row . ':' .
                getColumnFromIndexMassive($numberOfDays + 10) . $row . ')');

        $row += 2;

    }

    // Scrittura totale e percentuale ore
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue(getColumnFromIndexMassive($numberOfDays + 2) . (10 + count($userIds)*2),
            '=SUM(' . getColumnFromIndexMassive($numberOfDays + 2) . '10:' .
            getColumnFromIndexMassive($numberOfDays + $i) . (9 + count($userIds)*2) . ')');

    for($i=3; $i<11; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + $i) . (10 + count($userIds)*2),
                '=SUM(' . getColumnFromIndexMassive($numberOfDays + $i) . '10:' .
                getColumnFromIndexMassive($numberOfDays + $i) . (9 + count($userIds)*2) . ')');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue(getColumnFromIndexMassive($numberOfDays + $i) . (11 + count($userIds)*2),
                '=' . getColumnFromIndexMassive($numberOfDays + $i) . (10 + count($userIds)*2) . '/' .
                getColumnFromIndexMassive($numberOfDays + 2) . (10 + count($userIds)*2));

        $objPHPExcel->getActiveSheet()->getStyle(getColumnFromIndexMassive($numberOfDays + $i) . (11 + count($userIds)*2))
            ->getNumberFormat()->applyFromArray(
                array(
                    'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
                )
            );
    }

    $objPHPExcel->getActiveSheet()
            ->setTitle($title);

    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$title.'.xls"');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');

?>
