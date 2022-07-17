<?php

session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/message.class.php');
require_once('class/attivita.class.php');
require_once('assets/php/functions.php');
require_once('class/info_hr.class.php');
include('redirect.php');

$database = new Database();
$conn = $database->dbConnection();

$ore_max = new InfoHR();
$ore_max = $ore_max->getOreGiornoByUserId($_SESSION['user_idd']);
$ore_max = number_format($ore_max,'2','.','');

$giorni_max = new InfoHR();
$giorni_max = $giorni_max->getGiorniSettimanaByUserId($_SESSION['user_idd']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AREA RISERVATA - Timesheet</title>

    <script type="text/javascript" src="assets/lib/jquery-1.3.2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/calendario.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.multidatespicker.css">
    <link rel="stylesheet" type="text/css" href="assets/scss/style.scss">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <script src="assets/js/chart-master/Chart.js"></script>
    <script>
        function toggleMe(obj, timecardEvent, extraHoursEvent) {
          var timecardBox = document.getElementById(timecardEvent);
          var extraHoursBox = document.getElementById(extraHoursEvent);
          if (obj != "Presente" && obj != "Lavoro Agile" && obj != "Permesso" && obj != "Permesso Studi" &&
                obj != "Permesso 104" && obj != "Donazione Sangue" && obj != "Cure Termali") {
            timecardBox.style.display = "none";
            if (obj == "Straordinario" || obj == "Lavoro Agile" || obj == "Permesso" || obj == "Permesso Studi" ||
                    obj == "Permesso 104" || obj == "Donazione Sangue" || obj == "Cure Termali" || obj == "Reperibilità") {
                extraHoursBox.style.display = "";
            } else {
                extraHoursBox.style.display = "none";
            }
          } else {
            timecardBox.style.display = "";
            extraHoursBox.style.display = "none";
            if(obj == "Permesso" || obj== "Permesso Studi" || obj == "Permesso 104" ||
                    obj == "Donazione Sangue" || obj == "Cure Termali" || obj == "Reperibilità") {
                extraHoursBox.style.display = "";
            }
          }
        }
    </script>
    <script>
        function setOre(ore,ore_max) {
            val = ore_max - ore;
            val = val.toFixed(2);
            var elements = document.querySelectorAll('input[type=checkbox]:checked');

            for (var i = 0; i < elements.length; i++) {
                document.getElementById('ore_'+elements[i].value).value = val;
                if(val<=0) document.getElementById('commessa_check_'+elements[i].value).checked = false;
            }

            if(elements.length == 0) {
                if(val > 0) {
                    var element = document.querySelectorAll('input[type=checkbox]');
                    document.getElementById('commessa_check_'+element[0].value).checked = true;
                }
            }

        }

        function setOreDettaglio(ore, ore_max) {

            val = ore_max - ore;
            val = val.toFixed(2);

            var elements = document.querySelectorAll('input[type=checkbox]:checked');
		    for (var i = 0; i < elements.length; i++) {
                document.getElementById('ore_dettaglio_' + elements[i].value).value = val;
                if(val <= 0) document.getElementById('commessa_dettaglio_' + elements[i].value).checked = false;
            }

	        if (elements.length == 1) {
	            if(val > 0) {
				    var element = document.querySelectorAll('input[type=checkbox]');
				    document.getElementById('commessa_dettaglio_' + element[0].value).checked = true;
	            }
	        }
	    }
    </script>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <!--[endif]-->

  </head>

  <body>
    <section id="container" >
        <?php
        include('menu.php');

        if (isset($_POST['auto_presenze'])){
            $attivita = new Attivita();
            $attivita->insertAutoPresences($_SESSION['user_idd']);

}

if (isset($_POST['aggiungi'])) {
    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
        $id_mese1 = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    $hoursArray = array();
    foreach($_REQUEST['commesse'] as $value) {
        array_push($hoursArray, $_REQUEST['ore_' . $value]);
    }

    if ($_REQUEST['tipo'] == "Permesso Studi" || $_REQUEST['tipo'] == "Permesso" || $_REQUEST['tipo'] == "Permesso 104" || $_REQUEST['tipo'] == "Donazione Sangue") {
        $attivita = new Attivita();
        $add_attivita = $attivita->setAttivita($_GET['id'], $_REQUEST['data'], $hoursArray, 'Presente', $_REQUEST['commesse'], $_REQUEST['nota']);
        $attivita = new Attivita();
        $add_attivita = $attivita->setAttivitaPer($_GET['id'], $_REQUEST['data'], $_REQUEST['ore_extra'], $_REQUEST['tipo'], NULL, $_REQUEST['nota']);
    } else if ($_REQUEST['tipo'] == "Straordinario" || $_REQUEST['tipo'] == "Reperibilità") {
        $attivita = new Attivita();
        $add_attivita = $attivita->setAttivitaPer($_GET['id'], $_REQUEST['data'], $_REQUEST['ore_extra'], $_REQUEST['tipo'], $_REQUEST['commesse'], $_REQUEST['nota']);
    } else {
        $attivita = new Attivita();
        $add_attivita = $attivita->setAttivita($_GET['id'], $_REQUEST['data'], $hoursArray, $_REQUEST['tipo'], $_REQUEST['commesse'], $_REQUEST['nota']);
    }
}

if (isset($_POST['modifica'])) {
    /* ==> DA COMPLETARE <==*/

    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
        $id_mese1 = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    $hoursArray = array();

    foreach($_REQUEST['commesse'] as $value) {
        array_push($hoursArray, $_REQUEST['ore_' . $value]);
    }
    $conto = count($_REQUEST['commesse']);

    if($_REQUEST['tipo'] == "Ferie" || $_REQUEST['tipo'] == "Maternita" || $_REQUEST['tipo'] == "Malattia" ||
            $_REQUEST['tipo'] == "Congedo Parentale" || $_REQUEST['tipo'] == "Lutto" || $_REQUEST['tipo'] == "Malattia Bambino" ||
            $_REQUEST['tipo'] == "Congedo Starordinario" || $_REQUEST['tipo'] == "Congedo Matrimoniale") {
        $attivita = new Attivita();
        $del_attivita = $attivita->delAltreAttivita($_GET['id'],$_REQUEST['data']);
    }

    if($_REQUEST['timecard-presenze']!="" || $_REQUEST['tipo'] == "Presente" || $conto>0) {

        $attivita = new Attivita();
        $del_attivita = $attivita->delAttivita($_REQUEST['attivita_evento']);
        $presenze= explode('!', $_REQUEST['timecard-presenze']);
        for($i = 0; $i <= count($presenze); $i++) {
              $p = explode("$",$presenze[$i]);
              $del_attivita = $attivita->delAttivita($p[0]);
        }

        if($_REQUEST['tipo'] != "Permesso" && $_REQUEST['tipo'] != "Permesso 104" && $_REQUEST['tipo'] != "Permesso Studi" &&
            $_REQUEST['tipo'] != "Donazione Sangue") {

          if($_REQUEST['tipo'] == "Straordinario") {
            $attivita = new Attivita();
            $add_attivita = $attivita->setAttivitaPer($_GET['id'], $_REQUEST['data'], $_REQUEST['ore_extra_event'], $_REQUEST['tipo'],  "", $_REQUEST['nota']);
          } else {
          foreach($_REQUEST['commesse'] as $value) {
                    $attivita = new Attivita();
                    $add_attivita = $attivita->setAttivita($_GET['id'], $_REQUEST['data'], $hoursArray, $_REQUEST['tipo'], $_REQUEST['commesse'], $_REQUEST['nota']);
            }
          }
        } else {
            if ($_REQUEST['tipo'] == "Permesso" || $_REQUEST['tipo'] == "Permesso Studi" || $_REQUEST['tipo'] == "Permesso 104" ||
                    $_REQUEST['tipo'] == "Donazione Sangue") {
                $attivita = new Attivita();
                $del_attivita_day = $attivita->delAltreAttivita($_GET['id'],$_REQUEST['data']);
                foreach($_REQUEST['commesse'] as $value) {
                    $attivita = new Attivita();
                    $add_attivita = $attivita->setAttivita($_GET['id'], $_REQUEST['data'], $hoursArray, 'Presente', $_REQUEST['commesse'], $_REQUEST['nota']);
                }
            }
            $attivita = new Attivita();
            $add_attivita = $attivita->setAttivitaPer($_GET['id'], $_REQUEST['data'], $_REQUEST['ore_extra_event'], $_REQUEST['tipo'], "", $_REQUEST['nota']);
        }
    } else {
        $commessa = array(0 => 0);
        $ore_array[0] = $_REQUEST['ore_extra_event'];
        if($_REQUEST['tipo']=="Straordinario") $ore_array[0] = $_REQUEST['ore_extra_event_dettaglio'];
        $attivita = new Attivita();
        $del_attivita = $attivita->delAttivita($_REQUEST['attivita_evento']);
        $attivita = new Attivita();
        $add_attivita = $attivita->setAttivita($_GET['id'], $_REQUEST['data'], $ore_array, $_REQUEST['tipo'],  $commessa, $_REQUEST['nota']);
    }
}

if(isset($_POST['elimina'])) {
    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese']!="") {
        $id_mese1 = $_REQUEST['id_mese'];
        $id_mese = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno']!="") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if ($_REQUEST['tipo'] == "Presente" || $_REQUEST['tipo'] == "Recupero") {
        $attivita = new Attivita();
        $add_attivita = $attivita->delAttivitaPres($_GET['id'],$_REQUEST['data'],$_REQUEST['tipo']);
    } else {
        $attivita = new Attivita();
        $add_attivita = $attivita->delAttivita($_REQUEST['attivita_evento']);
    }
}


