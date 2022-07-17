<?php
session_start();

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

include('dbconn.php');
require_once('tcpdf_include.php');
$conn = new dbconnect();
$r = $conn->connect();
$cod_user = $_SESSION['user_idd'];
$cod_anagr = $_SESSION['user_idd'];

ob_start();

if ($cod_user != 0) {

   if(!isset($_GET['id']))
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
                            phone,
                            codice_fiscale,img_name FROM anagrafica WHERE user_id=" . $cod_user;


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);
    }
    else if (isset($_GET['id']))
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
                            phone,
                            codice_fiscale,
                            img_name FROM anagrafica WHERE user_id=" . $_GET['id'];


    $result3_anagrafica = mysql_query($sql3_anagrafica, $conn->db);
    }


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
            $phone = $row['phone'];
            $codice_fiscale = $row['codice_fiscale'];
            $img_name = $row['img_name'];
        }
    }

    if(!isset($_GET['id']))
    {

            $sql4_anagrafica = "SELECT

                            email
                                                        FROM login WHERE user_idd=" . $cod_user;

    }  else if (isset($_GET['id']))
    {
            $sql4_anagrafica = "SELECT

							email
                                                        FROM login WHERE user_idd=" . $_GET['id'];
}

    $result4_anagrafica = mysql_query($sql4_anagrafica, $conn->db);

    if (!$result4_anagrafica) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        while ($row = mysql_fetch_array($result4_anagrafica, MYSQL_ASSOC)) {
            $telefono = $row['phone'];
            $email = $row['email'];
        }
    }
}



