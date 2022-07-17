<?php
session_start();
require_once('dbconn.php');
require_once('./assets/php/functions.php');

class Project
{
    public $db;
    //variabili tabella login
    var $id;
    var $titolo;
    var $descrizione;
    var $data_inizio;
    var $data_fine;
    var $id_utente;


    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }



    public function createProject($titolo, $descrizione,$id_utente,$data_inizio=NULL,$data_fine=NULL) {

        $stmtInsert = $this->db->prepare("INSERT INTO progetti (id_utente,titolo,
                descrizione, data_inizio, data_fine)
                VALUES (?, ?, ?, ?, ?)");

        $stmtInsert->bind_param("issss", $id_utente, $titolo, $descrizione,
                $data_inizio, $data_fine);
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

    public function editProject($id, $name, $surname, $birthDate, $cf, $profile, $email) {
        consoleLog($id . ' | ' . $name . ' | ' . $surname . ' | ' . $birthDate
                . ' | ' . $cf . ' | ' . $profile . ' | ' . $email);
        $stmtUser = $this->db->prepare("UPDATE login SET email = ?, is_admin = ?
            WHERE user_idd = ?");
        $stmtUser->bind_param('sii', $email, $profile, $id);
        $stmtUser->execute();

        $stmtAnagraphic = $this->db->prepare("UPDATE anagrafica SET nome = ?, cognome = ?,
            data_nascita = ?, codice_fiscale = ? WHERE user_id = ?");
        $stmtAnagraphic->bind_param('ssssi', $name, $surname, $birthDate, $cf, $id);
        $stmtAnagraphic->execute();

        if($stmtUser && $stmtAnagraphic) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllProjects() {
        $outputArray = array();
        $result = $this->db->query("SELECT id, id_utente, titolo, descrizione, data_inizio, data_fine FROM progetti ORDER BY id");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }
}

?>
