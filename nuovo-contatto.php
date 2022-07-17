<?php
session_start();
//ciao
include('dbconn.php');

$conn = new dbconnect();
$r = $conn->connect();

//Gestione errori.
//ini_set('display_errors','On');
//error_reporting(E_ALL);
//echo $_SESSION['user_idd'];

if (!isset($_SESSION['username']))
    header("location: login.htm.php");

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
	
} else {
    $action = '';
}

$cod_user = $_SESSION['user_idd'];
$cod_anagr = $_SESSION['user_idd'];
$alert = "";

if(isset($_POST["submit"])&& $_POST["submit"]=="Carica Immagine")
    {
  
$target_dir = "/var/www/html/form/img/CV/";
$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = $_POST["id_cv"] . '.' . end($temp);
$target_file = $target_dir . $newfilename;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
// Check if file already exists
/*if (file_exists($target_file)) {
    $alert .= "<p align='center' style='color:red'>Sorry, file already exists.</p>";
    $uploadOk = 0;
}*/
// Check file size

if ($_FILES["fileToUpload"]["size"] > 1000000) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file è troppo grande.</p>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, sono permessi solo file JPG, JPEG, PNG & GIF.</p>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file non è stato caricato.</p>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $sql_conoscenza = "UPDATE anagrafica
						
						   SET 
						   img_name = '" . $newfilename . "'
						   WHERE user_id ='" . $cod_user . "'";
		 
		  $result_conoscenza = mysql_query($sql_conoscenza, $conn->db);
		  
		  if (!$result_conoscenza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } 
        $alert .= "<p align='center' style='color:red'>Il file è stato caricato con successo.</p>";

    } else {
         $alert .= "<p align='center' style='color:red'>Spiacenti, c'è stato un errore nel caricamento del file.</p>";
    }
}
if(isset($alert) && $alert!="")
		{
		 echo $alert;
		}
}
switch ($action) {

	case 'Aggiorna Password':
	
	if (isset($_REQUEST['user_idd']) && $_REQUEST['user_idd'] != '') {
            $user_idd = $_REQUEST['user_idd'];
        } 

         if (isset($_REQUEST['password']) && $_REQUEST['password'] != '') {
            $password = $_REQUEST['password'];
        } else {
			$alert .= "<p align='center' style='color:red'>Attenzione: inserire la password!</p>";

        }
          if (isset($_REQUEST['c_password']) && $_REQUEST['c_password'] != '') {
            $c_password = $_REQUEST['c_password'];
        } else {

        	$alert .= "<p align='center' style='color:red'>Attenzione: inserire la password di conferma!</p>";
        }
	
if(isset($alert) && $alert!="")
		{
		 echo $alert;
		 	break;
		}

	if($password!="" && ($password==$c_password)) {
$sql_esperienza = "UPDATE login
						
						   SET 
						   
						   password = '" . $password . "'
						   
						   WHERE user_idd ='" . $user_idd . "'";
		 
		  $result_esperienza = mysql_query($sql_esperienza, $conn->db);
		  
		  if (!$result_esperienza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
       }
        else if($password!=$c_password)
        {
        		$alert = "<p align='center' style='color:red'>Le password non corrispondono!</p>";
        }	

		if(isset($alert) && $alert!="")
		{
		 echo $alert;
		}
		
	break;

	case 'Aggiorna Istruzione':
	
	if (isset($_REQUEST['id_form']) && $_REQUEST['id_form'] != '') {
            $id_form = $_REQUEST['id_form'];
        } 

		if (isset($_REQUEST['istituto']) && $_REQUEST['istituto'] != '' && $_REQUEST['istituto'] != 'Altro' ) {
            $istituto = $_REQUEST['istituto'];
        } 	
		else if (isset($_REQUEST['istituto_altro']) && $_REQUEST['istituto_altro'] != '') {
            $istituto = $_REQUEST['istituto_altro'];
		}
		else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il tipo di istituto!</p>";
        }
		
        if (isset($_REQUEST['laurea']) && $_REQUEST['laurea'] != '') {
            $laurea = $_REQUEST['laurea'];
        } else {
            $laurea="";
            
        }
		
		if (isset($_REQUEST['titolo']) && $_REQUEST['titolo'] != '') {
            $titolo = $_REQUEST['titolo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il titolo di studi!</p>";
            
        }

       if (isset($_REQUEST['corso']) && $_REQUEST['corso'] != '') {
            $corso = $_REQUEST['corso'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il corso di studi!</p>";
        }

         if ((isset($_REQUEST['da_giorno_formazione']) && $_REQUEST['da_giorno_formazione'] != '') && (isset($_REQUEST['da_mese_formazione']) && $_REQUEST['da_mese_formazione'] != '') && (isset($_REQUEST['da_anno_formazione']) && $_REQUEST['da_anno_formazione'] != '')) {
            $da_formazione = $_REQUEST['da_anno_formazione'] . "-" . $_REQUEST['da_mese_formazione'] . "-" . $_REQUEST['da_giorno_formazione'];
        } 

       if (isset($_REQUEST['in_corso'])) {
            $a_formazione = "0000-00-00";
		} else if ((isset($_REQUEST['a_giorno_formazione']) && $_REQUEST['a_giorno_formazione'] != '') && (isset($_REQUEST['a_mese_formazione']) && $_REQUEST['a_mese_formazione'] != '') && (isset($_REQUEST['a_anno_formazione']) && $_REQUEST['a_anno_formazione'] != '')) {
            $a_formazione = $_REQUEST['a_anno_formazione'] . "-" . $_REQUEST['a_mese_formazione'] . "-" . $_REQUEST['a_giorno_formazione'];
        }  else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la fine della formazione!</p>";
            
        }

        if (isset($_REQUEST['descrizione']) && $_REQUEST['descrizione'] != '') {
            $descrizione = $_REQUEST['descrizione'];
        } else {
			$descrizione = "";	
		}
		
        if (isset($_REQUEST['esperienza_estera']) && $_REQUEST['esperienza_estera'] != '' && $_REQUEST['esperienza_estera'] != 'Esperienza Estera') {
            $esperienza_estera = $_REQUEST['esperienza_estera'];
        } else if($_REQUEST['esperienza_estera']=="Esperienza Estera") {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire lo stato dell'esperienza estera!</p>";
           
        }
		
		if (isset($_REQUEST['voto'])) {
            $voto = $_REQUEST['voto'];
        } 
        
		if(isset($alert) && $alert!="")
		{
		 echo $alert;
		  break;
		}
		
        $sql_esperienza = "UPDATE formazione
						
						   SET 
						   
						   istituto = '" . $istituto . "',
                 		   titolo = '" .$titolo. "',
				           corso = '" .$corso. "',
						   da = '" .$da_formazione ."',
						   a = '" .$a_formazione. "',
						   voto = '" .$voto. "',
						   laurea = '" .$laurea."',
						   descrizione = '" .$descrizione. "',
						   esperienza_estera= '" .$esperienza_estera. "'
						   
						   WHERE id_form ='" . $id_form . "'";
		 
		  $result_esperienza = mysql_query($sql_esperienza, $conn->db);
		  
		  if (!$result_esperienza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		if(isset($alert) && $alert!="")
		{
		 echo $alert;
		}
		
	break;
	
	case 'Aggiorna Esperienza':

		if (isset($_REQUEST['id_esp']) && $_REQUEST['id_esp'] != '') {
            $id_esp = $_REQUEST['id_esp'];
        }

		if (isset($_REQUEST['azienda']) && $_REQUEST['azienda'] != '') {
            $azienda = $_REQUEST['azienda'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'azienda!</p>";
            
        }

        if (isset($_REQUEST['area']) && $_REQUEST['area'] != '') {
            $area = $_REQUEST['area'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'area di competenza!</p>";
            
        }

          if (isset($_REQUEST['sub_area']) && $_REQUEST['sub_area'] != '') {
            $sub_area = $_REQUEST['sub_area'];
        } else {
            $sub_area = "";
            
        }
		
		if (isset($_REQUEST['ruolo']) && $_REQUEST['ruolo'] != '') {
            $ruolo = $_REQUEST['ruolo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il ruolo ricoperto in azienda!</p>";
            
        }

        if (isset($_REQUEST['indirizzo']) && $_REQUEST['indirizzo'] != '') {
            $indirizzo = $_REQUEST['indirizzo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'indirizzo!</p>";
            
        }

        if (isset($_REQUEST['mansione']) && $_REQUEST['mansione'] != '') {
            $mansione = $_REQUEST['mansione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la mansione!</p>";
            
        }
		

        if ((isset($_REQUEST['da_giorno_esperienza']) && $_REQUEST['da_giorno_esperienza'] != '') && (isset($_REQUEST['da_mese_esperienza']) && $_REQUEST['da_mese_esperienza'] != '') && (isset($_REQUEST['da_anno_esperienza']) && $_REQUEST['da_anno_esperienza'] != '')) {
            $da_esperienza = $_REQUEST['da_anno_esperienza'] . "-" . $_REQUEST['da_mese_esperienza'] . "-" . $_REQUEST['da_giorno_esperienza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'inizio dell'esperienza!</p>";
            
        }

		if(isset($_REQUEST['attuale']) && $_REQUEST['attuale'] == 'Si'){
			$a_esperienza = "0000-00-00";
		} else if ((isset($_REQUEST['a_giorno_esperienza']) && $_REQUEST['a_giorno_esperienza'] != '') && (isset($_REQUEST['a_mese_esperienza']) && $_REQUEST['a_mese_esperienza'] != '') && (isset($_REQUEST['a_anno_esperienza']) && $_REQUEST['a_anno_esperienza'] != '')) {
            $a_esperienza = $_REQUEST['a_anno_esperienza'] . "-" . $_REQUEST['a_mese_esperienza'] . "-" . $_REQUEST['a_giorno_esperienza'];
        }  else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la fine dell'esperienza!</p>";
            
        }

        if (isset($_REQUEST['note']) && $_REQUEST['note'] != '') {
            $note = $_REQUEST['note'];
        } else {
			$note = "";	
		}
		
        if (isset($_REQUEST['attuale']) && $_REQUEST['attuale'] != '') {
            $attuale = $_REQUEST['attuale'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire attuale!</p>"; 
        }
		  
		if(isset($alert) && $alert!="")
		{
		 echo $alert;
		  break;
		}
		
        $sql_esperienza = "UPDATE esperienza
						
						   SET 
						   
						   azienda = '" . $azienda . "',
                 		   indirizzo = '" .$indirizzo. "',
				           mansione = '" .$mansione. "',
						   ruolo = '" .$ruolo. "',
						   da = '" .$da_esperienza ."',
						   a = '" .$a_esperienza. "',
						   attuale = '" .$attuale. "',
						   area = '" .$area. "',
						   sub_area = '" .$sub_area. "',
						   note = '" .mysql_real_escape_string($note). "'
						   
						   
						   WHERE id_esp ='" . $id_esp . "'";
		 
		  $result_esperienza = mysql_query($sql_esperienza, $conn->db);
		  
		  if (!$result_esperienza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		if(isset($alert) && $alert!="")
		{
		 echo $alert;
		}
		
	break;
	
	case 'Aggiorna Anagrafica':

		if (isset($_REQUEST['nome']) && $_REQUEST['nome'] != '') {
            $nome = $_REQUEST['nome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il nome!</p>";
        }
		
		if (isset($_REQUEST['codice_fiscale']) && $_REQUEST['codice_fiscale'] != '') {
            $codice_fiscale = $_REQUEST['codice_fiscale'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il codice fiscale!</p>";
        }
		

        if (isset($_REQUEST['cognome']) && $_REQUEST['cognome'] != '') {
            $cognome = $_REQUEST['cognome'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il cognome!</p>";
        }

        if (isset($_REQUEST['indirizzo_residenza']) && $_REQUEST['indirizzo_residenza'] != '') {
            $indirizzo_residenza = $_REQUEST['indirizzo_residenza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'indirizzo di residenza!</p>";
        }

        if (isset($_REQUEST['luogo_nascita']) && $_REQUEST['luogo_nascita'] != '') {
            $luogo_nascita = $_REQUEST['luogo_nascita'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il luogo di nascita!</p>";
        }

        if (isset($_REQUEST['indirizzo_domicilio']) && $_REQUEST['indirizzo_domicilio'] != '') {
            $indirizzo_domicilio = $_REQUEST['indirizzo_domicilio'];
        } else {
            $indirizzo_domicilio = "";
		}

        if ((isset($_REQUEST['giorno_anagrafica']) && $_REQUEST['giorno_anagrafica'] != '') && (isset($_REQUEST['mese_anagrafica']) && $_REQUEST['mese_anagrafica'] != '') && (isset($_REQUEST['anno_anagrafica']) && $_REQUEST['anno_anagrafica'] != '')) {
            $data_nascita = $_REQUEST['anno_anagrafica'] . "-" . $_REQUEST['mese_anagrafica'] . "-" . $_REQUEST['giorno_anagrafica'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la data di nascita!</p>";
        }

        if (isset($_REQUEST['citta_residenza']) && $_REQUEST['citta_residenza'] != '') {
            $citta_residenza = $_REQUEST['citta_residenza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la propria città di residenza!</p>";
        }

        if (isset($_REQUEST['citta_domicilio']) && $_REQUEST['citta_domicilio'] != '') {
            $citta_domicilio = $_REQUEST['citta_domicilio'];
        } else {
            $citta_domicilio = "";
        }
		
		if(isset($alert)&&$alert!="")
		{
		 echo $alert;
		 break;	
		}
		
        $sql_conoscenza = "UPDATE anagrafica
						
						   SET 
						   nome = '" . $nome . "',
                 		   cognome = '" .$cognome. "',
				           luogo_nascita = '" .$luogo_nascita. "',
						   data_nascita = '" .$data_nascita. "',
						   citta_residenza = '" .$citta_residenza. "',
						   indirizzo_residenza = '" .$indirizzo_residenza. "',
						   citta_domicilio = '" .$citta_domicilio. "',
						   indirizzo_domicilio = '" .$indirizzo_domicilio. "',
						   codice_fiscale  = '" .strtoupper($codice_fiscale). "'
						   WHERE user_id ='" . $cod_user . "'";
		 
		  $result_conoscenza = mysql_query($sql_conoscenza, $conn->db);
		  
		  if (!$result_conoscenza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert))
		{
		 echo $alert;	
		}
		
	break;
	
	case 'Elimina Competenza':
	
	$sq_elimina_con = "DELETE FROM conoscenze
						   WHERE id ='" . $_POST['id_con'] . "'";
		 
		  $result_elimina_con = mysql_query($sq_elimina_con, $conn->db);
		  
		  if (!$result_elimina_con) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert))
		{
		 echo $alert;	
		}
		
	break;
        
        case 'Elimina Istruzione':
	
	$sq_elimina_con = "DELETE FROM formazione
						   WHERE id_form ='" . $_POST['id_form'] . "'";
		 
		  $result_elimina_con = mysql_query($sq_elimina_con, $conn->db);
		  
		  if (!$result_elimina_con) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert))
		{
		 echo $alert;	
		}
		
	break;
        
        case 'Elimina Corso':
	
	$sq_elimina_con = "DELETE FROM corsi
						   WHERE id_corso ='" . $_POST['id_corso'] . "'";
		 
		  $result_elimina_con = mysql_query($sq_elimina_con, $conn->db);
		  
		  if (!$result_elimina_con) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert))
		{
		 echo $alert;	
		}
		
	break;
	
	
	case 'Elimina Esperienza':
	
	$sq_elimina_esp = "DELETE FROM esperienza
						   WHERE id_esp ='" . $_POST['id_esp'] . "'";
		 
		  $result_elimina_esp = mysql_query($sq_elimina_esp, $conn->db);
		  
		  if (!$result_elimina_esp) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		}
		
	break;
	
	case 'Elimina Certificazione':
	
	$sq_elimina_con = "DELETE FROM certificazione
						   WHERE id ='" . $_POST['id_cer'] . "'";
		 
		  $result_elimina_con = mysql_query($sq_elimina_con, $conn->db);
		  
		  if (!$result_elimina_con) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		}
		
	break;
	
	
	case 'Aggiorna Certificazione':

		if (isset($_REQUEST['id_cer']) && $_REQUEST['id_cer'] != '') {
            $id = $_REQUEST['id_cer'];
		}
		
        if (isset($_REQUEST['titolo_certificazione']) && $_REQUEST['titolo_certificazione'] != '') {
            $titolo_certificazione = $_REQUEST['titolo_certificazione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il titolo!</p>";break;
        }

        if (isset($_REQUEST['ente_certificante']) && $_REQUEST['ente_certificante'] != '') {
            $ente_certificante = $_REQUEST['ente_certificante'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l' ente certificante!</p>"; break;
        }

        if (isset($_REQUEST['cod_licenza']) && $_REQUEST['cod_licenza'] != '') {
            $cod_licenza = $_REQUEST['cod_licenza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il codice licenza!</p>";break;
        }

        if (isset($_REQUEST['url']) && $_REQUEST['url'] != '') {
            $url = $_REQUEST['url'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il sito utilizzato!</p>";break;
        }

        if ((isset($_REQUEST['da_giorno_certificazione']) && $_REQUEST['da_giorno_certificazione'] != '') && (isset($_REQUEST['da_mese_certificazione']) && $_REQUEST['da_mese_certificazione'] != '') && (isset($_REQUEST['da_anno_certificazione']) && $_REQUEST['da_anno_certificazione'] != '')) {
            $da_certificazione = $_REQUEST['da_anno_certificazione'] . "-" . $_REQUEST['da_mese_certificazione'] . "-" . $_REQUEST['da_giorno_certificazione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'inizio della certificazione!</p>";
        }
           
       if (isset($_REQUEST['scadenza'])) {
            $a_certificazione = "0000-00-00";
       }
       else if ((isset($_REQUEST['a_giorno_certificazione']) && $_REQUEST['a_giorno_certificazione'] != '') && (isset($_REQUEST['a_mese_certificazione']) && $_REQUEST['a_mese_certificazione'] != '') && (isset($_REQUEST['a_anno_certificazione']) && $_REQUEST['a_anno_certificazione'] != '')) {
            $a_certificazione = $_REQUEST['a_anno_certificazione'] . "-" . $_REQUEST['a_mese_certificazione'] . "-" . $_REQUEST['a_giorno_certificazione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la fine della certificazione!</p>";
        }
		
		if(isset($alert) && $alert!="")
		{
		 echo $alert;
		 break;	
		}
		
        $sql_conoscenza = "UPDATE certificazione
						
						   SET 
						   titolo_certificazione = '" . $titolo_certificazione . "',
                 		   cod_licenza = '" .$cod_licenza. "',
				           url = '" .$url. "',
						   ente_certificante = '" .$ente_certificante. "',
						   url = '" .$url. "',
						   da = '" .$da_certificazione. "',
						   a = '" .$a_certificazione. "'
						   WHERE id ='" . $id . "'";
		 
		  $result_conoscenza = mysql_query($sql_conoscenza, $conn->db);
		  
		  if (!$result_conoscenza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		}
		
	break;
	
	
	case 'Aggiorna Competenza':

		if (isset($_REQUEST['id_con']) && $_REQUEST['id_con'] != '') {
            $id = $_REQUEST['id_con'];
		}
		
        if (isset($_REQUEST['livello']) && $_REQUEST['livello'] != '') {
            $livello = $_REQUEST['livello'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il livello!</p>";
        }
		
        if (isset($_REQUEST['tipologia']) && $_REQUEST['tipologia'] != '') {
            $tipologia = $_REQUEST['tipologia'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la tipologia!</p>";
        }
		if (isset($_REQUEST['descrizione']) && $_REQUEST['descrizione'] != '') {
            $descrizione = $_REQUEST['descrizione'];
		} else {
			$alert .= "<p align='center' style='color:red'>Attenzione: inserire la descrizione!</p>";	
		}
		
		if(isset($alert)&&$alert!="")
		{
		 echo $alert;
		 break;	
		}
		
		
        $sql_conoscenza = "UPDATE conoscenze
						
						   SET 
						   livello = '" . $livello . "',
                 		   tipologia = '" .$tipologia. "',
				           descrizione = '" .$descrizione. "'
						   WHERE id ='" . $id . "'";
		 
		  $result_conoscenza = mysql_query($sql_conoscenza, $conn->db);
		  
		  if (!$result_conoscenza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert)&&$alert!="")
		{
		 echo $alert;	
		}
		
	break;
        
        case 'Aggiorna Corso':

		if (isset($_REQUEST['id_corso']) && $_REQUEST['id_corso'] != '') {
            $id_corso = $_REQUEST['id_corso'];
		}
		
        if (isset($_REQUEST['tipo']) && $_REQUEST['tipo'] != '') {
            $tipo = $_REQUEST['tipo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il tipo di corso!</p>";
        }
		
        if (isset($_REQUEST['descrizione']) && $_REQUEST['descrizione'] != '') {
            $descrizione = $_REQUEST['descrizione'];
        } 
		
		if(isset($alert)&&$alert!="")
		{
		 echo $alert;
		 break;	
		}
		
		
        $sql_conoscenza = "UPDATE corsi
						
						   SET 
						   tipo = '" . $tipo . "',
                                                   descrizione = '" .mysql_real_escape_string($descrizione). "'
						   WHERE id_corso ='" . $id_corso . "'";
		 
		  $result_conoscenza = mysql_query($sql_conoscenza, $conn->db);
		  
		  if (!$result_conoscenza) {
			  
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
			
			$alert = "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		
		if(isset($alert)&&$alert!="")
		{
		 echo $alert;	
		}
		
	break;
	
    
    case 'Salva Istruzione':
	
		if (isset($_REQUEST['laurea']) && $_REQUEST['laurea'] != '') {
            $laurea = $_REQUEST['laurea'];
        } else {
            $laurea="";
            
        }
	
        if (isset($_REQUEST['istituto']) && $_REQUEST['istituto'] != '' && $_REQUEST['istituto'] != 'Altro' ) {
            $istituto = $_REQUEST['istituto'];
        } 	
		else if (isset($_REQUEST['istituto_altro']) && $_REQUEST['istituto_altro'] != '') {
            $istituto = $_REQUEST['istituto_altro'];
		}
		else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il tipo di istituto!</p>";
        }
	
        if ((isset($_REQUEST['da_giorno_formazione']) && $_REQUEST['da_giorno_formazione'] != '') && (isset($_REQUEST['da_mese_formazione']) && $_REQUEST['da_mese_formazione'] != '') && (isset($_REQUEST['da_anno_formazione']) && $_REQUEST['da_anno_formazione'] != '')) {
            $da_formazione = $_REQUEST['da_anno_formazione'] . "-" . $_REQUEST['da_mese_formazione'] . "-" . $_REQUEST['da_giorno_formazione'];
        } 

        if (isset($_REQUEST['in_corso'])) {
            $a_formazione = "0000-00-00";
		}
        else if ((isset($_REQUEST['a_giorno_formazione']) && $_REQUEST['a_giorno_formazione'] != '') && (isset($_REQUEST['a_mese_formazione']) && $_REQUEST['a_mese_formazione'] != 'mese') && $_REQUEST['a_mese_formazione'] != 'Mese' && (isset($_REQUEST['a_anno_formazione']) && $_REQUEST['a_anno_formazione'] != '' && $_REQUEST['a_anno_formazione'] != 'Anno' )) {
            $a_formazione = $_REQUEST['a_anno_formazione'] . "-" . $_REQUEST['a_mese_formazione'] . "-" . $_REQUEST['a_giorno_formazione'];
        } else  {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la fine della Formazione!</p>";
        }

        if (isset($_REQUEST['titolo']) && $_REQUEST['titolo'] != '') {
            $titolo = $_REQUEST['titolo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il titolo della Formazione!</p>";
        }

        if (isset($_REQUEST['corso']) && $_REQUEST['corso'] != '') {
            $corso = $_REQUEST['corso'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il corso di studi!</p>";
        }

        if (isset($_REQUEST['voto'])) {
            $voto = $_REQUEST['voto'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il voto!</p>";
        }

        if (isset($_REQUEST['descrizione']) && $_REQUEST['descrizione'] != '') {
            $descrizione = $_REQUEST['descrizione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la descrizione della Formazione!</p>";
        }

        if (isset($_REQUEST['esperienza_estera']) && $_REQUEST['esperienza_estera'] != '' && $_REQUEST['esperienza_estera'] != 'Esperienza Estera') {
            $esperienza_estera = $_REQUEST['esperienza_estera'];
        } else if($_REQUEST['esperienza_estera']=="Esperienza Estera") {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire lo stato dell'esperienza estera!</p>";
           
        }
	
		if(isset($alert) && $alert!='')
		{
		
		 echo $alert;	
		 break;
		}
		
		

        $sql_formazione = "INSERT INTO `formazione` (   
													`id_form`,
													`user_id`,
													`istituto`, 
													`da`,
													`a`,
													`titolo`,
													`corso`,
													`laurea`,
													`voto`,
													`descrizione`,
													`esperienza_estera`
													)
										VALUES (
												NULL,
												'" . $cod_anagr . "',
												'" . mysql_real_escape_string($istituto) . "',
												'" . $da_formazione . "',
												'" . $a_formazione . "',
												'" . mysql_real_escape_string($titolo) . "',
												'" . mysql_real_escape_string($corso) . "',
												'" . mysql_real_escape_string($laurea) . "',
												'" . mysql_real_escape_string($voto) . "',
												'" . mysql_real_escape_string($descrizione) . "',
												'" . mysql_real_escape_string($esperienza_estera) . "')";

        $result_formazione = mysql_query($sql_formazione, $conn->db);

        if (!$result_formazione) {
            die('Errore di inserimento dati : ' . mysql_error());
			
        } else {
				
            $alert = "<p align='center' style='color:red'>Salvataggio effettuato con successo!</p>";
        }
		if(isset($alert) && $alert!='')
		{
		 echo $alert;	
		}
		
        break;

 case 'Salva Esperienza':

        if (isset($_REQUEST['azienda']) && $_REQUEST['azienda'] != '') {
            $azienda = $_REQUEST['azienda'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'azienda!</p>";
            
        }


        if (isset($_REQUEST['area']) && $_REQUEST['area'] != '') {
            $area = $_REQUEST['area'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'area di competenza!</p>";
            
        }


        if (isset($_REQUEST['sub_area']) && $_REQUEST['sub_area'] != '') {
            $sub_area = $_REQUEST['sub_area'];
        } else {
           $sub_area = "";
            
        }

		
		if (isset($_REQUEST['ruolo']) && $_REQUEST['ruolo'] != '') {
            $ruolo = $_REQUEST['ruolo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il ruolo ricoperto in azienda!</p>";
            
        }

        if (isset($_REQUEST['indirizzo']) && $_REQUEST['indirizzo'] != '') {
            $indirizzo = $_REQUEST['indirizzo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'indirizzo!</p>";
            
        }

        if (isset($_REQUEST['mansione']) && $_REQUEST['mansione'] != '') {
            $mansione = $_REQUEST['mansione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la mansione!</p>";
            
        }

        if ((isset($_REQUEST['da_giorno_esperienza']) && $_REQUEST['da_giorno_esperienza'] != '') && (isset($_REQUEST['da_mese_esperienza']) && $_REQUEST['da_mese_esperienza'] != '') && (isset($_REQUEST['da_anno_esperienza']) && $_REQUEST['da_anno_esperienza'] != '' && $_REQUEST['da_anno_esperienza'] != 'Anno' && $_REQUEST['da_mese_esperienza'] != 'Mese')) {
            $da_esperienza = $_REQUEST['da_anno_esperienza'] . "-" . $_REQUEST['da_mese_esperienza'] . "-" . $_REQUEST['da_giorno_esperienza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'inizio dell'esperienza!</p>";
            
        }
		
		if(isset($_REQUEST['attuale']) && $_REQUEST['attuale'] == 'Si'){
			$a_esperienza = "0000-00-00";
		} else if ((isset($_REQUEST['a_giorno_esperienza']) && $_REQUEST['a_giorno_esperienza'] != '') && (isset($_REQUEST['a_mese_esperienza']) && $_REQUEST['a_mese_esperienza'] != '' && $_REQUEST['a_mese_esperienza'] != 'Mese' && $_REQUEST['a_anno_esperienza'] != 'Anno') && (isset($_REQUEST['a_anno_esperienza']) && $_REQUEST['a_anno_esperienza'] != '')) {
            $a_esperienza = $_REQUEST['a_anno_esperienza'] . "-" . $_REQUEST['a_mese_esperienza'] . "-" . $_REQUEST['a_giorno_esperienza'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la fine dell'esperienza!</p>";    
        }
		
        if (isset($_REQUEST['note']) && $_REQUEST['note'] != '') {
            $note = $_REQUEST['note'];
        } else {
			$note = "";	
		}
		
        if (isset($_REQUEST['attuale']) && $_REQUEST['attuale'] != '') {
            $attuale = $_REQUEST['attuale'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire attuale!</p>";
           
        }
		if(isset($alert) && $alert!="")
		{
		 echo $alert;
		  break;
		}


        $sql_esperienza = "INSERT INTO `esperienza` (  
													`id_esp`,
													`user_id`,
													`azienda`,
													`indirizzo`,
													`mansione`,
													`ruolo`,
													`da`,
													`a`,
													`attuale`,
													`area`,
													`sub_area`,
													`note`
													)
										VALUES (
												NULL,
												'" . $cod_anagr . "',
												'" . mysql_real_escape_string($azienda) . "',
												'" . mysql_real_escape_string($indirizzo) . "',
												'" . mysql_real_escape_string($mansione) . "',
												'" . mysql_real_escape_string($ruolo) . "',
												'" . $da_esperienza . "',
												'" . $a_esperienza . "',
												'" . mysql_real_escape_string($attuale) . "',
												'" . mysql_real_escape_string($area) . "',
												'" . mysql_real_escape_string($sub_area) . "',
												'" . mysql_real_escape_string($note) . "')";

        $result_esperienza = mysql_query($sql_esperienza, $conn->db);

        if (!$result_esperienza) {
            die('Errore di inserimento dati : ' . mysql_error());
        } else {
          
            $alert .= "<p align='center' style='color:red'>Aggiornamento avvenuto con successo!</p>";
        }
		if(isset($alert))
		{
		 echo $alert;	
		}

        break;

    case 'Salva Competenza':


        if (isset($_REQUEST['tipologia']) && $_REQUEST['tipologia'] != '') {
            $tipologia = $_REQUEST['tipologia'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire la tipologia !</p>";
        }

        if (isset($_REQUEST['descrizione'])) {
            $descrizione = $_REQUEST['descrizione'];
        } 

        if (isset($_REQUEST['livello']) && $_REQUEST['livello'] != '') {
            $livello = $_REQUEST['livello'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il livello!</p>";
        }
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		 break;
		}
        


        $sql_conoscenze = "INSERT INTO `conoscenze` (   
													`user_id`,
													`tipologia`,
													`descrizione`,
													`livello`
												)
										VALUES (
												'" . $cod_anagr . "',
												'" . mysql_real_escape_string($tipologia) . "',
												'" . mysql_real_escape_string($descrizione) . "',
												'" . mysql_real_escape_string($livello) . "'
												)";


        //echo $sql;
        //die($sql);

        $result_conoscenze = mysql_query($sql_conoscenze, $conn->db);

        if (!$result_conoscenze) {
            die('Errore di inserimento dati : ' . mysql_error());
        } else {

            $alert = "<p align='center' style='color:red'>Salvataggio effettuato con successo!</p>";
        }
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		}
        break;
        
        case 'Salva Corso':


        if (isset($_REQUEST['id_corso']) && $_REQUEST['id_corso'] != '') {
            $id_corso = $_REQUEST['id_corso'];
		}
		
        if (isset($_REQUEST['tipo']) && $_REQUEST['tipo'] != '') {
            $tipo = $_REQUEST['tipo'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il tipo di corso!</p>";
        }
		
        if (isset($_REQUEST['descrizione'])) {
            $descrizione = $_REQUEST['descrizione'];
        } 

		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		 break;
		}
        


        $sql_conoscenze = "INSERT INTO `corsi` (                                                        `id_corso`,
													`user_id`,
													`tipo`,
													`descrizione`
												)
										VALUES (        
                                                                                                NULL,
												'" . $cod_anagr . "',
												'" . mysql_real_escape_string($tipo) . "',
												'" . mysql_real_escape_string($descrizione) . "'
												)";


        //echo $sql;
        //die($sql);

        $result_conoscenze = mysql_query($sql_conoscenze, $conn->db);

        if (!$result_conoscenze) {
            die('Errore di inserimento dati : ' . mysql_error());
        } else {

            $alert = "<p align='center' style='color:red'>Salvataggio effettuato con successo!</p>";
        }
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		}
        break;
		
    case 'Salva Certificazione':


        if (isset($_REQUEST['titolo_certificazione']) && $_REQUEST['titolo_certificazione'] != '') {
            $titolo_certificazione = $_REQUEST['titolo_certificazione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il titolo della certificazione!</p>";
        }

        if (isset($_REQUEST['ente_certificante']) && $_REQUEST['ente_certificante'] != '') {
            $ente_certificante = $_REQUEST['ente_certificante'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l' ente certificante!</p>";
        }

        if (isset($_REQUEST['cod_licenza'])) {
            $cod_licenza = $_REQUEST['cod_licenza'];
        }

        if (isset($_REQUEST['url'])) {
            $url = $_REQUEST['url'];
        } 

        if ((isset($_REQUEST['da_giorno_certificazione']) && $_REQUEST['da_giorno_certificazione'] != '') && (isset($_REQUEST['da_mese_certificazione']) && $_REQUEST['da_mese_certificazione'] != '') && (isset($_REQUEST['da_anno_certificazione']) && $_REQUEST['da_anno_certificazione'] != 'Anno' && $_REQUEST['a_mese_certificazione'] != 'Mese' && $_REQUEST['da_mese_certificazione'] != 'Mese'&& $_REQUEST['a_anno_certificazione'] != 'Anno'  && $_REQUEST['da_anno_certificazione'] != '')) {
            $da_certificazione = $_REQUEST['da_anno_certificazione'] . "-" . $_REQUEST['da_mese_certificazione'] . "-" . $_REQUEST['da_giorno_certificazione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'inizio della certificazione!</p>";
        }

        if (isset($_REQUEST['scadenza'])) {
            $a_formazione = "0000-00-00";
        }
        else if ((isset($_REQUEST['a_giorno_certificazione']) && $_REQUEST['a_giorno_certificazione'] != '') && (isset($_REQUEST['a_mese_certificazione']) && $_REQUEST['a_mese_certificazione'] != '') && (isset($_REQUEST['a_anno_certificazione']) && $_REQUEST['a_anno_certificazione'] != '')) {
            $a_certificazione = $_REQUEST['a_anno_certificazione'] . "-" . $_REQUEST['a_mese_certificazione'] . "-" . $_REQUEST['a_giorno_certificazione'];
        } 
		
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		 break;
		}
        $sql_certificazione = "INSERT INTO `certificazione` (  
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
												'" . $cod_anagr . "',
												'" . mysql_real_escape_string($titolo_certificazione) . "',
												'" . mysql_real_escape_string($ente_certificante) . "',
												'" . mysql_real_escape_string($cod_licenza) . "',
												'" . mysql_real_escape_string($url) . "',
												'" . $da_certificazione . "',
												'" . $a_certificazione . "')";


        $result_certificazione = mysql_query($sql_certificazione, $conn->db);

        if (!$result_certificazione) {
            die('Errore di inserimento dati : ' . mysql_error());
        } else {

            $alert= "<p align='center' style='color:red'>Salvataggio effettuato con successo!</p>";
        }
		
		if(isset($alert) && $alert!="")
		{
		 echo $alert;	
		}
		
        break;
}
//echo isset($alert) ? $alert : '';

// Gestione info database 

if ($cod_user != 0) {

    $sql3_anagrafica = "SELECT 
							nome, 
							cognome, 
							luogo_nascita, 
							data_nascita, 
							citta_residenza,
							indirizzo_residenza,
							citta_domicilio,
							indirizzo_domicilio,
							codice_fiscale FROM anagrafica WHERE user_id=" . $cod_user;


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);

    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row3 = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $nome = $row3['nome'];
            $cognome = $row3['cognome'];
            $luogo_nascita = $row3['luogo_nascita'];
            $citta_residenza = $row3['citta_residenza'];
            $indirizzo_res = $row3['indirizzo_residenza'];
            $data_nascita = $row3['data_nascita'];
            $citta_domicilio = $row3['citta_domicilio'];
            $indirizzo_dom = $row3['indirizzo_domicilio'];
            $codice_fiscale = $row3['codice_fiscale'];
        }
    }
    $data_nascita = @explode('-', $data_nascita);
}
//------

if ($cod_anagr != 0) {
    $sql3_formazione = "SELECT 
							istituto, 
							da, 
							a, 
							titolo,
							corso,
							voto,
							descrizione,
							esperienza_estera FROM formazione WHERE user_id=" . $cod_anagr;


    $result3_formazione = mysql_query($sql3_formazione, $conn->db);

    if (!$result3_formazione) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row3 = mysql_fetch_array($result3_formazione, MYSQL_ASSOC)) {
            $istituto = $row3['istituto'];
            $da_formazione = $row3['da'];
            $a_formazione = $row3['a'];
            $titolo = $row3['titolo'];
            $corso = $row3['corso'];
            $voto = $row3['voto'];
            $descrizione = $row3['descrizione'];
            $esperienza_estera = $row3['esperienza_estera'];
        }

        $da_formazione = @explode('-', $da_formazione);
        $a_formazione = @explode('-', $a_formazione);
    }
// ----------------------------------------------------------------------------
    $sql3_esperienza = "SELECT 
							azienda, 
							indirizzo, 
							mansione, 
							da, 
							a, 
							attuale,
							note FROM esperienza WHERE user_id=" . $cod_anagr;


    $result3_esperienza = mysql_query($sql3_esperienza, $conn->db);

    if (!$result3_esperienza) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row3 = mysql_fetch_array($result3_esperienza, MYSQL_ASSOC)) {
            $azienda = $row3['azienda'];
            $indirizzo = $row3['indirizzo'];
            $mansione = $row3['mansione'];
            $da_esperienza = $row3['da'];
            $a_esperienza = $row3['a'];
            $attuale = $row3['attuale'];
            $note = $row3['note'];
        }

        $da_esperienza = @explode('-', $da_esperienza);
        $a_esperienza = @explode('-', $a_esperienza);
    }
//----------------------------------------------------------------------------
    $sql3_conoscenze = "SELECT 
							tipologia,
							descrizione,
							livello FROM conoscenze WHERE user_id=" . $cod_anagr;


    $result3_conoscenze = mysql_query($sql3_conoscenze, $conn->db);

    if (!$result3_conoscenze) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row3 = mysql_fetch_array($result3_conoscenze, MYSQL_ASSOC)) {
            $tipologia = $row3['tipologia'];
            $descrizione = $row3['descrizione'];
            $livello = $row3['livello'];
        }
    }
//----------------------------------------------------------------------------
    $sql3_certificazione = "SELECT 
							titolo_certificazione, 
							ente_certificante,
							cod_licenza,
							url,
							da, 
							a FROM certificazione WHERE user_id=" . $cod_anagr;


    $result3_certificazione = mysql_query($sql3_certificazione, $conn->db);

    if (!$result3_certificazione) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row3 = mysql_fetch_array($result3_certificazione, MYSQL_ASSOC)) {
            $titolo_certificazione = $row3['titolo_certificazione'];
            $ente_certificante = $row3['ente_certificante'];
            $cod_licenza = $row3['cod_licenza'];
            $url = $row3['url'];
            $da_certificazione = $row3['da'];
            $a_certificazione = $row3['a'];
        }

        $da_certificazione = @explode('-', $da_certificazione);
        $a_certificazione = @explode('-', $a_certificazione);
    }
}
?>

<!-- INIZIO CODICE HTML -->

<!doctype html>
<html class="">

    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Anagrafica</title>
        <link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="MODULO CV/css/modulocv.css" rel="stylesheet" type="text/css">
<script src="respond.min.js"></script>
 <link href="css/modulocv.css" rel="stylesheet" type="text/css">
        <script language="javascript" type="text/javascript" src="css/datetimepicker.js"></script>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script>var __adobewebfontsappname__="dreamweaver"</script><script src="http://use.edgefonts.net/play:n4:default.js" type="text/javascript"></script>
        <script language="javascript"> 
    function toggleMe1(obj, a){ 
      var e=document.getElementById(a); 
      if(obj=="Altro"){ 
        e.style.display=""; 
      }else{ 
    e.style.display="none"; 
} 
    } 
</script> 

    <script language="javascript"> 
    function toggleMe2(obj, a){ 
      var sys=document.getElementById("sys_div"+a); 
      var tec=document.getElementById("tec_div"+a);
      var phi=document.getElementById("phi_div"+a);
      var imp=document.getElementById("imp_div"+a);
      var net=document.getElementById("net_div"+a);
      var con=document.getElementById("con_div"+a);
      var dev=document.getElementById("dev_div"+a);
      var web=document.getElementById("web_div"+a);
      var sys1=document.getElementById("sys_select"+a); 
      var tec1=document.getElementById("tec_select"+a);
      var phi1=document.getElementById("phi_select"+a);
      var imp1=document.getElementById("imp_select"+a);
      var net1=document.getElementById("net_select"+a);
      var con1=document.getElementById("con_select"+a);
      var dev1=document.getElementById("dev_select"+a);
      var web1=document.getElementById("web_select"+a);
      if(obj=="Sys Admin"){ 
       	sys.style.display=""; 
       	tec.style.display="none";
       	phi.style.display="none";
       	imp.style.display="none";
       	net.style.display="none";
       	con.style.display="none";
       	dev.style.display="none";
       	web.style.display="none";
       	sys1.disabled=false;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
      }else if (obj=="Tecnico Hardware"){ 
   		sys.style.display="none";
   		phi.style.display="none";
   		imp.style.display="none";
    	tec.style.display="";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=false;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}
	else if (obj=="Phisical Network Developer"){ 
    	sys.style.display="none";
     	phi.style.display="";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=false;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Impiegato"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=false;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Network Admin"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=false;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Consulente Direzionale"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=false;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Developer - IT Solutions"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=false;
       	web1.disabled=true;
	}
		else if (obj=="Web Design - Grafica 3D"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=false;
	}  
} 
</script> 

 <script language="javascript"> 
    function toggleMe3(obj){ 
      var sys=document.getElementById("sys_div"); 
      var tec=document.getElementById("tec_div");
      var phi=document.getElementById("phi_div");
      var imp=document.getElementById("imp_div");
      var net=document.getElementById("net_div");
      var con=document.getElementById("con_div");
      var dev=document.getElementById("dev_div");
      var web=document.getElementById("web_div");
      var sys1=document.getElementById("sys_select"); 
      var tec1=document.getElementById("tec_select");
      var phi1=document.getElementById("phi_select");
      var imp1=document.getElementById("imp_select");
      var net1=document.getElementById("net_select");
      var con1=document.getElementById("con_select");
      var dev1=document.getElementById("dev_select");
      var web1=document.getElementById("web_select");
      if(obj=="Sys Admin"){ 
       	sys.style.display=""; 
       	tec.style.display="none";
       	phi.style.display="none";
       	imp.style.display="none";
       	net.style.display="none";
       	con.style.display="none";
       	dev.style.display="none";
       	web.style.display="none";
       	sys1.disabled=false;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
      }else if (obj=="Tecnico Hardware"){ 
   		sys.style.display="none";
   		phi.style.display="none";
   		imp.style.display="none";
    	tec.style.display="";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=false;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}
	else if (obj=="Phisical Network Developer"){ 
    	sys.style.display="none";
     	phi.style.display="";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=false;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Impiegato"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=false;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Network Admin"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=false;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Consulente Direzionale"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="";
    	dev.style.display="none";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=false;
       	dev1.disabled=true;
       	web1.disabled=true;
	}  
	else if (obj=="Developer - IT Solutions"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="";
    	web.style.display="none";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=false;
       	web1.disabled=true;
	}
		else if (obj=="Web Design - Grafica 3D"){ 
    	sys.style.display="none";
     	phi.style.display="none";
    	tec.style.display="none";
    	imp.style.display="none";
    	net.style.display="none";
    	con.style.display="none";
    	dev.style.display="none";
    	web.style.display="";
    	sys1.disabled=true;
       	tec1.disabled=true;
       	phi1.disabled=true;
       	imp1.disabled=true;
       	net1.disabled=true;
       	con1.disabled=true;
       	dev1.disabled=true;
       	web1.disabled=false;
	}  
} 
</script> 

<script language="javascript"> 
    function toggleMe(obj, a){ 
      var e=document.getElementById(a); 
      if(obj=="Si"){ 
        e.style.display="none"; 
      }else{ 
    e.style.display=""; 
} 
    } 
</script> 
  <script type="text/javascript">
function comparsa_cor() {if (document.getElementById("add_cor").style.display=="none"){ document.getElementById("add_cor").style.display="";} else {document.getElementById("add_cor").style.display="none";} }
</script>
   <script type="text/javascript">
function comparsa_data1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
   <script type="text/javascript">
function comparsa_data() {if (document.getElementById("data_a").style.display=="none"){ document.getElementById("data_a").style.display="";} else {document.getElementById("data_a").style.display="none";} }
</script>
  <script type="text/javascript">
function comparsa_cert1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
        <script type="text/javascript">
function comparsa_cer() {if (document.getElementById("add").style.display=="none"){ document.getElementById("add").style.display="";} else {document.getElementById("add").style.display="none";} }
</script>
<script type="text/javascript">
function comparsa() {if (document.getElementById("add_con").style.display=="none"){ document.getElementById("add_con").style.display="";} else {document.getElementById("add_con").style.display="none";} }
</script>
<script type="text/javascript">
function comparsa_esp() {if (document.getElementById("esperienza_form").style.display=="none"){ document.getElementById("esperienza_form").style.display="";} else {document.getElementById("esperienza_form").style.display="none";} }
</script>
<script type="text/javascript">
function comparsa_form() {if (document.getElementById("formazione_form").style.display=="none"){ document.getElementById("formazione_form").style.display="";} else {document.getElementById("formazione_form").style.display="none";} }
</script>
    </head>
    <body>
    <?php
if (isset($_SESSION['logged'])) { ?>
    
        <div class="gridContainer clearfix">
		<div id="div1" class="fluid">
  <div id="header" class="fluid"><img src="img/header.png"></div>
<div id="riepilogo" class="fluid" style="width:200px;float:right;"><input class="liste_titre" type="image" title="Torna al Riepilogo" value="Torna al Riepilogo" src="img/riepilogo.png" name="button_search" onclick="window.location = 'riepilogo.php'"></div>
<div id="logout" class="fluid" style="width:200px;float:right;" ><input class="liste_titre" type="image" title="Logout" value="Logout" src="img/logout.png" name="button_search" onclick="window.location = 'logout.php'"></div>
        
    
<?php } ?>
 
<?php if($alert!="") echo '<div class="row"><div class="col-md-12">'.$alert.'</div></div>'; ?>

<!-- INIZIO ANAGRAFICA ----------------------------------------------------------------------------------------------------------------------------------------------  -->

<div id="contenitore1" class="fluid ">
 <p><img src="img/anagrafica.png" style="float:left; padding:10px;"></p><br>
    <?php
if (isset($_SESSION['logged'])) {
    ?>

        <div id="bordotab">
            <form method="post" action="" enctype="multipart/form-data"> 

                 <table width="100%" cellspacing="5">
  <tbody>
    <tr>
         <th height="62" scope="col" class="span">Nome<br></span><input id="nome" class="shadows" name="nome" placeholder="nome" type="nome" value="<?php echo (isset($_REQUEST['nome']) OR isset($nome)) ? $nome : 'Nome'; ?>"/> <br> </th>
         <th height="62" scope="col" class="span">Cognome<br><input id="cognome" class="shadows" name="cognome" placeholder="cognome" type="cognome" value="<?php echo (isset($_REQUEST['cognome']) || isset($cognome)) ? $cognome : 'Cognome'; ?>"/> <br> </th>      
         <th height="62" scope="col" class="span">Data di Nascita<br>
            <select class="shadows" name="giorno_anagrafica">
                    <?php for($d=1;$d<=31;$d++)
                    {
                    ?>
                    <option <?php  if($data_nascita[2] == $d) { ?>selected="selected" <?php } ?> value="<?php echo $d; ?>"><?php echo $d; ?></option>
                    <?php 
                    }
                    ?>
                    </select>
             <select class="shadows" name="mese_anagrafica" style="margin-top:8px;">
                            <option <?php if($data_nascita[1] == "01") { ?>selected="selected" <?php } ?> value="01">Gennaio</option>
                            <option <?php if($data_nascita[1] == "02") { ?>selected="selected" <?php } ?> value="02">Febbraio</option>
                            <option <?php if($data_nascita[1] == "03") { ?>selected="selected" <?php } ?>value="03">Marzo</option>
                            <option <?php if($data_nascita[1] == "04") { ?>selected="selected" <?php } ?>value="04">Aprile</option>
                            <option <?php if($data_nascita[1] == "05") { ?>selected="selected" <?php } ?>value="05">Maggio</option>
                            <option <?php if($data_nascita[1] == "06") { ?>selected="selected" <?php } ?>value="06">Giugno</option>
                            <option <?php if($data_nascita[1] == "07") { ?>selected="selected" <?php } ?>value="07">Luglio</option>
                            <option <?php if($data_nascita[1] == "08") { ?>selected="selected" <?php } ?>value="08">Agosto</option>
                            <option <?php if($data_nascita[1] == "09") { ?>selected="selected" <?php } ?>value="09">Settembre</option>
                            <option <?php if($data_nascita[1] == "10") { ?>selected="selected" <?php } ?>value="10">Ottobre</option>
                            <option <?php if($data_nascita[1] == "11") { ?>selected="selected" <?php } ?>value="11">Novembre</option>
                            <option <?php if($data_nascita[1] == "12") { ?>selected="selected" <?php } ?>value="12">Dicembre</option>
              </select>
                <select class="shadows" name="anno_anagrafica" style="margin-top:8px;">
                 <?php for($y=date("Y");$y>=1930;$y--)
                {
                   ?>
                            <option <?php if($data_nascita[0] == $y) { ?>selected="selected" <?php } ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
                <?php 
                }
                ?>
                </select>
                </th>
                 <th height="62" scope="col" class="span">Città di residenza<br><input id="citta_residenza" class="shadows" name="citta_residenza" placeholder="città di residenza" type="nome" value="<?php echo (isset($_REQUEST['citta_residenza']) || isset($citta_residenza)) ? $citta_residenza : 'Citta di Residenza'; ?>"/> <br> </th>
                  <th scope="col" class="span">Indirizzo di residenza<br><input id="indirizzo_residenza" class="shadows" name="indirizzo_residenza" placeholder="indirizzo di residenza" type="indirizzo di residenza" value="<?php echo (isset($_REQUEST['indirizzo_residenza']) || isset($indirizzo_res)) ? $indirizzo_res : 'Indirizzo residenza'; ?>"/> <br> </th>  
                    </tr>
  </tbody>
</table>
           
<br>

<table width="100%"  cellspacing="5">
  <tbody>
    <tr>
         <th height="62" scope="col" class="span">Luogo di Nascita <br><input id="luogo_nascita" class="shadows" name="luogo_nascita" placeholder="Luogo di Nascita" type="Luogo di Nascita" value="<?php echo (isset($_REQUEST['luogo_nascita']) || isset($luogo_nascita)) ? $luogo_nascita : 'Luogo di nascita'; ?>"/><br> </th>
      <th scope="col" class="span">Città di domicilio<br><input id="citta_domicilio"  class="shadows" name="citta_domicilio" placeholder="Città di domicilio" type="Città di domicilio" value="<?php echo (isset($_REQUEST['citta_domicilio']) || isset($citta_domicilio)) ? $citta_domicilio : 'Citta di Domicilio'; ?>"/> <br> </th>
 <th scope="col" class="span">Indirizzo di domicilio<br><input id="indirizzo_domicilio" class="shadows" name="indirizzo_domicilio" placeholder="Indirizzo di domicilio" type="Indirizzo di domicilio" value="<?php echo (isset($_REQUEST['indirizzo_domicilio']) || isset($indirizzo_dom)) ? $indirizzo_dom : 'Indirizzo domicilio'; ?>"/> <br> </th>
 <th scope="col" class="span">Codice Fiscale<br><input id="codice_fiscale" class="shadows" name="codice_fiscale" placeholder="Codice Fiscale" type="Codice Fiscale" value="<?php echo (isset($_REQUEST['codice_fiscale']) || isset($codice_fiscale)) ? $codice_fiscale : 'Codice fiscale'; ?>"/> <br> </th>
   </tr>
  </tbody>
</table>
    <br>
 <center>
 <table width="73%" cellspacing="5">
  <tbody>
    <tr>
        
    <th width="27%" height="59" scope="col"><p style="font-style: normal; font-family: play; font-weight: 200; font-size:12px; margin-top:20px;">Carica un'immagine profilo per il CV:</p></th>
      <th width="29%" height="59" scope="col"><span style="font-style: normal; font-family: play; font-weight: 200; font-size:12px;"></span>
       <input type="file"  name="fileToUpload" id="fileToUpload">    
      </th>
      <th width="22%" scope="col">
           <input type="hidden" name="id_cv" value="<?php echo $cod_anagr;?>">
          <input type="image" title="Carica Immagine" value="Carica Immagine" src="img/carica_immagine.png" name="submit"></th>
<th width="22%" scope="col"><input type="image" title="Aggiorna Anagrafica" value="Aggiorna Anagrafica" src="img/aggiorna_anagrafica.png" name="action"></th>
       
        </tr>
  </tbody>
</table>

</center>
    </form>
</div>
<div id="bordotab">
            <form method="post" action="" enctype="multipart/form-data"> 

                 <table width="100%" cellspacing="5">
  <tbody>
    <tr>
         <th height="62" scope="col" class="span">Nuova Password<br></span><input id="password" class="shadows" name="password" placeholder="password" type="password"/> <br> </th>
         <th height="62" scope="col" class="span">Conferma Password<br></span><input id="c_password" class="shadows" name="c_password" placeholder="conferma password" type="password"/> <br> </th>
     </tr>
 </tbody>
</table>

<table width="100%" cellspacing="5">
  <tbody>
    <tr>
           <input type="hidden" name="user_idd" value="<?php echo $cod_anagr;?>">
<th scope="col"><input type="image" title="Aggiorna Password" value="Aggiorna Password" src="img/aggiorna_password.png" name="action"></th>
       
        </tr>
  </tbody>
</table>
</form>
</div>
</div>

<!--  FORMAZIONE
----------------------------------------------------------------------------------------------------------------------------------------  -->

<?php include '/var/www/html/form/sezioni_anagrafica/formazione.php'; ?>

<!--  ESPERIENZA PROF. ----------------------------------------------------------------------------------------------------------------------------------------  -->

<?php include '/var/www/html/form/sezioni_anagrafica/esperienze.php'; ?>

<!--  CERTIFICAZIONI  ----------------------------------------------------------------------------------------------------------------------------------------  -->

<?php include '/var/www/html/form/sezioni_anagrafica/certificazioni.php'; ?>
   
<!--  CONOSCENZE ----------------------------------------------------------------------------------------------------------------------------------------------  -->  
 <?php include '/var/www/html/form/sezioni_anagrafica/conoscenze.php'; ?>
<!--  CORSI ----------------------------------------------------------------------------------------------------------------------------------------------  -->  
 <?php include '/var/www/html/form/sezioni_anagrafica/corsi.php'; ?>

<?php 
} else {

    echo "<p align='center' style='color:red'>Per accedere alla pagina devi effettuare il <a href='/servicetech/login.htm.php'>login</a></p>";
}

?>
</body>
</html>