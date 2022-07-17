<?php
session_start();
require_once('dbconn.php');
require_once('./assets/php/functions.php');
require_once('./class/user_ferie_permessi.class.php');
require_once('./class/user.class.php');
require_once('./assets/php/PHPMailer/PHPMailerAutoload.php');

class Attivita
{
    public $db;
    var $id_attivita;
    var $id_utente;
    var $data;
    var $ore;
    var $tipo;
    var $timecard;
    var $stato;
    var $motivo;
    var $protocollo;
    var $note;

    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

public function getTodayPresences() {
    $presencesArray = array();
    $outputArray = array();

    $resultPresences = $this->db->query(
        "SELECT id_attivita, id_utente, ore, tipo
        FROM attivita
        WHERE attivita.data = DATE_FORMAT(CURRENT_DATE, \"%d/%m/%Y\")");
    while ($rowPresences = $resultPresences->fetch_assoc()) {
        array_push($presencesArray, $rowPresences);
    }

    $resultAll = $this->db->query(
        "SELECT anagrafica.user_id, anagrafica.nome, anagrafica.cognome, info_hr.ore_giorno,
            info_hr.giorni_settimana
        FROM anagrafica
        INNER JOIN info_hr ON info_hr.user_id = anagrafica.user_id
        ORDER BY anagrafica.cognome ASC");
    while ($row = $resultAll->fetch_assoc()) {
        $row['presenze'] = array_filter($presencesArray, function ($presence) use ($row) {
            return $presence['id_utente'] == $row['user_id'];
        });
        array_push($outputArray, $row);
    }

    return $outputArray;
}

public function updateAttivita($id_attivita,$ore,$tipo,$timecard){
    $sql = "UPDATE attivita

                           SET
                           ore = '" . $ore . "',
                           tipo = '" .$tipo. "',
                           timecard = '" .$timecard. "'

                           WHERE id_attivita ='" . $id_attivita . "'";

    $result = $this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }

    public function setAttivita($id_utente, $data, $oreArray, $tipo, $commesseArray, $nota) {

        for($i = 0; $i < count($commesseArray); $i++) {
            $stmtInsert = $this->db->prepare("INSERT INTO attivita (id_utente,
                data, ore, tipo, id_commessa, note) VALUES (?, ?, ?, ?, ?, ?)");

            $stmtInsert->bind_param("isssis", $id_utente, $data, $oreArray[$i], $tipo, $commesseArray[$i], $nota);
            $stmtInsert->execute();
            $stmtInsert->close();
        }

    }

    public function setAttivitaPer($id_utente, $data, $oreArray, $tipo, $commessa, $nota) {

        $stmtInsert = $this->db->prepare("INSERT INTO attivita (id_utente,
            data, ore, tipo, id_commessa, note) VALUES (?, ?, ?, ?, ?, ?)");

        $stmtInsert->bind_param("isssis", $id_utente, $data, $oreArray, $tipo, $commessa, $nota);
        $stmtInsert->execute();
        $stmtInsert->close();

    }

     public function delAttivita($id_attivita){

         $sql = "DELETE from attivita WHERE id_attivita = '" . $id_attivita . "'";
         $result = $this->db->query($sql);
         if($result){

             return 'ok';

         } else return 'error';
    }

    public function delAltreAttivita($id_utente, $data){

        $sql = "DELETE from attivita WHERE id_utente = '" . $id_utente . "' AND data ='" . $data . "'";
        $result = $this->db->query($sql);
        if($result){

            return 'ok';

        } else return 'error';
   }

   public function delAttivitaPres($id,$data){

         $sql = "DELETE from attivita WHERE id_utente = '" . $id . "' AND data = '" . $data . "' AND tipo = 'Presente'";
         $result = $this->db->query($sql);
         if($result){

             return 'ok';

         } else return 'error';
    }

    public function selectHoursByMonthAndYear($month, $year) {
        $outputArray = array();
        $currentUserArray = array();
        $statoNonVal = 'Non Validato';
        $activeStatus = 1;

        // Query per ricavare il totale delle ore (lavorate + non lavorate)
        $stmt = $this->db->prepare(
            "SELECT attivita.id_utente, anagrafica.nome,
              anagrafica.cognome, SUM(attivita.ore) AS tot_ore
            FROM attivita
            INNER JOIN anagrafica ON attivita.id_utente = anagrafica.user_id
            INNER JOIN login ON attivita.id_utente = login.user_idd
            WHERE SUBSTRING(attivita.data, 4, 2) = ?
              AND SUBSTRING(attivita.data, 7, 4) = ?
              AND login.is_active = ?
            GROUP BY attivita.id_utente
            ORDER BY anagrafica.cognome");
        $stmt->bind_param('ssi', $month, $year, $activeStatus);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId, $currentName, $currentSurname, $currentTotHours);
        while($stmt->fetch()) {
            $currentUserArray['id'] = $currentId;
            $currentUserArray['name'] = $currentName;
            $currentUserArray['surname'] = $currentSurname;
            $currentUserArray['totHours'] = $currentTotHours;

            // Query per ricavare le commesse a cui ha lavorato l'utente
            $commesseString = [];
            $stmtJobs = $this->db->prepare(
              "SELECT DISTINCT commesse.id_commessa, commesse.commessa
              FROM attivita
              INNER JOIN commesse ON attivita.id_commessa = commesse.id_commessa
              WHERE attivita.id_utente = ?
                AND SUBSTRING(data, 4, 2) = ?
                AND SUBSTRING(data, 7, 4) = ?");
            $stmtJobs->bind_param('iss', $currentId, $month, $year);
            $stmtJobs->execute();
            $stmtJobs->store_result();
            $stmtJobs->bind_result($idCommessa, $nomeCommessa);
            while($stmtJobs->fetch()) {
              $commesseString[] = $nomeCommessa;
            }
            $currentUserArray['jobs'] = implode(", ", $commesseString);
            $outputArray[$currentId] = $currentUserArray;
        }

        // Query per ricavare il totale dei giorni
        $stmt = $this->db->prepare(
            "SELECT id_utente, COUNT(DISTINCT data) AS tot_giorni
            FROM attivita
            INNER JOIN login ON attivita.id_utente = login.user_idd
            WHERE SUBSTRING(data, 4, 2) = ?
              AND SUBSTRING(data, 7, 4) = ?
              AND login.is_active = ?
            GROUP BY id_utente");
        $stmt->bind_param('ssi', $month, $year, $activeStatus);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId, $currentTotDays);
        while($stmt->fetch()) {
            $outputArray[$currentId]['totDays'] = $currentTotDays;
        }

        // Query per ricavare l'info sulle attività non validate
        $stmt = $this->db->prepare(
          "SELECT id_utente, COUNT(id_utente) AS not_valid
          FROM attivita
          INNER JOIN login ON attivita.id_utente = login.user_idd
          WHERE SUBSTRING(data, 4, 2) = ?
            AND SUBSTRING(data, 7, 4) = ?
            AND login.is_active = ?
            AND stato = ?
          GROUP BY id_utente");
        $stmt->bind_param('ssis', $month, $year, $activeStatus, $statoNonVal);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId, $notValid);
        while($stmt->fetch()) {
            $outputArray[$currentId]['valid'] = $notValid;
        }

        return $outputArray;
    }

    public function selectHoursByMonthAndYearForResponsabile($month, $year, $jobIDs) {
        $outputArray = array();
        $currentUserArray = array();
        $statoNonVal = 'Non Validato';
        $jobIDs = implode(",", $jobIDs);
        $activeStatus = 1;

        // Query per ricavare il totale delle ore (lavorate + non lavorate)
        $stmt = $this->db->prepare(
          "SELECT attivita.id_utente, anagrafica.nome, anagrafica.cognome, SUM(attivita.ore) AS tot_ore
          FROM attivita
          INNER JOIN anagrafica ON attivita.id_utente = anagrafica.user_id
          INNER JOIN login ON attivita.id_utente = login.user_idd
          WHERE SUBSTRING(attivita.data, 4, 2) = ?
            AND SUBSTRING(attivita.data, 7, 4) = ?
            AND attivita.id_commessa IN (" . $jobIDs . ")
            AND login.is_active = ?
          GROUP BY attivita.id_utente");
        $stmt->bind_param('ssi', $month, $year, $activeStatus);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId, $currentName, $currentSurname, $currentTotHours);
        while($stmt->fetch()) {
            $currentUserArray['id'] = $currentId;
            $currentUserArray['name'] = $currentName;
            $currentUserArray['surname'] = $currentSurname;
            $currentUserArray['totHours'] = $currentTotHours;

            // Query per ricavare le commesse a cui ha lavorato l'utente
            $commesseString = [];
            $stmtJobs = $this->db->prepare(
              "SELECT DISTINCT commesse.id_commessa, commesse.commessa
              FROM attivita
              INNER JOIN commesse ON attivita.id_commessa = commesse.id_commessa
              INNER JOIN login ON attivita.id_utente = login.user_idd
              WHERE attivita.id_utente = ?
                AND SUBSTRING(data, 4, 2) = ?
                AND SUBSTRING(data, 7, 4) = ?
                AND attivita.id_commessa IN (" . $jobIDs . ")
                AND login.is_active = ?");
            $stmtJobs->bind_param('issi', $currentId, $month, $year, $activeStatus);
            $stmtJobs->execute();
            $stmtJobs->store_result();
            $stmtJobs->bind_result($idCommessa, $nomeCommessa);
            while($stmtJobs->fetch()) {
              $commesseString[] = $nomeCommessa;
            }
            $currentUserArray['jobs'] = implode(", ", $commesseString);
            $outputArray[$currentId] = $currentUserArray;
        }

        // Query per ricavare il totale dei giorni
        $stmt = $this->db->prepare(
          "SELECT id_utente, COUNT(DISTINCT data) AS tot_giorni
          FROM attivita
          INNER JOIN login ON attivita.id_utente = login.user_idd
          WHERE SUBSTRING(data, 4, 2) = ?
            AND SUBSTRING(data, 7, 4) = ?
            AND attivita.id_commessa IN (" . $jobIDs . ")
            AND login.is_active = ?
          GROUP BY id_utente");
        $stmt->bind_param('ssi', $month, $year, $activeStatus);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId, $currentTotDays);
        while($stmt->fetch()) {
            $outputArray[$currentId]['totDays'] = $currentTotDays;
        }

        // Query per ricavare l'info sulle attività non validate
        $stmt = $this->db->prepare(
          "SELECT id_utente, COUNT(id_utente) AS not_valid
          FROM attivita
          INNER JOIN login ON attivita.id_utente = login.user_idd
          WHERE SUBSTRING(data, 4, 2) = ?
            AND SUBSTRING(data, 7, 4) = ?
            AND stato = ?
            AND attivita.id_commessa IN (" . $jobIDs . ")
            AND login.is_active = ?
          GROUP BY id_utente");
        $stmt->bind_param('sssi', $month, $year, $statoNonVal, $activeStatus);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId, $notValid);
        while($stmt->fetch()) {
            $outputArray[$currentId]['valid'] = $notValid;
        }

        return $outputArray;
    }

    public function insertAutoPresences($userId) {

        // Getting work infos
        $workHours = $workDays = null;
        $stmtContract = $this->db->prepare("SELECT ore_giorno, giorni_settimana "
                . "FROM info_hr WHERE user_id = ?");
        $stmtContract->bind_param('i', $userId);
        $stmtContract->execute();
        $stmtContract->store_result();
        $stmtContract->bind_result($workHours, $workDays);
        $stmtContract->fetch();

        if($workDays > 5) {
            $saturdayWork = true;
        } else {
            $saturdayWork = false;
        }

        // Getting first job
        $jobId = null;
        $stmtJob = $this->db->prepare("SELECT id_commessa FROM user_commesse "
                . "WHERE id_user = ? LIMIT 1");
        $stmtJob->bind_param('i', $userId);
        $stmtJob->execute();
        $stmtJob->store_result();
        $stmtJob->bind_result($jobId);
        $stmtJob->fetch();

        if($workHours != null && $workDays != null && $jobId != null) {

            // Getting past presences
            $presences = array();
            $stmtPresences = $this->db->prepare("SELECT data FROM attivita WHERE id_utente = ?");
            $stmtPresences->bind_param('i', $userId);
            $stmtPresences->execute();
            $stmtPresences->store_result();
            $stmtPresences->bind_result($currentDate);
            while($stmtPresences->fetch()) {
                array_push($presences, $currentDate);
            }

            // Setting starting date infos
            $tempDate = '01/08/2017';
            $dayOfWeek = 2;

            // Setting current date infos
            $currentDay = date('j');
            $currentDayOfWeek = date('N');
            $currentSunday = $currentDay + (7 - $currentDayOfWeek);
            $currentMonth = date('m');
            $currentYear = date('Y');
            $currentDaysOfMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            if($currentSunday > $currentDaysOfMonth) {
                $currentSunday -= $currentDaysOfMonth;
                if($currentMonth == 12) {
                     $currentMonth = '01';
                     $currentYear++;
                } else {
                     $currentMonth++;
                     if($currentMonth < 10) {
                          $currentMonth = '0' . $currentMonth;
                     }
                }
            }
            if($currentSunday < 10) {
                $currentSunday = '0' . $currentSunday;
            }
            $currentDate = $currentSunday. '/' .$currentMonth. '/'. $currentYear;

            $type = 'Presente';
            $vacanze = array();
            array_push($vacanze, "15/08");
            array_push($vacanze, "01/01");
            array_push($vacanze, "06/01");
            array_push($vacanze, "25/04");
            array_push($vacanze, "01/05");
            array_push($vacanze, "02/06");
            array_push($vacanze, "01/11");
            array_push($vacanze, "08/12");
            array_push($vacanze, "24/12");
            array_push($vacanze, "25/12");

            while($tempDate != $currentDate) {
                if(!in_array($tempDate, $presences) && (!in_array(substr($tempDate,0,5), $vacanze)) && $dayOfWeek != 7) {
                    if($dayOfWeek != 6 || ($dayOfWeek == 6 && $saturdayWork)) {
                        $stmtInsert = $this->db->prepare("INSERT INTO attivita (id_utente,
                            data, ore, tipo, id_commessa) VALUES (?, ?, ?, ?, ?)");
                        $stmtInsert->bind_param("issss", $userId, $tempDate, $workHours,
                            $type, $jobId);
                        $stmtInsert->execute();
                        $stmtInsert->close();
                    }
                }

                $tempDate = str_replace('/', '-', $tempDate);
                                $tempDate = date("d-m-Y", strtotime("+1 day", strtotime($tempDate)));
                $tempDate = str_replace('-', '/', $tempDate);
                $dayOfWeek++;
                if($dayOfWeek == 8) {
                     $dayOfWeek = 1;
                }
            }

            return 1;
        } else {
            return -1;
        }

    }

    public function requestVacation($userId, $dates, $hours, $vacationsStored) {
        $dates = str_replace("[", "", $dates);
        $dates = str_replace("]", "", $dates);
        $dates = str_replace('"', "", $dates);
        $dates = str_replace(" ", "", $dates);
        $dates = preg_replace("/[\n\r]/", "", $dates);
        $datesList = explode(",", $dates);

        $type = 'Ferie';
        $idJob = 0;
        $state = 'Da Approvare';
        foreach($datesList as $date) {
            $stmtInsert = $this->db->prepare("INSERT INTO attivita (id_utente, data, ore, tipo, id_commessa, stato)
                            SELECT * FROM (SELECT " . $userId . ", '" . $date . "', " . $hours . ", '" . $type . "', " . $idJob . ", '" . $state . "') AS tmp
                            WHERE NOT EXISTS (
                                SELECT id_utente, data, ore, tipo, id_commessa, stato
                                FROM attivita
                                WHERE id_utente = ? AND data = ? AND ore = ? AND tipo = ? AND id_commessa = ? AND stato = ?
                            ) LIMIT 1");
            $stmtInsert->bind_param("isssis", $userId, $date, $hours, $type, $idJob, $state);
            $stmtInsert->execute();
            $stmtInsert->close();
        }

		if(count($datesList) > 0) {
			$user = new User();
			$email = $user->getEmailByUserId($userId);
			$userData = $user->getUserById($userId);
			$nameSurname = $userData['nome'] . ' ' . $userData['cognome'];

			$mail = new PHPMailer;
			$mail->IsHTML(true);
			$mail->setFrom($email, $nameSurname);
			//$mail->addAddress('HR@service-tech.it', 'HR');
            $mail->addAddress('marco.salmi89@gmail.com', 'HR');
			$mail->Subject = 'Richiesta Ferie';
			$bodyMail = 'Salve,<br><br>
					con la presente intendo richiedere le ferie per le seguenti giornate:<br><br>';
			foreach($datesList as $date) {
				$bodyMail .= '- ' . $date . '<br>';
			}
			$bodyMail .= '<br>Cordiali saluti.<br><br>' . $nameSurname;
			$mail->Body = convertUTF($bodyMail);
			$mail->send();
		}

	}

    public function getVacationToApprove($userId) {
        $outputArray = array();
        $stato = 'Da Approvare';
        $stmt = $this->db->prepare("SELECT id_attivita,data FROM attivita WHERE id_utente = ? AND stato = ? ORDER BY str_to_date(data,'%d/%m/%Y') ASC");
        $stmt->bind_param('is', $userId, $stato);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_attivita,$data);
        while($stmt->fetch()) {
			$arrayDati = array($id_attivita,$data);
            array_push($outputArray, $arrayDati);
        }

        return $outputArray;
    }

    public function getActivityToValidateByJobs($jobsIds, $month, $year) {
        $outputArray = array();
        $stato = 'Non Validato';
        $idRole = 4;
        $idCommessaVuoto = 0;

        foreach($jobsIds as $jobId) {
            $stmtWithJob = $this->db->prepare("SELECT attivita.id_attivita, attivita.id_utente, anagrafica.nome, "
                    . "anagrafica.cognome, attivita.data, attivita.ore, attivita.tipo, "
                    . "attivita.id_commessa FROM attivita INNER JOIN anagrafica "
                    . "ON attivita.id_utente = anagrafica.user_id WHERE (attivita.stato = ? "
                    . "AND attivita.id_commessa = ? AND SUBSTRING(attivita.data, 4, 2) = ? "
                    . "AND SUBSTRING(attivita.data, 7, 4) = ?) OR (attivita.stato = ? AND attivita.id_utente IN "
                    . "(SELECT id_user FROM user_commesse WHERE id_commessa = ? AND id_role = ?) "
                    . "AND SUBSTRING(attivita.data, 4, 2) = ? AND SUBSTRING(attivita.data, 7, 4) = ? "
                    . "AND attivita.id_commessa = ?)");

            $stmtWithJob->bind_param('sisssiissi', $stato, $jobId[0], $month, $year,
                    $stato, $jobId[0], $idRole, $month, $year, $idCommessaVuoto);
            $stmtWithJob->execute();
            $stmtWithJob->store_result();
            $stmtWithJob->bind_result($idAtt, $idUser, $nome, $cognome, $data, $ore,
                    $tipo, $idCommessa);
            while($stmtWithJob->fetch()) {
                $currentPresence = array($idAtt, $idUser, $nome, $cognome, $data, $ore, $tipo, $idCommessa);
                if(!in_array($currentPresence, $outputArray)) {
                    array_push($outputArray, $currentPresence);
                }
            }
        }

        return $outputArray;
    }

    public function getActivityToValidateByJobsAndByUser($jobsIds, $month, $year, $userId) {
        $outputArray = array();
        $stato = 'Non Validato';
        $idRole = 4;
        $idCommessaVuoto = 0;

        foreach($jobsIds as $jobId) {
            $stmtWithJob = $this->db->prepare("SELECT attivita.id_attivita, attivita.id_utente, anagrafica.nome, "
                    . "anagrafica.cognome, attivita.data, attivita.ore, attivita.tipo, "
                    . "attivita.id_commessa FROM attivita INNER JOIN anagrafica "
                    . "ON attivita.id_utente = anagrafica.user_id WHERE (attivita.stato = ? "
                    . "AND attivita.id_commessa = ? AND SUBSTRING(attivita.data, 4, 2) = ? "
                    . "AND SUBSTRING(attivita.data, 7, 4) = ? AND attivita.id_utente = ?) "
                    . "OR (attivita.stato = ? AND attivita.id_utente IN "
                    . "(SELECT id_user FROM user_commesse WHERE id_commessa = ? AND id_role = ? "
                    . "AND id_user = ?) AND SUBSTRING(attivita.data, 4, 2) = ? "
                    . "AND SUBSTRING(attivita.data, 7, 4) = ? AND attivita.id_commessa = ?)");

            $stmtWithJob->bind_param('sissisiiissi', $stato, $jobId[0], $month, $year,
                    $userId, $stato, $jobId[0], $idRole, $userId, $month, $year, $idCommessaVuoto);
            $stmtWithJob->execute();
            $stmtWithJob->store_result();
            $stmtWithJob->bind_result($idAtt, $idUser, $nome, $cognome, $data, $ore,
                    $tipo, $idCommessa);
            while($stmtWithJob->fetch()) {
                $currentPresence = array($idAtt, $idUser, $nome, $cognome, $data, $ore, $tipo, $idCommessa);
                if(!in_array($currentPresence, $outputArray)) {
                    array_push($outputArray, $currentPresence);
                }
            }
        }

        return $outputArray;
    }

    public function getVacationToApproveByJobs($jobsIds, $month, $year) {
        $outputArray = array();
        $stato = 'Da Approvare';
        $idRole = 4;
        $idCommessaVuoto = 0;

        foreach($jobsIds as $jobId) {
            $stmtWithJob = $this->db->prepare(
                "SELECT attivita.id_attivita, attivita.id_utente, anagrafica.nome,
                            anagrafica.cognome, attivita.data, attivita.ore, attivita.tipo, attivita.id_commessa
                        FROM attivita INNER JOIN anagrafica ON attivita.id_utente = anagrafica.user_id
                        WHERE attivita.stato = ? AND attivita.id_utente IN
                            (SELECT id_user FROM user_commesse WHERE id_commessa = ? AND id_role = ?)
                        AND SUBSTRING(attivita.data, 4, 2) = ? AND SUBSTRING(attivita.data, 7, 4) = ?
                        AND attivita.id_commessa = ?");

            $stmtWithJob->bind_param('siissi', $stato, $jobId[0], $idRole, $month, $year, $idCommessaVuoto);
            $stmtWithJob->execute();
            $stmtWithJob->store_result();
            $stmtWithJob->bind_result($idAtt, $idUser, $nome, $cognome, $data, $ore,
                    $tipo, $idCommessa);
            while($stmtWithJob->fetch()) {
                $currentPresence = array($idAtt, $idUser, $nome, $cognome, $data, $ore, $tipo, $idCommessa);
                if(!in_array($currentPresence, $outputArray)) {
                    array_push($outputArray, $currentPresence);
                }
            }
        }

        return $outputArray;
    }

    public function getVacationToApproveByJobsAndByUser($jobsIds, $month, $year, $userId) {
        $outputArray = array();
        $stato = 'Da Approvare';
        $idRole = 4;
        $idCommessaVuoto = 0;

        foreach($jobsIds as $jobId) {
            $stmtWithJob = $this->db->prepare("SELECT attivita.id_attivita, attivita.id_utente, anagrafica.nome,
                        anagrafica.cognome, attivita.data, attivita.ore, attivita.tipo,
                        attivita.id_commessa FROM attivita INNER JOIN anagrafica ON attivita.id_utente = anagrafica.user_id
                    WHERE attivita.stato = ? AND attivita.id_utente IN
                        (SELECT id_user FROM user_commesse WHERE id_commessa = ? AND id_role = ?
                        AND id_user = ?) AND SUBSTRING(attivita.data, 4, 2) = ?
                        AND SUBSTRING(attivita.data, 7, 4) = ? AND attivita.id_commessa = ?");

            $stmtWithJob->bind_param('siiissi', $stato, $jobId[0], $idRole,
                    $userId, $month, $year, $idCommessaVuoto);
            $stmtWithJob->execute();
            $stmtWithJob->store_result();
            $stmtWithJob->bind_result($idAtt, $idUser, $nome, $cognome, $data, $ore,
                    $tipo, $idCommessa);
            while($stmtWithJob->fetch()) {
                $currentPresence = array($idAtt, $idUser, $nome, $cognome, $data, $ore, $tipo, $idCommessa);
                if(!in_array($currentPresence, $outputArray)) {
                    array_push($outputArray, $currentPresence);
                }
            }
        }

        return $outputArray;
    }

    public function getUserByJob($idJob) {
        $stmt = $this->db->prepare("SELECT id_utente FROM attivita WHERE id_attivita = ?");
        $stmt->bind_param('i', $idJob);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($idUser);
        $stmt->fetch();
        return $idUser;
    }

    public function getAllToValidate() {
        $outputArray = array();
        $stato = 'Non Validato';
        $stmt = $this->db->prepare("SELECT attivita.id_attivita, attivita.id_utente, anagrafica.nome, "
                . "anagrafica.cognome, attivita.data, attivita.ore, attivita.tipo, "
                . "attivita.id_commessa FROM attivita INNER JOIN anagrafica "
                . "ON attivita.id_utente = anagrafica.user_id WHERE attivita.stato = ?");
        $stmt->bind_param('s', $stato);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($idAtt, $idUser, $nome, $cognome, $data, $ore,
                $tipo, $idCommessa);
        while($stmt->fetch()) {
            $currentPresence = array($idAtt, $idUser, $nome, $cognome, $data, $ore, $tipo, $idCommessa);
            array_push($outputArray, $currentPresence);
        }

        return $outputArray;
    }

    function validateActivity($idActivity) {
        $stato = 'Validato';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ? WHERE id_attivita = ?");
        $stmt->bind_param('si', $stato, $idActivity);
        if($stmt->execute()) {
            $stmt = $this->db->prepare("SELECT tipo, ore, id_utente FROM attivita WHERE id_attivita = ?");
            $stmt->bind_param('i', $idActivity);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($tipoAttivita, $oreAttivita, $idUser);
            $stmt->fetch();

            $userFeriePermessi = new UserFeriePermessi();
            if($tipoAttivita == 'Permesso' || $tipoAttivita == 'Permesso Studi') {
                $orePermesso = $userFeriePermessi->getOrePermessoByIdUser($idUser);
                $orePermesso -= $oreAttivita;
                $userFeriePermessi->setOrePermessoByIdUser($idUser, $orePermesso);
            } else if($tipoAttivita == 'Ferie') {
                $giorniFerie = $userFeriePermessi->getGiorniFerieByIdUser($idUser);
                $giorniFerie -= 1.2;
                $userFeriePermessi->setGiorniFerieByIdUser($idUser, $giorniFerie);
            }

            return 1;
        } else {
            return -1;
        }
    }

    function refuseActivity($idActivity, $cause) {
        $stato = 'Rifiutato';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ?, motivo = ? WHERE id_attivita = ?");
        $stmt->bind_param('ssi', $stato, $cause, $idActivity);
        if($stmt->execute()) {
            return 1;
        } else {
            return -1;
        }
    }

    function validateAllByUser($userId, $month, $year) {
        $statoNV = 'Non Validato';
        $tipoPermesso = 'Permesso';
        $tipoPermessoStudio = 'Permesso Studi';
        $orePermessoToRemove = 0;
        $stmt = $this->db->prepare("SELECT ore FROM attivita WHERE "
                . "id_utente = ? AND SUBSTRING(data, 4, 2) = ? "
                . "AND SUBSTRING(attivita.data, 7, 4) = ? AND stato = ? "
                . "AND (tipo = ? OR tipo = ?)");
        $stmt->bind_param('isssss', $userId, $month, $year, $statoNV, $tipoPermesso,
                $tipoPermessoStudio);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($oreAttivita);
        while($stmt->fetch()) {
            $orePermessoToRemove += $oreAttivita;
        }
        if($orePermessoToRemove > 0) {
            $userFeriePermessi = new UserFeriePermessi();
            $orePermesso = $userFeriePermessi->getOrePermessoByIdUser($userId);
            if($orePermesso != -1) {
                $orePermesso -= $orePermessoToRemove;
                $userFeriePermessi->setOrePermessoByIdUser($userId, $orePermesso);
            }
        }

        $tipoFerie = 'Ferie';
        $giorniFerieToRemove = 0;
        $stmt = $this->db->prepare("SELECT ore FROM attivita WHERE "
                . "id_utente = ? AND SUBSTRING(data, 4, 2) = ? "
                . "AND SUBSTRING(attivita.data, 7, 4) = ? AND stato = ? "
                . "AND tipo = ?");
        $stmt->bind_param('issss', $userId, $month, $year, $statoNV, $tipoFerie);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($oreAttivita);
        while($stmt->fetch()) {
            $giorniFerieToRemove += 1.2;
        }
        if($giorniFerieToRemove > 0) {
            $userFeriePermessi = new UserFeriePermessi();
            $giorniFerie = $userFeriePermessi->getGiorniFerieByIdUser($userId);
            if($giorniFerie != -1) {
                $giorniFerie -= $giorniFerieToRemove;
                $userFeriePermessi->setGiorniFerieByIdUser($userId, $giorniFerie);
            }
        }

        $stato = 'Validato';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ? "
                . "WHERE id_utente = ? AND SUBSTRING(data, 4, 2) = ? "
                . "AND SUBSTRING(attivita.data, 7, 4) = ?");
        $stmt->bind_param('siss', $stato, $userId, $month, $year);
        if($stmt->execute()) {
            return 1;
        } else {
            return -1;
        }
    }

    function refuseAllByUser($userId, $month, $year, $cause) {
        $stato = 'Rifiutato';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ?, motivo = ? "
                . "WHERE id_utente = ? AND SUBSTRING(data, 4, 2) = ? "
                . "AND SUBSTRING(attivita.data, 7, 4) = ?");
        $stmt->bind_param('ssiss', $stato, $cause, $userId, $month, $year);

        if($stmt->execute()) {
            return 1;
        } else {
            return -1;
        }
    }

    function approveVacation($idActivity) {
        $stato = 'Non Validato';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ? WHERE id_attivita = ?");
        $stmt->bind_param('si', $stato, $idActivity);
        if($stmt->execute()) {
            return 1;
        } else {
            return -1;
        }
    }

    function disapproveVacation($idActivity, $cause) {
        $stato = 'Non Approvato';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ?, motivo = ? WHERE id_attivita = ?");
        $stmt->bind_param('ssi', $stato, $cause, $idActivity);
        if($stmt->execute()) {
            return 1;
        } else {
            return -1;
        }
    }

    function approveAllByUser($userId, $month, $year) {
        $statoToApprove = 'Da Approvare';
        $stato = 'Non Validato';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ? "
                . "WHERE id_utente = ? AND SUBSTRING(data, 4, 2) = ? "
                . "AND SUBSTRING(data, 7, 4) = ? AND stato = ?");
        $stmt->bind_param('sisss', $stato, $userId, $month, $year, $statoToApprove);

        if($stmt->execute()) {
            return 1;
        } else {
            return -1;
        }
    }

    function disapproveAllByUser($userId, $month, $year, $cause) {
        $stato = 'Non Approvato';
        $statoToApprove = 'Da Approvare';
        $stmt = $this->db->prepare("UPDATE attivita SET stato = ?, motivo = ? "
                . "WHERE id_utente = ? AND SUBSTRING(data, 4, 2) = ? "
                . "AND SUBSTRING(attivita.data, 7, 4) = ? AND stato = ?");
        $stmt->bind_param('ssisss', $stato, $cause, $userId, $month, $year, $statoToApprove);

        if($stmt->execute()) {
            return 1;
        } else {
            return -1;
        }
    }

}

?>
