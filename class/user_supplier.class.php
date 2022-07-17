<?php

class UserSupplier {
    
    private $id;
    private $supplierId;
    private $username;
    private $password;
    private $role;
    private $db;
    
    public function __construct() {
        $this->id = null;
        $this->supplierId = null;
        $this->username = null;
        $this->password = null;
        $this->role = null;
        $this->db = (new Database())->dbConnection();
    }
    
    // Setters and getters
    
    public function getID() {
        return $this->id;
    }
    
    public function getSupplierID() {
        return $this->supplierId;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getDB() {
        return $this->db;
    }
    
    public function getRole() {
        return $this->role;
    }
    
    public function setID($id) {
        $this->id = $id;
    }
    
    public function setSupplierID($supplierId) {
        $this->supplierId = $supplierId;
    }
    
    public function setUsername($usr) {
        $this->username = $usr;
    }
    
    public function setPassword($psw) {
        $this->password = $psw;
    }
    
    public function setDB($db) {
        $this->db = $db;
    }
    
    public function setRole($role) {
        $this->role = $role;
    }
    
    // Functions
    
    public function insertRelation() {
        $stmtInsert = $this->db->prepare("INSERT INTO user_supplier (user_id, supplier_id) "
            . "VALUES (?, ?)");
        $stmtInsert->bind_param("ss", $this->id, $this->supplierId);
        if(!$stmtInsert->execute()) {
            trigger_error("Errore! " . $db->error, E_USER_WARNING);
        }
        $stmtInsert->close();
    }
    
    public function editUser() {
        $outputCode = 0;
        $id = 0;
        
        $stmtSelect = $this->db->prepare("SELECT user_idd FROM login WHERE username = BINARY ? "
                . "AND user_idd != ? LIMIT 1");
        $stmtSelect->bind_param('si', $this->username, $this->id);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($id);
        $stmtSelect->fetch();
        
        if($stmtSelect->num_rows == 0) {
            if($this->password == '9b880ac23e45a106842a9031d5bb62452d555030e755991757263a166f9de3408226cb04da27f2c3469eacc9d172c8836ec1345a5f74ccaa698e45ff3adfcec6') {
                $stmt = $this->db->prepare("UPDATE login INNER JOIN user_supplier "
                        . "ON login.user_idd = user_supplier.user_id "
                        . "SET login.username = ?, user_supplier.supplier_id = ? WHERE login.user_idd = ?");

                $stmt->bind_param('sii', $this->username, $this->supplierId, $this->id);
                $stmt->execute();
            } else {
                $this->password = hash('sha512', $this->password);
                $stmt = $this->db->prepare("UPDATE User SET username = ?,
                        supplier_id = ?, password = ? WHERE id = ?");
                $stmt->bind_param('ssi', $this->username, $this->supplierId, $this->password, $this->id);
                $stmt->execute();
            }
            $outputCode = 1;
        } else {
            $outputCode = -1;
        }
        
        return $outputCode;
    }
    
    public function getUser($id) {
        $this->id = $id;
        $stmtSelect = $this->db->prepare("SELECT user_supplier.supplier_id, login.username "
            . "FROM user_supplier INNER JOIN login ON user_supplier.user_id = login.user_idd "
            . "WHERE user_supplier.user_id = ? LIMIT 1");
        $stmtSelect->bind_param('i', $this->id);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($this->supplierId, $this->username);
        $stmtSelect->fetch();
    }
    
    public function searchUsers() {
        $idResult = null;
        $usernameResult = null;

        $userTemp = null;
        $userArray = array();
        
        $query = "SELECT login.user_idd, login.username FROM login INNER JOIN user_supplier "
                . "ON login.user_idd = user_supplier.user_id WHERE login.username LIKE CONCAT('%',?,'%')";
        $stmtSelect = $this->db->prepare($query);
        
        if(!$stmtSelect) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmtSelect->bind_param('s', $this->username);        
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($idResult, $usernameResult);
        
        while($stmtSelect->fetch()) {
            $userTemp = new UserSupplier();
            $userTemp->setID($idResult);
            $userTemp->setUsername($usernameResult);
            
            array_push($userArray, $userTemp);
        }
        
        return $userArray;
    }
    
}

?>