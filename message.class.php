<?php
session_start();
require_once('dbconn.php');
class Message
{
    public $db;
    //privateiabili tabella esperienza
    public $id_messaggio;
    public $tipo;
    public $testo;
    public $data_creazione;
    public $ultima_modifica;
    public $id_autore;
    public $titolo;
    public $data_fine;
    public $gruppi;
    public $id_destinatario;
    public $autore;
    
    public function __construct() {  
        
        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function getMessage($id_mex){
        
         $sql="SELECT * FROM messaggi WHERE id_messaggio=".$id_mex;

         $result=$this->db->query($sql);
        
         if ($result)
		{
                                
				$objp = $result->fetch_object();
                                
                                
                                $this->id_messaggio	= $objp->id_messaggio;
                                $this->tipo             = $objp->tipo;
                                $this->testo            = $objp->testo;
                                $this->data_creazione	= $objp->data_creazione;
                                $this->ultima_modifica	= $objp->ultima_modifica;
                                $this->id_autore        = $objp->id_autore;
                                $this->autore           = $objp->autore;
                                $this->titolo		= $objp->titolo;
                                $this->data_fine	= $objp->data_fine;
                                $this->gruppi   	= $objp->gruppi;
                                $this->id_destinatario 	= $objp->id_destinatario;

                }
                
                else return 'error';
    }
    
    public function addMessage($groups=NULL,$destinatario=0){
        
       $this->data_fine = substr($this->data_fine,8,2)."/".substr($this->data_fine,5,2)."/".substr($this->data_fine,0,4);
        if($this->data_fine =="//") $this->data_fine = "";
         $sql="INSERT INTO messaggi (tipo,testo,id_autore,titolo,data_fine) VALUES (
                                '".$this->tipo."',     
                                '".$this->testo."',          
                                ".$this->id_autore.",                
                                '".$this->titolo."',		
                                '".$this->data_fine."')";
      
         $result=$this->db->query($sql);
         $insert_id = $this->db->insert_id;

         if ($result)
		{
                    $alert= "<div class='panel panel-success'><div class='panel-heading'>Messaggio inviato con successo</div></div>";        

                }
                
                else return "<div class='panel panel-danger'><div class='panel-heading'>Problema nell'invio del messaggio. Se il problema persiste contattare l'amministratore.</div></div>";

                
   $directory = "files/temp/".$this->id_autore."/";
// Apriamo una directory e leggiamone il contenuto.
if (is_dir($directory)) {
//Apro l'oggetto directory
if ($directory_handle = opendir($directory)) {
//Scorro l'oggetto fino a quando non è termnato cioè false
while (($file = readdir($directory_handle)) !== false) {
//Se l'elemento trovato è diverso da una directory
//o dagli elementi . e .. lo visualizzo a schermo
if((!is_dir($file))&($file!=".")&($file!=".."))
    if ($file!=".DS_Store") {
       
$originale = "files/temp/".$this->id_autore."/".$file;
if(!file_exists("files/".$insert_id)){
mkdir("files/".$insert_id, 0777);
}
$copia = "files/".$insert_id."/".$file;
copy($originale,$copia);
$estensione=  explode(".", $file);
unlink("files/temp/".$_SESSION['user_idd']."/".$file);

$sql_files="INSERT into files (estensione, nome, id_messaggio)
                          VALUES ('" . $estensione[count($estensione)-1] . "','" .$estensione[0]. "'," .$insert_id. ")";
   $result_sql_files = $this->db->query($sql_files);

    }
}
//Chiudo la lettura della directory.
closedir($directory_handle);
}
} 

foreach($groups as $value){
     $sql="INSERT INTO message_groups (id_messaggio,id_gruppo,id_destinatario) VALUES (
                                ".$insert_id.",     
                                ".$value.",          
                                ".$destinatario.")";
      
         $result=$this->db->query($sql);
         $sql_group="SELECT a.user_id FROM user_groups as ug, anagrafica a WHERE ug.id_user = a.user_id AND ug.id_user != '".$destinatario."' AND ug.id_gruppo =".$value;
      
         $result_group=$this->db->query($sql_group);
        
        while($obj_group = $result_group->fetch_object()){
            
         $sql_read="INSERT INTO read_messages (id_messaggio,id_user) VALUES (
                                ".$insert_id.",       
                                ".$obj_group->user_id.")";
        
         $result_read=$this->db->query($sql_read);

        } 
        
        
}

if($destinatario != 0) {
            
            $sql="INSERT INTO read_messages (id_messaggio,id_user) VALUES (
                                ".$insert_id.",       
                                ".$destinatario.")";
        
         $result=$this->db->query($sql);
            
        }

                return $alert;
                }
    
    public function delMessage($id){
        
        $alert="";
        $sql="DELETE FROM messaggi WHERE id_messaggio=".$id;
      
     $result=$this->db->query($sql);
    if(!$result){
         return "<p align='center' style='color:red'>Spiacenti, errore nell'eliminazione del messaggio</p>";
    } else {
            $alert .= "<p align='center' style='color:green'>Messaggio eliminato con successo!</p>";
        }

        $directory = "/files/".$id."/";
    if (is_dir($directory)) {
//Apro l'oggetto directory
if ($directory_handle = opendir($directory)) {
//Scorro l'oggetto fino a quando non è termnato cioè false
while (($file = readdir($directory_handle)) !== false) {
//Se l'elemento trovato è diverso da una directory
//o dagli elementi . e .. lo visualizzo a schermo
if((!is_dir($file))&($file!=".")&($file!=".."))
    if ($file!=".DS_Store") {
        chdir($directory);
    unlink($file);
    unset($file);
        
   }
}
}
    }
     $sql="DELETE FROM files WHERE id_messaggio=".$id;
   $result=$this->db->query($sql);
    if(!$result){
        return "<p align='center' style='color:red'>Spiacenti, errore nell'eliminazione del file</p>";
    } else {
            $alert .= "<p align='center' style='color:green'>File correlato eliminato con successo!</p>";
        }
        
      return $alert;
    }
    
    
    public function updateMessage($id){
        
      
         $sql="UPDATE messaggi SET testo = '".$this->testo."', titolo= '".$this->titolo."' WHERE id_messaggio =".$id;
      
       
         $result=$this->db->query($sql);

         if ($result)
		{
                    $alert= "<div class='panel panel-success'><div class='panel-heading'>Messaggio aggiornato con successo</div></div>";        

                }
                
                else return "<div class='panel panel-danger'><div class='panel-heading'>Problema nell'aggiornamento del messaggio. Se il problema persiste contattare l'amministratore.</div></div>";

                return $alert;
    }
    
    public function havePermission($id_mex,$id_user){
        
        $sql="SELECT * FROM read_messages WHERE id_messaggio =".$id_mex." AND id_user =".$id_user;
        
         $result=$this->db->query($sql);
         
         $sql1="SELECT * FROM messaggi WHERE id_messaggio =".$id_mex." AND id_autore =".$id_user;
        
         $result1=$this->db->query($sql1);
         
         if($result->num_rows>0||$result1->num_rows>0) return 'ok';
         else return "<div class='panel panel-danger'><div class='panel-heading'>Non disponi dei privilegi necessari per visualizzare il messaggio</div></div>";
        
    }
    
    public function setRead($id_mex,$id_user){
        
        $sql="UPDATE read_messages SET stato='1' WHERE id_messaggio =".$id_mex." AND id_user =".$id_user;
        
         $result=$this->db->query($sql);
    }
}

?>