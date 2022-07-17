<?php

class SupplierDocuments {
    
    private $db;
    
    private $supplierId;
    private $infPrivacy;
    private $bilanci;
    private $docIdLegali;
    private $casGiudizialeSoggCamera;
    private $carichiSoggCamera;
    private $casAnagrafeSanzAmm;
    private $certCarichiPendenti;
    private $dichSostitutiva;
    private $durc;
    private $visuraCamerale;
    private $iso9001;
    
    public function __construct() {
        $this->supplierId = null;
        $this->infPrivacy = null;
        $this->bilanci = null;
        $this->docIdLegali = null;
        $this->casGiudizialeSoggCamera = null;
        $this->carichiSoggCamera = null;
        $this->casAnagrafeSanzAmm = null;
        $this->certCarichiPendenti = null;
        $this->dichSostitutiva = null;
        $this->durc = null;
        $this->visuraCamerale = null;
        $this->iso9001 = null;
        
        $this->db = (new Database())->dbConnection();
    }
    
    // Getters
    
    public function getSupplierId() {
        return $this->supplierId;
    }
    
    public function getInfPrivacy() {
        return $this->infPrivacy;
    }
    
    public function getBilanci() {
        return $this->bilanci;
    }
    
    public function getDocIdLegali() {
        return $this->docIdLegali;
    }
    
    public function getCasGiudizialeSoggCamera() {
        return $this->casGiudizialeSoggCamera;
    }
    
    public function getCarichiSoggCamera() {
        return $this->carichiSoggCamera;
    }
    
    public function getCasAnagrafeSanzAmm() {
        return $this->casAnagrafeSanzAmm;
    }
    
    public function getCertCarichiPendenti() {
        return $this->certCarichiPendenti;
    }
    
    public function getDichSostitutiva() {
        return $this->dichSostitutiva;
    }
    
    public function getDurc() {
        return $this->durc;
    }
    
    public function getVisuraCamerale() {
        return $this->visuraCamerale;
    }
    
    public function getIso9001() {
        return $this->iso9001;
    }
    
    
    // Setters
    
    public function setSupplierId($id) {
        $this->supplierId = $id;
    }
    
    public function setInfPrivacy($infPrivacy) {
        $this->infPrivacy = $infPrivacy;
    }
    
    public function setBilanci($bilanci) {
        $this->bilanci = $bilanci;
    }
    
    public function setDocIdLegali($docIdLegali) {
        $this->docIdLegali = $docIdLegali;
    }
    
    public function setCasGiudizialeSoggCamera($casGiudizialeSoggCamera) {
        $this->casGiudizialeSoggCamera = $casGiudizialeSoggCamera;
    }
    
    public function setCarichiSoggCamera($carichiSoggCamera) {
        $this->carichiSoggCamera = $carichiSoggCamera;
    }
    
    public function setCasAnagrafeSanzAmm($casAnagrafeSanzAmm) {
        $this->casAnagrafeSanzAmm = $casAnagrafeSanzAmm;
    }
    
    public function setCertCarichiPendenti($certCarichiPendenti) {
        $this->certCarichiPendenti = $certCarichiPendenti;
    }
    
    public function setDichSostitutiva($dichSostitutiva) {
        $this->dichSostitutiva = $dichSostitutiva;
    }
    
    public function setDurc($durc) {
        $this->durc = $durc;
    }
    
    public function setVisuraCamerale($visuraCamerale) {
        $this->visuraCamerale = $visuraCamerale;
    }
    
    public function setIso9001($iso9001) {
        $this->iso9001 = $iso9001;
    }
    
    // Functions
    
