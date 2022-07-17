<?php
session_start();
include('dbconn.php');
$conn = new dbconnect();
$r = $conn->connect();
$cod_user = $_SESSION['user_idd'];
$cod_anagr = $_SESSION['user_idd'];

if ($cod_user != 0) {
    if(!isset($_GET['id']))
    {
        $codice=$cod_anagr;
    }
    else if (isset($_GET['id']))
    {   
            $codice=$_GET['id'];
    }

    $controllo=0;
if($controllo==0) 
{
    $query_control = "SELECT * FROM certificazione WHERE user_id = " .$cod_user;
    $qc=mysql_query($query_control);
    if(mysql_num_rows($qc)>0) $controllo = 1;
}

if($controllo==0) 
{
    $query_control1 = "SELECT * FROM corsi WHERE user_id = " .$cod_user;
    $qc1=mysql_query($query_control1);
    if(mysql_num_rows($qc1)>0) $controllo = 1;
}
if($controllo==0) 
{
      $query_control2 = "SELECT * FROM conoscenze WHERE user_id = " .$cod_user;
    $qc2=mysql_query($query_control2);
    if(mysql_num_rows($qc2)>0) $controllo = 1;
}
if($controllo==0) 
{
      $query_control3 = "SELECT * FROM formazione WHERE user_id = " .$cod_user;
    $qc3=mysql_query($query_control3);
    if(mysql_num_rows($qc3)>0) $controllo = 1;
}
if($controllo==0) 
{
      $query_control4 = "SELECT * FROM esperienza WHERE user_id = " .$cod_user;
    $qc4=mysql_query($query_control4);
    if(mysql_num_rows($qc4)>0) $controllo = 1;
}
 $sql4_anagrafica = "SELECT 
							phone, 
							email,
                            is_admin
                                                        FROM login WHERE user_idd=" . $codice;


    $result4_anagrafica = mysql_query($sql4_anagrafica, $conn->db);

    if (!$result4_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row = mysql_fetch_array($result4_anagrafica, MYSQL_ASSOC)) {
            $telefono = $row['phone'];
            $email = $row['email'];
            $is_admin = $row['is_admin'];
        }
    }
    if($is_admin==1&&isset($_GET['id']))
    {
        $sql3_anagrafica = "SELECT 
							nome, 
							cognome, 
							luogo_nascita, 
							data_nascita, 
							citta_residenza,
							indirizzo_residenza,
							citta_domicilio,
							indirizzo_domicilio,
							codice_fiscale FROM anagrafica WHERE user_id=" . $_GET['id'];


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);

    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $luogo_nascita = $row['luogo_nascita'];
            $citta_residenza = $row['citta_residenza'];
            $indirizzo_res = $row['indirizzo_residenza'];
            $data_nascita = $row['data_nascita'];
            $citta_domicilio = $row['citta_domicilio'];
            $indirizzo_dom = $row['indirizzo_domicilio'];
            $codice_fiscale = $row['codice_fiscale'];
        }
    }
    $data_nascita = @explode('-', $data_nascita);
    }
    else
    {
    $sql3_anagrafica = "SELECT 
							nome, 
							cognome, 
							luogo_nascita, 
							data_nascita, 
							citta_residenza,
							indirizzo_residenza,
							citta_domicilio,
							indirizzo_domicilio,
                                                        img_name,
							codice_fiscale FROM anagrafica WHERE user_id=" . $codice;


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);

    if (!$result3_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row = mysql_fetch_array($result3_anagrafica, MYSQL_ASSOC)) {
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $luogo_nascita = $row['luogo_nascita'];
            $citta_residenza = $row['citta_residenza'];
            $indirizzo_res = $row['indirizzo_residenza'];
            $data_nascita = $row['data_nascita'];
            $citta_domicilio = $row['citta_domicilio'];
            $indirizzo_dom = $row['indirizzo_domicilio'];
            $codice_fiscale = $row['codice_fiscale'];
            $img_name = $row['img_name'];
        }
    }
    $data_nascita = @explode('-', $data_nascita);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Riepilogo</title>
