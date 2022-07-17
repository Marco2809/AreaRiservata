<?php
session_start();
//ciao
include('dbconn.php');
include('Mail.php'); 
include('Mail/mime.php');
$conn = new dbconnect();
$r = $conn->connect();

if(!isset($_SESSION['user_idd'])) header("location: login.htm.php");

if(isset($_SESSION['user_idd']))
{
$cod_user = $_SESSION['user_idd'];
$cod_anagr = $_SESSION['user_idd'];
}
//Gestione errori.
//ini_set('display_errors','On');
//error_reporting(E_ALL);
//echo $_SESSION['user_idd'];

if(isset($_SESSION['is_admin'])) {
    if(isset($_GET['id_utente'])){
    $cod_user = $_GET['id_utente'];
$cod_anagr = $_GET['id_utente'];}
}
/*if (!isset($_SESSION['username']))
    header("location: login.htm.php");*/
    
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    
} else {
    $action = '';
}

$alert="";

if(isset($_POST['delete'])&&isset($_POST['delete'])=="delete"){
    $target_dir = "./files/certificazioni/".$_POST['id_certificazione']."/";
    chdir($target_dir);
    unlink($_POST['nome_file']);
    unset($_POST['nome_file']);
    
}

switch ($action) {
    
    case 'Elimina Corso':
    
    $sq_elimina_con = "DELETE FROM corsi
                           WHERE id_corso ='" . $_POST['id_corso'] . "'";
         
          $result_elimina_con = mysql_query($sq_elimina_con, $conn->db);
          
          if (!$result_elimina_con) {
              
            die('Errore di inserimento dati : ' . mysql_error());
            
        } else {
            
            $alert = "<p align='center' style='color:green'>Aggiornamento avvenuto con successo!</p>";
        }
        
        if(isset($alert))
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
            
            $alert = "<p align='center' style='color:green'>Aggiornamento avvenuto con successo!</p>";
        }
        
$dirpath = "./files/certificazioni/".$_POST['id_cer']."/";
 

  $handle = opendir($dirpath);
  while (($file = readdir($handle)) !== false) {
    @unlink($dirpath . $file);
  }
  closedir($handle);

    

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
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire il titolo!</p>";
        }

        if (isset($_REQUEST['ente_certificante']) && $_REQUEST['ente_certificante'] != '') {
            $ente_certificante = $_REQUEST['ente_certificante'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l' ente certificante!</p>"; 
        }

        if (isset($_REQUEST['cod_licenza']) && $_REQUEST['cod_licenza'] != '') {
            $cod_licenza = $_REQUEST['cod_licenza'];
        } else {
            $cod_licenza="";
        }

        if (isset($_REQUEST['url']) && $_REQUEST['url'] != '') {
            $url = $_REQUEST['url'];
        } else {
            $url="";
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
        
$target_dir = "./files/certificazioni/".$id."/";
$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = $_FILES["fileToUpload"]["name"];
$target_file = $target_dir . $newfilename;
$uploadOk = 1;
$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
//echo $FileType;
// Check if image file is a actual image or fake image

    $check = $_FILES["fileToUpload"]["tmp_name"];
// Check if file already exists
if (file_exists($target_file)) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il file già esiste.</p>";
    $uploadOk = 0;
}
// Check file size

if ($_FILES["fileToUpload"]["size"] > 10000000) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file è troppo grande.</p>";
    $uploadOk = 0;
}
// Allow certain file formats
if($FileType != "pdf" && $FileType != "docx" && $FileType != "csv" && $FileType != "xslx" && $FileType != "png" && $FileType != "pptx" && $FileType != "jpg" && $FileType != "jpeg" && $FileType != "gif" && $FileType != "idt" && $FileType != "xls" && $FileType != "txt"
&& $FileType != "doc" ) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, sono permessi solo file XSL, XSLX, TXT, DOC, DOCX, PPTX, PDF, PPJPG, JPEG, PNG & GIF.</p>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file non è stato caricato.</p>";
// if everything is ok, try to upload file
    }else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        
        $alert .= "<p align='center' style='color:red'>Il file è stato caricato con successo.</p>";

    } else {
         $alert .= "<p align='center' style='color:red'>Spiacenti, c'è stato un errore nel caricamento del file.</p>";
    }
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

        if ((isset($_REQUEST['da_giorno_certificazione']) && $_REQUEST['da_giorno_certificazione'] != '') && (isset($_REQUEST['da_mese_certificazione']) && $_REQUEST['da_mese_certificazione'] != '') && (isset($_REQUEST['da_anno_certificazione']) && $_REQUEST['da_anno_certificazione'] != 'Anno' && $_REQUEST['da_mese_certificazione'] != 'Mese' && $_REQUEST['da_anno_certificazione'] != '')) {

            $da_certificazione = $_REQUEST['da_anno_certificazione'] . "-" . $_REQUEST['da_mese_certificazione'] . "-" . $_REQUEST['da_giorno_certificazione'];
        } else {
            $alert .= "<p align='center' style='color:red'>Attenzione: inserire l'inizio della certificazione!</p>";
        }

        if (isset($_REQUEST['scadenza'])) {
            $a_certificazione = "0000-00-00";
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

            $alert.= "<p align='center' style='color:green'>Salvataggio effettuato con successo!</p>";
        }
        

