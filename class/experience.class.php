<?php
session_start();
require_once('dbconn.php');
class Experience
{
    public $db;
    //variabili tabella esperienza
    var $id_exp;
    var $society;
    var $society_address;
    var $society_city;
    var $society_province;
    var $description;
    var $role;
    var $experience_from;
    var $experience_to;
    var $work_now;
    var $exp_note;
    var $exp_area;
    var $exp_sub_area;
    var $exp_title;
    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function getExperience($id_exp){
        
         $sql="SELECT * FROM esperienza WHERE id_esp=".$id_exp;

         $result=$this->db->query($sql);
        
         if ($result)
		{
                                
				$objp = $result->fetch_object();
                                
                                
                                $this->id_exp		= $objp->id_esp;
                                $this->society          = $objp->azienda;
                                $this->society_address	= $objp->indirizzo;
                                $this->society_city	= $objp->citta_azienda;
                                $this->society_province	= $objp->provincia_azienda;
                                $this->description	= $objp->mansione;
                                $this->role		= $objp->ruolo;
                                $this->experience_from	= $objp->da;
                                $this->experience_to	= $objp->a;
                                $this->work_now 	= $objp->attuale;
                                $this->exp_note 	= $objp->note;
                                $this->exp_area 	= $objp->area;
                                $this->exp_sub_area 	= $objp->sub_area;
                                $this->exp_title 	= $objp->titolo_esp;
                        
                                  
                    
                }
                
                else echo 'error';
    }
}

?>