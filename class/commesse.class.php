<?php

class Commessa {

    private $id;
    private $db;
    private $idResp;
    private $commessa;
    private $cliente;
    private $nomeCommessa;

    private $idUser;
    private $idRole;

    public function __construct() {
        $this->id = null;
        $this->idUser = null;
        $this->idRole = null;
        $this->idResp = null;
        $this->commessa = null;
        $this->cliente = null;
        $this->nomeCommessa = null;

        $db = new Database();
        $this->db = $db->dbConnection();
    }

public function insertCommessaFromTemp($idUser,$idAdmin) {

    $sql = "SELECT * FROM commesse_temp WHERE id_session=" . $idAdmin;
    $result = $this->db->query($sql);

    if($result) {
        while($objp = $result->fetch_object()) {
            $stmtInsert = $this->db->prepare("INSERT INTO user_commesse (id_commessa, id_user, id_role) VALUES (?, ?, ?)");
            $stmtInsert->bind_param("iii", $objp->id_commessa, $idUser, $objp->id_role);
            $code = $stmtInsert->execute();
            if(!$code) {
                trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
            }
            $stmtInsert->close();

            $idModulo = 2;
            $stmtSelect = $this->db->prepare("SELECT row_id FROM user_moduli "
                    . "WHERE id_user = ? AND id_modulo = ? LIMIT 1");
            $stmtSelect->bind_param('ii', $idUser, $idModulo);
            $stmtSelect->execute();
            $stmtSelect->store_result();
            $stmtSelect->bind_result($rowId);
            if($stmtSelect->num_rows == 0) {
                $stmtModuli = $this->db->prepare("INSERT INTO user_moduli (id_user,
                    id_modulo) VALUES (?, ?)");
                $stmtModuli->bind_param("ii", $idUser, $idModulo);
                $stmtModuli->execute();
                $stmtModuli->close();
            }
        }
    }

    $delete = "DELETE FROM commesse_temp WHERE id_user =" . $idAdmin;
    $result = $this->db->query($delete);
    return $result;
}

public function delCommessaTemp($idCommessa){

$delete = "DELETE FROM commesse_temp WHERE id_temp =". $idCommessa;
$result = $this->db->query($delete);
        return $result;


}

public function addTempCommessa($idCommessa,$idRole, $idSession) {
        $stmtInsert = $this->db->prepare("INSERT INTO commesse_temp (id_commessa,
            id_role,id_session) VALUES (?, ?, ?)");
        $stmtInsert->bind_param("iii", $idCommessa,$idRole, $idSession);
        $code = $stmtInsert->execute();
        if(!$code) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtInsert->close();
        return $code;
    }