<link href="boilerplate.css" rel="stylesheet" type="text/css">
<link href="css/infopersonali.css" rel="stylesheet" type="text/css">
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
<script src="respond.min.js"></script>
</head>
<body>

    <div class="gridContainer clearfix">
		<div id="div1" class="fluid">
        
        <div id="header" class="fluid "><img src="img/header.png">
                <?php if (isset($_SESSION['logged'])) { ?>
        <div id="logout" class="fluid "><input class="liste_titre" onclick="window.location = 'logout.php'" type="image" title="Logout" value="Logout" src="img/logout.png" name="button_search"></div>
        </div>
        
       <table align="center" cellspacing="5" id="menu">
  <tbody>
    <tr>
            <?php if(!isset($_GET['id'])) {
        
    ?>

      <th scope="col"><input class="liste_titre" onclick="window.location = 'nuovo-contatto.php'" type="image" title="Modifica" value="Modifica" <?php if($controllo==1) { ?> src="img/modifica_cv.png" <?php } else { ?> src="img/inserisci_cv.png" <?php } ?>  name="button_search"></th>
      <?php if($is_admin==1) {?>
      <th scope="col"><input class="liste_titre" onclick="window.location = 'calendario.php'" type="image" title="Timesheet" value="Timesheet" src="img/timesheet.png" name="button_search"></th>
      <?php } ?>
      <th scope="col"><input class="liste_titre" onclick="window.open('/form/tcpdf/examples/PDF_create.php')"  type="image" title="Genera PDF" value="Genera PDF" src="img/pdf.png" name="button_search"></th>
      <?php if($is_admin==1) {?>
      <th scope="col"><input class="liste_titre" onclick="window.location = 'amministrazione.php?id=cerca'" type="image" title="Admin" value="Admin" src="img/admin.png" name="button_search"></th>
    <?php } ?>
      <?php } else if(isset($_GET['id'])) 
      {
        ?>
        <th scope="col"><input class="liste_titre" onclick="window.location = 'amministrazione.php?id=dip'" type="image" title="Admin" value="Admin" src="img/admin.png" name="button_search"></th>
        <?php

      }
    ?>
    
    </tr>
  </tbody>
                <?php } ?>    
</table>

<div id="contenitore" class="fluid" style="box-shadow: 5px 5px 5px #666666;
   -webkit-border-radius: 12px;
-moz-border-radius: 12px;
border-radius: 12px; 
font-weight:200; 
font-family:play; 
font-size:12px; 
text-align:center;
height: 100%;
color:#666;">

<table  width="100%" cellspacing="5" style="font-style:normal;
    font-family:play;
    font-weight:200;
    font-size:16px; color:#000000;
    text-align: left;
    ">
  <tbody>
    <tr>
 <td  rowspan="6" width="35%"><?php if($img_name==0) { ?><img src="img/foto_profilo.png"><?php } else { ?><img style="max-width:200px; max-height:200px; border: 2px solid black;" src="img/CV/<?php echo $img_name;?>"><?php } ?></td>
        <td  width="30%" ><b>Nome e Cognome:</b></td>
      <td width="35%"><?php echo $nome . " " . $cognome; ?></td>
    </tr>
    <tr>
        <td ><b>Data di Nascita:</b></td>
      <td ><?php echo $luogo_nascita . ", " . $data_nascita[2] . "/" . $data_nascita[1] . "/" . $data_nascita[0] ?></td>
    </tr>
    <tr>
        <td ><b>Residenza</b></td>
      <td ><?php echo $citta_residenza . ", " . $indirizzo_res; ?></td>
    </tr>
    <tr>
        <td ><b>Telefono</b></td>
      <td ><?php echo $telefono; ?></td>
    </tr>
    <tr>
        <td ><b>Email</b></td>
      <td ><?php echo $email ?></td>
    </tr>
    <tr>
        <td ><b>Codice Fiscale</b></td>
      <td ><?php echo $codice_fiscale ?></td>
    </tr>
  </tbody>
