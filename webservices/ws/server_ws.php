<?php

//include('../dbconn.php');
// Con public ottengo errore in PHP protected è senza nulla.
//include "http://service-tech.org/servicetech/area_riservata/dbconn.php";

class ServerWS
{

    private $dbhost = null;
    private $dbuser = null;
    private $dbpass = null;
    private $dbname = null;
    public $db = null;
    private $con;

    public function __construct()
    {

        $db_host = "service-tech.org";
        $db_user = 'adm_area';
        $db_pass = "Iniziale1$$";
        $db_name = "area_riservata";
		 $db_name = "new_area_riservata";
		

        $this->dbhost = $db_host;
        $this->dbuser = $db_user;
        $this->dbpass = $db_pass;
        $this->dbname = $db_name;


        $this->connect();
    }

    public function connect()
    {
        if (is_null($this->db))
        {
            $this->db = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
            if (!$this->db)
            {
                die('Non riesco a connettermi: ' . mysql_error());
                return false;
            }
            $this->con = mysql_select_db($this->dbname);
        }

        return $this->con;
    }

    public function getAllFromAnagrafica($username)
    {

        // $query = "SELECT * FROM anagrafica";
        $query = "SELECT * FROM anagrafica as a LEFT JOIN login as l ON  a.user_id =  l.user_idd";

        $query .= $username;
        $result_query = mysql_query($query, $this->db);
        if ($result_query)
        {
            while ($row = mysql_fetch_array($result_query, MYSQL_ASSOC))
            {
                $array_dati[] = $row;
            }
        }
        $str = json_encode($array_dati);
        return $str; // ritorna gli asset del magazzino padre e figli
    }

    public function certificazioni($addasset)
    {

        // $query = "SELECT * FROM anagrafica";
        $query = "SELECT * FROM certificazione as c LEFT JOIN anagrafica as a ON  c.user_id =  a.user_id";
        $query .= $addasset;
        $result_query = mysql_query($query, $this->db);
        if ($result_query)
        {
            while ($row = mysql_fetch_array($result_query, MYSQL_ASSOC))
            {
                $array_dati[] = $row;
            }
        }
        $str = json_encode($array_dati);
        return $str; // ritorna gli asset del magazzino padre e figli
    }

    /*     * ***** metodi implementati da Amin ******** */

    public function getHR_DatiDipendente($user_id)
    {

        if (empty($user_id))
        {
            return null;
        }
        $sql = "SELECT * FROM " . "hr_anagrafica ";
        $sql.= "WHERE user_id = " . $user_id;
        //$res = $this->db->query($sql);
        $res = mysql_query($sql, $this->db);
        $array_dipendente = array();
        if ($res)
        {
            while ($anagrafica = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $array_dipendente [] = $anagrafica;
            }
        }
        return $array_dipendente;
    }

    public function modifica_anagrafica($array_anagrafica)
    {

        $dati_anagrafica_json = json_decode($array_anagrafica, true);
        $id_user = $dati_anagrafica_json['user_id'];

        $esiste_record = $this->checkRecord("hr_anagrafica", $id_user);

        if ($esiste_record) // se esiste occoree solo aggiornare
        {
            $this->updateAnagraficaHR($dati_anagrafica_json);
        } else
        {
            if ($id_user > 0)
            {
                //aggiungere record
                $this->insertAnagraficaHR($dati_anagrafica_json);
            }
        }
    }

    private function updateAnagraficaHR($array_anagrafica = array())
    {
        $sql = "UPDATE hr_anagrafica SET"
                . " data_assunzione=" . "'" . $array_anagrafica['data_assunzione'] . "'"
                . " ,tipologia_contratto=" . "'" . $array_anagrafica['tipologia_contratto'] . "'"
                . " ,scadenza_contratto=" . "'" . $array_anagrafica['scadenza_contratto'] . "'"
                . " ,mansione=" . "'" . $array_anagrafica['mansione'] . "'"
                . " ,qualifica=" . "'" . $array_anagrafica['qualifica'] . "'"
                . " ,livello_contratto=" . "'" . $array_anagrafica['livello_contratto'] . "'"
                . " ,commessa=" . "'" . $array_anagrafica['commessa'] . "'"
                . " ,scadenza_commessa=" . "'" . $array_anagrafica['scadenza_commessa'] . "'"
                . " ,modo=" . "'" . $array_anagrafica['modo'] . "'"
                . " ,scadenza_modalita=" . "'" . $array_anagrafica['scadenza_modalita'] . "'"
                . " ,visita_medica=" . "'" . $array_anagrafica['visita_medica'] . "'"
                . " ,data_scadvisita=" . "'" . $array_anagrafica['data_scadvisita'] . "'"
                . " ,cat_protetta=" . "'" . $array_anagrafica['cat_protetta'] . "'"
                . " ,status=" . "'" . $array_anagrafica['status'] . "'";


        $sql .= " WHERE user_id = " . $array_anagrafica['user_id'];
        //$aggiornato = $this->db->query($sql);
        $aggiornato = mysql_query($sql, $this->db);
    }

