<?php

class Database
{   
    private $host = "localhost";
    private $db_name = "new_area_riservata";
    private $username = "root";
    private $password = "mysql1989";
    public $dbconn;

    public function dbConnection()
    {

        $this->dbconn = null;    
        
		$this->dbconn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

		if ($this->dbconn->connect_errno) {
			printf("Connect failed: %s\n", $this->dbconn->connect_error);
		} else {
			$this->dbconn->set_charset("utf8");
			return $this->dbconn;
		}

	}
} 
 
?>