    public function createSupplierDocuments($supplierId) {
        $query = "INSERT INTO suppliers_documents (supplier_id, inf_privacy, 
            bilanci, doc_id_legale, cas_giudiziale_sogg_camera, 
            carichi_pendenti_sogg_camera, cas_anagrafe_sanzioni_amm, 
            cert_carichi_pendenti, dich_sostitutiva, durc, 
            visura_camerale, iso_9001) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        echo $this->db->error;
        $statusStart = '13000000000000000000000000000000';
        $stmt->bind_param('isssssssssss', $supplierId, $statusStart, 
                $statusStart, $statusStart, $statusStart, $statusStart, $statusStart, 
                $statusStart, $statusStart, $statusStart, $statusStart, $statusStart);
        if(!$stmt->execute()) {
            trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
        }
        $stmt->close();
    }
    
    public function getSupplierDocuments() {
        $query = "SELECT * FROM suppliers_documents WHERE supplier_id = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $this->supplierId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->supplierId, $this->infPrivacy, $this->bilanci, 
            $this->docIdLegali, $this->casGiudizialeSoggCamera, $this->carichiSoggCamera, 
            $this->casAnagrafeSanzAmm, $this->certCarichiPendenti, $this->dichSostitutiva, 
            $this->durc, $this->visuraCamerale, $this->iso9001);
        $stmt->fetch();
        $output = array($this->infPrivacy, $this->bilanci, 
            $this->docIdLegali, $this->casGiudizialeSoggCamera, $this->carichiSoggCamera, 
            $this->casAnagrafeSanzAmm, $this->certCarichiPendenti, $this->dichSostitutiva, 
            $this->durc, $this->visuraCamerale, $this->iso9001);
        return $output;
    }
    
    public function getAllExpiryStates() {
        $outputArray = array();
        $query = "SELECT supplier_id, ragione_sociale, SUBSTRING(inf_privacy, 2, 1), "
                . "SUBSTRING(bilanci, 2, 1), SUBSTRING(doc_id_legale, 2, 1), "
                . "SUBSTRING(cas_giudiziale_sogg_camera, 2, 1), SUBSTRING(carichi_pendenti_sogg_camera, 2, 1), "
                . "SUBSTRING(cas_anagrafe_sanzioni_amm, 2, 1), SUBSTRING(cert_carichi_pendenti, 2, 1), "
                . "SUBSTRING(dich_sostitutiva, 2, 1), SUBSTRING(durc, 2, 1), "
                . "SUBSTRING(visura_camerale, 2, 1), SUBSTRING(iso_9001, 2, 1) "
                . "FROM suppliers_documents INNER JOIN suppliers "
                . "ON suppliers_documents.supplier_id = suppliers.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($supplierId, $ragSociale, $infPrivacy, $bilanci, 
            $docIdLegali, $casGiudizialeSoggCamera, $carichiSoggCamera, 
            $casAnagrafeSanzAmm, $certCarichiPendenti, $dichSostitutiva, 
            $durc, $visuraCamerale, $iso9001);
        while($stmt->fetch()) {
            $tempArray = array($supplierId, $ragSociale, $infPrivacy, $bilanci, 
                $docIdLegali, $casGiudizialeSoggCamera, $carichiSoggCamera, 
                $casAnagrafeSanzAmm, $certCarichiPendenti, $dichSostitutiva, 
                $durc, $visuraCamerale, $iso9001);
            array_push($outputArray, $tempArray);
        }
        
        return $outputArray;
    }
    
    public function getAllApprovalStates() {
        $outputArray = array();
        $query = "SELECT supplier_id, ragione_sociale, SUBSTRING(inf_privacy, 1, 1), "
                . "SUBSTRING(bilanci, 1, 1), SUBSTRING(doc_id_legale, 1, 1), "
                . "SUBSTRING(cas_giudiziale_sogg_camera, 1, 1), SUBSTRING(carichi_pendenti_sogg_camera, 1, 1), "
                . "SUBSTRING(cas_anagrafe_sanzioni_amm, 1, 1), SUBSTRING(cert_carichi_pendenti, 1, 1), "
                . "SUBSTRING(dich_sostitutiva, 1, 1), SUBSTRING(durc, 1, 1), "
                . "SUBSTRING(visura_camerale, 1, 1), SUBSTRING(iso_9001, 1, 1) "
                . "FROM suppliers_documents INNER JOIN suppliers "
                . "ON suppliers_documents.supplier_id = suppliers.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($supplierId, $ragSociale, $infPrivacy, $bilanci, 
            $docIdLegali, $casGiudizialeSoggCamera, $carichiSoggCamera, 
            $casAnagrafeSanzAmm, $certCarichiPendenti, $dichSostitutiva, 
            $durc, $visuraCamerale, $iso9001);
        while($stmt->fetch()) {
            $tempArray = array($supplierId, $ragSociale, $infPrivacy, $bilanci, 
                $docIdLegali, $casGiudizialeSoggCamera, $carichiSoggCamera, 
                $casAnagrafeSanzAmm, $certCarichiPendenti, $dichSostitutiva, 
                $durc, $visuraCamerale, $iso9001);
            array_push($outputArray, $tempArray);
        }
        
        return $outputArray;
    }
    
    public function getSupplierDocumentsFromIds($supplierIds) {
        $output = array();
        $query = 'SELECT * FROM suppliers_documents WHERE supplier_id IN (' . 
                implode(",", $supplierIds) . ')';
        $suppliersResults = $this->db->query($query);
        if($suppliersResults->num_rows > 0) {
            while($result = $suppliersResults->fetch_assoc()) {
                array_push($output, $result);
            }
        }
        return $output;
    }
    
    public function editState($documentId, $newState) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $docElements = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($docElements);
        $stmtSelect->fetch();
        $document = $newState . substr($docElements, 1);
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        return $document;
    }
    
    public function editDocScaState($documentId, $newExpiryState) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $oldDocument = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($oldDocument);
        $stmtSelect->fetch();
        $document = substr($oldDocument, 0, 1) . $newExpiryState . substr($oldDocument, 2);
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        return $document;
    }
    
    public function editRequestDate($documentId, $newReqDate) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $oldDocument = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($oldDocument);
        $stmtSelect->fetch();
        $document = substr($oldDocument, 0, 2) . $newReqDate . substr($oldDocument, 12);
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        return $document;
    }
    
    public function editExpiryDate($documentId, $newExpDate) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $oldDocument = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($oldDocument);
        $stmtSelect->fetch();
        $document = substr($oldDocument, 0, 12) . $newExpDate . substr($oldDocument, 22);
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        
        // Creating events
        if($stmtUpdate) {
            // EVENT #1 --> At expiry date
            $event1Name = 'scad_sup' . $this->supplierId . '_doc' . $documentId;
            // Dropping of possible existing event
            $query = 'DROP EVENT IF EXISTS ' . $event1Name;
            $this->db->query($query);
            
            $futureDocument = '10' . substr($oldDocument, 2);
            
            $query = 'CREATE EVENT ' . $event1Name . ' ON SCHEDULE AT "' . 
            getMySQLDate($newExpDate) . '" DO UPDATE suppliers_documents SET ' . 
                $documentNames[$documentId-1] . ' = "' . $futureDocument . 
                    '" WHERE supplier_id = ' . $this->supplierId;
            $this->db->query($query);
            
            // EVENT #2 --> Updating DOCSCA State 2 weeks before
            $eventScadQuery = "DROP EVENT IF EXISTS before_scad_sup" . $this->supplierId . "_doc" . $documentId .";
                delimiter $$

                CREATE EVENT before_scad_sup" . $this->supplierId . "_" . $documentId ."
                ON SCHEDULE AT " . getMySQLDate($newExpDate) . " + INTERVAL 5 SECOND
                DO
                    BEGIN
                        DECLARE control BIT(1) DEFAULT 1;
                        DECLARE current_value VARCHAR(1) DEFAULT \" \";
                        DECLARE col_name VARCHAR(50);
                        DECLARE finished INTEGER DEFAULT 0;
                        DECLARE current_query VARCHAR(1000) DEFAULT \"\";

                        DECLARE col_names CURSOR FOR
                          SELECT column_name
                          FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE table_schema = 'supplier' AND table_name = 'suppliers_documents';

                        DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;

                        UPDATE suppliers_documents
                        SET sanzioni_amm_reato = \"1000000000000000000000\"
                        WHERE supplier_id = 3;

                        OPEN col_names;

                        loop_docs: LOOP

                                FETCH col_names INTO col_name;

                                SET @current_query = CONCAT('SELECT SUBSTRING(', col_name, ', 1, 1)
                                INTO @current_value 
                                FROM suppliers_documents 
                                WHERE supplier_id = " . $this->supplierId . ";');

                                IF col_name != \"id\" THEN
                                        PREPARE stmt
                                        FROM @current_query;
                                        EXECUTE stmt;

                                        IF @current_value = 3 THEN 
                                                SET control = 0;
                                                LEAVE loop_docs;
                                        END IF;

                                        IF finished = 1 THEN 
                                                LEAVE loop_docs;
                                        END IF;
                                END IF;

                        END LOOP loop_docs;

                        CLOSE col_names;

                        IF control = 0 THEN
                                UPDATE suppliers
                                SET stato_doc_sca = 3
                                WHERE id = " . $this->supplierId . ";
                        ELSE
                                UPDATE suppliers
                                SET stato_doc_sca = 0
                                WHERE id = " . $this->supplierId . ";
                        END IF;

                    END $$

                delimiter ;";
            
            $this->db->query($eventScadQuery);
            
            // EVENT #3 --> 2 weeks before expiry date
            $event2Name = 'before_scad_sup' . $this->supplierId . '_doc' . $documentId;
            // Dropping of possible existing event
            $query = 'DROP EVENT IF EXISTS ' . $event2Name;
            $this->db->query($query);
            
            $futureDocument = substr($oldDocument, 0, 1) . '1' . substr($oldDocument, 2);
            
            $query = 'CREATE EVENT ' . $event2Name . ' ON SCHEDULE AT "' . 
            getMySQLDate(subtractDaysFromDate($newExpDate, 15)) . '" DO UPDATE suppliers_documents SET ' . 
                $documentNames[$documentId-1] . ' = "' . $futureDocument . '" WHERE supplier_id = ' . 
                $this->supplierId;
            $this->db->query($query);
            
            // EVENT #4 --> Updating DOCSCA State 2 weeks before
            $eventPreScadQuery = "DROP EVENT IF EXISTS before_scad_sup" . $this->supplierId . "_doc" . $documentId .";
                delimiter $$

                CREATE EVENT before_scad_sup" . $this->supplierId . "_" . $documentId ."
                ON SCHEDULE AT " . getMySQLDate(subtractDaysFromDate($newExpDate, 15)) . " + INTERVAL 5 SECOND
                DO
                    BEGIN
                        DECLARE control BIT(1) DEFAULT 1;
                        DECLARE current_value VARCHAR(1) DEFAULT \" \";
                        DECLARE col_name VARCHAR(50);
                        DECLARE finished INTEGER DEFAULT 0;
                        DECLARE current_query VARCHAR(1000) DEFAULT \"\";

                        DECLARE col_names CURSOR FOR
                          SELECT column_name
                          FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE table_schema = 'supplier' AND table_name = 'suppliers_documents';

                        DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;

                        UPDATE suppliers_documents
                        SET sanzioni_amm_reato = \"1000000000000000000000\"
                        WHERE supplier_id = 3;

                        OPEN col_names;

                        loop_docs: LOOP

                                FETCH col_names INTO col_name;

                                SET @current_query = CONCAT('SELECT SUBSTRING(', col_name, ', 1, 1)
                                INTO @current_value 
                                FROM suppliers_documents 
                                WHERE supplier_id = " . $this->supplierId . ";');

                                IF col_name != \"id\" THEN
                                        PREPARE stmt
                                        FROM @current_query;
                                        EXECUTE stmt;

                                        IF @current_value = 3 THEN 
                                                SET control = 0;
                                                LEAVE loop_docs;
                                        END IF;

                                        IF finished = 1 THEN 
                                                LEAVE loop_docs;
                                        END IF;
                                END IF;

                        END LOOP loop_docs;

                        CLOSE col_names;

                        IF control = 0 THEN
                                UPDATE suppliers
                                SET stato_doc_sca = 3
                                WHERE id = " . $this->supplierId . ";
                        ELSE
                                UPDATE suppliers
                                SET stato_doc_sca = 0
                                WHERE id = " . $this->supplierId . ";
                        END IF;

                    END $$

                delimiter ;";
            
            $this->db->query($eventPreScadQuery);
        }
        
        return $document;
    }
    
    public function editEmissionDate($documentId, $newEmissionDate) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $oldDocument = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($oldDocument);
        $stmtSelect->fetch();
        $document = substr($oldDocument, 0, 22) . $newEmissionDate . substr($oldDocument, 32);
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        
        return $document;
    }
    
    public function deleteEmissionDate($documentId) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $oldDocument = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($oldDocument);
        $stmtSelect->fetch();
        $document = substr($oldDocument, 0, 22) . '0000000000' . substr($oldDocument, 32);
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        
        return $document;
    }
    
    public function deleteExpiryDate($documentId) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $oldDocument = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($oldDocument);
        $stmtSelect->fetch();
        $document = substr($oldDocument, 0, 12) . '0000000000' . substr($oldDocument, 22);
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        
        return $document;
    }
    
    public function editNote($documentId, $newNote) {
        $documentNames = array('inf_privacy', 'bilanci', 'doc_id_legale', 
            'cas_giudiziale_sogg_camera', 'carichi_pendenti_sogg_camera', 
            'cas_anagrafe_sanzioni_amm', 'cert_carichi_pendenti', 
            'dich_sostitutiva', 'durc', 'visura_camerale', 'iso_9001');
        $note = '';
        
        $stmtSelect = $this->db->prepare('SELECT ' . $documentNames[$documentId-1] . 
                ' FROM suppliers_documents WHERE supplier_id = ? LIMIT 1');
        $stmtSelect->bind_param('i', $this->supplierId);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $stmtSelect->bind_result($note);
        $stmtSelect->fetch();

        $document = substr($note, 0, 32) . $newNote;
        
        $query = 'UPDATE suppliers_documents SET ' . $documentNames[$documentId-1] . 
                ' = ? WHERE supplier_id = ?';
        $stmtUpdate = $this->db->prepare($query);
        $stmtUpdate->bind_param('si', $document, $this->supplierId);
        $stmtUpdate->execute();
        return $document;
    }
    
    public function getDocumentsExpiryStateCount() {
        
        $outputArray = array();
        $countStates = [0, 0, 0, 0];
        
        $documentsNames = array('supplier_id', 
            'inf_privacy',
            'bilanci',
            'doc_id_legale',
            'cas_giudiziale_sogg_camera',
            'carichi_pendenti_sogg_camera',
            'cas_anagrafe_sanzioni_amm',
            'cert_carichi_pendenti',
            'dich_sostitutiva',
            'durc', 
            'visura_camerale',
            'iso_9001');
        
        for($i=0; $i<11; $i++) {
            
            for($state=0; $state<4; $state++) {
                
                $stmtSelect = $this->db->prepare('SELECT COUNT(supplier_id) '
                    . 'FROM suppliers_documents WHERE SUBSTRING(' . $documentsNames[$i] . 
                    ', 2, 1) = ?');
                $stmtSelect->bind_param('s', $state);
                $stmtSelect->execute();
                $stmtSelect->store_result();
                $stmtSelect->bind_result($countStates[$state]);
                $stmtSelect->fetch();
                
            }
            
            array_push($outputArray, $countStates);
            
        }
        
        return $outputArray;
        
    }
    
}

?>