<?php
session_start();
require_once('dbconn.php');
class Istruzione
{
    public $db;
    //variabili tabella esperienza
    var $id_form;
    var $user_id;
    var $titolo_studi;
    var $da;
    var $a;
    var $titolo;
    var $paese;
    var $corso;
    var $laurea;
    var $voto;
    var $descrizione;
    var $esperienza_estera;


    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function getIstruzione($id_form){

         $sql="SELECT * FROM formazione WHERE id_form=".$id_form;

         $result=$this->db->query($sql);

         if ($result)
		{

				$objp = $result->fetch_object();


                                $this->id_form              = $objp->id_form;
                                $this->user_id              = $objp->user_id;
                                $this->titolo_studi         = $objp->titolo_studi;
                                $this->da                   = $objp->da;
                                $this->a                    = $objp->a;
                                $this->titolo               = $objp->titolo;
                                $this->paese                = $objp->paese;
                                $this->corso                = $objp->corso;
                                $this->laurea               = $objp->laurea;
                                $this->descrizione          = $objp->descrizione;
                                $this->voto                 = $objp->voto;
                                $this->esperienza_estera    = $objp->esperienza_estera;




                }

                else echo 'error';
    }

    public function setIstruzione($user_id,$titolo_studi,$da,$a,$titolo,$paese,$corso,$laurea,$voto,$descrizione,$esperienza_estera,$ateneo){

         $sql = "INSERT INTO formazione (user_id,titolo_studi,ateneo,da,a,titolo,paese,corso,laurea,voto,descrizione,esperienza_estera) VALUES ('".$user_id."','".$this->db->escape_string($titolo_studi)."','".$this->db->escape_string($ateneo)."','".$da."','".$a."','".$this->db->escape_string($titolo)."','".$this->db->escape_string($paese)."','".$this->db->escape_string($corso)."','".$this->db->escape_string($laurea)."','".$voto."','".$this->db->escape_string($descrizione)."','".$esperienza_estera."')";
         $result = $this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }

    public function delIstruzione($id_form){

         $sql="DELETE from formazione WHERE id_form = '".$id_form."'";

         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }

    public function updateIstruzione($id_form,$user_id,$titolo_studi,$da,$a,$titolo,$paese,$corso,$laurea,$voto,$descrizione,$esperienza_estera,$ateneo){

         $sql="UPDATE formazione SET titolo_studi = '".$this->db->escape_string($titolo_studi)."',da='".$da."',a='".$a."',titolo='".$this->db->escape_string($titolo)."',ateneo='".$this->db->escape_string($ateneo)."',paese='".$this->db->escape_string($paese)."',corso='".$this->db->escape_string($corso)."',laurea='".$this->db->escape_string($laurea)."',voto='".$voto."',descrizione='".$this->db->escape_string($descrizione)."',esperienza_estera='".$esperienza_estera."' WHERE id_form =".$id_form;

         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
}

?>