$target_dir = "./files/certificazioni/".mysql_insert_id()."/";
if(is_dir($target_dir)==false) mkdir($target_dir, 0777); 
$temp = explode(".", $_FILES["fileToUpload"]["name"]);
$newfilename = $_FILES["fileToUpload"]["name"];
$target_file = $target_dir . $newfilename;
$uploadOk = 1;
$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
//echo $FileType;
// Check if image file is a actual image or fake image

    $check = $_FILES["fileToUpload"]["tmp_name"];
// Check if file already exists
if (file_exists($target_file)) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il file già esiste.</p>";
    $uploadOk = 0;
}
// Check file size

if ($_FILES["fileToUpload"]["size"] > 10000000) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file è troppo grande.</p>";
    $uploadOk = 0;
}
// Allow certain file formats
if($FileType != "pdf" && $FileType != "docx" && $FileType != "csv" && $FileType != "xslx" && $FileType != "png" && $FileType != "pptx" && $FileType != "jpg" && $FileType != "jpeg" && $FileType != "gif" && $FileType != "idt" && $FileType != "xls" && $FileType != "txt"
&& $FileType != "doc" ) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, sono permessi solo file XSL, XSLX, TXT, DOC, DOCX, PPTX, PDF, PPJPG, JPEG, PNG & GIF.</p>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file non è stato caricato.</p>";
// if everything is ok, try to upload file
    }else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        
        $alert .= "<p align='center' style='color:red'>Il file è stato caricato con successo.</p>";

    } else {
         $alert .= "<p align='center' style='color:red'>Spiacenti, c'è stato un errore nel caricamento del file.</p>";
    }
}



        if(isset($alert) && $alert!="")
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
            
            $alert .= "<p align='center' style='color:green'>Aggiornamento avvenuto con successo!</p>";
        }
        
        if(isset($alert)&&$alert!="")
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

            $alert .= "<p align='center' style='color:green'>Salvataggio effettuato con successo!</p>";
        }

        if(isset($alert) && $alert!="")
        {
         echo $alert;   
        }
        break;
}

?>



<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Esperienza</title>
     <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
      <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
      <link href="css/styles.css" rel="stylesheet">
<!-- 
To learn more about the conditional comments around the html tags at the top of the file:
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/

Do the following if you're using your customized build of modernizr (http://www.modernizr.com/):
* insert the link to your js here
* remove the link below to the html5shiv
* add the "no-js" class to the html tags at the top
* you can also remove the link to respond.min.js if you included the MQ Polyfill in your modernizr build 
-->
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="respond.min.js"></script>
<script type="text/javascript">
function comparsa_cert1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
        <script type="text/javascript">
function comparsa_cer() {if (document.getElementById("add").style.display=="none"){ document.getElementById("add").style.display="";} else {document.getElementById("add").style.display="none";} }
</script>
<script type="text/javascript">
function comparsacorsi(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display=""; document.getElementById(a).scrollIntoView();} else {document.getElementById(a).style.display="none";} }
</script>
<script type='text/javascript'>

function modificaLink(parametro, valore,area){

    var link = window.location.href;
    if(link.indexOf("?")!=-1){

        link = link + "&"+"area"+"="+area+"" + "&"+parametro+"="+valore+"";

    }else{

        link = link + "?"+"area"+"="+area+""+"&"+parametro+"="+valore+"";

    }
    
    window.location.href=link;
}
</script>
<script type="text/javascript">
    function removeItem(key) {
   
    var rtn = window.location.href;
        rtn = rtn.replace(key,"");
    window.location.href=rtn;
}

    </script>
   <script type="text/javascript">
function comparsa_data1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
   <script type="text/javascript">
function comparsa_data() {if (document.getElementById("data_a").style.display=="none"){ document.getElementById("data_a").style.display="";} else {document.getElementById("data_a").style.display="none";} }
</script>
<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>
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
function comparsa() {if (document.getElementById("eventbody-anagrafica").style.display=="none"){ document.getElementById("eventbody-anagrafica").style.display=""; window.scrollTo(0, 1000);} else {document.getElementById("eventbody-anagrafica").style.display="none";} }
</script>
<script type="text/javascript">
function comparsa1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display=""; document.getElementById(a).scrollIntoView();} else {document.getElementById(a).style.display="none";} }
</script>
</head>
 
<body style="background-color:#f1f1f1;">