</table>
    <br><br>

  
                                            <?php
                $sql_esperienza = "SELECT 
							id_esp,
							azienda,
							indirizzo,
							mansione,
							ruolo,
                            area,
                            sub_area,
							da,
							a,
							attuale,
							note
							FROM esperienza WHERE user_id=" . $codice;


                $result_esperienza = mysql_query($sql_esperienza, $conn->db);

                if (!$result_esperienza) {
                    die('Errore caricamento dati 3: ' . mysql_error());
                } else {
                    if (mysql_num_rows($result_esperienza) > 0) {
                        ?>
                        <br><br>
                                <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:18px; color:#000000; " width="100%"  cellspacing="0" cellpadding="0" border="0">
                                    <tr>  
                                        <td width="25%" rowspan="4"><b><u><i>Esperienza Professionale</b></u></i></td>
                                        <td width="75%"></td>
                                    </tr>

                                </table>
                                <?php
                            }
                            while ($row3 = mysql_fetch_array($result_esperienza)) {

                                $azienda = $row3['azienda'];
                                $indirizzo = $row3['indirizzo'];
                                $note = trim($row3['note']);
                                $mansione = $row3['mansione'];
                                $ruolo = $row3['ruolo'];
                                $area=$row3['area'];
                                $sub_area=$row3['sub_area'];
                                list($da_anno_esperienza, $da_mese_esperienza, $da_giorno_esperienza) = explode("-", $row3['da']);
                                list($a_anno_esperienza, $a_mese_esperienza, $a_giorno_esperienza) = explode("-", $row3['a']);
                                $datada = $da_giorno_esperienza . "/" . $da_mese_esperienza . "/" . $da_anno_esperienza;
                                $dataa = $a_giorno_esperienza . "/" . $a_mese_esperienza . "/" . $a_anno_esperienza;
                                if ($a_anno_esperienza == "0000")
                                    $dataa = "data attuale";
                                ?>
                                <br>
                                <div id="contenitore1" class="fluid ">
                                    <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:16px; color:#000000;" width="100%" cellspacing="0" cellpadding="5" border="0">
                                        <tr>  
                                            <td width="25%"><b><?php echo $datada .' - '. $dataa?></b></td>
                                            <td width="75%"><b><?php echo $area . " - " . $sub_area?></b></td>
                                        </tr>
                                        <tr style="color:#000;">
                                            <td style="font-size:11px; text-align:right;">Azienda:</td>
                                            <td ><?php echo $azienda . ', ' . $indirizzo ?></td>
                                        </tr>
                                         <tr style="color:#000;">
                                            <td style="font-size:11px; text-align:right;">Ruolo:</td>
                                            <td ><?php echo $ruolo?></td>
                                        </tr>
                                        <tr style="color:#000;">
                                            <td style="font-size:11px; text-align:right;">Mansione:</td>
                                            <td><?php echo $mansione?></td>
                                        </tr>
                                        <tr style="color:#000;">
                                            <td style="font-size:11px; text-align:right;">Note:</td>
                                            <td><?php echo $note ?></td>
                                        </tr>
                                    </table>
                                </div>
                                    <?php
                                }
                            }


                            $sql_formazione = "SELECT 
							id_form,
							istituto,
							titolo,
							corso,
							laurea,
							voto,
							da,
							a,
							descrizione,
							esperienza_estera
							FROM formazione WHERE user_id=" . $codice;


                            $result_formazione = mysql_query($sql_formazione, $conn->db);

                            if (!$result_formazione) {
                                die('Errore caricamento dati 3: ' . mysql_error());
                            } else {
                                if (mysql_num_rows($result_formazione) > 0) {
                                    ?>
                                    <br>
                                        <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:18px; color:#000000;" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>  
                                                <td width="25%" rowspan="4"><b><u><i>Istruzione e Formazione</b></u></i></td>
                                                <td width="75%"> </td>
                                            </tr>

                                        </table>
                                        <?php
                                    }
                                    while ($row3 = mysql_fetch_array($result_formazione)) {

                                        list($da_anno_formazione, $da_mese_formazione, $da_giorno_formazione) = explode("-", $row3['da']);
                                        list($a_anno_formazione, $a_mese_formazione, $a_giorno_formazione) = explode("-", $row3['a']);
                                        $datadaf = $da_giorno_formazione . "/" . $da_mese_formazione . "/" . $da_anno_formazione;
                                        $dataaf = $a_giorno_formazione . "/" . $a_mese_formazione . "/" . $a_anno_formazione;
                                        if ($a_anno_formazione == "0000")
                                            $dataaf = "data attuale";
                                        if ($da_anno_formazione == "0000")
                                            $datadaf = "Non Definito";
                                        $istituto = $row3['istituto'];
                                        $corso = $row3['corso'];
                                        $laurea = $row3['laurea'];
                                        $descrizionef = trim($row3['descrizione']);
                                        $titolo = $row3['titolo'];
                                        $voto = $row3['voto'];
                                        ?>
                                        <br>
                                         <div id="contenitore1" class="fluid ">
                                            <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:16px; color:#000000;" width="100%" cellspacing="0" cellpadding="5" border="0">
                                                <tr>  
                                                    <td width="25%"><b><?php echo $datadaf .' - '. $dataaf?></b></td>
                                                    <td width="75%"><b><?php echo $titolo.' '. $corso.' '. $laurea?></b></td>
                                                </tr>
                                                <tr style="color:#000;">
                                                    <td style="font-size:11px; text-align:right;">Tipo Istituto:</td>
                                                    <td><?php echo $istituto?></td>
                                                </tr>
                                                <?php if($voto!="") {?>
                                                <tr style="color:#000;">
                                                    <td style="font-size:11px; text-align:right;">Voto:</td>
                                                    <td><?php echo $voto ?></td>
                                                </tr>
                                                <?php } ?>
                                                <tr style="color:#000;">
                                                    <td style="font-size:11px; text-align:right;">Descrizione:</td>
                                                    <td><?php echo $descrizionef?></td>
                                                </tr>

                                            </table>
                                        </div>
                                            <?php
                                        }
                                    }

                                    $sql3_conoscenze = "SELECT 
							id,
							tipologia,
							descrizione,
							livello 
							FROM conoscenze WHERE user_id=" . $codice;


                                    $result3_conoscenze = mysql_query($sql3_conoscenze, $conn->db);

                                    if (!$result3_conoscenze) {
                                        die('Errore di inserimento dati 3: ' . mysql_error());
                                    } else {
                                        if (mysql_num_rows($result3_conoscenze) > 0) {
                                            ?>
                                            <br>
                                                <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:18px; color:#000000;" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                    <tr>  
                                                        <td width="25%" rowspan="4"><b><u><i>Competenze Acquisite</b></u></i></td>
                                                        <td width="75%"> </td>
                                                    </tr>

                                                </table>
                                                <?php
                                            }
                                            while ($row3 = mysql_fetch_array($result3_conoscenze)) {

                                                $tipologia = $row3['tipologia'];
                                                $descrizionecon = trim($row3['descrizione']);
                                                $livello = $row3['livello'];
                                                ?>
                                                <br>
                                                 <div id="contenitore1" class="fluid ">
                                                    <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:16px; color:#000000;" width="100%" cellspacing="0" cellpadding="5" border="0">
                                                        <tr>  
                                                            <td width="25%" style="font-size:11px; text-align:right; color:#000;">Tipologia:</td>
                                                            <td width="75%"><b><?php echo $tipologia ?></b></td>
                                                        </tr>
                                                        <tr style="color:#000;">
                                                            <td style="font-size:11px; text-align:right;">Descrizione:</td>
                                                            <td><?php echo $descrizionecon?></td>
                                                        </tr>
                                                        <tr style="color:#000;">
                                                            <td style="font-size:11px; text-align:right;">Livello:</td>
                                                            <td><?php echo $livello ?></td>
                                                        </tr>

                                                    </table>
                                                </div>
        <?php
    }
}