require_once('tcpdf_include.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = 'https://www.italyformovies.it/media/img/europass_logo.jpg';
		$this->Image($image_file, 18, 2, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', '', 8);
		// Title
		$this->Cell(0, 15, '                                              Curriculum Vitae', 0, false, 'L', 0, '', 0, false, 'T', 'B');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($nome. " ". $cognome);
$pdf->SetTitle('CV_'.$cognome. "_". $nome);
$pdf->SetSubject('CV');
$pdf->SetKeywords('CV, PDF');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// set font
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor($col1 = 51,$col2 = 59,$col3 = 140,$col4 = -1,$ret = false,$name = '');
// add a page
$pdf->AddPage();
if(!isset($_GET['id']))
    {
        $codice=$cod_user;
    }
    else if (isset($_GET['id']))
    {
            $codice=$_GET['id'];
    }



$img="https://www.service-tech.it/new_area_riservata/assets/img/CV/".$img_name;


$horizontal_alignments = array('L', 'C', 'R');
$vertical_alignments = array('T', 'M', 'B');

$x = 20;
$y = 35;
$w = 35;
$h = 35;
$fitbox = $horizontal_alignments[1].' ';
$fitbox[1] = $vertical_alignments[1];

$pdf->Image($output, $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);

$tbl ='<table cellspacing="0" cellpadding="1" border="0" align="left">
    <tr>

        <td width="165px"><b>Informazioni Personali</b></td>
        <td width="115px" colspan="1"></td>
    </tr>
    <tr>
        <td rowspan="7"></td>
        <td></td>
    </tr>
        <tr >
        <td><b>Nome e Cognome</b>:</td>
            <td style="color:#000;"><b>'.$nome .' '.$cognome.'</b></td>
        </tr>
        <tr >
        <td><b>Data di nascita</b>:</td>
            <td style="color:#000;">'.$luogo_nascita.', '. $data_nascita .'</td>
        </tr>
    <tr>
    	<td><b>Residenza</b>:</td>
            <td style="color:#000;">'.$citta_residenza.', '. $indirizzo_res.'</td>
    </tr>
         <tr>
       <td><b>Telefono</b>:</td>
        <td style="color:#000;">'.$phone.'</td>
    </tr>
        <tr>
       <td><b>Email</b>:</td>
        <td style="color:#000;">'.$email.'</td>
    </tr>
    <tr>
       <td><b>Codice Fiscale</b>:</td>
        <td style="color:#000;">'.$codice_fiscale.'</td>
    </tr>
</table>';
 $tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');

$sql_esperienza = "SELECT
							id_esp,
							azienda,
							indirizzo,
							mansione,
							ruolo,
							da,
							a,
							attuale,
							note
							FROM esperienza WHERE user_id=" . $codice ." ORDER BY str_to_date(da, '%d/%m/%Y') DESC";


    $result_esperienza = mysql_query($sql_esperienza, $conn->db);

    if (!$result_esperienza) {
        die('Errore caricamento dati 3: ' . mysql_error());
    } else {
        if(mysql_num_rows($result_esperienza)>0)
        {
   $tbl = <<<EOD
        <br><br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" rowspan="4"><b>Esperienza Professionale</b></td>
        <td width="75%"> _________________________________________________________________</td>
    </tr>

</table>
EOD;

     $tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
        }
        while ($row3 = mysql_fetch_array($result_esperienza)) {

						$id = $row3['id_esp'];
                        $azienda= $row3['azienda'];
                         $indirizzo= $row3['indirizzo'];
                         $note= trim($row3['note']);
                         $mansione= $row3['mansione'];
                         $ruolo= $row3['ruolo'];
			list($da_giorno_esperienza,$da_mese_esperienza,$da_anno_esperienza) = explode("/",$row3['da']);
			list($a_giorno_esperienza,$a_mese_esperienza,$a_anno_esperienza) = explode("/",$row3['a']);
                        $datada= $da_giorno_esperienza."/".$da_mese_esperienza."/".$da_anno_esperienza;
                        $dataa= $a_giorno_esperienza."/".$a_mese_esperienza."/".$a_anno_esperienza;
                        if($a_anno_esperienza=="0000") $dataa= "data attuale";


			$sql_skill = "SELECT * FROM skill WHERE id_esp=" . $id;
    $result_skill = mysql_query($sql_skill, $conn->db);

			if(mysql_num_rows($result_skill)>0){
				$skill="<ul>";
     while ($row_skill = mysql_fetch_array($result_skill)) {

		$skill .= "<li>".$row_skill['skill']." - ".$row_skill['livello_skill']."</li>";

	 }
				$skill.="</ul>";
			}

			//mail("marco.salmi89@gmail.com","prova",$skill);

$tbl = '<br>
<table cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td width="25%">'.$datada.' - '.$dataa.'</td>
        <td width="75%"><b>'.$ruolo.'</b></td>
    </tr>
    <tr style="color:#000;">
        <td style="font-size:11px; text-align:right;">Azienda:</td>
        <td >'.$azienda.','. $indirizzo.'</td>
        </tr>
         <tr style="color:#000;">
       <td style="font-size:11px; text-align:right;">Mansione:</td>
           <td>'.$mansione.'</td>
    </tr>';
	if($skill!=""){
$tbl.='<tr style="color:#000;">
       <td style="font-size:11px; text-align:right;">Skill:</td>
           <td>'.$skill.'</td>
    </tr>
	</table>';
	}
$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');

        }
    }


  $sql_formazione = "SELECT
							id_form,
							titolo,
							corso,
							laurea,
							titolo_studi,
							voto,
							da,
							a,
							descrizione,
							esperienza_estera
							FROM formazione WHERE user_id=" . $codice ." ORDER BY str_to_date(da, '%Y') DESC";


    $result_formazione = mysql_query($sql_formazione, $conn->db);
//mail("marco.salmi89@gmail.com","prova",$sql_formazione);
    if (!$result_formazione) {
        die('Errore caricamento dati 3: ' . mysql_error());
    } else {
        if(mysql_num_rows($result_formazione)>0)
        {
			//mail("marco.salmi89@gmail.com","prova",mysql_num_rows($result_formazione));
		  $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" rowspan="4"><b>Istruzione e Formazione</b></td>
        <td width="75%"> _________________________________________________________________</td>
    </tr>

</table>
EOD;

$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
        }
        while ($row3 = mysql_fetch_array($result_formazione)) {
			//mail("marco.salmi89@gmail.com","prova","ciao");
			list($da_anno_formazione,$da_mese_formazione,$da_giorno_formazione) = explode("-",$row3['da']);
			list($a_anno_formazione,$a_mese_formazione,$a_giorno_formazione) = explode("-",$row3['a']);
                        $datadaf= $da_anno_formazione;
                        $dataaf= $a_anno_formazione;
                        if($a_anno_formazione=="0000") $dataaf= "data attuale";
                        if($row3['corso']!="") $corso = $row3['corso'];
                        else $corso = "";
                        $titolo_studi=$row3['titolo_studi'];
                        if($row3['laurea']!="") $laurea = "- ".$row3['laurea'];
                        else $laurea = "";
                        $descrizionef=trim($row3['descrizione']);
                        $titolo=$row3['titolo'];
                        $voto=$row3['voto'];

			//mail("marco.salmi89@gmail.com","prova",$titolo_studi);
   $tbl ='
        <br>
<table cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td width="25%">'.$datadaf.' - ' .$dataaf.'</td>
        <td width="75%"><b>'.$corso.' '. $laurea.'</b></td>
    </tr>
        <tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Titolo Studi:</td>
        <td>'.$titolo_studi.'</td>
        </tr>';

if($voto!=""){

$tbl.= '<tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Voto:</td>
    	<td>'.$voto.'</td>
    </tr>';
}

if($descrizionef!=""){

$tbl.='<tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Materie Principali:</td>
       <td>'.$descrizionef.'</td>
    </tr>';
}
$tbl.= '</table>';

$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
 //mail("marco.salmi89@gmail.com","prova",$tbl);
        }
    }

    $sql3_conoscenze = "SELECT
							lingua,
							scrittura,
							lettura,
							espressione
							FROM lingue_straniere WHERE user_id=" . $_GET['id'];


    $result3_conoscenze = mysql_query($sql3_conoscenze, $conn->db);

    if (!$result3_conoscenze) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        if(mysql_num_rows($result3_conoscenze)>0)
        {
        $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" rowspan="4"><b>Lingue Straniere</b></td>
        <td width="75%"> _________________________________________________________________</td>
    </tr>

</table>
EOD;
                  $tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
        }
        while ($row3 = mysql_fetch_array($result3_conoscenze)) {

                        $lingua=$row3['lingua'];
                        $scrittura=$row3['scrittura'];
                        $lettura=$row3['lettura'];
$espressione=$row3['espressione'];

   $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td width="25%" style="font-size:11px; text-align:right; color:#000;">Lingua:</td>
        <td width="75%"><b>$lingua</b></td>
    </tr>
        <tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Lettura:</td>
        <td>$lettura</td>
        </tr>
    <tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Scrittura:</td>
    	<td>$scrittura</td>
    </tr>

   <tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Espressione Orale:</td>
    	<td>$espressione</td>
    </tr>

</table>
EOD;

$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
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
							FROM certificazione WHERE user_id=" . $codice ." ORDER BY str_to_date(da, '%Y') DESC";


            $result_certificazioni = mysql_query($sql_certificazioni, $conn->db);


            if (!$result_certificazioni) {
                die('Errore caricamento dati 3: ' . mysql_error());
            } else {
                if(mysql_num_rows($result_certificazioni)>0)
                {
            $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" rowspan="4"><b>Certificazioni</b></td>
        <td width="75%"> _________________________________________________________________</td>
    </tr>

</table>
EOD;
                  $tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
                }
               while ($row6 = mysql_fetch_array($result_certificazioni)) {

                    list($da_anno_certificazione, $da_mese_certificazione, $da_giorno_certificazione) = explode("/", $row6['da']);
                    list($a_anno_certificazione, $a_mese_certificazione, $a_giorno_certificazione) = explode("/", $row6['a']);

                        $titolo_cer=$row6['titolo_certificazione'];
                        $ente_certificante=$row6['ente_certificante'];
                        $cod_licenza=$row6['cod_licenza'];
                        $url=$row6['url'];
                        $datadacer= $da_anno_certificazione;
                        $dataacer= $a_anno_certificazione;
                        $dataa= $row6['a'];
                        $datada= $row6['da'];
                        if($dataa=="00/00/0000") $dataa = "Nessuna Scadenza";



    $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td width="25%">$datada - $dataa</td>
        <td width="75%"><b>$titolo_cer</b></td>
    </tr>
<tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Ente Certificante:</td>
    	<td>$ente_certificante</td>
    </tr>

</table>
EOD;

$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
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
                if(mysql_num_rows($result_corsi)>0)
                {
             $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" rowspan="4"><b>Corsi di Sicurezza</b></td>
        <td width="75%"> _________________________________________________________________</td>
    </tr>

</table>
EOD;
                  $tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
                }
                while ($row3 = mysql_fetch_array($result_corsi)) {

                         $tipo=$row3['tipo'];
                        $descrizionecor=trim($row3['descrizione']);

   $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td width="25%" style="font-size:11px; text-align:right; color:#000;">Tipologia:</td>
        <td width="75%"><b>$tipo</b></td>
    </tr>
        <tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Descrizione:</td>
        <td>$descrizionecor</td>
        </tr>
</table>
EOD;

$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
        }
    }

$sql3_conoscenze = "SELECT
							tipo_competenza,
							descrizione
							FROM riepilogo_competenze WHERE user_id=" . $codice;


    $result3_conoscenze = mysql_query($sql3_conoscenze, $conn->db);

    if (!$result3_conoscenze) {
        die('Errore di inserimento dati 3: ' . mysql_error());
    } else {
        if(mysql_num_rows($result3_conoscenze)>0)
        {
        $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" rowspan="4"><b>Competenze</b></td>
        <td width="75%"> _________________________________________________________________</td>
    </tr>

</table>
EOD;
                  $tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
        }
        while ($row3 = mysql_fetch_array($result3_conoscenze)) {

                        if($row3['tipo_competenza']=="1") $tipo_competenza = "Competenze Informatiche";
                        if($row3['tipo_competenza']=="2") $tipo_competenza = "Altre Competenze";
                        $descrizione=$row3['descrizione'];

   $tbl = <<<EOD
        <br>
<table cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td width="25%" style="font-size:11px; text-align:right; color:#000;">Tipo Competenza:</td>
        <td width="75%"><b>$tipo_competenza</b></td>
    </tr>
        <tr style="color:#000;">
           <td style="font-size:11px; text-align:right;">Descrizione:</td>
        <td>$descrizione</td>
        </tr>
</table>
EOD;

$tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
        }
    }

   $tbl = <<<EOD
        <br><br>
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td width="25%" rowspan="4"><b>Dati Personali</b></td>
        <td width="75%" style="color:#000;">Autorizzo il trattamento dei miei dati personali ai sensi del Decreto Legislativo 30 giugno 2003, n. 196 "Codice in materia di protezione dei dati personali”.</td>
    </tr>

</table>
EOD;
                  $tbl=utf8_encode($tbl);
$pdf->writeHTML($tbl, true, false, false, false, '');
//Close and output PDF document
ob_end_clean();
$pdf->Output('example_cv.pdf', 'I');
