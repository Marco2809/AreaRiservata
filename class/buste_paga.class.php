<?php
session_start();
require_once('dbconn.php');

class BustePaga
{
    public $db;
    private $idBustePaga;
    private $idUser;
    private $mese;
    private $anno;
    private $stato;
    private $token;

    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }
    
    public function getBPNotReadByUserId($idUser) {
        $outputCount = 0;
        $notReadState = 0;
        $stmtSelect = $this->db->prepare("SELECT COUNT(id_buste) "
            . "FROM buste_paga WHERE id_user = ? AND stato = ?");
        $stmtSelect->bind_param('ii', $idUser, $notReadState);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($outputCount);
        $stmtSelect->fetch();
        return $outputCount;
    }

    public function getAllByUserIdAndByYear($userId, $year) {
        $outputArray = array();
        $stmtSelect = $this->db->prepare("SELECT mese, stato, token, nome, cognome, id_buste FROM buste_paga 
               INNER JOIN anagrafica ON id_user = user_id WHERE id_user = ? AND anno = ?");
        $stmtSelect->bind_param('is', $userId, $year);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($month, $state, $token, $firstname, $surname, $idBusta);
        while($stmtSelect->fetch()) {
            $currentArray = array($month, $state, $token, $firstname, $surname, $idBusta);
            array_push($outputArray, $currentArray);
        }
        return $outputArray;
    }

    public function addBP($idUser, $month, $year, $token) {

        $idDuplicate = null;
        $stmtSelect = $this->db->prepare("SELECT id_buste FROM buste_paga WHERE
            id_user = ? AND mese = ? AND anno = ? LIMIT 1");
        $stmtSelect->bind_param('iss', $idUser, $month, $year);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($idDuplicate);
        $stmtSelect->fetch();
        
        if($stmtSelect->num_rows == 0) {
            $stmtInsert = $this->db->prepare("INSERT INTO buste_paga (id_user,
                    mese, anno, token) VALUES (?, ?, ?, ?)");
            
            $stmtInsert->bind_param('isss', $idUser, $month, $year, $token);
            if(!$stmtInsert->execute()) {
                trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
            }
            $stmtInsert->close();
            
            $bpIdCreated = null;
            $stmt = $this->db->prepare("SELECT LAST_INSERT_ID()");
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($bpIdCreated);
            $stmt->fetch();
            return $bpIdCreated;
        } else {
            $statoNew = 0;
            $stmtUpdate = $this->db->prepare("UPDATE buste_paga SET stato = ?,  
                    token = ? WHERE id_buste = ?");
            $stmtUpdate->bind_param('ssi', $statoNew, $token, $idDuplicate);
            $stmtUpdate->execute();
            return $idDuplicate;
        }
    }

    function makeViewed($idBP) {
         $viewedState = 1;
         $stmtUpdate = $this->db->prepare("UPDATE buste_paga SET stato = ?  
                 WHERE id_buste = ?");
         $stmtUpdate->bind_param('ii', $viewedState, $idBP);
         $code = $stmtUpdate->execute();
         return $code;
    }

}

?>