$sql_certificazioni = "SELECT 
							id,
							titolo_certificazione,
							cod_licenza,
							url,
							ente_certificante,
							da,
							a 
							FROM certificazione WHERE user_id=" . $codice;


$result_certificazioni = mysql_query($sql_certificazioni, $conn->db);


if (!$result_certificazioni) {
    die('Errore caricamento dati 3: ' . mysql_error());
} else {
    if (mysql_num_rows($result_certificazioni) > 0) {
        ?>
                                                    <br>
                                                        <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:18px; color:#000000;" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                            <tr>  
                                                                <td width="25%" rowspan="4"><b><u><i>Certificazioni</b></u></i></td>
                                                                <td width="75%"> </td>
                                                            </tr>

                                                        </table>
        <?php
    }
    while ($row6 = mysql_fetch_array($result_certificazioni)) {

        list($da_anno_certificazione, $da_mese_certificazione, $da_giorno_certificazione) = explode("-", $row6['da']);
        list($a_anno_certificazione, $a_mese_certificazione, $a_giorno_certificazione) = explode("-", $row6['a']);

        $titolo_cer = $row6['titolo_certificazione'];
        $ente_certificante = $row6['ente_certificante'];
        $cod_licenza = $row6['cod_licenza'];
        $url = $row6['url'];
        $datadacer = $da_giorno_certificazione . "/" . $da_mese_certificazione . "/" . $da_anno_certificazione;
        $dataacer = $a_giorno_certificazione . "/" . $a_mese_certificazione . "/" . $a_anno_certificazione;
        ?>
                                                        <br>
                                                         <div id="contenitore1" class="fluid ">
                                                            <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:16px; color:#000000;" width="100%" cellspacing="0" cellpadding="5" border="0">
                                                                <tr>  
                                                                    <td width="25%"><b><?php echo $datadacer .' - '. $dataacer?></b></td>
                                                                    <td width="75%"><b><?php echo $titolo_cer ?></b></td>
                                                                </tr>
                                                                <tr style="color:#000;">
                                                                    <td style="font-size:11px; text-align:right;">Ente Certificante:</td>
                                                                    <td><?php echo $ente_certificante ?></td>
                                                                </tr>
                                                                <tr style="color:#000;">
                                                                    <td style="font-size:11px; text-align:right;">Codice Licenza:</td>
                                                                    <td><?php echo $cod_licenza?></td>
                                                                </tr>
                                                                <tr style="color:#000;">
                                                                    <td style="font-size:11px; text-align:right;">URL:</td>
                                                                    <td><?php echo $url ?></td>
                                                                </tr>

                                                            </table>
                                                        </div>
        <?php
    }
}



