<?php

header("content-type: application/json");
$voglio_laTabella = empty($_GET['tab_nome']) ? "" : $_GET['tab_nome'];

//ciao
include('dbconn.php');

$conn = new dbconnect();
$r = $conn->connect();

if (!empty($voglio_laTabella))
{
    switch ($voglio_laTabella)
    {
        case "anagrafica":
            $query = "SELECT * FROM anagrafica";
            break;
        
         case "login":
            $query = "SELECT * FROM login";
            break;
    }
}

$result_query = mysql_query($query, $conn->db);
$array_dati = array();
if (!$result_query)
{
    die('Errore di inserimento dati 3: ' . mysql_error());
} else
{
    while ($row = mysql_fetch_array($result_query, MYSQL_ASSOC))
    {
        $array_dati[] = $row;
    }
}
echo $_GET['callback'] . "(" . json_encode($array_dati) . ");";
