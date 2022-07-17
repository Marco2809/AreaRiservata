<?php
session_start();
require_once('dbconn.php');
class Certificazione
{
    public $db;
    //variabili tabella esperienza
    var $id;
    var $user_id;
    var $titolo_certificazione;
    var $cod_licenza;
    var $url;
    var $ente_certificante;
    var $da;
    var $a;


    public function __construct() {

        $database = new Database();
        $dbconn = $database->dbConnection();
        $this->db = $dbconn;
    }

    public function setCertificazione($user_id,$titolo_certificazione,$cod_licenza,$url,$ente_certificante,$da,$a){

         $sql= "INSERT INTO `certificazione` (
                                                    `id`,
                                                    `user_id`,
                                                    `titolo_certificazione`,
                                                    `ente_certificante`,
                                                    `cod_licenza`,
                                                    `url`,
                                                    `da`,
                                                    `a`)
                                        VALUES (
                                                NULL,
                                                '" . $user_id . "',
                                                '" . $this->db->escape_string($titolo_certificazione) . "',
                                                '" . $this->db->escape_string($ente_certificante) . "',
                                                '" . $this->db->escape_string($cod_licenza) . "',
                                                '" . $this->db->escape_string($url) . "',
                                                '" . $da . "',
                                                '" . $a . "')";
         $result=$this->db->query($sql);

         if($result){
           $userIdCreated = null;
           $stmt = $this->db->prepare("SELECT LAST_INSERT_ID()");
           $stmt->execute();
           $stmt->store_result();
           $stmt->bind_result($userIdCreated);
           $stmt->fetch();
           return $userIdCreated;
         } else return 'error';
    }

    public function delCertificazione($id){

         $sql="DELETE from certificazione WHERE id = '".$id."'";

         $result=$this->db->query($sql);
         if($result){
             return $sql;
         } else return 'error';
    }

    public function updateCertificazione($id,$titolo_certificazione,$cod_licenza,$url,$ente_certificante,$da,$a){

          $sql = "UPDATE certificazione

                           SET
                           titolo_certificazione = '" . $this->db->escape_string($titolo_certificazione) . "',
                           cod_licenza = '" .$this->db->escape_string($cod_licenza). "',
                           url = '" .$this->db->escape_string($url). "',
                           ente_certificante = '" .$this->db->escape_string($ente_certificante). "',
                           da = '" .$da. "',
                           a = '" .$a. "'
                           WHERE id ='" . $id . "'";
         $result=$this->db->query($sql);
         if($result){
             return 'ok';
         } else return 'error';
    }
}

?>
