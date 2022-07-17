<?php

class Modulo {

    private $db;
    private $id;
    private $idUser;
    private $idModule;

    public function __construct() {
        $this->id = null;
        $this->idUser = null;
        $this->idModule = null;

        $db = new Database();
        $this->db = $db->dbConnection();
    }

    public function createUserModule($idUtente, $idModulo) {
        $stmtInsert = $this->db->prepare("INSERT INTO user_moduli (id_user, id_modulo) "
                . "VALUES (?, ?)");

        $stmtInsert->bind_param("ii", $idUtente, $idModulo);
        $code = $stmtInsert->execute();
        if(!$code) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        } else {
            if($idModulo == 1) {
                $newIsAdmin = 1;
                $stmtUpdate = $this->db->prepare("UPDATE login SET is_admin = ? WHERE user_idd = ?");
                $stmtUpdate->bind_param('ii', $newIsAdmin, $idUtente);
                if($stmtUpdate->execute()) {
                    $_SESSION['is_admin'] = 1;
                }
            }
        }
        $stmtInsert->close();
    }

    public function deleteUserModule($idUtente, $idModulo) {
        $stmtDelete = $this->db->prepare("DELETE FROM user_moduli WHERE id_user = ? AND id_modulo = ?");
        $stmtDelete->bind_param("ii", $idUtente, $idModulo);
        if(!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error);
        } else {
            if($idModulo == 1) {
                $newIsAdmin = 0;
                $stmtUpdate = $this->db->prepare("UPDATE login SET is_admin = ? WHERE user_idd = ?");
                $stmtUpdate->bind_param('ii', $newIsAdmin, $idUtente);
                if($stmtUpdate->execute()) {
                    $_SESSION['is_admin'] = 0;
                }
            }
        }
        $stmtDelete->close();
    }

    public function deleteUserModules($idUtente) {
        $stmtDelete = $this->db->prepare("DELETE FROM user_moduli WHERE id_user = ?");
        $stmtDelete->bind_param("i", $idUtente);
        if(!$stmtDelete->execute()) {
            trigger_error("Errore! " . $this->db->error);
        } else {
          return 'ok';
        }
        $stmtDelete->close();
    }

    public function getAll() {
        $outputArray = array();
        $result = $this->db->query("SELECT id_modulo, nome FROM moduli");
        while($row = $result->fetch_assoc()) {
            switch($row['nome']) {
                case 'Amministrazione':
                    if(file_exists('./amministrazione.php')) {
                        array_push($outputArray, $row);
                    }
                    break;

                case 'Responsabile':
                    if(file_exists('./responsabile.php')) {
                        array_push($outputArray, $row);
                    }
                    break;

                case 'Albo Fornitori':
                    if(file_exists('./albo-fornitori.php')) {
                        array_push($outputArray, $row);
                    }
                    break;

                case 'HR':
                    if(file_exists('./hr.php')) {
                        array_push($outputArray, $row);
                    }
                    break;
				case 'Costi Aziendali':
                    if(file_exists('./costi_aziendali.php')) {
                        array_push($outputArray, $row);
                    }
                    break;
            }
        }
        return $outputArray;
    }

    public function getUsersByModuleId($idModulo) {
        $outputArray = array();
        $result = $this->db->query("SELECT anagrafica.user_id, anagrafica.nome, "
                . "anagrafica.cognome, anagrafica.data_nascita FROM user_moduli "
                . "INNER JOIN anagrafica WHERE user_moduli.id_user = anagrafica.user_id "
                . "AND user_moduli.id_modulo = " . $idModulo . " ORDER BY anagrafica.user_id");

        while($row = $result->fetch_assoc()) {
            array_push($outputArray, $row);
        }
        return $outputArray;
    }

    public function getModulesByUserId($idUser) {
        $currentModule = 0;
        $modulesArray = array();
        $stmtSelect = $this->db->prepare("SELECT id_modulo FROM user_moduli WHERE id_user = ?");
        $stmtSelect->bind_param('i', $idUser);
        if(!$stmtSelect->execute()) {
            echo 'Error! ' . $this->db->error;
        }
        $stmtSelect->store_result();
        $stmtSelect->bind_result($currentModule);
        while($stmtSelect->fetch()) {
            array_push($modulesArray, $currentModule);
        }
        return $modulesArray;
    }

}
