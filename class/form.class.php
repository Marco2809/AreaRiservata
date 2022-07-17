<?php

session_start();
require_once('dbconn.php');

class Form {

    public $db;
    public $idModulo;
    public $titolo;
    public $file;

    public function __construct() {
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    // Setters and getters

    public function setIDModulo($idModulo) {
        $this->idModulo = $idModulo;
    }

    public function setTitolo($titolo) {
        $this->titolo = $titolo;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function getIDModulo() {
        return $this->idModulo;
    }

    public function getTitolo() {
        return $this->titolo;
    }

    public function getFile() {
        return $this->file;
    }

    // Functions

    public function getAll() {
        $formsArray = array();
        $stmtSelect = $this->db->prepare("SELECT id_modulo, titolo, file FROM modulistica");

        if(!$stmtSelect) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($idResult, $titleResult, $fileResult);

        while($stmtSelect->fetch()) {
            $currentForm = array(
                'idModulo' => $idResult,
                'titolo' => $titleResult,
                'file' => $fileResult
            );
            array_push($formsArray, $currentForm);
        }
        return $formsArray;
    }

    public function createForm() {
        $stmtInsert = $this->db->prepare("INSERT INTO modulistica (titolo, file) VALUES (?, ?)");
        $stmtInsert->bind_param("ss", $this->titolo, $this->file);
        if (!$stmtInsert->execute()) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtInsert->close();
    }

    public function deleteForm() {
        $stmtDelete = $this->db->prepare("DELETE FROM modulistica WHERE id_modulo = ?");
        $stmtDelete->bind_param("i", $this->idModulo);
        if (!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtDelete->close();
    }

}
