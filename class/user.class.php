<?php
session_start();
require_once('dbconn.php');
require_once('./assets/php/functions.php');

class User
{
    public $db;
    //variabili tabella login
    private $esperienza;
    var $id_user;
    var $username;
    var $email;
    var $password;
    var $recupero;
    var $is_admin;
    var $is_active;
    var $advise;
    var $primo_accesso;


    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function login($username,$password){
         $this->db = new mysqli("localhost", "root", "", "area_riservata");

         if (empty($_POST['username']) || empty($_POST['password'])) {
		$error = "Inserire User e/o Password!";
	}
	else
	{
		$username = stripslashes($username);
		$password = stripslashes($password);
		//$username = mysql_real_escape_string($username);
		//$password = mysql_real_escape_string($password);

		$sql = "SELECT username,password,user_idd,is_admin
		FROM login
		WHERE username = '$username' AND  password = '$password'";

		//echo $sql;

		//eseguo la query
		$result = $this->db->query($sql);
		if(! $result )
		{
			die('Non riesco a prendere dati: ' . mysql_error());
		}

		if (isset($result))
		{
			$rows = $result->num_rows;

			if ($rows == 1) {

				//Trovato! inizio la sessione
				session_start();

				$row = $result->fetch_assoc();

				//Arrivato qui.
				$_SESSION['username'] = $username;
				$_SESSION['user_idd'] = $row['user_idd'];
				$_SESSION['logged'] = 1;
				if($row['is_admin']==1) $_SESSION['is_admin'] = 1;
                                else if($row['is_admin']==2) $_SESSION['is_admin'] = 2;
                                else $_SESSION['is_admin'] = 0;

                                 $query="SELECT nome,cognome from anagrafica where user_id=".$_SESSION['user_idd'];
                $result = $this->db->query($query);
                if($result->num_rows>0){

                     $row =$result->fetch_assoc();
                     $_SESSION['name'] = $row['nome'];
                     $_SESSION['surname'] = $row['cognome'];
                }

                                $query="SELECT * from commesse where id_responsabile=".$_SESSION['user_idd'];
                $result = $this->db->query($query);

                if($result->num_rows>0){

                    $_SESSION['responsabile']=1;

                } else $_SESSION['responsabile'] = 0;

                    $controllo_anag= $this->db->query("SELECT user_id FROM anagrafica WHERE user_id ="."'".$_SESSION['user_idd']."'", $conn->db);
                    $row1 =$controllo_anag->fetch_assoc();

		if($controllo_anag->num_rows >0) {
				if($_SESSION['is_admin']==1) header("location: amministrazione.php");

				else header("location: dashboard.php"); // Redirecting To Other Page
		} else header("location: anagrafica.php?id='".$_SESSION['user_idd']."'");
                    header("location: anagrafica.php?id=".$_SESSION['user_idd']."");
                }
                    else {
                        $error = "User o Password non valide!";
                    }
		}

	}
         return $error;
    }

    public function logout(){
        $isSupplier = $_SESSION['is_supplier'];
        session_destroy();
        if(!$isSupplier) {
            header("location: login.php");
        } else {
            header("location: login-albo-fornitori.php");
        }
    }

  public function delUser($id){
       $delete = "DELETE FROM login WHERE user_idd =". $id;
$result = $this->db->query($delete);
        return $result;

 $delete = "DELETE FROM anagrafica WHERE user_id =". $id;
$result = $this->db->query($delete);
        return $result;

    }

