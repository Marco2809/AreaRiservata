<?php
session_start();
require_once('dbconn.php');
class Skill
{
    public $db;
    //variabili tabella esperienza
    var $skill_id;
    var $skill_user_id;
    var $skill;
    var $livello_skill;
    var $id_esp;

    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    
    
    public function setSkill($skill_user_id,$skill,$livello_skill,$id_esp){
        
		if(empty($id_esp)) $id_esp = 0;
		
         $sql="INSERT INTO skill (skill_user_id,skill,livello_skill,id_esp) VALUES ('".$skill_user_id."','".$skill."','".$livello_skill."','".$id_esp."')";

         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    
    
    
    public function delSkill($skill_id){
        
         $sql="DELETE from skill WHERE skill_id = '".$skill_id."'";

         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
    
   
}

?>