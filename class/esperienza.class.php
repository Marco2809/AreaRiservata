<?php
session_start();
require_once('dbconn.php');
class Esperienza
{
    public $db;
    //variabili tabella esperienza
    var $id_esp;
    var $user_id;
    var $azienda;
    var $indirizzo;
    var $citta_azienda;
    var $provincia_azienda;
    var $mansione;
    var $ruolo;
    var $da;
    var $a;
    var $attuale;
    var $note;
    var $area;
    var $sub_area;
    var $titolo_esp;

    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function getEsperienza($id_esp){
        
         $sql="SELECT * FROM esperienza WHERE id_esp=".$id_esp;

         $result=$this->db->query($sql);
        
         if ($result)
		{
                                
				$objp = $result->fetch_object();
                                
                                
                                $this->id_esp               = $objp->id_esp;
                                $this->user_id              = $objp->user_id;
                                $this->azienda              = $objp->azienda;
                                $this->indirizzo            = $objp->indirizzo;
                                $this->citta_azienda        = $objp->citta_azienda;
                                $this->provincia_azienda    = $objp->provincia_azienda;
                                $this->mansione             = $objp->mansione;
                                $this->ruolo                = $objp->ruolo;
                                $this->da                   = $objp->da;
                                $this->a                    = $objp->a;
                                $this->attuale              = $objp->attuale;
                                $this->note                 = $objp->note;
                                $this->area                 = $objp->area;
                                $this->sub_area             = $objp->sub_area;
                                $this->titolo_esp           = $objp->titolo_esp;
                                
                        
                                  
                    
                }
                
                else echo 'error';
    }
    
   
    
    public function setEsperienza($user_id,$azienda,$indirizzo,$citta_azienda,$provincia_azienda,$mansione,$ruolo,$da,$a,$attuale,$note,$area,$sub_area,$titolo_esp){
        
         $sql="INSERT INTO esperienza (user_id,azienda,indirizzo,citta_azienda,provincia_azienda,mansione,ruolo,da,a,attuale,note,area,sub_area,titolo_esp) VALUES ('".$user_id."','".$this->db->escape_string($azienda)."','".$this->db->escape_string($indirizzo)."','".$this->db->escape_string($citta_azienda)."','".$provincia_azienda."','".$this->db->escape_string($mansione)."','".$this->db->escape_string($ruolo)."','".$da."','".$a."','".$attuale."','".$this->db->escape_string($note)."','".$area."','".$sub_area."','".$this->db->escape_string($titolo_esp)."')";
         
         $result=$this->db->query($sql);
         $sql_id = 'SELECT MAX(id_esp) as id from esperienza WHERE user_id = "'.$user_id.'"';
         $result_id = $this->db->query($sql_id);
         while($obj_id=  $result_id->fetch_object()){
             $last_id= $obj_id->id;
         }
         
         if($result){
             
             
             $sql_update = 'UPDATE skill SET id_esp="'.$last_id.'" WHERE id_esp=0 AND skill_user_id = "'.$user_id.'"';
             $result_update = $this->db->query($sql_update);
             
             if($result_update){
             return 'ok';
             } else return 'error';
             
         } else return 'error';
    }
    
    public function delEsperienza($id_esp){
        
         $sql="DELETE from esperienza WHERE id_esp = '".$id_esp."'";

         $result=$this->db->query($sql);
         if($result){
             
              $sql_skill="DELETE from skill WHERE id_esp = '".$id_esp."'";
         $result_skill=$this->db->query($sql_skill);
             if($result_skill){
             return 'ok';
             } else return 'error';
         } else return 'error';
    }
    
    public function updateEsperienza($id_esp,$user_id,$azienda,$indirizzo,$citta_azienda,$provincia_azienda,$mansione,$ruolo,$da,$a,$attuale,$note,$area,$sub_area,$titolo_esp){
        
         $sql="UPDATE esperienza SET user_id='".$user_id."',azienda='".$this->db->escape_string($azienda)."',indirizzo='".$this->db->escape_string($indirizzo)."',citta_azienda='".$this->db->escape_string($citta_azienda)."',provincia_azienda='".$provincia_azienda."',mansione='".$this->db->escape_string($mansione)."',ruolo='".$this->db->escape_string($ruolo)."',da='".$da."',a='".$a."',attuale='".$attuale."',note='".$this->db->escape_string($note)."',area='".$area."',sub_area='".$sub_area."',titolo_esp='".$this->db->escape_string($titolo_esp)."' WHERE id_esp =".$id_esp;
         
         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return $sql;
    }
}

?>