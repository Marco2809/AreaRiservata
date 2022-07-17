<?php
session_start();
require_once('dbconn.php');
class Corso
{
    public $db;
    //variabili tabella esperienza
    var $id_corso;
    var $user_id;
    var $tipo;
    var $descrizione;


    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }
    
public function updateCorso($id_corso,$tipo,$descrizione){
     
    $sql = "UPDATE corsi
                        
                           SET 
                           tipo = '" . $tipo . "',
                           descrizione = '" .$this->db->escape_string($descrizione). "'
                               
                           WHERE id_corso ='" . $id_corso . "'";
         
    $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    public function setCorso($user_id,$tipo,$descrizione){
        
      $sql = "INSERT INTO corsi (      id_corso,
                                                    user_id,
                                                    tipo,
                                                    descrizione
                                                )
                                        VALUES (        
                                                                                                NULL,
                                                '" . $user_id . "','" .$this->db->escape_string($tipo) . "',
                                                '" . $this->db->escape_string($descrizione) . "'
                                                )";


         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    
     public function delCorso($id_corso){
        
         $sql="DELETE from corsi WHERE id_corso = '".$id_corso."'";

         $result=$this->db->query($sql);
         if($result){

             return 'ok';
           
         } else return 'error';
    }
    
    
   
}

?>