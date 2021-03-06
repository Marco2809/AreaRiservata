<?php

class Gruppo {
    
    private $db;
    private $id;
    private $idUser;
    private $idRole;
    
    public function __construct() {
        $this->id = null;
        $this->idUser = null;
        $this->idRole = null;
        
        $db = new Database();
        $this->db = $db->dbConnection();
    }
    
    public function createGroup($name) {
        $stmtInsert = $this->db->prepare("INSERT INTO gruppi (gruppo) VALUES (?)");

            $stmtInsert->bind_param("s", $name);
            $code = $stmtInsert->execute();
            if(!$code) {
                trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
            }
            $stmtInsert->close();
    }
    
    public function createUserGruppi($idUtente, $infoArray) {
        
        foreach($infoArray as $gruppo) {
            $info = explode('-', $gruppo);
            $stmtInsert = $this->db->prepare("INSERT INTO user_groups (id_gruppo,
                id_user, id_role) VALUES (?, ?, ?)");

            $stmtInsert->bind_param("iii", $info[0], $idUtente, $info[1]);
            $code = $stmtInsert->execute();
            if(!$code) {
                trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
            }
            $stmtInsert->close();
        }
    }
    
    public function deleteUserGruppi($idUtente, $idGruppo) {
        $stmtDelete = $this->db->prepare("DELETE FROM user_groups WHERE id_user = ? AND id_gruppo = ?");
        $stmtDelete->bind_param("ii", $idUtente, $idGruppo);
        if(!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error);
        }
    }
    
    public function getAll() {
        $outputArray = array();
        $result = $this->db->query("SELECT id_gruppo, gruppo FROM gruppi");
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }
    
    public function getUserByGroupId($idGroup) {
        $outputArray = array();
        $result = $this->db->query("SELECT anagrafica.user_id, anagrafica.nome, "
                . "anagrafica.cognome, anagrafica.data_nascita FROM user_groups "
                . "INNER JOIN anagrafica WHERE user_groups.id_user = anagrafica.user_id "
                . "AND user_groups.id_gruppo = " . $idGroup);
        
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }
    
}
