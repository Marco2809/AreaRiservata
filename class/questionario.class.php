<?php
session_start();
require_once('dbconn.php');
class Questionario
{
    public $db;
    //variabili tabella esperienza
    var $id_quest;
    var $user_id;
    var $tempo;
    var $soddisfazione;
    var $ambiente;
    var $ambiente_altro;
    var $ruolo_ambiente;
    var $ruolo_ambiente_altro;
    var $area;
    var $area_altro;
    var $crescita;

    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }
    
public function updateQuestionario($user_id,$tempo,$soddisfazione,$ambiente,$ambiente_altro,$ruolo_ambiente,$ruolo_ambiente_altro,$area,$area_altro,$crescita){
     
    $sql = "UPDATE questionario
                        
                           SET 
                           tempo = '" . $tempo . "',
                           soddisfazione = '" .$soddisfazione. "',
                           ambiente = '" .$ambiente. "',
                           ambiente_altro = '" .$this->db->escape_string($ambiente_altro). "',
                               ruolo_ambiente = '" .$ruolo_ambiente. "',
                           ruolo_ambiente_altro = '" .$this->db->escape_string($ruolo_ambiente_altro). "',
                           area = '" .$area. "',
                           area_altro = '" .$this->db->escape_string($area_altro). "',
                           crescita = '" .$this->db->escape_string($crescita). "'
                               
                           WHERE user_id ='" . $user_id . "'";
    $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    public function setQuestionario($user_id,$tempo,$soddisfazione,$ambiente,$ambiente_altro,$ruolo_ambiente,$ruolo_ambiente_altro,$area,$area_altro,$crescita){
        
         $sql = "INSERT INTO questionario

                          (user_id,tempo, soddisfazione, ambiente,ambiente_altro,ruolo_ambiente,ruolo_ambiente_altro,area ,area_altro,crescita)
                          VALUES ('" . $user_id . "','" . $tempo . "','" .$soddisfazione. "','" .$ambiente. "','" .$this->db->escape_string($ambiente_altro). "','" .$ruolo_ambiente. "','" .$this->db->escape_string($ruolo_ambiente_altro). "','".$area."','" .$this->db->escape_string($area_altro). "','" .$this->db->escape_string($crescita). "')";
         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    
    
   
}

?>