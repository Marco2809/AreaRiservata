<?php
session_start();
require_once('dbconn.php');
class Anagrafica
{
    public $db;
    //variabili tabella esperienza
    var $id_anagrafica;
    var $firstname;
    var $lastname;
    var $city_of_birthday;
    var $date_of_birthday;
    var $nationality;
    var $phone;
    var $city_of_residence;
    var $address_of_residence;
    var $city_of_domicile;
    var $address_of_domicily;
    var $fiscal_code;
    var $img;
    var $referent;
    var $profile;
    var $last_update;

    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function getIdAnagrafica() {
         return $this->id_anagrafica;
    }

    public function getNome() {
         return $this->firstname;
    }

    public function getCognome() {
         return $this->lastname;
    }

    public function getAnagrafica($id_exp){

         $sql="SELECT * FROM anagrafica WHERE id_esp=".$id_exp;

         $result=$this->db->query($sql);

         if ($result)
		{

				$objp = $result->fetch_object();


                                $this->id_anagrafica        = $objp->user_id;
                                $this->firstname            = $objp->nome;
                                $this->lastname             = $objp->cognome;
                                $this->city_of_birthday     = $objp->luogo_nascita;
                                $this->date_of_birthday     = $objp->data_nascita;
                                $this->nationality          = $objp->nazionalita;
                                $this->phone                = $objp->phone;
                                $this->city_of_residence    = $objp->citta_residenza;
                                $this->address_of_residence = $objp->indirizzo_residenza;
                                $this->city_of_domicile     = $objp->citta_domicilio;
                                $this->address_of_domicily  = $objp->indirizzo_domicilio;
                                $this->fiscal_code          = $objp->codice_fiscale;
                                $this->img                  = $objp->img_name;
                                $this->referent             = $objp->referente;
                                $this->profile              = $objp->profilo_utente;
                                $this->last_update          = $objp->ultimo_update;



                }

                else echo 'error';
    }

    public function setAnagrafica($user_id,$nome,$cognome,$luogo_nascita,$data_nascita,$nazionalita,$phone,$citta_residenza,$indirizzo_residenza,$citta_domicilio,$indirizzo_domicilio,$codice_fiscale){

         $sql="INSERT INTO anagrafica (user_id,nome,cognome,luogo_nascita,data_nascita,nazionalita,phone,citta_residenza,indirizzo_residenza,citta_domicilio,indirizzo_domicilio,codice_fiscale) VALUES ('".$user_id."','".$this->db->escape_string($nome)."','".$this->db->escape_string($cognome)."','".$this->db->escape_string($luogo_nascita)."','".$data_nascita."','".$nazionalita."','".$phone."','".$this->db->escape_string($citta_residenza)."','".$this->db->escape_string($indirizzo_residenza)."','".$this->db->escape_string($citta_domicilio)."','".$this->db->escape_string($indirizzo_domicilio)."','".$codice_fiscale."')";

//mail("marco.salmi89@gmail.com","Prova",$sql);

         $result=$this->db->query($sql);
         if($result){
$_SESSION['name'] = $nome;
$_SESSION['cognome'] = $cognome;
             return 'ok';
         } else return 'error';
    }

 public function setAnagraficaAdmin($user_id,$nome,$cognome,$data_nascita,$codice_fiscale, $luogo, $nazionalita, $telefono, $residenza, $indirizzo_residenza, $domicilio, $indirizzo_domicilio){

         $sql="INSERT INTO anagrafica (user_id,nome,cognome,data_nascita,codice_fiscale,luogo_nascita,nazionalita,phone,citta_residenza,indirizzo_residenza,citta_domicilio,indirizzo_domicilio) VALUES ('".$user_id."','".$this->db->escape_string($nome)."','".$this->db->escape_string($cognome)."','".$data_nascita."','".$codice_fiscale."','".$this->db->escape_string($luogo)."','".$this->db->escape_string($nazionalita)."','".$this->db->escape_string($telefono)."','".$this->db->escape_string($residenza)."','".$this->db->escape_string($indirizzo_residenza)."','".$this->db->escape_string($domicilio)."','".$this->db->escape_string($indirizzo_domicilio)."')";

         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else {
             return "Errore! " . $this->db->error;
         }
    }

    public function updateAnagrafica($user_id,$nome,$cognome,$luogo_nascita,$data_nascita,$nazionalita,$phone,$citta_residenza,$indirizzo_residenza,$citta_domicilio,$indirizzo_domicilio,$codice_fiscale,$user_id){

         $sql="UPDATE anagrafica SET nome= '".$this->db->escape_string($nome)."',cognome = '".$this->db->escape_string($cognome)."',luogo_nascita = '".$this->db->escape_string($luogo_nascita)."',data_nascita = '".$data_nascita."',nazionalita = '".$this->db->escape_string($nazionalita)."',phone = '".$phone."',citta_residenza = '".$this->db->escape_string($citta_residenza)."',indirizzo_residenza = '".$this->db->escape_string($indirizzo_residenza)."',citta_domicilio = '".$this->db->escape_string($citta_domicilio)."',indirizzo_domicilio = '".$this->db->escape_string($indirizzo_domicilio)."',codice_fiscale = '".$codice_fiscale."' WHERE user_id =".$user_id;

         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }

    public function loginFromMobile($username,$password){
		$this->db = new mysqli("service-tech.org", "adm_area", "Iniziale1$$", "new_area_riservata");
		$username = stripslashes($username);
		$password = stripslashes($password);
		$sql = "SELECT a.*, l.email, COALESCE(NULLIF(ambiente,'Altro'), ambiente_altro) as r1, COALESCE(NULLIF(ruolo_ambiente,'Altro'), ruolo_ambiente_altro) as r2
                FROM login as l INNER JOIN anagrafica as a ON l.user_idd=a.user_id LEFT JOIN questionario as q ON q.user_id=l.user_idd
			WHERE l.username='$username' AND l.password= '$password'";
        $result = mysqli_query($this->db, $sql);
        $row = array();
        while ($r = mysqli_fetch_assoc($result)) {
            array_push($row, $r);
        }
        return $row;
	}

     public function getAllUser(){
		$this->db = new mysqli("service-tech.org", "adm_area", "Iniziale1$$", "new_area_riservata");
		$sql = "SELECT a.*, l.email, COALESCE(NULLIF(ambiente,'Altro'), ambiente_altro) as r1, COALESCE(NULLIF(ruolo_ambiente,'Altro'), ruolo_ambiente_altro) as r2
                FROM login as l INNER JOIN anagrafica as a ON l.user_idd=a.user_id LEFT JOIN questionario as q ON q.user_id=l.user_idd";
        $result = mysqli_query($this->db, $sql);
        $row = array();
        while ($r = mysqli_fetch_assoc($result)) {
            array_push($row, $r);
        }
        return $row;
	}

       public function getAnagraficaFromCF($cf) {
        $this->fiscal_code = $cf;
        $stmtSelect = $this->db->prepare("SELECT user_id, nome, cognome
                 FROM anagrafica WHERE UPPER(codice_fiscale) = ? LIMIT 1");
        $stmtSelect->bind_param('s', $this->fiscal_code);
        if(!$stmtSelect->execute()) {
            echo 'Error! ' . $this->db->error;
        }
        $stmtSelect->store_result();
        $stmtSelect->bind_result($this->id_anagrafica, $this->firstname, $this->lastname);
        $stmtSelect->fetch();
    }
}

?>
