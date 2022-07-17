<?php
session_start();
require_once('dbconn.php');
class Instruction
{
    public $db;
    //variabili tabella esperienza
    var $id_inst;
    var $title_of_study;
    var $from;
    var $to;
    var $title;
    var $country;
    var $course;
    var $graduate;
    var $vote;
    var $description;
    var $foreign_experience;
    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function getInstruction($id_inst){
        
         $sql="SELECT * FROM formazione WHERE id_form=".$id_inst;

         $result=$this->db->query($sql);
        
         if ($result)
		{
                                
				$objp = $result->fetch_object();
                                
                                
                                $this->id_instr             = $objp->id_form;
                                $this->title_of_study       = $objp->titolo_studi;
                                $this->from                 = $objp->da;
                                $this->to                   = $objp->a;
                                $this->title                = $objp->titolo;
                                $this->country              = $objp->paese;
                                $this->course               = $objp->corso;
                                $this->graduate             = $objp->laurea;
                                $this->vote                 = $objp->voto;
                                $this->description          = $objp->descrizione;
                                $this->foreign_experience   = $objp->esperienza_estera;
                                
                    
                }
                
                else echo 'error';
    }
}

?>