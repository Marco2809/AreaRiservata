<?php
/*
class Database
{
    private $host = "localhost";
    private $db_name = "area_riservata";
    private $username = "root";
    private $password = "";
    public $dbconn;

    public function dbConnection()
    {

        $this->dbconn = null;

            $this->dbconn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

           if ($this->dbconn->connect_errno) {
    printf("Connect failed: %s\n", $this->dbconn->connect_error);
} else return $this->dbconn;

}
    }
 */

/*
  Questo piccolo script esegue la connessione al database test e legge i record dalla tabella catalogo.
  Utilizzo funzioni base php. Nessuna Estensione PEAR!
 */

class dbconnect {

    private $dbhost = null;
    private $dbuser = null;
    private $dbpass = null;
    private $dbname = null;
    public $db = null;

    public function __construct($db_host = "localhost", $db_user = 'adm_area', $db_pass = "Iniziale1$$", $db_name = "new_area_riservata") {
        $this->dbhost = $db_host;
        $this->dbuser = $db_user;
        $this->dbpass = $db_pass;
        $this->dbname = $db_name;
    }

    public function connect() {
        if (is_null($this->db)) {
            $this->db = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
            if (!$this->db) {
                die('Non riesco a connettermi: ' . mysql_error());
                return false;
            }
            mysql_select_db($this->dbname);
        }

        // return $this->db;
        return true;
    }

}

//dati di accesso in chiaro :)
/* $dbhost = 'localhost';
  $dbuser = 'root';
  $dbpass = '';
  $dbname = 'curriculum_db';

 */


?>
