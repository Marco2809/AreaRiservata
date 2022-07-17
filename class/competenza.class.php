<?php
session_start();
require_once('dbconn.php');
class Competenza
{
    public $db;
    //variabili tabella esperienza
    var $id_competenza;
    var $user_id;
    var $tipo_competenza;
    var $descrizione;


    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }
    
public function updateCompetenza($id_competenza,$tipo,$descrizione){
     
    $sql = "UPDATE riepilogo_competenze
                        
                           SET 
                           descrizione = '" .$this->db->escape_string($descrizione). "'
                               
                           WHERE id_competenza ='" . $id_competenza . "'";
         
    $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    public function setCompetenza($user_id,$tipo,$descrizione){
        
      $sql = "INSERT INTO riepilogo_competenze (    
                                                    user_id,
                                                    tipo_competenza,
                                                    descrizione
                                                )
                                        VALUES (        
                                               
                                                '" . $user_id . "',
                                                '" . $tipo. "',
                                                '" . $this->db->escape_string($descrizione) . "'
                                                )";


         $result=$this->db->query($sql);
         
         if($result){
             return 'ok';
         } else return 'error';
    }
    
     public function delCompetenza($id_competenza){
        
         $sql="DELETE from riepilogo_competenze WHERE id_competenza = '".$id_competenza."'";

         $result=$this->db->query($sql);
         if($result){

             return 'ok';
           
         } else return 'error';
    }
    
    
   
}

?>