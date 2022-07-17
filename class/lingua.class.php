<?php
session_start();
require_once('dbconn.php');
class Lingua
{
    public $db;
    //variabili tabella esperienza
    var $id_lang;
    var $user_id;
    var $lingua;
    var $lettura;
    var $scrittura;
    var $espressione;


    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }
    
public function updateLingua($id_lang,$lingua,$lettura,$scrittura,$espressione){
     
    $sql= "UPDATE lingue_straniere
                        
                           SET 
                           
                           lingua = '" . $this->db->escape_string($lingua) . "',
                           lettura = '" . $this->db->escape_string($lettura) . "',
                           scrittura = '" .$this->db->escape_string($scrittura). "',
                           espressione = '" .$this->db->escape_string($espressione). "'
                           
                           WHERE id_lang =". $id_lang;
         
    $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    public function setLingua($user_id,$lingua,$lettura,$scrittura,$espressione){
        
      $sql = "INSERT INTO lingue_straniere (   
                                                   
                                                    user_id,
                                                    lingua,
                                                    lettura,
                                                    scrittura, 
                                                    espressione
                                                    )
                                        VALUES (
                                               
                                                '" . $user_id . "',
                                                '" . $this->db->escape_string($lingua) . "',
                                                '" . $this->db->escape_string($lettura) . "',
                                                '" . $this->db->escape_string($scrittura) . "',
                                                '" . $this->db->escape_string($espressione) . "')";

         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    
     public function delLingua($id_lang){
        
         $sql="DELETE from lingue_straniere WHERE id_lang = '".$id_lang."'";

         $result=$this->db->query($sql);
         if($result){

             return 'ok';
           
         } else return 'error';
    }
    
    
   
}

?>