<?php
session_start();
require_once('dbconn.php');
class File
{
    public $db;
    //privateiabili tabella esperienza
    public $id_file;
    public $nome;
    public $estensione;
    public $dimensione;
    public $id_messaggio;
    public $gruppi;
    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    
    public function addFile(){
        
       
        
         $sql="INSERT INTO files (nome,estensione,dimensione,id_messaggio,gruppi) VALUES (
                                '".$this->nome."',     
                                '".$this->estensione."',          
                                ".$this->dimesnione.",                
                                '".$this->id_messaggio."',		
                                '".$this->gruppi."')";
         $result=$this->db->query($sql);
        
         if ($result)
		{
                    return "<div class='panel panel-success'><div class='panel-heading'>File aggiunto con successo</div></div>";        

                }
                
                else return "<div class='panel panel-danger'><div class='panel-heading'>Problema nell'upload del file. Se il problema persiste contattare l'amministratore.</div></div>";
    }
    
    public function delFile($id){
        
        $alert="";
        $sql="DELETE FROM files WHERE id_messaggio=".$id;
     $result=$this->db->query($sql);
    if(!$result){
         return "<p align='center' style='color:red'>Spiacenti, errore nell'eliminazione del messaggio</p>";
    } else {
            $alert .= "<p align='center' style='color:green'>Messaggio eliminato con successo!</p>";
        }
        
      return $alert;
    }
    
}

?>