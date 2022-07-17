<?php
/*
require_once ('/httpdocs/new_area_riservata/class/dbconn.php');
require_once ('/httpdocs/new_area_riservata/class/user_ferie_permessi.class.php');
*/

require_once ('class/dbconn.php');
require_once ('class/user_ferie_permessi.class.php');
require_once ('assets/php/functions.php');

$database = new Database();
$db = $database->dbConnection();

$userFeriePermessi = new UserFeriePermessi();
$arrayAllUsers = $userFeriePermessi->getAll();

foreach($arrayAllUsers as $user) {

    if (true) {
        $currentGiorniFerie = $user['giorni_ferie'];
        $currentOrePermesso = $user['ore_permesso'];

        // Update giorni ferie
        $currentMonth = date('n');
        if($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 4 ||
            $currentMonth == 6 || $currentMonth == 7 || $currentMonth == 9 ||
            $currentMonth == 10 || $currentMonth == 12) {
            $currentGiorniFerie += 2.17;
        } else {
            $currentGiorniFerie += 2.16;
        }
        $userFeriePermessi->setGiorniFerieByIdUser($user['id_user'], $currentGiorniFerie);

        // Update ore permesso
        $oreSettimanali = $user['ore_giorno'] * $user['giorni_settimana'];
        if($oreSettimanali > 0 && $oreSettimanali < 8) {
            if($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 4 ||
                $currentMonth == 6 || $currentMonth == 7 || $currentMonth == 9 ||
                $currentMonth == 10 || $currentMonth == 12) {
                $currentOrePermesso += 0.33;
            } else {
                $currentOrePermesso += 0.34;
            }
        } else if($oreSettimanali >= 8 && $oreSettimanali < 13) {
            if($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 4 ||
                $currentMonth == 6 || $currentMonth == 7 || $currentMonth == 9 ||
                $currentMonth == 10 || $currentMonth == 12) {
                $currentOrePermesso += 0.67;
            } else {
                $currentOrePermesso += 0.66;
            }
        } else if($oreSettimanali >= 13 && $oreSettimanali < 18) {
            $currentOrePermesso += 1;
        } else if($oreSettimanali >= 18 && $oreSettimanali < 23) {
            if($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 4 ||
                $currentMonth == 6 || $currentMonth == 7 || $currentMonth == 9 ||
                $currentMonth == 10 || $currentMonth == 12) {
                $currentOrePermesso += 1.33;
            } else {
                $currentOrePermesso += 1.34;
            }
        } else if($oreSettimanali >= 23 && $oreSettimanali < 28) {
            if($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 4 ||
                $currentMonth == 6 || $currentMonth == 7 || $currentMonth == 9 ||
                $currentMonth == 10 || $currentMonth == 12) {
                $currentOrePermesso += 1.67;
            } else {
                $currentOrePermesso += 1.66;
            }
        } else if($oreSettimanali >= 28 && $oreSettimanali < 33) {
            $currentOrePermesso += 2;
        } else if($oreSettimanali >= 33 && $oreSettimanali < 38) {
            if($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 4 ||
                $currentMonth == 6 || $currentMonth == 7 || $currentMonth == 9 ||
                $currentMonth == 10 || $currentMonth == 12) {
                $currentOrePermesso += 2.33;
            } else {
                $currentOrePermesso += 2.34;
            }
        } else if($oreSettimanali >= 38 && $oreSettimanali < 42) {
            if($currentMonth == 1 || $currentMonth == 3 || $currentMonth == 4 ||
                $currentMonth == 6 || $currentMonth == 7 || $currentMonth == 9 ||
                $currentMonth == 10 || $currentMonth == 12) {
                $currentOrePermesso += 2.67;
            } else {
                $currentOrePermesso += 2.66;
            }
        } else if($oreSettimanali >= 42) {
            $currentOrePermesso += 3;
        }

        $dataAssunzioneTimestamp = getTimestampFromDate($user['data_assunzione']);
        $dataInizioPRO = strtotime('-2 year');
        $dataFullPRO = strtotime('-4 year');
        if ($dataAssunzioneTimestamp < $dataFullPRO) {
            $currentOrePermesso += 6;
        } else if ($dataAssunzioneTimestamp < $dataInizioPRO) {
            $currentOrePermesso += 3;
        }

        $userFeriePermessi->setOrePermessoByIdUser($user['id_user'], $currentOrePermesso);
    }

}

?>