    public function createUser($email, $profilo) {
        $defaultPassword = '6f6bfd120fc9fb50ca47ea0e83bc27b12d2de5a62a6bd7702b8bdf713594453a4a6889cebdd0ffe55c6b5ae983dde560bb6c286a0cfcffc18616e853eb8f6102';
        $defaultIsActive = 1;
        $defaultRecupero = '';
        $defaultAvviso = 0;

        $stmtInsert = $this->db->prepare("INSERT INTO login (username,
                password, email, is_admin, is_active, recupero, avviso)
                VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmtInsert->bind_param("sssiisi", $email, $defaultPassword, $email,
                $profilo, $defaultIsActive, $defaultRecupero, $defaultAvviso);
        if(!$stmtInsert->execute()) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtInsert->close();

        $userIdCreated = null;
        $stmt = $this->db->prepare("SELECT LAST_INSERT_ID()");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userIdCreated);
        $stmt->fetch();
        return $userIdCreated;
    }

    public function editEmployee($id, $name, $surname, $birthDate, $cf, $profile, $email, $luogo, $residenza, $indirizzo_residenza, $domicilio, $indirizzo_domicilio, $ore_giorno, $qualifica, $mansione, $giorni_settimana, $scad_visita_medica, $scad_contratto, $scad_distacco, $data_assunzione, $du, $email_personale, $scad_cf, $doc, $scad_doc, $livello, $corso_81, $legge_104, $cat_protetta, $articolo, $percentuale) {
        $stmtUser = $this->db->prepare("UPDATE login SET email = ?, is_admin = ?
            WHERE user_idd = ?");
        $stmtUser->bind_param('sii', $email, $profile, $id);
        $stmtUser->execute();

        $stmtAnagraphic = $this->db->prepare("UPDATE anagrafica SET nome = ?, cognome = ?,
            luogo_nascita = ?, data_nascita = ?,citta_residenza = ?, indirizzo_residenza = ?, citta_domicilio = ?, indirizzo_domicilio = ?, codice_fiscale = ? WHERE user_id = ?");
        $stmtAnagraphic->bind_param('sssssssssi', $name, $surname, $luogo, $birthDate, $residenza, $indirizzo_residenza, $domicilio, $indirizzo_domicilio, $cf, $id);
        $stmtAnagraphic->execute();

        $stmtInfo1 = $this->db->prepare("SELECT info_id FROM info_hr WHERE user_id = ?");
        $stmtInfo1->bind_param('i',$id);
        $stmtInfo1->execute();
        $stmtInfo1->store_result();

        if($stmtInfo1->num_rows >0){
          $stmtInfo = $this->db->prepare("UPDATE info_hr SET ore_giorno = ?, qualifica = ?,
              mansione = ?, giorni_settimana = ?, scad_visita_medica = ?, scad_contratto = ?, scad_distacco = ?, data_assunzione = ?, d_u = ?, email_personale = ?, scad_cf = ?, doc = ?, scad_doc = ?, livello = ?, corso_81 = ?, legge_104 = ?, cat_protetta = ?, articolo = ?, percentuale = ? WHERE user_id = ?");
          $stmtInfo->bind_param('sssssssssssssssisisi', $ore_giorno, $qualifica, $mansione, $giorni_settimana, $scad_visita_medica, $scad_contratto, $scad_distacco, $data_assunzione, $du, $email_personale, $scad_cf, $doc, $scad_doc, $livello, $corso_81, $legge_104, $cat_protetta, $articolo, $percentuale, $id);
          if(!$stmtInfo->execute()) {
              trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
          }
        } else{

          $stmtInfo = $this->db->prepare("INSERT INTO info_hr (user_id, ore_giorno, qualifica, mansione, giorni_settimana, scad_visita_medica, scad_contratto, scad_distacco, data_assunzione, d_u, email_personale, scad_cf, doc, scad_doc, livello, corso_81, legge_104, cat_protetta, articolo, percentuale) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
          $stmtInfo->bind_param('isssssssssssssssisis', $id, $ore_giorno, $qualifica, $mansione, $giorni_settimana, $scad_visita_medica, $scad_contratto, $scad_distacco, $data_assunzione, $du, $email_personale, $scad_cf, $doc, $scad_doc, $livello, $corso_81, $legge_104, $cat_protetta, $articolo, $percentuale);
          if(!$stmtInfo->execute()) {
            echo "Errore! " . $this->db->error, E_USER_WARNING;
          }
        }

        if($stmtUser && $stmtAnagraphic && $stmtInfo) {
            return true;
        } else {
            return false;
        }
    }

    public function getResponsabili() {
        $outputArray = array();
        $result = $this->db->query("SELECT login.user_idd, anagrafica.nome, anagrafica.cognome "
                . "FROM login INNER JOIN anagrafica WHERE anagrafica.user_id = login.user_idd "
                . "AND (login.is_admin = 1 OR login.is_admin = 2)");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getListaCert($user_id) {
        $outputArray = array();
        $result = $this->db->query("SELECT nome, titolo, id_certificazione FROM files_certificazioni WHERE id_user = ".$user_id);
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getFeriePermessi($idUser) {
        $outputArray = array();
        $result = $this->db->query("SELECT giorni_ferie, ore_permesso "
                . "FROM user_ferie_permessi WHERE id_user = '".$idUser."' ");
        while($row = $result->fetch_assoc()) {
            $outputArray[0] = $row['giorni_ferie'];
            $outputArray[1] = $row['ore_permesso'];
        }
        return $outputArray;
    }

    public function getResponsabiliByCommessa($idCommessa) {
        $outputArray = array();
        $result = $this->db->query("SELECT user_commesse.id_user, anagrafica.nome, anagrafica.cognome "
                . "FROM user_commesse INNER JOIN anagrafica ON user_commesse.id_user = anagrafica.user_id "
                . "WHERE user_commesse.id_commessa = " . $idCommessa . " AND user_commesse.id_role = 2");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getCommesseById($idUser) {
        $outputArray = array();
        $result = $this->db->query("SELECT user_commesse.id_commessa, commesse.nome_commessa, anagrafica.cognome "
                . "FROM user_commesse INNER JOIN commesse ON user_commesse.id_commessa = commesse.id_commessa INNER JOIN anagrafica ON user_commesse.id_user = anagrafica.user_id "
                . "WHERE user_commesse.id_role !=2 AND user_commesse.id_user = " . $idUser);
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getUsersMailCommessa($idCommessa) {
        $respArray = array();
        $resultResp = $this->db->query("SELECT email FROM user_commesse INNER JOIN login ON id_user = user_idd "
                . "WHERE id_role=4 AND id_commessa = " . $idCommessa);
        while($row = $resultResp->fetch_assoc()) {
            array_push($respArray, $row['email']);
        }
        return $outputArray;
    }

    public function getUsersExceptRespCommessa($idCommessa) {
        $respArray = array();
        $resultResp = $this->db->query("SELECT id_user FROM user_commesse "
                . "WHERE id_commessa = " . $idCommessa . " AND id_role = 2");
        while($row = $resultResp->fetch_assoc()) {
            array_push($respArray, $row['id_user']);
        }

        $outputArray = array();
        $result = $this->db->query("SELECT user_id, nome, cognome FROM anagrafica "
                . "WHERE user_id NOT IN ( '" . implode($respArray, "', '") . "' ) "
                . "ORDER BY cognome ASC");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getAllExceptGroup($idGroup) {
        $outputArray = array();
        $result = $this->db->query("SELECT user_id, nome, cognome, data_nascita
                FROM anagrafica WHERE user_id NOT IN (
                    SELECT id_user FROM user_groups WHERE id_gruppo = " . $idGroup .
                ") ORDER BY cognome;");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getAllExceptModule($idModule) {
        $outputArray = array();
        $result = $this->db->query("SELECT user_id, nome, cognome, data_nascita
                FROM anagrafica WHERE user_id NOT IN (
                    SELECT id_user FROM user_moduli WHERE id_modulo = " . $idModule .
                ");");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getListaCommesse($userId){
      $lista = "";
      $result = $this->db->query("SELECT nome_commessa
              FROM commesse INNER JOIN user_commesse ON commesse.id_commessa = user_commesse.id_commessa WHERE id_user = '".$userId."' AND user_commesse.id_role = 4");
      while($row = $result->fetch_assoc()) {
          $lista.= $row['nome_commessa'].",";
      }
      return substr($lista,0,-1);
    }

    public function getListaModuli($userId){
      $lista = "";
      $result = $this->db->query("SELECT id_modulo
              FROM user_moduli WHERE id_user = '".$userId."'");
      while($row = $result->fetch_assoc()) {
          $lista.= $row['id_modulo'].",";
      }
      return substr($lista,0,-1);
    }

    public function getAllExceptJob($idJob) {
        $outputArray = array();
        $result = $this->db->query("SELECT user_id, nome, cognome, data_nascita
                FROM anagrafica WHERE user_id NOT IN (
                    SELECT id_user FROM user_commesse WHERE id_commessa = " . $idJob .
                ") ORDER BY cognome;");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getAllExcept104() {
        $outputArray = array();
        $result = $this->db->query("SELECT user_id, nome, cognome, data_nascita
                FROM anagrafica WHERE user_id NOT IN (
                    SELECT id_user FROM user_permesso_104
                );");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getUsersPermesso104() {
        $outputArray = array();
        $result = $this->db->query("SELECT user_id, nome, cognome, data_nascita "
                . "FROM anagrafica INNER JOIN user_permesso_104 ON anagrafica.user_id = user_permesso_104.id_user");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function addUserToPermesso104($userId) {
        $stmtInsert = $this->db->prepare("INSERT INTO user_permesso_104 (id_user) VALUES (?)");
        $stmtInsert->bind_param("i", $userId);
        if(!$stmtInsert->execute()) {
            trigger_error("Errore! " . $this->db->error);
        }
        $stmtInsert->close();
    }

    public function removeUserFromPermesso104($userId) {
        $stmtDelete = $this->db->prepare("DELETE FROM user_permesso_104 WHERE id_user = ?");
        $stmtDelete->bind_param("i", $userId);
        if(!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error);
        }
    }

	public function getUserIdBySurname($surname) {
        $idFound = "";
        $stmt = $this->db->prepare("SELECT user_id FROM anagrafica WHERE cognome = ?");
        $stmt->bind_param('s', $surname);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($idFound);
        $stmt->fetch();
        return $idFound;
    }

    public function getUserById($id) {
        $user = array();
        $stmt = $this->db->prepare("SELECT nome, cognome, luogo_nascita, data_nascita,
            nazionalita, phone, citta_residenza, indirizzo_residenza, codice_fiscale, img_name
            FROM anagrafica WHERE user_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user['nome'], $user['cognome'], $user['luogo_nascita'],
                $user['data_nascita'], $user['nazionalita'], $user['phone'],
                $user['citta_residenza'], $user['indirizzo_residenza'],
                $user['codice_fiscale'], $user['img_name']);
        $stmt->fetch();
        return $user;
    }

    public function getUserLoginById($id) {
        $user = array();
        $stmt = $this->db->prepare("SELECT user_idd, username, is_admin, primo_accesso
            FROM login WHERE user_idd = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user['user_idd'], $user['username'], $user['is_admin'],
            $user['primo_accesso']);
        $stmt->fetch();
        return $user;
    }

    public function updateFirstUserAccess($userId) {
        $newFirstUserAccess = 1;
        $stmtUser = $this->db->prepare("UPDATE login SET primo_accesso = ? WHERE user_idd = ?");
        $stmtUser->bind_param('ii', $newFirstUserAccess, $userId);
        $stmtUser->execute();
    }

    public function getEmailByUserId($userId) {
        $stmt = $this->db->prepare("SELECT email FROM login WHERE user_idd = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($email);
        $stmt->fetch();
        return $email;
    }

    public function getAllEmail() {
        $outputArray = array();
        $emailToRemove = 'giuseppe.tarsitano@service-tech.org';
        $emailToRemove2 = 'HR@service-tech.org';
        $stmt = $this->db->prepare("SELECT email FROM login WHERE email != ? AND email != ? AND email NOT IN
            (SELECT login.email FROM login INNER JOIN user_supplier ON login.user_idd = user_supplier.user_id
            INNER JOIN suppliers ON user_supplier.supplier_id = suppliers.id) ORDER BY email");
        $stmt->bind_param('ss', $emailToRemove, $emailToRemove2);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($email);
        while($stmt->fetch()) {
            array_push($outputArray, $email);
        }
        return $outputArray;
    }

    public function getAllUsersAndAnagraphics() {
        $outputArray = array();
        $query = "SELECT anagrafica.user_id, anagrafica.nome, anagrafica.cognome, "
                . "anagrafica.data_nascita, anagrafica.codice_fiscale, anagrafica.citta_residenza as residenza, anagrafica.luogo_nascita as luogo, anagrafica.nazionalita, anagrafica.phone as telefono, anagrafica.indirizzo_residenza, anagrafica.citta_domicilio as domicilio, anagrafica.indirizzo_domicilio, login.email, info_hr.ore_giorno, info_hr.giorni_settimana, info_hr.qualifica, info_hr.mansione, info_hr.scad_visita_medica, info_hr.scad_contratto, info_hr.scad_distacco, info_hr.data_assunzione, info_hr.d_u, info_hr.email_personale, info_hr.scad_cf, info_hr.doc, info_hr.scad_doc, info_hr.livello, info_hr.corso_81, info_hr.sub, "
                . "login.is_admin FROM anagrafica INNER JOIN login ON anagrafica.user_id = login.user_idd LEFT JOIN info_hr ON anagrafica.user_id = info_hr.user_id LEFT JOIN  ORDER BY anagrafica.cognome ASC";
        $result = $this->db->query($query);
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getActiveUsersAndAnagraphics() {
        $outputArray = array();
        $query = "SELECT DISTINCT anagrafica.user_id, anagrafica.nome, anagrafica.cognome, "
                . "anagrafica.data_nascita, anagrafica.codice_fiscale, anagrafica.citta_residenza as residenza, anagrafica.luogo_nascita as luogo, anagrafica.nazionalita, anagrafica.phone as telefono, anagrafica.indirizzo_residenza, anagrafica.citta_domicilio as domicilio, anagrafica.indirizzo_domicilio,anagrafica.codice_fiscale as cf, login.email, info_hr.ore_giorno, info_hr.giorni_settimana, info_hr.qualifica, info_hr.mansione, info_hr.scad_visita_medica, info_hr.legge_104, info_hr.cat_protetta, info_hr.articolo, info_hr.percentuale, info_hr.scad_contratto, info_hr.scad_distacco, info_hr.data_assunzione, info_hr.d_u, info_hr.email_personale, info_hr.scad_cf, info_hr.doc, info_hr.scad_doc, info_hr.livello, info_hr.corso_81, beni_hr.tel, beni_hr.tel_text, beni_hr.pc, beni_hr.pc_text, beni_hr.car, beni_hr.car_text, beni_hr.varie, beni_hr.varie_text, "
                . "login.is_admin FROM anagrafica INNER JOIN login ON anagrafica.user_id = login.user_idd LEFT JOIN info_hr ON anagrafica.user_id = info_hr.user_id LEFT JOIN beni_hr ON anagrafica.user_id = beni_hr.id_user WHERE login.is_active = 1 ORDER BY anagrafica.cognome ASC";
        $result = $this->db->query($query);
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getInactiveUsersAndAnagraphics() {
        $outputArray = array();
        $query = "SELECT anagrafica.user_id, anagrafica.nome, anagrafica.cognome, "
                . "anagrafica.data_nascita, anagrafica.codice_fiscale, anagrafica.citta_residenza as residenza, anagrafica.luogo_nascita as luogo, anagrafica.nazionalita, anagrafica.phone as telefono, anagrafica.indirizzo_residenza, anagrafica.citta_domicilio as domicilio, anagrafica.indirizzo_domicilio, login.email, info_hr.ore_giorno, info_hr.giorni_settimana, info_hr.qualifica, info_hr.mansione, info_hr.scad_visita_medica, info_hr.scad_contratto, info_hr.scad_distacco, info_hr.data_assunzione, info_hr.d_u, info_hr.email_personale, info_hr.scad_cf, info_hr.doc, info_hr.scad_doc, info_hr.livello, info_hr.corso_81, "
                . "login.is_admin FROM anagrafica INNER JOIN login ON anagrafica.user_id = login.user_idd LEFT JOIN info_hr ON anagrafica.user_id = info_hr.user_id WHERE login.is_active = 0 ORDER BY anagrafica.cognome ASC";
        $result = $this->db->query($query);
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getAll() {
        $outputArray = array();
        $result = $this->db->query("SELECT user_id, nome, cognome, data_nascita FROM anagrafica ORDER BY cognome");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getAllActive() {
        $outputArray = array();
        $result = $this->db->query(
          "SELECT user_id, nome, cognome, data_nascita
          FROM anagrafica
          INNER JOIN login ON anagrafica.user_id = login.user_idd
          WHERE login.is_active = 1
          ORDER BY cognome");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getAllForMessageWrite() {
        $outputArray = array();
        $result = $this->db->query(
          "SELECT user_id, nome, cognome, data_nascita
          FROM anagrafica
          INNER JOIN login ON anagrafica.user_id = login.user_idd
          WHERE login.is_active = 1
          ORDER BY cognome"
        );
        while($row = $result->fetch_assoc()) {
            $outputArray[$row['user_id']] = $row['nome'] . ' ' . $row['cognome'];
        }
        return $outputArray;
    }
}

?>
