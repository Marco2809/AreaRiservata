<?php

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

    public function __construct($db_host = "localhost", $db_user = 'adm_riservata', $db_pass = "Iniziale1$$", $db_name = "area_riservata") {
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

