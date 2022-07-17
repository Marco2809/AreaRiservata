<?php

class Supplier {
    
    private $db;
    private $id;
    private $ragSociale;
    private $stato;
    private $statoArt80;
    private $statoDocScad;
    private $dataInsOE;
    private $indirizzo;
    private $cap;
    private $cittaProv;
    private $nazione;
    private $email;
    private $cf;
    private $iva;
    private $scadAnn;
    private $pec;
    private $note;
    
    public function __construct() {
        $this->id = null;
        $this->ragSociale = null;
        $this->stato = null;
        $this->statoArt80 = null;
        $this->statoDocScad = null;
        $this->dataInsOE = null;
        $this->indirizzo = null;
        $this->cap = null;
        $this->cittaProv = null;
        $this->nazione = null;
        $this->email = null;
        $this->cf = null;
        $this->iva = null;
        $this->scadAnn = null;
        $this->pec = null;
        $this->note = null;
        
        $this->db = (new Database())->dbConnection();
    }
    
    // Getters
    
    public function getId() {
        return $this->id;
    }
    
    public function getRagSociale() {
        return $this->ragSociale;
    }
    
    public function getStato() {
        return $this->stato;
    }
    
    public function getStatoArt80() {
        return $this->statoArt80;
    }
    
    public function getStatoAssettoSoc() {
        return $this->statoAssettoSoc;
    }
    
    public function getStatoListe() {
        return $this->statoListe;
    }
    
    public function getStatoDocScad() {
        return $this->statoDocScad;
    }
    
    public function getDataInsOE() {
        return $this->dataInsOE;
    }
    
    public function getIndirizzo() {
        return $this->indirizzo;
    }
    
    public function getCap() {
        return $this->cap;
    }
    
    public function getCittaProv() {
        return $this->cittaProv;
    }
    