$sql_corsi = "SELECT 
							id_corso,
							tipo,
							descrizione
							FROM corsi WHERE user_id=" . $codice;


$result_corsi = mysql_query($sql_corsi, $conn->db);

if (!$result_corsi) {
    die('Errore di inserimento dati: ' . mysql_error());
} else {
    if (mysql_num_rows($result_corsi) > 0) {
        ?>
                                                            <br>
                                                                <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:18px; color:#000000;" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                                    <tr>  
                                                                        <td width="25%" rowspan="4"><b><u><i>Corsi di Sicurezza</b></u></i></td>
                                                                        <td width="75%"></td>
                                                                    </tr>

                                                                </table>
        <?php
    }
    while ($row3 = mysql_fetch_array($result_corsi)) {

        $tipo = $row3['tipo'];
        $descrizionecor = trim($row3['descrizione']);
        ?>
                                                                <br>
                                                                 <div id="contenitore1" class="fluid ">
                                                                    <table style="font-style:normal;
	font-family:play;
	font-weight:200;
	font-size:16px; color:#000000;" width="100%" cellspacing="0" cellpadding="5" border="0">
                                                                        <tr>  
                                                                            <td width="25%" style="font-size:11px; text-align:right; color:#000;">Tipologia:</td>
                                                                            <td width="75%"><b><?php echo $tipo ?></b></td>
                                                                        </tr>
                                                                        <tr style="color:#000;">
                                                                            <td style="font-size:11px; text-align:right;">Descrizione:</td>
                                                                            <td><?php echo $descrizionecor ?></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
        <?php
    }
}
?>
                                            
                                            
</div>

                    
                    

</div>
</div>                  
 
</body>
</html>