    private function insertAnagraficaHR($array_anagrafica = array())
    {

        //aggiungerlo
        $sql = "INSERT INTO " . "hr_anagrafica (";
        $sql.= " user_id";
        $sql.= ", data_assunzione";
        $sql.= ", tipologia_contratto";
        $sql.= ", scadenza_contratto";
        $sql.= ", mansione";
        $sql.= ", qualifica";
        $sql.= ", livello_contratto";
        $sql.= ", commessa";
        $sql.= ", scadenza_commessa";
        $sql.= ", modo";
        $sql.= ", scadenza_modalita";
        $sql.= ", visita_medica";
        $sql.= ", data_scadvisita";
        $sql.= ", cat_protetta";
        $sql.= ", status";

        $sql.= ") VALUES (";
        $sql.= "'" . $array_anagrafica['user_id'] . "'";
        $sql.= ", '" . $array_anagrafica['data_assunzione'] . "'";
        $sql.= ", '" . $array_anagrafica['tipologia_contratto'] . "'";
        $sql.= ", '" . $array_anagrafica['scadenza_contratto'] . "'";
        $sql.= ", '" . $array_anagrafica['mansione'] . "'";
        $sql.= ", '" . $array_anagrafica['qualifica'] . "'";
        $sql.= ", '" . $array_anagrafica['livello_contratto'] . "'";
        $sql.= ", '" . $array_anagrafica['commessa'] . "'";
        $sql.= ", '" . $array_anagrafica['scadenza_commessa'] . "'";
        $sql.= ", '" . $array_anagrafica['modo'] . "'";
        $sql.= ", '" . $array_anagrafica['scadenza_modalita'] . "'";
        $sql.= ", '" . $array_anagrafica['visita_medica'] . "'";
        $sql.= ", '" . $array_anagrafica['data_scadvisita'] . "'";
        $sql.= ", '" . $array_anagrafica['cat_protetta'] . "'";
        $sql.= ", '" . $array_anagrafica['status'] . "'";
        $sql.= ")";
        //   $res_insert = $this->db->query($sql);
        $res_insert = mysql_query($sql, $this->db);
    }

    private function checkRecord($nome_tab = "", $user_id = 0)
    {
        if (empty($nome_tab) && $user_id == 0)
        {
            return false;
        }
        $sql = "SELECT count(*) as tot FROM " . "$nome_tab ";
        $sql.= "WHERE user_id = " . $user_id;

        //$res = $this->db->query($sql);
        $res = mysql_query($sql, $this->db);
        if ($res)
        {
            $obj = mysql_fetch_object($res);
            if ($obj->tot == 0)
            {
                return false;
            }
        }
        return true;
    }

    /*     * ** fine metodi implementati da Amin ****** */

    public function getHRdipendente($user_id)
    {
        $id_user = (int) $user_id;
        if ($id_user > 0)
        {
            $array_dati_dip = $this->getHR_DatiDipendente($id_user);
            if (!empty($array_dati_dip))
            {
                $datiDip_json_encode = json_encode($array_dati_dip);
                return $datiDip_json_encode;
            }
        }
        return "errore";
    }

    public function getDatiHR($my_query)
    {
        $res = mysql_query($my_query, $this->db);
        $array_dipendenti = array();
        if ($res)
        {
            while ($anagrafica = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $array_dipendenti [] = $anagrafica;
            }
        }
        $datiDip_json_encode = json_encode($array_dipendenti);
        return $datiDip_json_encode;
    }

}

/* OPZIONALMENTE: Definire la versione del messaggio soap. Il secondo parametro non è obbligatorio. */
$server = new SoapServer("wsdl_engine2.wsdl", array('soap_version' => SOAP_1_2, "cache_wsdl" => WSDL_CACHE_NONE));
//$server=new SoapServer("search_engine.wsdl");
$server->setClass("ServerWS");
// Infine la funzione handle processa una richiesta SOAP e manda un messaggio di ritorno 
// al client che l’ha richiesta.
$server->addFunction(SOAP_FUNCTIONS_ALL);
$server->handle();
?>