    public function getNazione() {
        return $this->nazione;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getCf() {
        return $this->cf;
    }
    
    public function getIva() {
        return $this->iva;
    }
    
    public function getScadAnn() {
        return $this->scadAnn;
    }
    
    public function getPEC() {
        return $this->pec;
    }
    
    public function getNote() {
        return $this->note;
    }
    
    // Setters
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setRagSociale($ragSociale) {
        $this->ragSociale = $ragSociale;
    }
    
    public function setStato($stato) {
        $this->stato = $stato;
    }
    
    public function setStatoArt80($statoArt80) {
        $this->statoArt80 = $statoArt80;
    }
    
    public function setStatoAssettoSoc($statoAssettoSoc) {
        $this->statoAssettoSoc = $statoAssettoSoc;
    }
    
    public function setStatoListe($statoListe) {
        $this->statoListe = $statoListe;
    }
    
    public function setStatoDocScad($statoDocScad) {
        $this->statoDocScad = $statoDocScad;
    }
    
    public function setDataInsOE($dataInsOE) {
        $this->dataInsOE = $dataInsOE;
    }
    
    public function setIndirizzo($indirizzo) {
        $this->indirizzo = $indirizzo;
    }
    
    public function setCap($cap) {
        $this->cap = $cap;
    }
    
    public function setCittaProv($cittaProv) {
        $this->cittaProv = $cittaProv;
    }
    
    public function setNazione($nazione) {
        $this->nazione = $nazione;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setCf($cf) {
        $this->cf = $cf;
    }
    
    public function setIva($iva) {
        $this->iva = $iva;
    }
    
    public function setScadAnn($scadAnn) {
        $this->scadAnn = $scadAnn;
    }
    
    public function setPEC($pec) {
        $this->pec = $pec;
    }
    
    public function setNote($note) {
        $this->note = $note;
    }
    
    //Functions
    
    public function getSupplier($id) {
        $this->id = $id;
        $stmtSelect = $this->db->prepare("SELECT ragione_sociale, stato,
                 indirizzo, cap, citta_provincia, nazione, email, cf, partita_iva, 
                 scad_annuale, data_inserimento_oe, pec, note 
                 FROM suppliers WHERE id = ? LIMIT 1");
        $stmtSelect->bind_param('i', $this->id);
        if(!$stmtSelect->execute()) {
            echo 'Error! ' . $this->db->error;
        }
        $stmtSelect->store_result();
        $stmtSelect->bind_result($this->ragSociale, $this->stato, $this->indirizzo,
                $this->cap, $this->cittaProv, $this->nazione, $this->email,
                $this->cf, $this->iva, $this->scadAnn, $this->dataInsOE, 
                $this->pec, $this->note);
        $stmtSelect->fetch();
    }
    
    public function getSupplierFromUserId($user_id) {
        $stmtSelect = $this->db->prepare("SELECT suppliers.id, suppliers.ragione_sociale 
                 FROM suppliers INNER JOIN user_supplier ON suppliers.id = user_supplier.supplier_id
                 WHERE user_supplier.user_id = ? LIMIT 1");
        $stmtSelect->bind_param('s', $user_id);
        if(!$stmtSelect->execute()) {
            echo 'Error! ' . $this->db->error;
            echo '\n' . $user_id;
        }
        $stmtSelect->store_result();
        $stmtSelect->bind_result($this->id, $this->ragSociale);
        $stmtSelect->fetch();
    }
    
    public function getSupplierFromCf($cf) {
        $stmtSelect = $this->db->prepare("SELECT id FROM suppliers WHERE
                 cf = ? LIMIT 1");
        $stmtSelect->bind_param('s', $cf);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($this->id);
        $stmtSelect->fetch();
    }
    
    public function getSuppliersNotUser() {
        $idResult = NULL;
        $ragSocialeResult = NULL;
        $supplierTemp = NULL;
        $suppliersArray = array();
        
        $stmtSelect = $this->db->prepare("SELECT suppliers.id, suppliers.ragione_sociale 
                 FROM suppliers WHERE suppliers.id NOT IN 
                    (SELECT supplier_id FROM user_supplier)");
        if(!$stmtSelect->execute()) {
            echo 'Error! ' . $this->db->error;
        }
        $stmtSelect->store_result();
        $stmtSelect->bind_result($idResult, $ragSocialeResult);
        
        while($stmtSelect->fetch()) {
            $supplierTemp = new Supplier();
            $supplierTemp->setId($idResult);
            $supplierTemp->setRagSociale($ragSocialeResult);
            
            array_push($suppliersArray, $supplierTemp);
        }
        
        return $suppliersArray;
    }
    
    public function getAll() {
        
        $suppliersOutput = array();
        
        $idResult = null;
        $ragSocialeResult = null;
        $CFResult = null;
        $IVAResult = null;
        $statusResult = null;
        $dataInsOEResult = null;
        $statusArt80Result = null;
        $statusAssettoResult = null;
        $statusListeResult = null;
        $statusDocScadResult = null;
        
        $stmt = $this->db->prepare("SELECT id, ragione_sociale, stato, stato_art_80,
            stato_doc_sca, data_inserimento_oe, cf, partita_iva 
            FROM suppliers ORDER BY ragione_sociale ASC");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($idResult, $ragSocialeResult, $statusResult, $statusArt80Result,
                $statusDocScadResult, $dataInsOEResult, $CFResult, $IVAResult);

        while($stmt->fetch()) {
            $supplierTemp = new Supplier();
            
            $supplierTemp->setId($idResult);
            $supplierTemp->setRagSociale($ragSocialeResult);
            $supplierTemp->setStato($statusResult);
            $supplierTemp->setStatoArt80($statusArt80Result);
            $supplierTemp->setStatoAssettoSoc($statusAssettoResult);
            $supplierTemp->setStatoListe($statusListeResult);
            $supplierTemp->setStatoDocScad($statusDocScadResult);
            $supplierTemp->setDataInsOE($dataInsOEResult);
            $supplierTemp->setCf($CFResult);
            $supplierTemp->setIva($IVAResult);
            
            array_push($suppliersOutput, $supplierTemp);
        }
        
        return $suppliersOutput;
    }
    
    public function createSupplier() {
        $idDuplicate = null;
        $stmtSelect = $this->db->prepare("SELECT id FROM suppliers WHERE
            ragione_sociale = ? LIMIT 1");
        $stmtSelect->bind_param('s', $this->ragSociale);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($idDuplicate);
        $stmtSelect->fetch();
        
        if($stmtSelect->num_rows == 0) {
            $stmtInsert = $this->db->prepare("INSERT INTO suppliers (ragione_sociale,
                    stato, stato_art_80, stato_doc_sca, data_inserimento_oe, indirizzo, 
                    cap, citta_provincia, nazione, email, cf, partita_iva, scad_annuale,
                    pec) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?)");
            
            $stmtInsert->bind_param("siiissssssssss", $this->ragSociale,
                    $this->stato, $this->statoArt80, $this->statoDocScad, $this->dataInsOE, 
                    $this->indirizzo, $this->cap, $this->cittaProv, $this->nazione,
                    $this->email, $this->cf, $this->iva, $this->scadAnn, $this->pec);
            if(!$stmtInsert->execute()) {
                trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
            }
            $stmtInsert->close();
            
            $supplierIdCreated = null;
            $stmt = $this->db->prepare("SELECT LAST_INSERT_ID()");
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($supplierIdCreated);
            $stmt->fetch();
            return $supplierIdCreated;
        } else {
            return -1;
        }
    }
    
    public function deleteSupplier() {
        $stmtInsert = $this->db->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmtInsert->bind_param("i", $this->id);
        if(!$stmtInsert->execute()) {
            trigger_error("Errore! " . $this->db->error);
        }
    }
    
    public function editSupplier() {
        $outputCode = 0;
        $idDuplicate = 0;
        
        $stmtSelect = $this->db->prepare("SELECT id FROM suppliers WHERE
                 ragione_sociale = ? AND id != ? LIMIT 1");
        $stmtSelect->bind_param('si', $this->ragSociale, $this->id);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($idDuplicate);
        $stmtSelect->fetch();
        
        if($stmtSelect->num_rows == 0) {
            $stmt = $this->db->prepare("UPDATE suppliers SET ragione_sociale = ?, 
                    email = ?, partita_iva = ?, cf = ?, indirizzo = ?,
                    cap = ?, citta_provincia = ?, nazione = ?, 
                    data_inserimento_oe = ? WHERE id = ?");
            $stmt->bind_param('sssssssssi', $this->ragSociale, $this->email, 
                    $this->iva, $this->cf, $this->indirizzo, $this->cap, 
                    $this->cittaProv, $this->nazione, $this->dataInsOE, $this->id);
            $stmt->execute();
            $outputCode = 1;
        } else {
            $outputCode = -1;
        }
        
        return $outputCode;
    }
    
    public function editStateSupplier() {
        $stmt = $this->db->prepare("UPDATE suppliers SET stato = ? WHERE id = ?");
        $stmt->bind_param('si', $this->stato, $this->id);
        $stmt->execute();
    }
    
    public function searchSuppliers($statiFornitore, $statiArt80, $statiAssetto, 
            $statiListe, $statiDocSca, $dateInserimento) {
        
        $query = 'SELECT id, ragione_sociale, partita_iva, cf, stato, data_inserimento_oe, 
                stato_art_80, stato_doc_sca FROM suppliers WHERE ';
        
        $condArray = array();
        $types = '';
        $paramsArray = array();
        
        if($this->ragSociale != '') {
            array_push($condArray, 'LOWER(ragione_sociale) LIKE CONCAT("%",?,"%")');
            $types .= 's';
            $rsToPush = $this->ragSociale;
            $paramsArray[] = &$rsToPush;
        }

        if($this->iva != '') {
            array_push($condArray, 'LOWER(partita_iva) LIKE CONCAT("%",?,"%")');
            $types .= 's';
            $ivaToPush = $this->iva;
            $paramsArray[] = &$ivaToPush;
        }

        if($this->cf != '') {
            array_push($condArray, 'LOWER(cf) LIKE CONCAT("%",?,"%")');
            $types .= 's';
            $cfToPush = $this->cf;
            $paramsArray[] = &$cfToPush;
        }

        // Managing supplier states specified
        $statiSet = array();
        for($i=0; $i<count($statiFornitore); $i++) {
            if($statiFornitore[$i] != 0) {
                array_push($statiSet, $statiFornitore[$i]);
            }
        }
        if(count($statiSet) > 0) {
            $statiCondArray = array();
            for($i=0; $i<count($statiSet); $i++) {
                array_push($statiCondArray, $statiSet[$i]);
            }
            $statiCond = implode(', ', $statiCondArray);
            array_push($condArray, ' stato IN (' . $statiCond . ')');
        }
        
        // Managing supplier Art. 80 states specified
        $statiArt80Set = array();
        for($i=0; $i<count($statiArt80); $i++) {
            if($statiArt80[$i] != 0) {
                array_push($statiArt80Set, $statiArt80[$i]);
            }
        }
        if(count($statiArt80Set) > 0) {
            $statiArt80CondArray = array();
            for($i=0; $i<count($statiArt80Set); $i++) {
                array_push($statiArt80CondArray, $statiArt80Set[$i]);
            }
            $statiCond = implode(', ', $statiArt80CondArray);
            array_push($condArray, ' stato_art_80 IN (' . $statiCond . ')');
        }
        
        // Managing supplier states specified
        $statiDocScaSet = array();
        for($i=0; $i<count($statiDocSca); $i++) {
            if($statiDocSca[$i] != -1) {
                array_push($statiDocScaSet, $statiDocSca[$i]);
            }
        }
        if(count($statiDocScaSet) > 0) {
            $statiDocScaCondArray = array();
            for($i=0; $i<count($statiDocScaSet); $i++) {
                array_push($statiDocScaCondArray, $statiDocScaSet[$i]);
            }
            $statiCond = implode(', ', $statiDocScaCondArray);
            array_push($condArray, ' stato_doc_sca IN (' . $statiCond . ')');
        }

        if($dateInserimento != '') {
            array_push($condArray, 'data_inserimento_oe >= ? AND data_inserimento_oe < ?');
            $types .= 'ss';
            $data1ToPush = $dateInserimento[0];
            $data2ToPush = $dateInserimento[1];
            $paramsArray[] = &$data1ToPush;
            $paramsArray[] = &$data2ToPush;
        }

        $conditions = implode(' AND ', $condArray);
        $query .= $conditions;
        
        if($conditions == '') {
            return -1;
        }
        
        $stmt = $this->db->prepare($query);

        if($types != '') {
            array_unshift($paramsArray, $types);
            call_user_func_array(array($stmt, 'bind_param'), $paramsArray);
        }
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->id, $this->ragSociale, $this->iva, $this->cf, $this->stato, 
                $this->dataInsOE, $this->statoArt80, $this->statoDocScad);
        
        $suppliersArray = array();
        while($stmt->fetch()) {
            $supplierTemp = new Supplier();
            $supplierTemp->setId($this->id);
            $supplierTemp->setRagSociale($this->ragSociale);
            $supplierTemp->setIva($this->iva);
            $supplierTemp->setCf($this->cf);
            $supplierTemp->setStato($this->stato);
            $supplierTemp->setDataInsOE($this->dataInsOE);
            $supplierTemp->setStatoArt80($this->statoArt80);
            $supplierTemp->setStatoDocScad($this->statoDocScad);
            
            array_push($suppliersArray, $supplierTemp);
        }
        
        return $suppliersArray;
    }
    
    public function editNote() {
        $stmt = $this->db->prepare("UPDATE suppliers SET note = ? WHERE id = ?");
        $stmt->bind_param('si', $this->note, $this->id);
        return $stmt->execute();
    }
    
    public function editArt80State() {
        $stmt = $this->db->prepare("UPDATE suppliers SET stato_art_80 = ? WHERE id = ?");
        $stmt->bind_param('ii', $this->statoArt80, $this->id);
        $stmt->execute();
    }
    
    public function editDocScaState() {
        $stmt = $this->db->prepare("UPDATE suppliers SET stato_doc_sca = ? WHERE id = ?");
        $stmt->bind_param('ii', $this->statoDocScad, $this->id);
        $stmt->execute();
    }
    
    public function getCountStates() {
        $statoMonitorato = '2';
        $statoInLavorazione = '1';
        $countStates = [0, 0];
        
        $stmtSelectMon = $this->db->prepare("SELECT COUNT(stato) FROM suppliers WHERE stato = ?");
        $stmtSelectMon->bind_param('s', $statoMonitorato);
        $stmtSelectMon->execute();
        $stmtSelectMon->store_result();
        $stmtSelectMon->bind_result($countStates[0]);
        $stmtSelectMon->fetch();
        
        $stmtSelectLav = $this->db->prepare("SELECT COUNT(stato) FROM suppliers WHERE stato = ?");
        $stmtSelectLav->bind_param('s', $statoInLavorazione);
        $stmtSelectLav->execute();
        $stmtSelectLav->store_result();
        $stmtSelectLav->bind_result($countStates[1]);
        $stmtSelectLav->fetch();
        
        return $countStates;
    }
    
}

?>