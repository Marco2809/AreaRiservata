<?php
echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

echo ini_get('display_errors');
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/experience.class.php');
require_once('class/message.class.php');
$path = "assets/img/avatar/";

$database = new Database();
$conn = $database->dbConnection();
                          
                              
                             
if ($handle = opendir($path)) {

   //$i=0;
   while (false !== ($file = readdir($handle))) {

      if ($file != '.' && $file != '..') {

         
		  $cognome = explode(".",$file);
		 $sql_dati= 'SELECT * FROM anagrafica WHERE cognome= "'.$cognome[0].'"';
		  $result = $conn->query($sql_dati);
		  $obj = $result->fetch_object();
		  
		  echo $path.$file."-".$path.$obj->codice_fiscale.".png"."<br>";
		  
		  //rename($path.$file;$path.$obj->codice_fiscale.".png");
		  //return;
	   //echo $obj->codice_fiscale."<br>";
			
      }

   }

   if ($files == null) {

      echo "Directory vuota!!<br />\n";

   }

}

?>