<?php    include 'menu_top.php';?>
    <!-- Barra Avanzamento --> 

    	<center>
	<div>
	<img src="img/barra-plus.png" style="max-width:100%; margin-top:3%;">
	</div>
	</center>
	<!-- Fine Barra Avanzamento -->
            <?php if($alert!="") echo '<div class="row"><div class="col-md-12">'.$alert.'</div></div>'; ?>
<?php
            $sql_certificazioni = "SELECT 
							id,
							titolo_certificazione,
							cod_licenza,
							url,
							ente_certificante,
							da,
							a 
							FROM certificazione WHERE user_id=" . $cod_anagr;


            $result_certificazioni = mysql_query($sql_certificazioni, $conn->db);
            $i = 1;
            if (!$result_certificazioni) {
                die('Errore caricamento dati 3: ' . mysql_error());
            } else {
                $a_cer_div =0;
?>
<div class="section">
            <div class="container">
<?php
                while ($row3 = mysql_fetch_array($result_certificazioni)) {

 $dir = './files/certificazioni/'.$row3['id']; // Inserire il percorso della cartella da leggere
if($a_cer_div>0) unset($files);

if ( is_dir( $dir ) ) { // Controllo se $dir è una cartella
	if ( $dh = opendir( $dir ) ) { 
                $i=0;// Apro l'indice della cartella $dir
		while ( ($indice = readdir($dh)) !== false ) {
                    // Scorro tutti i file e cartelle di $dir
			if( $indice != '.' AND $indice != '..' ) { // Elimino della visualizzazione il . e i ..
				$files[$i]=$indice;
                                $i++;
			}
		}
	}
}
                    $a_cer_div++;    
                    list($row3['da_anno_certificazione'], $row3['da_mese_certificazione'], $row3['da_giorno_certificazione']) = explode("-", $row3['da']);
                    list($row3['a_anno_certificazione'], $row3['a_mese_certificazione'], $row3['a_giorno_certificazione']) = explode("-", $row3['a']);
    ?>

               <div class="row" style="margin-top:0%;padding-top:1%; padding-bottom:1%;">
                  
				  <!-- Lingua -->
				  <!-- Titolo -->
		  <div class="section">
          <div class="container">
          <div class="titolo-sezione">
		  <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; text-align: left;padding-left:2%;"><?php echo $row3['titolo_certificazione']?></span>
	     <a id="link-esperienza1" onClick="javascript:comparsa1('certificazione-<?php echo $a_cer_div; ?>')"><img src="img/apri.png"></img></a>
		  </div>
		  <!-- Fine Titolo 1 -->
				  <div class="border" id="certificazione-<?php echo $a_cer_div;?>" style="display:none;">
                                      <div class="section">
              <div class="container" style="max-width:90%; margin-left:7%; display:block;">
                <div class="row" style="margin-bottom:2%;">
				<div class="col-md-6">
				  <span style="font-weight:600;text-decoration:underline;font-size:23px;"><?php echo $row3['titolo_certificazione']?></span>
				  </div>
				  </div> 
                                      <form method="post" action="" enctype="multipart/form-data">
                

<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;padding-bottom:4%;font-size:16px;">Titolo della certificazione*:</span>
				  </div>
                  <div class="col-md-3"><input id="Titolo della certificazione" value="<?php echo $row3['titolo_certificazione']; ?>" class="shadows" name="titolo_certificazione"  placeholder="Titolo della certificazione" type="Titolo della certificazione"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Ente certificante*:</span>
				  </div>
                  <div class="col-md-3"><input id="Ente certificante" value="<?php echo $row3['ente_certificante']; ?>" class="shadows" name="ente_certificante" placeholder="Ente certificante" type="Ente certificante"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Codice Licenza:</span>
				  </div>
                  <div class="col-md-3"><input id="Codice Licenza" value="<?php echo $row3['cod_licenza']; ?>" class="shadows" name="cod_licenza" placeholder="Codice Licenza" type="Codice Licenza"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">URL della certificazione:</span>
				  </div>
                  <div class="col-md-3"><input id="URL della certificazione" value="<?php echo $row3['url']; ?>" class="shadows" name="url" placeholder="URL della certificazione" type="URL della certificazione"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Inizio validità certificazione*:</span>
				  </div>
                  <div class="col-md-4">
<select name="da_giorno_certificazione" style="margin-top:8px;">                  
                                            <?php
                                            for ($d_cer_da = 1; $d_cer_da <= 31; $d_cer_da++) {
                                                ?>
                                                <option <?php if ($row3['da_giorno_certificazione'] == $d_cer_da) { ?>selected="selected" <?php } ?> value="<?php echo $d_cer_da; ?>"><?php echo $d_cer_da; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
<select name="da_mese_certificazione" style="margin-top:8px;">
                                            <option value="01" <?php if ($row3['da_mese_certificazione'] == "01") { ?>selected="selected" <?php } ?>>Gennaio</option>
                                            <option value="02" <?php if ($row3['da_mese_certificazione'] == "02") { ?>selected="selected" <?php } ?>>Febbraio</option>
                                            <option value="03" <?php if ($row3['da_mese_certificazione'] == "03") { ?>selected="selected" <?php } ?>>Marzo</option>
                                            <option value="04" <?php if ($row3['da_mese_certificazione'] == "04") { ?>selected="selected" <?php } ?>>Aprile</option>
                                            <option value="05" <?php if ($row3['da_mese_certificazione'] == "05") { ?>selected="selected" <?php } ?>>Maggio</option>
                                            <option value="06" <?php if ($row3['da_mese_certificazione'] == "06") { ?>selected="selected" <?php } ?>>Giugno</option>
                                            <option value="07" <?php if ($row3['da_mese_certificazione'] == "07") { ?>selected="selected" <?php } ?>>Luglio</option>
                                            <option value="08" <?php if ($row3['da_mese_certificazione'] == "08") { ?>selected="selected" <?php } ?>>Agosto</option>
                                            <option value="09" <?php if ($row3['da_mese_certificazione'] == "09") { ?>selected="selected" <?php } ?>>Settembre</option>
                                            <option value="10" <?php if ($row3['da_mese_certificazione'] == "10") { ?>selected="selected" <?php } ?>>Ottobre</option>
                                            <option value="11" <?php if ($row3['da_mese_certificazione'] == "11") { ?>selected="selected" <?php } ?>>Novembre</option>
                                            <option value="12" <?php if ($row3['da_mese_certificazione'] == "12") { ?>selected="selected" <?php } ?>>Dicembre</option>
                                        </select>
 <select  name="da_anno_certificazione" id="da_anno_certificazione<?php echo $a_cer_div;?>" style="margin-top:8px;">
                                            <?php
                                            for ($y_cer_da = date("Y"); $y_cer_da >= 1930; $y_cer_da--) {
                                                ?>
                                                <option <?php if ($row3['da_anno_certificazione'] == $y_cer_da) { ?>selected="selected" <?php } ?> value="<?php echo $y_cer_da; ?>"><?php echo $y_cer_da; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                  </div>
</div>
                                         
<div class="row"  id="a_cer_div<?php echo $a_cer_div; ?>" <?php if($row3['a_mese_certificazione']=="00") { ?>  style="display:none; text-align: left; margin-bottom:1.5%;" <?php } else { ?> style="margin-bottom:1.5%;"<?php }?>>
    <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Fine validità certificazione*:</span>
				  </div>             
    <div class="col-md-4">
<select name="a_giorno_certificazione"  style="margin-top:8px;">
                                            <option value="Giorno">Giorno</option>
                                            <?php
                                            for ($d_cer_a = 1; $d_cer_a <= 31; $d_cer_a++) {
                                                ?>
                                                <option <?php if ($row3['a_giorno_certificazione'] == $d_cer_a) { ?>selected="selected" <?php } ?> value="<?php echo $d_cer_a; ?>"><?php echo $d_cer_a; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
<select name="a_mese_certificazione" style="margin-top:8px;">
                                            <option value="Mese">Mese</option>
                                            <option value="01" <?php if ($row3['a_mese_certificazione'] == "01") { ?>selected="selected" <?php } ?>>Gennaio</option>
                                            <option value="02" <?php if ($row3['a_mese_certificazione'] == "02") { ?>selected="selected" <?php } ?>>Febbraio</option>
                                            <option value="03" <?php if ($row3['a_mese_certificazione'] == "03") { ?>selected="selected" <?php } ?>>Marzo</option>
                                            <option value="04" <?php if ($row3['a_mese_certificazione'] == "04") { ?>selected="selected" <?php } ?>>Aprile</option>
                                            <option value="05" <?php if ($row3['a_mese_certificazione'] == "05") { ?>selected="selected" <?php } ?>>Maggio</option>
                                            <option value="06" <?php if ($row3['a_mese_certificazione'] == "06") { ?>selected="selected" <?php } ?>>Giugno</option>
                                            <option value="07" <?php if ($row3['a_mese_certificazione'] == "07") { ?>selected="selected" <?php } ?>>Luglio</option>
                                            <option value="08" <?php if ($row3['a_mese_certificazione'] == "08") { ?>selected="selected" <?php } ?>>Agosto</option>
                                            <option value="09" <?php if ($row3['a_mese_certificazione'] == "01") { ?>selected="selected" <?php } ?>>Settembre</option>
                                            <option value="10" <?php if ($row3['a_mese_certificazione'] == "10") { ?>selected="selected" <?php } ?>>Ottobre</option>
                                            <option value="11" <?php if ($row3['a_mese_certificazione'] == "11") { ?>selected="selected" <?php } ?>>Novembre</option>
                                            <option value="12" <?php if ($row3['a_mese_certificazione'] == "12") { ?>selected="selected" <?php } ?>>Dicembre</option>
                                        </select>
<select name="a_anno_certificazione" id="a_anno_certificazione<?php echo $a_cer_div;?>" style="margin-top:8px;">
                                            <option value="Anno">Anno</option>
                                            <?php
                                            for ($y_cer_a = 2000; $y_cer_a <= date("Y")+10; $y_cer_a++) {
                                                ?>
                                                <option <?php if ($row3['a_anno_certificazione'] == $y_cer_a) { ?>selected="selected" <?php } ?>  value="<?php echo $y_cer_a; ?>"><?php echo $y_cer_a; ?></option>
                                                <?php
                                            }
                                            ?>
</select></div></div>
         <div class="row" style="margin-bottom:1.5%;">
				<div class="col-md-3">
				  </div>
                  <div class="col-md-3">
				  Senza scadenza<input <?php if($row3['a_mese_certificazione']=="00") { ?>checked="checked" <?php } ?> onclick="comparsa_cert1('a_cer_div<?php echo $a_cer_div; ?>')" name="scadenza" type="checkbox" class="shadows" id="checkbox" value="Senza scadenza">
                  </div>
         </div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;padding-bottom:4%;font-size:16px;">Carica Pdf Certificazione</span>
				  </div>
                  <div class="col-md-3">
				  <input type="file" name="fileToUpload" id="fileToUpload" style="margin-left: 65px;width: 150px;">
				  </div>
                </div>
                                          <center>
       <div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-6">
                      <input type="submit" title="Aggiorna Certificazione" value="Aggiorna Certificazione" class="btn btn-success btn-sm" name="action">
    <input type="submit" title="Elimina Certificazione" value="Elimina Certificazione" class="btn btn-danger btn-sm" name="action">
    <input type="hidden" name="id_cer" value="<?php echo $row3['id'];?>">
</div>
       </div>
                                          </center>
    </form>

<?php if(isset($files)){?>
<div class="row" style="margin-bottom:1.5%;margin-top:1,5%;">
<?php for($s=0;$s<count($files);$s++) {?><form action="" method="post"><div class="col-md-2"><a target="blank" href="./files/certificazioni/<?php echo $row3['id']."/".$files[$s];?>"><img width="30px" src="img/file.png" alt="<?php echo $files[$s];?>" title="<?php echo $files[$s];?>" ></a>
<input style='width: 20px;' type='image' name='delete' value='delete' src='img/x.png'>
<input type='hidden' name='nome_file' value="<?php echo $files[$s]?>">
<input type='hidden' name='id_certificazione' value="<?php echo $row3['id']?>">
</div>
</form>
<?php } ?>

</div>
<?php } ?>
</div>
</div>
                  </div>
               </div>
            </div>
               </div>
  <?php }
    }
  ?>     
   </div></div>
                 <!-- Tasto aggiungi -->
		  <div class="section">
          <div class="container">
          <div style="margin-left:2%;margin-top: -50px;">
		  <a class="btn btn-success btn-md" id="link-lingua2" onclick="mostranascondi('certificazioni');"> Aggiungi Certificazione</a>
  		  </a>
		  </div>
		  </div>
                  </div>
		  <!-- Fine Tasto aggiungi -->

                <div class="section" id="certificazioni" style="display:none; ">
          <div class="container">
          
              <div class="border">
                   <div class="section">
              <div class="container" style="max-width:90%; margin-left:7%;">
    <form method="post" action="" enctype="multipart/form-data">
        
                <div class="row" style="margin-bottom:2%;">
				<div class="col-md-6">
				  <span style="font-weight:600;text-decoration:underline;font-size:23px;">Nuova Certificazione</span>
				  </div>
				  </div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;padding-bottom:4%;font-size:16px;">Titolo della certificazione*:</span>
				  </div>
                  <div class="col-md-3"><input id="Titolo della certificazione"  class="shadows" name="titolo_certificazione"  placeholder="Titolo della certificazione" type="Titolo della certificazione"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Ente certificante*:</span>
				  </div>
                  <div class="col-md-3"><input id="Ente certificante" class="shadows" name="ente_certificante" placeholder="Ente certificante" type="Ente certificante"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Codice Licenza:</span>
				  </div>
                  <div class="col-md-3"><input id="Codice Licenza" class="shadows" name="cod_licenza" placeholder="Codice Licenza" type="Codice Licenza"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">URL della certificazione:</span>
				  </div>
                  <div class="col-md-3"><input id="URL della certificazione"  class="shadows" name="url" placeholder="URL della certificazione" type="URL della certificazione"/>
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Inizio validità certificazione*:</span>
				  </div>
                  <div class="col-md-4">
<select name="da_giorno_certificazione" style="margin-top:8px;">                  
                                            <option value="Giorno">Giorno</option>
                                            <?php
                                            for ($d_cer_da = 1; $d_cer_da <= 31; $d_cer_da++) {
                                                ?>
                                                <option  value="<?php echo $d_cer_da; ?>"><?php echo $d_cer_da; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
<select name="da_mese_certificazione" style="margin-top:8px;">
                                            <option value="Mese">Mese</option>
                                            <option value="01">Gennaio</option>
                                            <option value="02">Febbraio</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Aprile</option>
                                            <option value="05">Maggio</option>
                                            <option value="06">Giugno</option>
                                            <option value="07">Luglio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Settembre</option>
                                            <option value="10">Ottobre</option>
                                            <option value="11">Novembre</option>
                                            <option value="12">Dicembre</option>
                                        </select>
 <select  name="da_anno_certificazione" id="da_anno_certificazione" style="margin-top:8px;">
                                            <option value="Anno">Anno</option>
                                            <?php
                                            for ($y_cer_da = date("Y"); $y_cer_da >= 1930; $y_cer_da--) {
                                                ?>
                                                <option  value="<?php echo $y_cer_da; ?>"><?php echo $y_cer_da; ?></option>
                                                <?php
                                            }
                                            ?>
 </select></div>
</div>
    <div class="row"  id="a_cer_div" style=" text-align: left; margin-bottom:1.5%;">
    <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Fine validità certificazione*:</span>
				  </div>             
    <div class="col-md-4">
<select name="a_giorno_certificazione" style="margin-top:8px;">
                                            <option value="Giorno">Giorno</option>
                                            <?php
                                            for ($d_cer_a = 1; $d_cer_a <= 31; $d_cer_a++) {
                                                ?>
                                                <option  value="<?php echo $d_cer_a; ?>"><?php echo $d_cer_a; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
<select name="a_mese_certificazione" style="margin-top:8px;">
                                            <option value="Mese">Mese</option>
                                            <option value="01" >Gennaio</option>
                                            <option value="02" >Febbraio</option>
                                            <option value="03" >Marzo</option>
                                            <option value="04" >Aprile</option>
                                            <option value="05" >Maggio</option>
                                            <option value="06" >Giugno</option>
                                            <option value="07" >Luglio</option>
                                            <option value="08" >Agosto</option>
                                            <option value="09" >Settembre</option>
                                            <option value="10" >Ottobre</option>
                                            <option value="11" >Novembre</option>
                                            <option value="12" >Dicembre</option>
                                        </select>
<select name="a_anno_certificazione" id="a_anno_certificazione" style="margin-top:8px;">
                                            <option value="Anno">Anno</option>
                                            <?php
                                            for ($y_cer_a = 2000; $y_cer_a <= date("Y")+10; $y_cer_a++) {
                                                ?>
                                                <option  value="<?php echo $y_cer_a; ?>"><?php echo $y_cer_a; ?></option>
                                                <?php
                                            }
                                            ?>
</select></div>
    </div>
        <div class="row" style="margin-bottom:1.5%;">
				<div class="col-md-3">
				  </div>
                  <div class="col-md-3">Senza Scadenza <input onclick="comparsa_cert1('a_cer_div');" name="scadenza" type="checkbox" class="shadows" id="checkbox" value="Senza scadenza"></td></tr>
                  </div>
        </div>
                      <div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;padding-bottom:4%;font-size:16px;">Carica Pdf Certificazione</span>
				  </div>
                  <div class="col-md-3">
				  <input type="file" name="fileToUpload" id="fileToUpload" style="margin-left: 65px;width: 150px;">
				  </div>
                </div>
       <div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
                      <input type="submit" title="Salva Certificazione" value="Salva Certificazione" class="btn btn-success btn-sm" name="action">
</div>
       </div>   </form>
                  </div></div>
 
</div>
            </div>
</div>
     
       
       <?php
            $sql_corsi = "SELECT 
							id_corso,
							tipo,
							descrizione
							FROM corsi WHERE user_id=" . $cod_anagr;


            $result_corsi = mysql_query($sql_corsi, $conn->db);
            $c = 1;
            $cor_div=0;
            if (!$result_corsi) {
                die('Errore di inserimento dati: ' . mysql_error());
            } else {
                while ($row3 = mysql_fetch_array($result_corsi)) {
                    $cor_div++
                    ?>
                  <div class="section">
            <div class="container">
               <div class="row" style="margin-top:0%;padding-top:1%; padding-bottom:1%;">
                  
				  <!-- Lingua -->
				  <!-- Titolo -->
		  <div class="section">
          <div class="container">
          <div class="titolo-sezione">
		  <span style="font-size:1.5em; color:#fff; font-weight:600; font-family:Play; margin-top:12%; text-align: left;padding-left:2%;"><?php echo $row3['tipo']?></span>
	     <a id="link-esperienza1" onclick="javascript:comparsacorsi('eventbody-corsi<?php echo $cor_div; ?>')"><img src="img/apri.png"></img></a>
		  </div>
		  <!-- Fine Titolo 1 -->
     <div class="border" id="eventbody-corsi<?php echo $cor_div; ?>" style=" display:none;">
		  <div class="section">
              <div class="container" style="max-width:90%; margin-left:7%;">
                <div class="row" style="margin-bottom:2%;">
				<div class="col-md-6">
				  <span style="font-weight:600;text-decoration:underline;font-size:23px;">Corsi di sicurezza</span>
				  </div>
				  </div>

    <form action="" method="post">
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-4">
				  <span style="font-weight:600;font-size:16px;">Corso Base</span>
				  </div>
                  <div class="col-md-1"><input <?php if($row3['tipo']=="Corso Base"){?>checked="checked"<?php } ?> name="tipo" type="radio" class="shadows" id="checkbox" value="Corso Base">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-4">
				  <span style="font-weight:600;font-size:16px;">Primo Soccorso</span>
				  </div>
                  <div class="col-md-1"><input <?php if($row3['tipo']=="Primo Soccorso"){?>checked="checked"<?php } ?> name="tipo" type="radio" class="shadows" id="checkbox" value="Primo Soccorso">
</div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-4">
				  <span style="font-weight:600;font-size:16px;">Anti Incendio</span>
				  </div>
                  <div class="col-md-1"><input <?php if($row3['tipo']=="Anti Incendio"){?>checked="checked"<?php } ?> name="tipo" type="radio" class="shadows" id="checkbox" value="Anti Incendio">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-4">
				  <span style="font-weight:600;font-size:16px;">Capocantiere</span>
				  </div>
                  <div class="col-md-1"><input <?php if($row3['tipo']=="Capocantiere") {?>checked="checked"<?php } ?> name="tipo" type="radio" class="shadows" id="checkbox" value="Capocantiere">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-4">
				  <span style="font-weight:600;font-size:16px;">RLS</span>
				  </div>
                  <div class="col-md-1"><input <?php if($row3['tipo']=="RLS"){?>checked="checked"<?php } ?> name="tipo" type="radio" class="shadows" id="checkbox" value="RLS">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-4">
				  <span style="font-weight:600;font-size:16px;">RSSP</span>
				  </div>
                  <div class="col-md-1"><input <?php if($row3['tipo']=="RSSP"){?>checked="checked"<?php } ?> name="tipo" type="radio" class="shadows" id="checkbox" value="RSSP">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-4">
				  <span style="font-weight:600;font-size:16px;">Descrizione del corso</span>
				  </div>
                  <div class="col-md-4"><textarea rows="4" cols="10" name="descrizione"><?php echo $row3['descrizione']; ?>
</textarea>
                  </div>
</div>
<center>
 <div id="pulsanti" class="fluid " style="margin-top:10px;">
     <input type="submit" title="Aggiorna Corso" value="Aggiorna Corso" class="btn btn-success btn-sm" name="action">
    <input type="submit" title="Elimina Corso" value="Elimina Corso" class="btn btn-danger btn-sm" name="action">
    <input type="hidden" name="id_corso" value="<?php echo $row3['id_corso']?>">
</div>
</center>
</div>
    </form>
       </div></div>
     </div>
          </div>
                  </div>
               </div>
            </div>
                  </div>
                  
                <?php }
            }?>
                  
                   <!-- Tasto aggiungi -->
		  <div class="section">
          <div class="container">
          <div style="margin-left:2%;margin-top: 1,5%;">
		  <a class="btn btn-success btn-md" id="link-lingua2" onclick="mostranascondi('corsi');"> Aggiungi Corso</a>
  		  </a>
		  </div>
		  </div>
                  </div>
		  <!-- Fine Tasto aggiungi -->

                <div class="section" id="corsi" style="display:none; ">
          <div class="container">
          
              <div class="border">
                   <div class="section">
              <div class="container" style="max-width:90%; margin-left:7%;">

    <form action="" method="post">
 <div class="row" style="margin-bottom:2%;">
				<div class="col-md-6">
				  <span style="font-weight:600;text-decoration:underline;font-size:23px;">Corsi di sicurezza</span>
				  </div>
				  </div>
       <div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Corso Base</span>
				  </div>
                  <div class="col-md-3"><input name="tipo" type="radio" class="shadows" id="checkbox" value="Corso Base">
                  </div></div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Primo Soccorso</span>
				  </div>
                  <div class="col-md-3"><input name="tipo" type="radio" class="shadows" id="checkbox" value="Primo Soccorso">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Anti Incendio</span>
				  </div>
                  <div class="col-md-3"><input  name="tipo" type="radio" class="shadows" id="checkbox" value="Anti Incendio">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Capocantiere</span>
				  </div>
                  <div class="col-md-3"><input  name="tipo" type="radio" class="shadows" id="checkbox" value="Capocantiere">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">RLS</span>
				  </div>
                  <div class="col-md-3"><input  name="tipo" type="radio" class="shadows" id="checkbox" value="RLS">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">RSSP</span>
				  </div>
                  <div class="col-md-3"><input name="tipo" type="radio" class="shadows" id="checkbox" value="RSSP">
                  </div>
</div>
<div class="row" style="margin-bottom:1.5%;">
                  <div class="col-md-3">
				  <span style="font-weight:600;font-size:16px;">Descrizione del corso</span>
				  </div>
                  <div class="col-md-3"><textarea rows="4" cols="10" name="descrizione">Inserire qui la descrizione
</textarea>
                  </div>
</div>
<center>
<div id="pulsanti" class="fluid " style="margin-top:3%;">
    <input type="submit" title="Salva Corso" value="Salva Corso" class="btn btn-success btn-sm" name="action">
</div>
</center>
    </form>
</div>

       
</div>
</div>
   </div>
                </div>
  <div id="indietro" class="fluid " style="float:left; position: relative;width:50%; margin-left: 50px;margin-bottom:20px; margin-top:10px;">
        <a href="lingue_straniere.php"><img src="img/previous.png"></a>
  <span style="font-size:16px; font-family:play; font-weight:bold; color:#333;">Indietro</span>
  </div>
    <div id="avanti" class="fluid " style="text-align: right;float:right; position: relative; width:50%; margin-right: 50px;margin-bottom:20px; margin-top:-45px;">
        <span style="font-size:16px; font-family:play; font-weight:bold; color:#333;">Avanti</span>
        <a href="riepilogo_competenze.php"><img src="img/next.png"></a></div>
</body>
</html>
<script>
function mostranascondi(div, switchImgTag) {
        var ele = document.getElementById(div);
        var imageEle = document.getElementById(switchImgTag);
        if(ele.style.display == "inline") {
                ele.style.display = "none";
		imageEle.innerHTML = '<img src="../img/apri.png">';
        }
        else {
                ele.style.display = "inline";
                imageEle.innerHTML = '<img src="../img/apri.png">';
        }
}
</script>
<script>
$( "#da_anno_certificazione" ).change(function() {
  var anno = $('#da_anno_certificazione').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione option[value='"+i+"']").hide();
  }
  for(var i=anno; i<new Date().getFullYear()+10; i++){
    $("#a_anno_certificazione option[value='"+i+"']").show();
  }
  $("#a_anno_certificazione option[value='"+anno+"']").prop('selected', true);
});

$( document ).ready(function() {
var anno = $('#da_anno_certificazione1').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione1 option[value='"+i+"']").hide();
  }
});

$( document ).ready(function() {
var anno = $('#da_anno_certificazione2').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione2 option[value='"+i+"']").hide();
  }
});

$( document ).ready(function() {
var anno = $('#da_anno_certificazione3').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione3 option[value='"+i+"']").hide();
  }
});

$( document ).ready(function() {
var anno = $('#da_anno_certificazione4').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione4 option[value='"+i+"']").hide();
  }
});

