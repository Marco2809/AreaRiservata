<?php

class UserFeriePermessi {

    private $idUser;
    private $giorniFerie;
    private $orePermesso;
    private $livelloStress;
    private $db;

    public function __construct() {
        $this->idUser = null;
        $this->giorniFerie = null;
        $this->orePermesso = null;
        $this->livelloStress = null;
        $this->db = (new Database())->dbConnection();
    }

    // Setters and getters

    public function getIDUser() {
        return $this->idUser;
    }

    public function getGiorniFerie() {
        return $this->giorniFerie;
    }

    public function getOrePermesso() {
        return $this->orePermesso;
    }

    public function getLivelloStress() {
        return $this->livelloStress;
    }

    public function getDB() {
        return $this->db;
    }

    public function setIDUser($idUser) {
        $this->idUser = $idUser;
    }

    public function setGiorniFerie($giorniF) {
        $this->giorniFerie = $giorniF;
    }

    public function setOrePermesso($oreP) {
        $this->orePermesso = $oreP;
    }

    public function setLivelloStress($stress) {
        $this->livelloStress = $stress;
    }

    public function setDB($db) {
        $this->db = $db;
    }

    // Functions

    public function setFeriePermessiStartByIdUser($idUser, $giorniFerie, $orePermesso) {

        $livelloStress = 0;
        $giorniFerie = str_replace(",", ".", $giorniFerie);
        $orePermesso = str_replace(",", ".", $orePermesso);
        $stmtInsert = $this->db->prepare("INSERT INTO user_ferie_permessi (id_user,
                giorni_ferie, ore_permesso, livello_stress) VALUES (?, ?, ?, ?)");

        $stmtInsert->bind_param('iddd', $idUser, $giorniFerie, $orePermesso,
                $livelloStress);
        if(!$stmtInsert->execute()) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtInsert->close();

    }

    public function getGiorniFerieByIdUser($idUser) {
        $stmtSelect = $this->db->prepare("SELECT giorni_ferie FROM user_ferie_permessi "
            . "WHERE id_user = ? LIMIT 1");
        $stmtSelect->bind_param('i', $idUser);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($giorniFerieTot);
        $stmtSelect->fetch();
        if($stmtSelect->num_rows > 0) {
            return $giorniFerieTot;
        } else {
            return '-1';
        }
    }

    public function setGiorniFerieByIdUser($idUser, $giorniFerie) {
        consoleLog("Entrato");
        $stmtUpdate = $this->db->prepare("UPDATE user_ferie_permessi 
            SET giorni_ferie = ? WHERE id_user = ?");
        $stmtUpdate->bind_param('di', $giorniFerie, $idUser);
        $stmtUpdate->execute();
    }

    public function getOrePermessoByIdUser($idUser) {
        $stmtSelect = $this->db->prepare("SELECT ore_permesso FROM user_ferie_permessi "
            . "WHERE id_user = ? LIMIT 1");
        $stmtSelect->bind_param('i', $idUser);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($orePermessoTot);
        $stmtSelect->fetch();
        if($stmtSelect->num_rows > 0) {
            return $orePermessoTot;
        } else {
            return '-1';
        }
    }

    public function setOrePermessoByIdUser($idUser, $orePermesso) {
        $stmtUpdate = $this->db->prepare("UPDATE user_ferie_permessi 
            SET ore_permesso = ? WHERE id_user = ?");
        $stmtUpdate->bind_param('di', $orePermesso, $idUser);
        $stmtUpdate->execute();
    }

    public function getLivelloStressByIdUser($idUser) {
        $stmtSelect = $this->db->prepare("SELECT livello_stress FROM user_ferie_permessi "
            . "WHERE id_user = ? LIMIT 1");
        $stmtSelect->bind_param('i', $idUser);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($livelloStressTot);
        $stmtSelect->fetch();
        if($stmtSelect->num_rows > 0) {
            return $livelloStressTot;
        } else {
            return '0';
        }
    }

    public function getAll() {
        $outputArray = array();
        $sql = "SELECT id_user, giorni_ferie, ore_permesso, ore_giorno, giorni_settimana, data_assunzione
                FROM user_ferie_permessi 
                INNER JOIN info_hr ON user_ferie_permessi.id_user = info_hr.user_id";
        $result = $this->db->query($sql);
        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

}

?>