    public function addCommessa($idResp, $commessa, $cliente, $nomeCommessa) {
        $stmtInsert = $this->db->prepare("INSERT INTO commesse (commessa, "
                . "cliente, nome_commessa) VALUES (?, ?, ?)");
        $stmtInsert->bind_param("sss", $commessa, $cliente, $nomeCommessa);
        $code = $stmtInsert->execute();
        if(!$code) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtInsert->close();

        $sql = "SELECT LAST_INSERT_ID()";
        $resultSelect = $this->db->query($sql);
        $row = $resultSelect->fetch_assoc();

        $role = '2';
        $stmtInsertResp = $this->db->prepare("INSERT INTO user_commesse (id_commessa, "
                . "id_user, id_role) VALUES (?, ?, ?)");
        $stmtInsertResp->bind_param("iii", $row['LAST_INSERT_ID()'], $idResp, $role);
        $codeResp = $stmtInsertResp->execute();
        if(!$codeResp) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        } else {
            $stmtInsertResp->close();

            $idModulo = 2;
            $stmtSelect = $this->db->prepare("SELECT row_id FROM user_moduli "
                    . "WHERE id_user = ? AND id_modulo = ? LIMIT 1");
            $stmtSelect->bind_param('ii', $idResp, $idModulo);
            $stmtSelect->execute();
            $stmtSelect->store_result();
            $stmtSelect->bind_result($rowId);
            if($stmtSelect->num_rows == 0) {
                $stmtModuli = $this->db->prepare("INSERT INTO user_moduli (id_user,
                    id_modulo) VALUES (?, ?)");
                $stmtModuli->bind_param("ii", $idResp, $idModulo);
                $stmtModuli->execute();
                $stmtModuli->close();
            }
            return $code;
        }
    }

    public function updateCommessa($listaResponsabiliOld, $listaResponsabili, $commessa,
            $cliente, $nomeCommessa, $idCommessa) {

        $arrayResponsabiliOld = explode("-", $listaResponsabiliOld);
        $arrayResponsabili = explode("-", $listaResponsabili);
        $arrayOldResp = array();
        $arrayNewResp = array();

        foreach($arrayResponsabiliOld as $keyOld=>$oldResp) {
            array_push($arrayOldResp, $oldResp);
            foreach($arrayResponsabili as $currentResp) {
                if($oldResp == $currentResp) {
                    unset($arrayOldResp[$keyOld]);
                }
            }
        }

        foreach($arrayResponsabili as $keyCurr=>$currentResp) {
            foreach($arrayResponsabiliOld as $keyOld=>$oldResp) {
                if($currentResp == $oldResp) {
                    $arrayResponsabili[$keyCurr] = 'old';
                }
            }
        }

        foreach($arrayResponsabili as $currentResp) {
            if($currentResp != 'old') {
                array_push($arrayNewResp, $currentResp);
            }
        }

        $stmt = $this->db->prepare("UPDATE commesse SET commessa = ?, cliente = ?, "
                . "nome_commessa = ? WHERE id_commessa = ?");
        $stmt->bind_param('sssi', $commessa, $cliente, $nomeCommessa, $idCommessa);
        if($stmt->execute()) {
            foreach($arrayOldResp as $idOldResp) {
                $idRuolo = 2;
                $stmtDeleteCommessa = $this->db->prepare("DELETE FROM user_commesse WHERE
                    id_commessa = ? AND id_user = ? AND id_role = ?");
                $stmtDeleteCommessa->bind_param("iii", $idCommessa, $idOldResp, $idRuolo);
                $stmtDeleteCommessa->execute();
                $stmtDeleteCommessa->close();

                $rowFound = 0;
                $stmtSelectOldResp = $this->db->prepare("SELECT rowid FROM user_commesse "
                    . "WHERE id_user = ? AND id_role = ? LIMIT 1");
                $stmtSelectOldResp->bind_param('ii', $idOldResp, $idRuolo);
                $stmtSelectOldResp->execute();
                $stmtSelectOldResp->store_result();
                $stmtSelectOldResp->bind_result($rowFound);
                if($stmtSelectOldResp->num_rows == 0) {
                    $idModulo = 2;
                    $stmtDeleteModule = $this->db->prepare("DELETE FROM user_moduli WHERE
                        id_user = ? AND id_modulo = ?");
                    $stmtDeleteModule->bind_param("ii", $idOldResp, $idModulo);
                    $stmtDeleteModule->execute();
                    $stmtDeleteModule->close();
                }
            }

            foreach($arrayNewResp as $idNewResp) {
                $idRuolo = 2;
                $stmtInsertCommessa = $this->db->prepare("INSERT INTO user_commesse
                    (id_commessa, id_user, id_role) VALUES (?, ?, ?)");
                $stmtInsertCommessa->bind_param("iii", $idCommessa, $idNewResp, $idRuolo);
                $stmtInsertCommessa->execute();
                $stmtInsertCommessa->close();

                $idModulo = 2;
                $rowFound = 0;
                $stmtSelectNewResp = $this->db->prepare("SELECT row_id FROM user_moduli "
                    . "WHERE id_user = ? AND id_modulo = ? LIMIT 1");
                $stmtSelectNewResp->bind_param('ii', $idNewResp, $idModulo);
                $stmtSelectNewResp->execute();
                $stmtSelectNewResp->store_result();
                $stmtSelectNewResp->bind_result($rowFound);
                if($stmtSelectNewResp->num_rows == 0) {
                    $idModulo = 2;
                    $stmtInsertModulo = $this->db->prepare("INSERT INTO user_moduli
                        (id_user, id_modulo) VALUES (?, ?)");
                    $stmtInsertModulo->bind_param("ii", $idNewResp, $idModulo);
                    $stmtInsertModulo->execute();
                    $stmtInsertModulo->close();
                }
            }
        }
    }

    public function deleteCommessa($idCommessa) {
        $stmtSelect = $this->db->prepare("SELECT id_responsabile FROM commesse "
                . "WHERE id_commessa = ? LIMIT 1");
        $stmtSelect->bind_param('i', $idCommessa);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($idResp);
        $stmtSelect->fetch();

        $stmtDelete = $this->db->prepare("DELETE FROM commesse WHERE id_commessa = ?");
        $stmtDelete->bind_param("i", $idCommessa);
        if(!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error);
        } else {
            $stmtSelectResp = $this->db->prepare("SELECT id_commessa FROM commesse "
                . "WHERE id_responsabile = ? LIMIT 1");
            $stmtSelectResp->bind_param('i', $idResp);
            $stmtSelectResp->execute();
            $stmtSelectResp->store_result();
            $stmtSelectResp->bind_result($idResp);
            if($stmtSelect->num_rows == 0) {
                $idModulo = 2;
                $stmtModuli = $this->db->prepare("DELETE FROM user_moduli WHERE id_user = ?
                    AND id_modulo = ?");
                $stmtModuli->bind_param("ii", $idResp, $idModulo);
                $stmtModuli->execute();
                $stmtModuli->close();
            }
        }
    }

    public function createUserCommesse($idCommessa, $idUtente, $idRuolo) {
        $stmtInsert = $this->db->prepare("INSERT INTO user_commesse (id_commessa,
                id_user, id_role) VALUES (?, ?, ?)");
        $stmtInsert->bind_param("iii", $idCommessa, $idUtente, $idRuolo);
        $code = $stmtInsert->execute();
        if(!$code) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtInsert->close();
        return $code;
    }

    public function deleteUserCommesse($idUserCommessa) {
        $stmtDelete = $this->db->prepare("DELETE FROM user_commesse WHERE rowid = ?");
        $stmtDelete->bind_param("i", $idUserCommessa);
        if(!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error);
        }
    }

    public function deleteUserCommesseById($idCommessa, $idUtente) {
        $stmtDelete = $this->db->prepare("DELETE FROM user_commesse "
                . "WHERE id_user = ? AND id_commessa = ?");
        $stmtDelete->bind_param("ii", $idUtente, $idCommessa);
        if(!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error);
        }
    }

    public function getCommesseByResponsabile($idResponsabile) {
        $commesseArray = array();
        $idRuolo = 2;
        $stmt = $this->db->prepare("SELECT user_commesse.id_commessa, commesse.cliente, commesse.commessa "
                . "FROM user_commesse INNER JOIN commesse ON user_commesse.id_commessa = commesse.id_commessa "
                . "WHERE id_user = ? AND id_role = ?");
        $stmt->bind_param('ii', $idResponsabile, $idRuolo);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId, $currentClient, $currentJob);
        while($stmt->fetch()) {
            $currentCommessa = array($currentId, $currentClient, $currentJob);
            array_push($commesseArray, $currentCommessa);
        }
        return $commesseArray;
    }

    public function getIDCommesseByResponsabile($idResponsabile) {
        $commesseArray = array();
        $idRuolo = 2;
        $stmt = $this->db->prepare("SELECT user_commesse.id_commessa "
                . "FROM user_commesse INNER JOIN commesse ON user_commesse.id_commessa = commesse.id_commessa "
                . "WHERE id_user = ? AND id_role = ?");
        $stmt->bind_param('ii', $idResponsabile, $idRuolo);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentId);
        while($stmt->fetch()) {
            $commesseArray[] = $currentId;
        }
        return $commesseArray;
    }

    public function getJobById($jobId) {
        $result = array();
        $stmt = $this->db->prepare("SELECT commessa, cliente, nome_commessa"
                . " FROM commesse WHERE id_commessa = ?");
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($result['commessa'], $result['cliente'],
                $result['nome_commessa']);
        $stmt->fetch();
        return $result;
    }

    public function getAssignedJobs($idUser) {
        $commesseArray = array();
        $stmt = $this->db->prepare("SELECT DISTINCT commesse.nome_commessa, user_commesse.rowid, user_commesse.id_role
                FROM user_commesse INNER JOIN commesse ON user_commesse.id_commessa = commesse.id_commessa
                WHERE user_commesse.id_user = ?");
        $stmt->bind_param('i', $idUser);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($currentNomeCommessa, $currentIdUserComm, $currentIDRole);
        while($stmt->fetch()) {
            $currentCommessa = array($currentNomeCommessa, $currentIdUserComm, $currentIDRole);
            array_push($commesseArray, $currentCommessa);
        }
        return $commesseArray;
    }

    public function getUsersByJobId($idJob) {
        $outputArray = array();
        $result = $this->db->query("SELECT anagrafica.user_id, anagrafica.nome, "
                . "anagrafica.cognome, anagrafica.data_nascita FROM user_commesse "
                . "INNER JOIN anagrafica WHERE user_commesse.id_user = anagrafica.user_id "
                . "AND user_commesse.id_commessa = " . $idJob);

        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getMembersByJobId($idJob) {
        $outputArray = array();
        $result = $this->db->query("SELECT anagrafica.user_id, anagrafica.nome, "
                . "anagrafica.cognome, anagrafica.data_nascita FROM user_commesse "
                . "INNER JOIN anagrafica WHERE user_commesse.id_user = anagrafica.user_id "
                . "AND user_commesse.id_commessa = " . $idJob . " "
                . "AND user_commesse.id_role = 4 ORDER BY anagrafica.cognome");

        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getRespByJobId($idJob) {
        $outputArray = array();
        $result = $this->db->query("SELECT anagrafica.user_id FROM user_commesse "
                . "INNER JOIN anagrafica WHERE user_commesse.id_user = anagrafica.user_id "
                . "AND user_commesse.id_commessa = " . $idJob . " "
                . "AND user_commesse.id_role = 2 ORDER BY anagrafica.user_id");

        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

	public function getIdCommessaByName($name) {

		$result = $this->db->query("SELECT id_commessa FROM commesse WHERE nome_commessa='".$name."'");
        if($result) {
        $id_commessa = $result->fetch_assoc();
		}
        return $id_commessa['id_commessa'];
    }

    public function getAll() {

        $arrayOutput = array();
        $result = $this->db->query("SELECT * FROM commesse ORDER BY nome_commessa");
        if($result) {
            while($currentJob = $result->fetch_object()) {
                array_push($arrayOutput, $currentJob);
            }
        }
        return $arrayOutput;
    }

}