$( document ).ready(function() {
var anno = $('#da_anno_certificazione5').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione5 option[value='"+i+"']").hide();
  }
});

$( "#da_anno_certificazione1" ).change(function() {
  var anno = $('#da_anno_certificazione1').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione1 option[value='"+i+"']").hide();
  }
  for(var i=anno; i<new Date().getFullYear()+10; i++){
    $("#a_anno_certificazione1 option[value='"+i+"']").show();
  }
   $("#a_anno_certificazione1 option[value='"+anno+"']").prop('selected', true);
});

$( "#da_anno_certificazione2" ).change(function() {
  var anno = $('#da_anno_certificazione2').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione2 option[value='"+i+"']").hide();
  }
  for(var i=anno; i<new Date().getFullYear()+10; i++){
    $("#a_anno_certificazione2 option[value='"+i+"']").show();
  }
   $("#a_anno_certificazione2 option[value='"+anno+"']").prop('selected', true);
});

$( "#da_anno_certificazione3" ).change(function() {
  var anno = $('#da_anno_certificazione3').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione3 option[value='"+i+"']").hide();
  }
  for(var i=anno; i<new Date().getFullYear()+10; i++){
    $("#a_anno_certificazione3 option[value='"+i+"']").show();
  }
   $("#a_anno_certificazione3 option[value='"+anno+"']").prop('selected', true);
});

$( "#da_anno_certificazione4" ).change(function() {
  var anno = $('#da_anno_certificazione4').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione4 option[value='"+i+"']").hide();
  }
  for(var i=anno; i<new Date().getFullYear()+10; i++){
    $("#a_anno_certificazione4 option[value='"+i+"']").show();
  }
   $("#a_anno_certificazione4 option[value='"+anno+"']").prop('selected', true);
});

$( "#da_anno_certificazione5" ).change(function() {
  var anno = $('#da_anno_certificazione5').val();
  for(var i=1930; i<anno; i++){
    $("#a_anno_certificazione5 option[value='"+i+"']").hide();
  }
  for(var i=anno; i<new Date().getFullYear()+10; i++){
    $("#a_anno_certificazione5 option[value='"+i+"']").show();
  }
   $("#a_anno_certificazione5 option[value='"+anno+"']").prop('selected', true);
});
</script>

