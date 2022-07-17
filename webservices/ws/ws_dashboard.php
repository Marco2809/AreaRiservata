<?php
include('dbconn.php');
$conn = new dbconnect();
$r = $conn->connect();

public function getAllFromAnagrafica($username)
    {
        $query = "SELECT * FROM attivita WHERE giorno ='".date('d')."' AND mese ='".date('m')."' AND anno ='".date('Y')."' AND ora <='".date('h')."' AND minuti ='".date('Y')."' ";

        $query .= $username;
        $result_query = mysql_query($query, $this->db);
        if ($result_query)
        {
            while ($row = mysql_fetch_array($result_query, MYSQL_ASSOC))
            {
                $array_dati[] = $row;
            }
        }
        $str = json_encode($array_dati);
        return $str; // ritorna gli asset del magazzino padre e figli
    }

?>