if (isset($_POST['next'])) {
    if(isset($_REQUEST['id_mese'])&&$_REQUEST['id_mese']!="") {
        $id_mese = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno'])&&$_REQUEST['id_anno']!="") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if($id_mese=="01") $id_mese = 1;
    if($id_mese=="02") $id_mese = 2;
    if($id_mese=="03") $id_mese = 3;
    if($id_mese=="04") $id_mese = 4;
    if($id_mese=="05") $id_mese = 5;
    if($id_mese=="06") $id_mese = 6;
    if($id_mese=="07") $id_mese = 7;
    if($id_mese=="08") $id_mese = 8;
    if($id_mese=="09") $id_mese = 9;

    if($id_mese == 12){
        $id_anno++;
        $id_mese = 1;
    }
    else {
        $id_mese= $id_mese + 1;
    }
 }

if(isset($_POST['prev'])){
    if (isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }

    if (isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if($id_mese==1) {$id_mese= 12;
        $id_anno = $id_anno - 1;
    }
    else {
        $id_mese= $id_mese - 1;
    }
 }


if (!isset($id_mese)) {
    $id_mese = date("m");
    $id_mese1 = date("m");
} else {
    if (!isset($id_mese1)) {
        if ($id_mese <= 9) $id_mese1 = "0" . $id_mese;
        else $id_mese1 = $id_mese;
    }
 }

if (!isset($id_anno)) {
    $id_anno = date("Y");
}


        if($id_mese==1) $mese_cal="Gennaio";
	if($id_mese==2) $mese_cal="Febbraio";
	if($id_mese==3) $mese_cal="Marzo";
	if($id_mese==4) $mese_cal="Aprile";
	if($id_mese==5) $mese_cal="Maggio";
	if($id_mese==6) $mese_cal="Giugno";
	if($id_mese==7) $mese_cal="Luglio";
	if($id_mese==8) $mese_cal="Agosto";
	if($id_mese==9) $mese_cal="Settembre";
	if($id_mese==10) $mese_cal="Ottobre";
	if($id_mese==11) $mese_cal="Novembre";
	if($id_mese==12) $mese_cal="Dicembre";

        if (!isset($_REQUEST['num_giorni'])) {
			if(!checkdate($id_mese,28+1,$id_anno)) { $num_giorni = 28;}
			else if(!checkdate($id_mese,29+1,$id_anno)) { $num_giorni = 29;}
			else if(!checkdate($id_mese,30+1,$id_anno)) { $num_giorni = 30;}
			else if(!checkdate($id_mese,31+1,$id_anno)) { $num_giorni = 31;}
}

 ?>

<!-- GESTIONE RICHIESTA FERIE -->

<?php

if(isset($_POST['ferie-input'])) {
	$infoHR = new InfoHR();
	$oreGiorno = $infoHR->getOreGiornoByUserId($_SESSION['user_idd']);

	$attivita = new Attivita();
	$vacationsStored = $attivita->getVacationToApprove($_SESSION['user_idd']);

	$attivita->requestVacation($_SESSION['user_idd'], $_POST['ferie-input'], $oreGiorno, $vacationsStored);
}

if(isset($_GET['id-vacation-to-delete'])) {
	$attivita = new Attivita();
	$resultDelete = $attivita->delAttivita($_GET['id-vacation-to-delete']);
	if($resultDelete == 'error') {
		consoleLog("Errore eliminazione Richiesta Ferie");
	}
}
?>

      <!-- **********************************************************************************************************************************************************
      CONTENUTO PRINCIPALE
      *********************************************************************************************************************************************************** -->
      <!--INIZIO CONTENUTO PRINCIPALE-->
   <section id="main-content">
            <section class="wrapper">
               <!-- CALENDARIO -->
               <center>
                  <div class="calendar-container">
                     <div class="panel panel-primary">
                        <div class="calendar-heading">
                            <form action="" method="post">
                                <button type="submit" name="prev" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-left fa-2x" aria-hidden="true"></i></button>

                           <span class="mese-title"><?php echo $mese_cal . " " . $id_anno; ?></span>

                           <button type="submit" name="next" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-right fa-2x" aria-hidden="true"></i></button>

                           <input type="hidden" name="id_mese" value="<?php echo $id_mese;?>">
                           <input type="hidden" name="id_anno" value="<?php echo $id_anno;?>">
                            </form>
                        </div>

                     </div>
                  </div>
               </center>
<div class="row">
	<div class="col-md-2 col-md-offset-4" style="float: left;">
		<input type="button" class="btn btn-info" name="richiesta-ferie" value="Richiedi Ferie" style="margin-bottom: 20px;"
			    data-toggle="modal" data-target="#richiestaFerieModal">
	</div>
    <div class="col-md-1">
            <a href="mailto:HR@service-tech.it?subject=Comunicazione" style="margin-bottom: 20px;" class="btn btn-info" >Comunica con HR</a>
    </div>
</div>
                     <table class="table table-responsive table-bordered">
                        <thead>
                           <tr class="days-heading">
                              <th style="width:14.28%;">
                                 <center>
                                    <span>LUN</span>
                                 </center>
                              </th>
                              <th style="width:14.28%;">
                                 <center>
                                    <span>MAR</span>
                                 </center>
                              </th>
                              <th style="width:14.28%;">
                                 <center>
                                    <span>MER</span>
                                 </center>
                              </th>
                              <th style="width:14.28%;">
                                 <center>
                                    <span>GIO</span>
                                 </center>
                              </th>
                              <th style="width:14.28%;">
                                 <center>
                                    <span>VEN</span>
                                 </center>
                              </th>
                              <th style="width:14.28%;">
                                 <center>
                                    <span>SAB</span>
                                 </center>
                              </th>
                              <th style="width:14.28%;">
                                 <center>
                                    <span>DOM</span>
                                 </center>
                              </th>
                           </tr>
                        </thead>
                        <tbody>
		<?php

		$inizio = date("l", mktime(0, 0, 0, $id_mese, 1, $id_anno));


		if($inizio=="Monday")
		{
                    $con1=1;
                    $con2=8;
                    $con3=15;
                    $con4=22;
                    $con5=29;

                    $cont1=7;
                    $cont2=14;
                    $cont3=21;
                    $cont4=28;

                    $numero=1;
                    $tr=0;
                }


		if($inizio=="Tuesday")
		{
                    $con1=1;
                    $con2=7;
                    $con3=14;
                    $con4=21;
                    $con5=28;

                    $cont1=6;
                    $cont2=13;
                    $cont3=20;
                    $cont4=27;
                    $num_giorni += 1;
			?>
                <tr><td class="no-day"></td>

                    <?php
                    $numero=2;
                 $tr=1;
                }

                if($inizio=="Wednesday")
		{
                    $con1=1;
                    $con2=6;
                    $con3=13;
                    $con4=20;
                    $con5=27;

                    $cont1=5;
                    $cont2=12;
                    $cont3=19;
                    $cont4=26;

                    $num_giorni += 2;
			?>
                <tr><td class="no-day"></td>
                    <td class="no-day"></td>

                    <?php
                    $numero=3;
                 $tr=2;
                }


                 if($inizio=="Thursday")
		{
                      $con1=1;
                    $con2=5;
                    $con3=12;
                    $con4=19;
                    $con5=31;

                    $cont1=4;
                    $cont2=11;
                    $cont3=18;
                    $cont4=25;

                    $num_giorni += 3;
			?>
                <tr><td class="no-day"></td>
                    <td class="no-day"></td>
                     <td class="no-day"></td>


                    <?php
                    $numero=4;
                 $tr=3;
                }
                if($inizio=="Friday")
		{
                     $con1=1;
                    $con2=4;
                    $con3=11;
                    $con4=18;
                    $con5=25;

                    $cont1=3;
                    $cont2=10;
                    $cont3=17;
                    $cont4=24;

                    $num_giorni += 4;
			?>
                <tr><td class="no-day"></td>
                    <td class="no-day"></td>
                     <td class="no-day"></td>
                     <td class="no-day"></td>


                    <?php
                    $numero=5;
                 $tr=4;
                }
                if($inizio=="Saturday")
		{
                    $con1=1;
                    $con2=3;
                    $con3=10;
                    $con4=17;
                    $con5=24;
                    $con6=31;

                    $cont1=2;
                    $cont2=9;
                    $cont3=16;
                    $cont4=23;
                    $cont5=30;

                    $num_giorni += 5;
			?>
                <tr><td class="no-day"></td>
                    <td class="no-day"></td>
                     <td class="no-day"></td>
                     <td class="no-day"></td>
                     <td class="no-day"></td>


                    <?php
                    $numero=6;
                 $tr=5;
                }

                 if($inizio=="Sunday")
		{
                    $con1=1;
                    $con2=2;
                    $con3=9;
                    $con4=16;
                    $con5=23;
                    $con6=30;

                    $cont1=1;
                    $cont2=8;
                    $cont3=15;
                    $cont4=22;
                    $cont5=29;

                    $num_giorni += 6;
			?>
                <tr><td class="no-day"></td>
                    <td class="no-day"></td>
                     <td class="no-day"></td>
                     <td class="no-day"></td>
                     <td class="no-day"></td>
                     <td class="no-day"></td>


                    <?php
                    $numero=7;
                 $tr=6;
                }
			$dayto = 0;
		for($i=$numero;$i<=$num_giorni;$i++)
		{
$button_presenza="";
                    $info="";





$t=$i-$tr;
               if($t<=9) $t1="0".$t;
               else $t1=$t;


$sql_work = "SELECT * FROM info_hr WHERE user_id =".$_GET['id'];
$result_work = $conn->query($sql_work);
$row_work = $result_work->fetch_object();

$giorni = array('Domenica','Lunedi','Martedi','Mercoledi','Giovedi','Venerdi','Sabato');
$data = $id_anno.'-'.$id_mese1.'-'.$t1;
$data_vacanza= $t1."/".$id_mese1;
//echo $row_work->giorni_settimana;

if ($row_work->giorni_settimana=="6"&&$giorni[date('w',strtotime($data))]=="Domenica") $work="no";
else if ($row_work->giorni_settimana=="5"&&($giorni[date('w',strtotime($data))]=="Sabato"||$giorni[date('w',strtotime($data))]=="Domenica")) $work="no";
else $work = "si";

$vacanze = array();
array_push($vacanze, "15/08");
array_push($vacanze, "01/01");
array_push($vacanze, "06/01");
array_push($vacanze, "25/04");
array_push($vacanze, "01/05");
array_push($vacanze, "02/06");
array_push($vacanze, "01/11");
array_push($vacanze, "08/12");
array_push($vacanze, "24/12");
array_push($vacanze, "25/12");

if(in_array($data_vacanza, $vacanze)) {
    $work="no";
}

$timesheet = "SELECT id_attivita, ore, stato, tipo, timecard, data, motivo, id_commessa, note
                FROM attivita
                WHERE stato!='Da Approvare' AND id_utente='" . $_GET['id'] . "'" . "AND data = '" . $t1 . '/' . $id_mese1 . '/' . $id_anno . "'";

$result_timesheet = $conn->query($timesheet);
$button = "";
$somma = 0;
$stato = "";
$control = 0;
$control_presenza = 0;
$timecard_control = "";
$contatore = 0;

if ($result_timesheet->num_rows > 0) {
    $contatore = 1;

    while ($row = $result_timesheet->fetch_object()) {
        $info = "";
        $note = $row->note;

        if ($row->tipo == "Presente") { $color_presenza = "success"; $type_presenza = "P"; $info = "ok"; $motivo = $row->motivo; $work_p = $work; $id_attivita = $row->id_attivita; }
        if ($row->tipo == "Lavoro Agile") { $color_presenza = "verde"; $type_presenza = "LA"; $info = "ok"; $motivo = $row->motivo; $work_p = $work; $id_attivita = $row->id_attivita; }
        if ($row->tipo == "Permesso") { $color = "info"; $type = "Pr" . " - " . $row->ore; $info="no"; }
        if ($row->tipo == "Permesso 104") { $color = "104"; $type = "104" . " - ". $row->ore; $info = "no"; }
        if ($row->tipo == "Ferie") { $color = "grigio"; $type = "F"; $info = "no"; }
        if ($row->tipo == "Malattia") { $color = "danger"; $type = "M"; $info = "no"; }
        if ($row->tipo == "Maternità") { $color = "warning"; $type = "Ma"; $info = "no"; }
        if ($row->tipo == "Straordinario") { $color = "primary"; $type = "S". " - " . $row->ore; }
        if ($row->tipo == "Permesso Studi") { $color = "arancio"; $type = "PS" . " - " . $row->ore; $info = "no"; }
        if ($row->tipo == "Congedo Parentale") { $color = "marrone"; $type = "CP"; $info = "no"; }
        if ($row->tipo == "Lutto") { $color = "nero"; $type = "L"; $info = "no"; }
        if ($row->tipo == "Recupero") { $color_presenza = "verde"; $type_presenza = "R"; $info = "no"; }
        if ($row->tipo == "Donazione Sangue") { $color = "verde-scuro"; $type = "DS" . " - " . $row->ore; $info = "no"; }
        if ($row->tipo == "Malattia Bambino") { $color = "blu-scuro"; $type = "MB" . " - " . $row->ore; $info = "no"; }
        if ($row->tipo == "Congedo Straordinario") { $color = "rosso"; $type = "CS" . " - " . $row->ore; $info = "no"; }
        if ($row->tipo == "Congedo Matrimoniale") { $color = "viola"; $type = "CM" . " - " . $row->ore; $info = "no"; }
        if ($row->tipo == "Cure Termali") { $color = "giallo"; $type = "CT" . " - " . $row->ore; $info = "no"; }
        if ($row->tipo == "Reperibilità") { $color = "verde-chiaro"; $type = "RP" . " - " . $row->ore; $info = "no"; }

        if ($row->stato == "Validato") {
            $info = "no";
            $validato = "ok";
        } else if($row->stato == "Rifiutato") {
            $validato = "no";
            $info = "no";
        } else {
            $validato = "";
        }

        if ($row->tipo != "Presente" && $row->tipo != "Lavoro Agile" && $row->tipo != "Recupero") {
            $button .= '<br><button type="button" data-motivo="'.$row->motivo.'" data-ore="'.$row->ore.'" data-subs="" data-data="'.
                $t1.'/'.$id_mese1.'/'.$id_anno.'" data-idattivita="'.$row->id_attivita.'" data-stato="'.$row->stato.
                '" data-motivo="'.$row->motivo.'" data-tipo="'.$row->tipo.'" style="width:55px; margin-top:1%;" class="btn btn-'.
                $color.' btn-xs legenda" data-note="' . $row->note . '" data-toggle="modal" data-target="#ModalEvents">'.$type.'</button>';
        } else {
            if ($control_presenza != "1"){
                $timecard_control = $row->id_attivita . "$" . $row->id_commessa . "$" . $row->ore;
            } else {
                $timecard_control .= "!" . $row->id_attivita . "$" . $row->id_commessa . "$" . $row->ore;
            }
        }

        if ($row->tipo == "Presente" || $row->tipo == "Lavoro Agile" || $row->tipo == "Recupero") $control_presenza = 1;
    }
} else {
    $validato = "";
}

if($timecard_control!="") {

$button_presenza =  '<br><button type="button" data-timecard-presenza="'.$timecard_control.'"  data-data="'.$t1.'/'.$id_mese1.'/'.$id_anno.'"';
               if ($type_presenza == "P") $button_presenza .= ' data-tipo="Presente" ';
               if ($type_presenza == "LA") $button_presenza .= ' data-tipo="Lavoro Agile" ';
               if ($type_presenza == "R") $button_presenza .= ' data-tipo="Recupero" ';
               $button_presenza .= 'style="width:55px; margin-top:1%;" data-motivo="'.$motivo.'" class="btn btn-'.$color_presenza.' btn-xs legenda" data-toggle="modal" data-target="#ModalEvents" data-idattivita="'.$id_attivita.'" data-note="' . $note . '">'.$type_presenza.'</button><br>';

$data_presenza = 'data-timecard-presenza="'.$timecard_control.'"';
$button = str_replace('data-subs=""', $data_presenza, $button);
}
			$festa="";
			if($id_mese==1 && $t==1) $festa="Capodanno";
			if($id_mese==1 && $t==6) $festa="Befana";
			if($id_mese==4 && $t==25) $festa="Liberazione";
			if($id_mese==5 && $t==1) $festa="Lavoratori";
			if($id_mese==6 && $t==2) $festa="Repubblica";
			if($id_mese==8 && $t==15) $festa="Ferragosto";
			if($id_mese==11 && $t==1) $festa="Ognissanti";
			if($id_mese==12 && $t==8) $festa="Immacolata";
			if($id_mese==12 && $t==25) $festa="Natale";
			if($id_mese==12 && $t==26) $festa="Stefano";

                   if($t==$con1||$t==$con2||$t==$con3||$t==$con4||$t==$con5||$t==$con6)
		   {
                       ?>

      <?php if($t!=1){?>
             <tr>
                  <?php } ?>

         <?php
		   }
		   ?>
          <td   <?php if ($festa == "" && $work == "si") { ?> class="cells" <?php } ?>
                <?php if ($festa != "" || $work == "no") { ?> class="festivo" <?php } ?>
          >
                <center>
                                    <span class="span-day events">
                                     <?php



                                    echo $t;?>
                                    </span>
                <?php echo $button_presenza."". $button;?>
<?php
$giorni = array('Domenica','Lunedi','Martedi','Mercoledi','Giovedi','Venerdi','Sabato');
$data = date('Y-m-d');
if($giorni[date('w',strtotime($data))]=="Lunedi") $count = 6;
if($giorni[date('w',strtotime($data))]=="Martedi") $count = 5;
if($giorni[date('w',strtotime($data))]=="Mercoledi") $count = 4;
if($giorni[date('w',strtotime($data))]=="Giovedi") $count = 3;
if($giorni[date('w',strtotime($data))]=="Venerdi") $count = 2;
if($giorni[date('w',strtotime($data))]=="Sabato") $count = 1;
if($giorni[date('w',strtotime($data))]=="Domenica") $count = 0;

//verifica per bottone aggiungi solo per settimana in corso e precedenti
if($id_anno==date('Y')&&$id_mese1==date('m')&&$t1 > date('d')) $dayto++;

if($id_anno > date('Y')||$id_mese1 > date('m')) $info="no";
else if($id_anno==date('Y')&&$id_mese1==date('m')&&$t1 > date('d')&&$dayto>$count) $info= "no";
//echo $dayto."-".$count;
//echo $giorni[date('w',strtotime($data))];
//echo $info;

?>


            <?php

$date_calendar = $id_anno.'-'.$id_mese1.'-'.$t1;
$date_today = date('Y-m-d');
$date_control = date('Y-m-d', strtotime('- 2 days'));

if($validato=="ok") print '<i title="Validato" class="fa fa-check-circle fa-2x validato"></i>';
else if($validato=="no"&&$contatore==1) print '<i title="Rifiutato" class="fa fa-times-circle fa-2x non-validato"></i>';
            if($info != "no" && $button == '') { ?>
                 <button style="border:none; background-color: transparent;" type="button" <?php if($festa == "" && (($date_calendar >= $date_control && $date_calendar <= $date_today) || ($_SESSION['user_idd'] == 92 || $_SESSION['user_idd'] == 3 || $_SESSION['user_idd'] == 62))) { ?> class="fa fa-plus-circle fa-2x aggiungi"<?php } else if($date_calendar >= $date_control && $date_calendar <= $date_today) { ?> class="fa fa-plus-circle fa-2x aggiungi-festivo" <?php }?> data-toggle="modal" data-target="#nuovaAttivitaModal" data-work="<?php echo $work;?>"
                data-calendar-date="<?php echo $t1.'/'.$id_mese1.'/'.$id_anno;?>" <?php if($info=="ok") { ?> data-info="presente" <?php } else { ?> data-info="no" <?php } ?>  data-user-id="<?php echo $_GET['id'];?>">

</button>
<?php } ?>
             </center>
          </td>
		<?php
			if($t==$cont1||$t==$cont2||$t==$cont3||$t==$cont4||$t==$cont5)
			{
		?>

         	</tr>
         <?php
		 	}
		}
?>
  </tbody>
</table>
</form>


                     <!-- LEGENDA -->
                     <div class="panel panel-primary" style="margin-bottom: 60px;">
                        <div class="legenda-heading">
                           <span class="legenda-title">Legenda</span>
                        </div>
                        <div class="panel-body">
                           <table style="border:0;width:100%;">
                              <tr style="border:0;">
                                 <th>
                                    <p>
                                       <span class="btn btn-success legenda">P</span>
                                       <span class="legenda">Presente</span>
                                    </p>
                                 </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-verde legenda">LA</span>
                                          <span class="legenda">Lavoro Agile</span>
                                      </p>
                                  </th>
                                 <th>
                                    <p>
                                       <span class="btn btn-grigio legenda">F</span>
                                       <span class="legenda">Ferie</span>
                                    </p>
                                 </th>
                                 <th>
                                    <p>
                                       <span class="btn btn-danger legenda">M</span>
                                       <span class="legenda">Malattia</span>
                                    </p>
                                 </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-primary legenda">S</span>
                                          <span class="legenda">Straordinario</span>
                                      </p>
                                  </th>
                              </tr>
                              <tr style="border:0;">
                                  <th>
                                      <p>
                                          <span class="btn btn-nero legenda">L</span>
                                          <span class="legenda">Lutto</span>
                                      </p>
                                  </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-blu-scuro legenda">M</span>
                                          <span class="legenda">Malattia Bambino</span>
                                      </p>
                                  </th>
                                 <th>
                                    <p>
                                       <span class="btn btn-marrone legenda">CP</span>
                                       <span class="legenda">Congedo Parentale</span>
                                    </p>
                                 </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-rosso legenda">CS</span>
                                          <span class="legenda">Congedo Straordinario</span>
                                      </p>
                                  </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-viola legenda">CM</span>
                                          <span class="legenda">Congedo Matrimoniale</span>
                                      </p>
                                  </th>
                              </tr>
                              <tr style="border:0;">
                                  <th>
                                      <p>
                                          <span class="btn btn-info legenda">Pr</span>
                                          <span class="legenda">Permesso</span>
                                      </p>
                                  </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-arancio legenda">PS</span>
                                          <span class="legenda">Permesso Studio</span>
                                      </p>
                                  </th>
                                 <th>
                                    <p>
                                       <span class="btn btn-104 legenda">104</span>
                                       <span class="legenda">Permesso 104</span>
                                    </p>
                                 </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-verde-scuro legenda">DS</span>
                                          <span class="legenda">Donazione Sangue</span>
                                      </p>
                                  </th>
                                  <th>
                                      <p>
                                          <span class="btn btn-warning legenda">Ma</span>
                                          <span class="legenda">Maternità</span>
                                      </p>
                                  </th>
                              </tr>
                               <tr style="border:0;">
                                   <th>
                                       <p>
                                           <span class="btn btn-giallo legenda">CT</span>
                                           <span class="legenda">Cure Termali</span>
                                       </p>
                                   </th>
                                   <th>
                                       <p>
                                           <span class="btn btn-verde-chiaro legenda">Rp</span>
                                           <span class="legenda">Reperibilità</span>
                                       </p>
                                   </th>
                               </tr>
                           </table>
                        </div>
                     </div>
                     <!-- FINE LEGENDA -->
                    <!-- CODICE MODAL RICHIESTA FERIE -->

				   <div id="richiestaFerieModal" class="modal fade" role="dialog">
					  <div class="modal-dialog">

						<form method="POST" action="">
						<!-- Modal content-->
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Richiesta Ferie</h4>
						  </div>
						  <div class="modal-body">
							  <center>Seleziona date:</center>
							  <br>
							<div id="calendar-richiesta-ferie"></div>
							<input type="hidden" id="ferie-input" name="ferie-input" value="">
					<?php
							$attivita = new Attivita();
							$listVacations = $attivita->getVacationToApprove($_SESSION['user_idd']);
							if(count($listVacations) > 0) {
					?>
							  <div class="row" style="margin-top: 20px;">
                                  <div class="col-md-6 col-md-offset-3">
                                      Giorni di ferie in attesa di approvazione:
                                      <ul class="list-group" style="margin-top:10px;">
                        <?php			foreach($listVacations as $dateVacation) {
                                            echo '<li class="list-group-item">' .
                                                $dateVacation[1] .
                                                '<a href="timesheet.php?id=' . $_SESSION['user_idd'] .
                                                        '&action=delete-vacation&id-vacation-to-delete=' . $dateVacation[0] .
                                                        '" class="btn btn-danger btn-xs"
                                                        style="float:right;line-height:3px;padding:3px 3px 4px 4px;">' .
                                                '<span class="glyphicon glyphicon-remove"></span>' .
                                                '</a>' .
                                                '</li>';
                                        } ?>
                                      </ul>
                                  </div>
							  </div>
					<?php	} ?>
						  </div>
						  <div class="modal-footer">
							  <button type="button" class="btn btn-success" name="richiesta-ferie-submit" onclick="printDates(this.form)">
								  Invia richiesta
							  </button>
							  <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						  </div>
						</div>
						</form>

					  </div>
					</div>

				    <!-- FINE CODICE MODAL RICHIESTA FERIE -->

                    <!-- CODICE MODAL NUOVA ATTIVITA' -->
                     <form action="" method="post">
<div class="modal fade" id="ModalEvents" role="dialog">
                        <div class="modal-dialog modal-lg">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                                 <h4 class="modal-title">DETTAGLIO EVENTO</h4>
                              </div>
                         <div class="modal-body text-center">
                             <div class="row">

                  <div class="col-md-6">
                     <div class="panel panel-primary panel-height">
                        <div class="calendar-heading">
                           <span class="span-title">PRESENZA</span>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                              <p class="span-form">Seleziona Tipo:</p>
                              <select class="form-control" id="tipo_event" name="tipo" style="width: 100%;" onchange="toggleMe(this.options[this.selectedIndex].value, 'timecard_event', 'ore_extra_event');">
                                <option>Presente</option>
                                <option>Lavoro Agile</option>
                                <option>Straordinario</option>
                                <option>Permesso</option>
                                <option>Permesso 104</option>
                                <option>Maternità</option>
                                <option>Malattia</option>
                                <option>Congedo Parentale</option>
                                <option>Permesso Studi</option>
                                <option>Lutto</option>
                                <option>Donazione Sangue</option>
                                <option>Malattia Bambino</option>
                                <option>Congedo Straordinario</option>
                                <option>Congedo Matrimoniale</option>
                                <option>Cure Termali</option>
                                <option>Reperibilità</option>
                              </select>
                           </div>
                           <div class="form-group">
                              <p class="span-form">Data:</p>
                              <input type="text" id="data_modal" name="data" class="form-control" value="">
                           </div>
                            <div class="form-group" style="display:none;" id="ore_extra_event">
                                <p class="span-form">Ore:</p>
								<input type="text" id="ore_extra_event_dettaglio" onkeyup="setOreDettaglio(this.value,<?php echo $ore_max;?>)" name="ore_extra_event" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <p class="span-form" id="protocollo">Nota:</p>
                                <textarea class="form-control" id="nota" name="nota" rows="2"></textarea>
                            </div>
<div class="form-group" id="motivo_event">
                              <p class="span-form">Motivo del Rifiuto:</p>
                              <input type="text" id="motivo" name="motivo" class="form-control" value="">
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                      <div class="panel panel-primary panel-height" id="timecard_event">
                        <div class="calendar-heading">
                           <span class="span-title">TIMECARD</span>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                              <fieldset style="text-align:left;">
                                 <p class="span-form">Seleziona Commessa:</p>
                                 <br>
                                 <?php $sql="SELECT * FROM user_commesse as uc, commesse as c WHERE uc.id_commessa = c.id_commessa AND uc.id_role=4 AND id_user=".$_GET['id'];
                                 $result = $conn->query($sql);
                                       $com=0;
                                        while($obj_comm= $result->fetch_object()){ $com++;?>

                                 <div class="row">
                                    <div class="col-md-7">
                                        <input  type="checkbox" id="commessa_dettaglio_<?php echo $obj_comm->id_commessa;?>" name="commesse[]" value="<?php print $obj_comm->id_commessa;?>"/>
                                       <span style="font-size:bold;"><?php echo $obj_comm->nome_commessa;?></span>
                                    </div>
                                    <div class="col-md-5">

										<input type="text" id="ore_dettaglio_<?php print $obj_comm->id_commessa;?>" name="ore_<?php print $obj_comm->id_commessa;?>" class="form-control" value="">

                                    </div>
                                 </div>
                                 <?php } ?>
                              </fieldset>
                           </div>
                        </div>
                     </div>
                  </div>

            </div>
                                 <p>
                                     <button type="submit" name="modifica" class="btn btn-info"><span class="fa fa-floppy-o"></span> Salva</button>
                                     <button type="submit" name="elimina" class="btn btn-danger"><span class="fa fa-trash"></span> Elimina</button>
                                     <input type="hidden" name="attivita_evento" value="" id="attivita_evento">
                                     <input type="hidden" name="id_mese" value="" id="id_mese_modal_event" >
                                     <input type="hidden" name="id_anno" value="" id="id_anno_modal_event">
                                     <input type="hidden" name="timecard-presenze" value="" id="timecard-presenze">
                                 </p>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> Chiudi</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </form>

                    <!-- MODAL FORM ATTIVITA' -->
                     <form action="" method="post">
                     <div class="modal fade" id="nuovaAttivitaModal" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">NUOVA ATTIVITA'</h4>
         </div>
         <div class="modal-body text-center">
            <div class="row">

                  <div class="col-md-6">
                     <div class="panel panel-primary panel-height">
                        <div class="calendar-heading">
                           <span class="span-title">PRESENZA</span>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                              <p class="span-form">Seleziona Tipo:</p>
                              <select class="form-control" id="tipo" name="tipo" style="width: 100%;" onchange="toggleMe(this.options[this.selectedIndex].value, 'timecard','ore_extra');">
                                  <option id="opt-p">Presente</option>
                                  <option id="opt-tl">Lavoro Agile</option>
                                  <option id="opt-st">Straordinario</option>
                                  <option id="opt-pr">Permesso</option>
                                  <option id="opt-104">Permesso 104</option>
                                  <option id="opt-ma">Maternità</option>
                                  <option id="opt-m">Malattia</option>
                                  <option id="opt-cp">Congedo Parentale</option>
                                  <option id="opt-ps">Permesso Studi</option>
                                  <option id="opt-l">Lutto</option>
                                  <option id="opt-ds">Donazione Sangue</option>
                                  <option id="opt-mb">Malattia Bambino</option>
                                  <option id="opt-cs">Congedo Straordinario</option>
                                  <option id="opt-cm">Congedo Matrimoniale</option>
                                  <option id="opt-ct">Cure Termali</option>
                                  <option id="opt-rp">Reperibilità</option>
                              </select>
                           </div>
                           <div class="form-group">
                              <p class="span-form">Data:</p>
                              <input type="text" id="data_modal" name="data" class="form-control" value="">
                           </div>
                            <div class="form-group" style="display:none;" id="ore_extra">
                              <p class="span-form">Ore:</p>
                             <input type="text" name="ore_extra" class="form-control" onkeyup="setOre(this.value,<?php echo $ore_max;?>)" value="<?php echo $ore_max;?>">
                            </div>
                            <div class="form-group">
                                <p class="span-form" id="protocollo2">Nota:</p>
                                <textarea class="form-control" id="nota2" name="nota" rows="2"></textarea>
                            </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                      <div class="panel panel-primary panel-height" id="timecard">
                        <div class="calendar-heading">
                           <span class="span-title">TIMECARD</span>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                              <fieldset style="text-align:left;">
                                 <p class="span-form">Seleziona Commessa:</p>
                                 <br>
                                 <?php $sql="SELECT * FROM user_commesse as uc, commesse as c WHERE uc.id_commessa = c.id_commessa AND uc.id_role=4 AND id_user=".$_GET['id'];
                                 $result = $conn->query($sql);
                                       $com=0;
                                        while($obj_comm= $result->fetch_object()){ $com++;?>

                                 <div class="row">
                                    <div class="col-md-7">
                                       <input <?php if($com==1){ ?> id="commessa_check_<?php print $obj_comm->id_commessa;?>" checked="checked" <?php } ?> type="checkbox"  name="commesse[]" value="<?php print $obj_comm->id_commessa;?>"/>
                                       <span style="font-size:bold;"><?php echo $obj_comm->nome_commessa;?></span>
                                    </div>
                                    <div class="col-md-5">

							<input type="text" name="ore_<?php print $obj_comm->id_commessa;?>" id="ore_<?php print $obj_comm->id_commessa;?>" class="form-control" value="<?php echo $ore_max;?>">
                                       <input type="hidden" name="id_user" value="" id="id_user">
                                       <input type="hidden" name="id_mese" value="" id="id_mese_modal" >
                                     <input type="hidden" name="id_anno" value="" id="id_anno_modal">
                                    </div>
                                 </div>
                                 <?php } ?>
                              </fieldset>
                           </div>
                        </div>
                     </div>
                  </div>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="submit" class="btn btn-success" id="aggiungi" name="aggiungi"><span class="fa fa-plus"></span> Aggiungi</button>
                </center>
            </div>
         </div>
      </div>
   </div>
</div>
</form>
<!-- FINE FORM ATTIVITA' -->

      <!--FINE CONTENUTO PRINCIPALE-->
      <!--INIZIO FOOTER-->
      <footer class="site-footer">
          <div class="text-center">
              Powered by Servicetech S.r.l. - Attiva dal 01/07/2020
              <a href="#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--FINE FOOTER-->
  </section>

    <!-- js-->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jquery-1.8.3.min.js"></script>
	<script src="assets/js/jquery-ui.js"></script>
	<script src="assets/js/jquery-ui.multidatespicker.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.sparkline.js"></script>

    <!--script-->
    <script src="assets/js/common-scripts.js"></script>

    <script>
        $(document).ready(function () {
            $('#nuovaAttivitaModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Il bottone che ha fatto il trigger dell'evento show.bs.modal
                var documentId = button.data('user-id'); // Estrae info da attributo HTML data-document-id
                var documentData = button.data('calendar-date'); // Estrae info da attributo HTML data-document-name
                var documentNote = button.data('supplier-name'); // Estrae info da attributo HTML data-document-note
                var datamodal = documentData.split("/");
                var work = button.data('work');

                console.log(button.data('tipo'));
                console.log(button.data('info'));

                var title = 'NUOVA ATTIVITA - ' + documentData;

                // Update the modal's content.
                var modal = $(this);
                modal.find('.modal-title').text(title);
                modal.find('#note').text(documentNote);
                modal.find('#data_modal').val(documentData);
                modal.find('#id_mese_modal').val(datamodal[1]);
                modal.find('#id_anno_modal').val(datamodal[2]);

                if (button.data('info') == "presente" || work == "no") {
                    modal.find('#opt-p').attr("disabled", true);
                    modal.find('#opt-tl').attr("disabled", true);
                    modal.find('#opt-pr').attr("disabled", true);
                    modal.find('#opt-104').attr("disabled", true);
                    modal.find('#opt-m').attr("disabled", true);
                    modal.find('#opt-ma').attr("disabled", true);
                    modal.find('#opt-cp').attr("disabled", true);
                    modal.find('#opt-f').attr("disabled", true);
                    modal.find('#opt-ps').attr("disabled", true);
                    modal.find('#opt-st').attr("selected", true);
                    modal.find('#opt-l').attr("disabled", true);
                    modal.find('#opt-ds').attr("disabled", true);
                    modal.find('#opt-mb').attr("disabled", true);
                    modal.find('#opt-cs').attr("disabled", true);
                    modal.find('#opt-cm').attr("disabled", true);
                    modal.find('#opt-ct').attr("disabled", true);
                    modal.find('#opt-rp').attr("selected", false);
                    document.getElementById("timecard").style.display = 'none';
                    document.getElementById("ore_extra").style.display = '';
                } else {
                    modal.find('#opt-p').attr("selected", true);
                    modal.find('#opt-p').attr("disabled", false);
                    modal.find('#opt-tl').attr("disabled", false);
                    modal.find('#opt-pr').attr("disabled", false);
                    modal.find('#opt-104').attr("disabled", false);
                    modal.find('#opt-m').attr("disabled", false);
                    modal.find('#opt-ma').attr("disabled", false);
                    modal.find('#opt-cp').attr("disabled", false);
                    modal.find('#opt-f').attr("disabled", false);
                    modal.find('#opt-ps').attr("disabled", false);
                    modal.find('#opt-st').attr("selected", false);
                    modal.find('#opt-l').attr("disabled", false);
                    modal.find('#opt-ds').attr("disabled", false);
                    modal.find('#opt-mb').attr("disabled", false);
                    modal.find('#opt-cs').attr("disabled", false);
                    modal.find('#opt-cm').attr("disabled", false);
                    modal.find('#opt-ct').attr("disabled", false);
                    modal.find('#opt-rp').attr("disabled", false);
                    document.getElementById("timecard").style.display = '';
                    document.getElementById("ore_extra").style.display = 'none';
                }

                // Volendo ad esempio passare l'ID del documento tramite il bottone di conferma del modal lo assegno al valore del bottone
                modal.find('button.btn-success').val(documentId);
            });
        });

        $(document).ready(function () {
            $('#ModalEvents').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Il bottone che ha fatto il trigger dell'evento show.bs.modal
                var documentId = button.data('idattivita'); // Estrae info da attributo HTML data-document-id
                var documentData = button.data('data'); // Estrae info da attributo HTML data-document-name
                var documentNote = button.data('note'); // Estrae info da attributo HTML data-note
                var tipo = button.data('tipo');
                var ore_extra = button.data('ore');
                var timecard = button.data('timecard-presenza');
                var motivo = button.data('motivo');

                var title = 'DETTAGLIO ATTIVITA';
                if (tipo == "Presente" || tipo == "Lavoro Agile" || tipo == "Recupero" || timecard != null) var sep = timecard.split('!');
                var datamodal = documentData.split("/");
                // Update the modal's content.
                var modal = $(this);
                modal.find('.modal-title').text(title);
                modal.find('#data_modal').val(documentData);
                modal.find('#tipo_event').val(tipo);
                modal.find('#attivita_evento').val(documentId);
                modal.find('#ore_extra_event_dettaglio').val(ore_extra);
                modal.find('#id_mese_modal_event').val(datamodal[1]);
                modal.find('#id_anno_modal_event').val(datamodal[2]);
                modal.find('#timecard-presenze').val(timecard);
                modal.find('#motivo').val(motivo);
                modal.find('#nota').val(documentNote);

                if (motivo == "") {
                    document.getElementById("motivo_event").style.display = 'none';
                }
                else {
                    document.getElementById("motivo_event").style.display = '';
                }


                if (tipo != "Presente" && tipo != "Lavoro Agile" && tipo != "Permesso" && tipo != "Permesso Studi" &&
                        tipo != "Permesso 104" && tipo != "Donazione Sangue" && tipo != "Cure Termali") {
                    document.getElementById("timecard_event").style.display = 'none';
                } else {
                    document.getElementById("timecard_event").style.display = '';
                }

                if (tipo == "Permesso" || tipo == "Straordinario" || tipo == "Permesso Studi" || tipo == "Permesso 104" ||
                        tipo == "Donazione Sangue" || tipo == "Cure Termali" || tipo == "Reperibilità") {
                    document.getElementById("ore_extra_event").style.display = '';
                } else document.getElementById("ore_extra_event").style.display = 'none';


                if (tipo == "Presente" || tipo == "Lavoro Agile" || tipo == "Recupero" || timecard != null) {
                    modal.find("[id*='commessa_dettaglio_']").attr('checked',false);
                    modal.find("[id*='ore_dettaglio_']").val(<?= $ore_max; ?>);

                    for(i  =0; i < sep.length; i++){
                        var com = sep[i].split("$");
                        modal.find("#ore_dettaglio_"+com[1]).val(com[2]);
                        modal.find("#commessa_dettaglio_"+com[1]).attr('checked',true);
                    }
                }

                if(timecard == null) {
                    modal.find("[id*='commessa_dettaglio_']").attr('checked',false);
                    modal.find("[id*='value_commessa_']").val(<?= $ore_max; ?>);
                }
                // Volendo ad esempio passare l'ID del documento tramite il bottone di conferma del modal lo assegno al valore del bottone
                modal.find('button.btn-success').val(documentId);
            });
        });

        $(document).ready(function () {
            modal.find("#ore").keyup(function() {
                alert(modal.find('#ore').val());
            });
        });
    </script>

    <!-- SCRIPT CALENDARIO RICHIESTA FERIE -->
    <script>
        var date = new Date();
        $('#calendar-richiesta-ferie').multiDatesPicker(
<?php
            $infoHRClass = new InfoHR;
            $giorniSettimanaUser = $infoHRClass->getGiorniSettimanaByUserId($_SESSION['user_idd']);
            if($giorniSettimanaUser < 6) {
                echo '{beforeShowDay: $.datepicker.noWeekends}';
            } else if($giorniSettimanaUser == 6) {
                echo '{beforeShowDay: function(date) {
                        var day = date.getDay();
                        if (day == 0) {
                            return [false, ""]
                        } else {
                            return [true, ""]
                        }
                      }}';
            }
?>
        );

        function printDates(form) {
            var dates = $('#calendar-richiesta-ferie').multiDatesPicker('getDates');
            var output = JSON.stringify(dates, null, 4);
            document.getElementById("ferie-input").value = output;
            form.submit();
        }
    </script>
    <script>
    $('#tipo').change(function(){
        if($(this).val()=="Malattia"&&$('#nota').val()=="")
        {
          $("#protocollo2").text("Protocollo Malattia:");
          document.getElementById("aggiungi").disabled = true;
          $('#nota2').css('border-color', 'red');
        } else{
          $("#protocollo2").text("Nota:");
          document.getElementById("aggiungi").disabled = false;
          $('#nota2').css('border-color', '');
        }
        })

    </script>
<script>
$("#nota2").on('change keyup paste', function() {
    // your code here

if($('#tipo').val()=="Malattia"&&$(this).val()!=""){
  document.getElementById("aggiungi").disabled = false;
  $('#nota2').css('border-color', '');
}
else if($('#tipo').val()=="Malattia"&&$(this).val()==""){
  document.getElementById("aggiungi").disabled = true;
  $('#nota2').css('border-color', 'red');
}

});
</script>
  </body>
</html>
