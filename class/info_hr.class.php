<?php
session_start();
require_once('dbconn.php');

class InfoHR
{
    public $db;
    private $id_user;
    private $ore;
    private $giorni;
    private $data_visita;
    private $data_assunzione;
    private $data_termine_contratto;

    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }


	public function editEmployeeHR($id, $name, $surname, $oreGiorno, $giorniSettimana, $scadVisitaMedica, $dataAssunzione, $scadContratto) {
        //consoleLog($id . ' | ' . $name . ' | ' . $oreGiorno . ' | ' . $giorniSettimana
                //. ' | ' . $scadVisitaMedica . ' | ' . $dataAssunzione . ' | ' . $scadContratto);


        $stmtAnagraphic = $this->db->prepare("UPDATE anagrafica SET nome = ?, cognome = ? WHERE user_id = ?");
        $stmtAnagraphic->bind_param('ssi', $name, $surname, $id);
        $stmtAnagraphic->execute();

		$stmtInfoHR = $this->db->prepare("UPDATE info_hr SET ore_giorno = ?, giorni_settimana = ?, scad_visita_medica = ?, data_assunzione = ?, scad_contratto = ? WHERE user_id = ?");
        $stmtInfoHR->bind_param('iisssi', $oreGiorno, $giorniSettimana, $scadVisitaMedica, $dataAssunzione, $scadContratto, $id);
        $stmtInfoHR->execute();

        if($stmtInfoHR && $stmtAnagraphic) {
            return true;
        } else {
            return false;
        }
    }

    public function activeUser($id, $value) {

          $stmtAnagraphic = $this->db->prepare("UPDATE info_hr SET active = ? WHERE user_id = ?");
          $stmtAnagraphic->bind_param('ii', $value, $id);
          $stmtAnagraphic->execute();

  		      $stmtInfoHR = $this->db->prepare("UPDATE login SET is_active = ? WHERE user_idd = ?");
          $stmtInfoHR->bind_param('ii', $value, $id);
          $stmtInfoHR->execute();

          if($stmtInfoHR && $stmtAnagraphic) {
              return true;
          } else {
              return false;
          }
      }

    public function editPositionHR($id, $oggetto, $detail, $luogo) {

          $stmtAnagraphic = $this->db->prepare("UPDATE posizioni_aperte SET positionOggetto = ?, positionDetail = ? ,positionLuogo = ? WHERE positionId = ?");
          $stmtAnagraphic->bind_param('sssi', $oggetto, $detail, $luogo, $id);
          $stmtAnagraphic->execute();

          if($stmtAnagraphic) {
              return true;
          } else {
              return false;
          }
      }

	public function editEmployeeCostiHR($id, $mese, $anno, $costo) {

		$stmtInfoHR = $this->db->prepare("UPDATE info_hr_costi SET costo_mensile = ? WHERE user_id = ? AND mese = ? AND anno = ?");
        $stmtInfoHR->bind_param('diii', $costo, $id, $mese, $anno);
        $stmtInfoHR->execute();

        if($stmtInfoHR) {
            return true;
        } else {
            return false;
        }
    }

    public function addPositionHR($oggetto, $dettagli, $luogo) {

  		$stmtInfoHR = $this->db->prepare("INSERT INTO posizioni_aperte (positionId,positionOggetto,positionDetail,positionLuogo) VALUES (NULL,?,?,?)");
          $stmtInfoHR->bind_param('sss', $oggetto,$dettagli,$luogo);
          $stmtInfoHR->execute();
          $stmtInfoHR->close();
          if($stmtInfoHR) {
              return true;
          } else {
              return false;
          }
      }

      public function addBeniHR($user, $tel, $car, $pc, $varie, $tel_text, $car_text, $pc_text, $varie_text) {

        $stmtInfoHR = $this->db->prepare("INSERT INTO beni_hr (id_beni,id_user,tel,car,pc,varie,tel_text,car_text,pc_text,varie_text ) VALUES (NULL,?,?,?,?,?,?,?,?,?)");
            $stmtInfoHR->bind_param('iiiiissss', $user, $tel, $car, $pc, $varie, $tel_text, $car_text, $pc_text, $varie_text);
            $stmtInfoHR->execute();
            $stmtInfoHR->close();
            if($stmtInfoHR) {
                return true;
            } else {
                return false;
            }
        }

      public function delPositionHR($id) {

        $stmtInfoHR = $this->db->prepare("DELETE FROM posizioni_aperte WHERE positionId = ?");
            $stmtInfoHR->bind_param('i', $id);
            $stmtInfoHR->execute();
            $stmtInfoHR->close();
            if($stmtInfoHR) {
                return true;
            } else {
                return false;
            }
        }

	public function saveEmployeeCostiHR($id, $mese, $anno, $costo) {

		$stmtInfoHR = $this->db->prepare("INSERT INTO info_hr_costi (info_hr_costi_id,user_id,mese,anno,costo_mensile) VALUES (NULL,?,?,?,?)");
        $stmtInfoHR->bind_param('iiid', $id,$mese,$anno,$costo);
        $stmtInfoHR->execute();
        $stmtInfoHR->close();
        if($stmtInfoHR) {
            return true;
        } else {
            return false;
        }
    }

    public function saveEmployeeInfoHR($id, $ore_giorno, $qualifica, $mansione,$giorni_settimana, $scad_visita_medica, $scad_contratto, $sub, $scad_distacco, $data_assunzione, $cat_protetta, $du, $email_personale, $scad_cf, $doc, $scad_doc, $livello, $corso_81, $centoquattro, $articolo, $percentuale) {

      $stmtInfoHR = $this->db->prepare("INSERT INTO info_hr (info_id,user_id,ore_giorno,qualifica,mansione,giorni_settimana, scad_visita_medica, scad_contratto, sub, scad_distacco, data_assunzione, cat_protetta, d_u, email_personale, scad_cf, doc, scad_doc, livello, corso_81, legge_104, articolo, percentuale) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
          $stmtInfoHR->bind_param('issssssississsssssiis', $id, $ore_giorno, $qualifica, $mansione, $giorni_settimana, $scad_visita_medica, $scad_contratto, $sub, $scad_distacco, $data_assunzione, $cat_protetta, $du, $email_personale, $scad_cf, $doc, $scad_doc, $livello, $corso_81, $centoquattro, $articolo, $percentuale);
          $stmtInfoHR->execute();
          $stmtInfoHR->close();
          if($stmtInfoHR) {
              return true;
          } else {
              return false;
          }
      }

    public function getOreGiornoByUserId($idUser) {
        $stmtSelect = $this->db->prepare("SELECT ore_giorno FROM info_hr WHERE user_id = ? LIMIT 1");
        $stmtSelect->bind_param('i', $idUser);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($oreGiorno);
        $stmtSelect->fetch();
        if($stmtSelect->num_rows > 0) {
            return $oreGiorno;
        } else {
            return -1;
        }
    }


    public function getGiorniSettimanaByUserId($idUser) {
        $stmtSelect = $this->db->prepare("SELECT giorni_settimana FROM info_hr WHERE user_id = ? LIMIT 1");
        $stmtSelect->bind_param('i', $idUser);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($giorniSettimana);
        $stmtSelect->fetch();
        if($stmtSelect->num_rows > 0) {
            return $giorniSettimana;
        } else {
            return -1;
        }
    }

    public function getAll() {
        $outputArray = array();
        $result = $this->db->query("SELECT info_hr.user_id, info_hr.ore_giorno, info_hr.scad_visita_medica,info_hr.scad_contratto, info_hr.giorni_settimana, info_hr.data_assunzione, anagrafica.nome, anagrafica.cognome, info_hr.active, GROUP_CONCAT(commesse.nome_commessa  separator ',') as commesse
FROM info_hr
INNER JOIN anagrafica ON info_hr.user_id = anagrafica.user_id
INNER JOIN user_commesse ON  info_hr.user_id = user_commesse.id_user AND user_commesse.id_role != 2
INNER JOIN commesse ON  user_commesse.id_commessa = commesse.id_commessa
GROUP BY info_hr.user_id, info_hr.ore_giorno, info_hr.scad_visita_medica,info_hr.scad_contratto, info_hr.giorni_settimana, info_hr.data_assunzione, anagrafica.nome, anagrafica.cognome, info_hr.active
ORDER BY anagrafica.cognome ASC");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getAllByIDUser($idUser) {
        $result = $this->db->query("SELECT info_hr.user_id, info_hr.ore_giorno, info_hr.scad_visita_medica,info_hr.scad_contratto, "
            . "info_hr.giorni_settimana, info_hr.data_assunzione, anagrafica.nome, anagrafica.cognome, info_hr.active "
            . "FROM info_hr INNER JOIN anagrafica ON info_hr.user_id = anagrafica.user_id WHERE info_hr.user_id = " . $idUser . " "
            . "ORDER BY anagrafica.cognome ASC LIMIT 1");
        return $result->fetch_assoc();
    }

    public function getAllPosition() {
        $outputArray = array();
        $result = $this->db->query("SELECT positionId, positionOggetto, positionDetail, positionLuogo FROM posizioni_aperte");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

	public function getAllCostiByMonth($mese,$anno) {
         $outputArray = array();
        $result = $this->db->query("SELECT anagrafica.user_id, info_hr_costi.costo_mensile, anagrafica.nome, anagrafica.cognome "
                . "FROM anagrafica LEFT JOIN info_hr_costi ON info_hr_costi.user_id = anagrafica.user_id AND info_hr_costi.mese=".$mese." AND info_hr_costi.anno=".$anno." ORDER BY anagrafica.cognome ASC");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

		public function getAllCostiAziendaliByMonth($mese,$anno) {
         	$outputArray = array();
			$array_commesse = array();

        	$result = $this->db->query("SELECT attivita.id_utente, commesse.nome_commessa, SUM(attivita.ore) as tot FROM anagrafica, attivita, commesse WHERE  anagrafica.user_id = attivita.id_utente AND commesse.id_commessa=attivita.id_commessa AND attivita.data LIKE '%".$mese.'/'.$anno."%' GROUP BY commesse.nome_commessa, anagrafica.user_id");

        while($row = $result->fetch_assoc()) {

			$result_costo = $this->db->query("SELECT info_hr_costi.costo_mensile FROM info_hr_costi WHERE info_hr_costi.mese = ".$mese." AND info_hr_costi.anno = ".$anno." AND info_hr_costi.user_id=".$row['id_utente']);

			$row_costo = $result_costo->fetch_assoc();
			if($row_costo['costo_mensile']=="") $row_costo['costo_mensile']=0;
			$id = $row['id_utente'];
			$costo_orario = $this->getCostoOrarioByMonth($mese,$anno,$id,$row_costo['costo_mensile']);

			$costo_tot = $costo_orario * $row['tot'];

					$array_commesse['ore'][$row['nome_commessa']] += $row['tot'];
 					$array_commesse['costo'][$row['nome_commessa']] += $costo_tot;

			}


		$result = $this->db->query("SELECT DISTINCT commesse.nome_commessa FROM commesse, attivita WHERE commesse.id_commessa=attivita.id_commessa ORDER BY commesse.nome_commessa ASC");

        while($row = $result->fetch_assoc()) {

		array_push($outputArray,$row['nome_commessa'],$array_commesse['ore'][$row['nome_commessa']],$array_commesse['costo'][$row['nome_commessa']]);
		}

        return $outputArray;
    }


	public function getAllDettaglioCostiAziendaliByMonth($mese,$anno,$id_commessa) {
         	$outputArray = array();
			$array_commesse = array();

        	$result = $this->db->query("SELECT attivita.id_utente, anagrafica.nome, anagrafica.cognome, commesse.nome_commessa, SUM(attivita.ore) as tot FROM anagrafica, attivita, commesse WHERE  anagrafica.user_id = attivita.id_utente AND commesse.id_commessa=attivita.id_commessa AND attivita.data LIKE '%".$mese.'/'.$anno."%' AND attivita.id_commessa=".$id_commessa." GROUP BY commesse.nome_commessa, anagrafica.user_id");

        while($row = $result->fetch_assoc()) {

			$result_costo = $this->db->query("SELECT info_hr_costi.costo_mensile FROM info_hr_costi WHERE info_hr_costi.mese = ".$mese." AND info_hr_costi.anno = ".$anno." AND info_hr_costi.user_id=".$row['id_utente']);

			$row_costo = $result_costo->fetch_assoc();
			if($row_costo['costo_mensile']=="") $row_costo['costo_mensile']=0;
			$id = $row['id_utente'];
			$costo_orario = $this->getCostoOrarioByMonth($mese,$anno,$id,$row_costo['costo_mensile']);

			$costo_tot = $costo_orario * $row['tot'];

				array_push($outputArray,$row['nome'],$row['cognome'],$row['tot'],$costo_tot);

			}



        return $outputArray;
    }


	public function getCostoOrarioByMonth($mese,$anno,$id,$costo_mensile) {


		$number = cal_days_in_month(CAL_GREGORIAN, $mese, $anno);
		$giorni = $this->getGiorniSettimanaByUserId($id);
		$ore = $this->getOreGiornoByUserId($id);

		if(strlen($mese)==1) $mese= "0".$mese;
		if(strlen($number)==1) $number = "0".$number;

		$d1 = "01-".$mese."-".$anno;
		$d2 = $number."-".$mese."-".$anno;
		$quanti_giorni = $this->giorni_lav($d1,$d2,$giorni);
		$costo_orario = $costo_mensile / ($quanti_giorni*$ore);

		//mail("marco.salmi89@gmail.com","prova",$ore."-".$quanti_giorni."-".$costo_mensile);

		return $costo_orario;
	}

	public function giorni_lav($d1, $d2, $giorni){
        $d_i=explode("-",$d1);//attento al formato delle date che hai tu
        $d_f=explode("-",$d2);
        $d_i_ts=mktime(0,0,0,$d_i[1],$d_i[0],$d_i[2]);//espressa in secondi TIME STAMP
        $d_f_ts=mktime(0,0,0,$d_f[1],$d_f[0],$d_f[2]);
        $g_ts= 24*60*60;//secondi in un giorno
        $quanti_giorni = cal_days_in_month(CAL_GREGORIAN, $d_i[1], $d_i[2]);
            while($d_i_ts <= $d_f_ts){
            //7=domenica, 6=sabato
            if((date("N",$d_i_ts)== '4' && $giorni<4) ||(date("N",$d_i_ts)== '5' && $giorni<5) || (date("N",$d_i_ts)== '6' && $giorni<6) || (date("N",$d_i_ts)== '7' && $giorni<7)){
                $quanti_giorni -= 1;// se trovo un sabato o una domenica decremento l'intervallo
            }
            $d_i_ts +=$g_ts;//incrementa l'iniziale di un giorno
        }
        return $quanti_giorni;
    }

    public function getMonthlyMedicalExpiries() {
        $expiriesArray = array();
        $currentMonthYear = date('m/Y');
        $activeUser = 1;
        $stmtSelect = $this->db->prepare("SELECT info_hr.user_id, anagrafica.nome, anagrafica.cognome, scad_visita_medica "
            . "FROM info_hr "
            . "INNER JOIN anagrafica ON info_hr.user_id = anagrafica.user_id "
            . "INNER JOIN login ON info_hr.user_id = login.user_idd "
            . "WHERE SUBSTRING(scad_visita_medica, 4, 7) = ? AND login.is_active = ? "
            . "ORDER BY scad_visita_medica");
        $stmtSelect->bind_param('si', $currentMonthYear, $activeUser);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($currentUserID, $currentUserNome,
            $currentUserCognome, $currentUserScadVisitaMedica);
        while ($stmtSelect->fetch()) {
            $expiriesArray[] = array(
                'id' => $currentUserID,
                'nome' => $currentUserNome,
                'cognome' => $currentUserCognome,
                'scadVisitaMedica' => $currentUserScadVisitaMedica
            );
        }
        return $expiriesArray;
    }

    public function getMonthlyContractExpiries() {
        $expiriesArray = array();
        $currentMonthYear = date('m/Y');
        $stmtSelect = $this->db->prepare("SELECT info_hr.user_id, anagrafica.nome, anagrafica.cognome, scad_contratto "
            . "FROM info_hr "
            . "INNER JOIN anagrafica ON info_hr.user_id = anagrafica.user_id "
            . "INNER JOIN login ON info_hr.user_id = login.user_idd "
            . "WHERE SUBSTRING(scad_contratto, 4, 7) = ? AND login.is_active = ? "
            . "ORDER BY scad_contratto");
        $stmtSelect->bind_param('s', $currentMonthYear);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($currentUserID, $currentUserNome,
            $currentUserCognome, $currentUserScadContratto);
        while ($stmtSelect->fetch()) {
            $expiriesArray[] = array(
                'id' => $currentUserID,
                'nome' => $currentUserNome,
                'cognome' => $currentUserCognome,
                'scadContratto' => $currentUserScadContratto
            );
        }
        return $expiriesArray;
    }

    public function getMonthlyDetachmentExpiries() {
        $expiriesArray = array();
        $currentMonthYear = date('m/Y');
        $stmtSelect = $this->db->prepare("SELECT info_hr.user_id, anagrafica.nome, anagrafica.cognome, sub, scad_distacco "
            . "FROM info_hr "
            . "INNER JOIN anagrafica ON info_hr.user_id = anagrafica.user_id "
            . "INNER JOIN login ON info_hr.user_id = login.user_idd "
            . "WHERE SUBSTRING(scad_distacco, 4, 7) = ? AND login.is_active = ? "
            . "ORDER BY scad_distacco");
        $stmtSelect->bind_param('s', $currentMonthYear);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($currentUserID, $currentUserNome,
            $currentUserCognome, $currentUserSub, $currentUserScadDistacco);
        while ($stmtSelect->fetch()) {
            $expiriesArray[] = array(
                'id' => $currentUserID,
                'nome' => $currentUserNome,
                'cognome' => $currentUserCognome,
                'sub' => $currentUserSub,
                'scadDistacco' => $currentUserScadDistacco
            );
        }
        return $expiriesArray;
    }

    public function getMonthlySubExpiries() {
        $expiriesArray = array();
        $currentMonthYear = date('m/Y');
        $stmtSelect = $this->db->prepare("SELECT info_hr.user_id, anagrafica.nome, anagrafica.cognome, scad_sub "
            . "FROM info_hr "
            . "INNER JOIN anagrafica ON info_hr.user_id = anagrafica.user_id "
            . "INNER JOIN login ON info_hr.user_id = login.user_idd "
            . "WHERE SUBSTRING(scad_sub, 4, 7) = ? AND login.is_active = ? "
            . "ORDER BY scad_distacco");
        $stmtSelect->bind_param('s', $currentMonthYear);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($currentUserID, $currentUserNome,
            $currentUserCognome, $currentUserScadSub);
        while ($stmtSelect->fetch()) {
            $expiriesArray[] = array(
                'id' => $currentUserID,
                'nome' => $currentUserNome,
                'cognome' => $currentUserCognome,
                'scadSub' => $currentUserScadSub
            );
        }
        return $expiriesArray;
    }

}




?>
