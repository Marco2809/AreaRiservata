<?php

session_start();
require_once('class/dbconn.php');
require_once('class/info_hr.class.php');
require_once('class/buste_paga.class.php');
require_once('class/message.class.php');
require_once('assets/php/functions.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AREA RISERVATA - HR</title>

<script type="text/javascript" src="assets/lib/jquery-1.3.2.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<script type="text/javascript">
function comparsa_data1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
<style>

#datepicker_da{width:25%;}
#datepicker_da > span:hover{cursor: pointer;}
#datepicker_a{width:25%;}
#datepicker_a > span:hover{cursor: pointer;}
</style>
  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">
        <link rel="stylesheet" type="text/css" href="assets/css/calendario.css">

    <link rel="stylesheet" type="text/css" href="assets/scss/style.scss">

    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <script src="assets/js/chart-master/Chart.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .centered-form{
	margin-top: 60px;
}

.centered-form .panel{
	background: rgba(255, 255, 255, 0.8);
	box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;
}
        </style>

  </head>

<body>

<section id="container" >
<?php
    require_once('menu.php');

    $database = new Database();
    $conn = $database->dbConnection();

?>

    <section id="main-content">
        <section class="wrapper" style="margin-top:100px;">

<?php
// Calendar management

if(isset($_POST['next'])) {

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'] + 1;
    }

 }

if(isset($_POST['prev'])) {

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'] - 1;
    }

 }

if(!isset($id_anno)) {
    $id_anno = date("Y");
}

?>

            <center>
                <div class="panel panel-primary">
                    <div class="calendar-heading">
                        <form action="buste-paga.php" method="POST">
                            <button type="submit" name="prev" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-left fa-2x" aria-hidden="true"></i></button>

                            <span class="mese-title"><?php echo $id_anno; ?></span>

                            <button type="submit" name="next" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-right fa-2x" aria-hidden="true"></i></button>

                            <input type="hidden" name="id_mese" value="<?php echo $id_mese;?>">
                            <input type="hidden" name="id_anno" value="<?php echo $id_anno;?>">
                        </form>
                    </div>
                </div>
            </center>

<?php
            $bustePaga = new BustePaga();
            $listaBP = $bustePaga->getAllByUserIdAndByYear($_SESSION['user_idd'], $id_anno);

            if(count($listaBP) != 0) {
?>
            <table class="table table-responsive table-bordered">
                <thead>
                    <tr class="days-heading">
                        <th style="width:60%;">Mese</th>
                        <th style="width:20%;">Stato</th>
                        <th style="width:20%;">Download</th>
                    </tr>
                </thead>
                <tbody>
<?php    } else {
                 echo '<div style="text-align: center; margin: 40px;">Nessuna busta paga presente</div>';
            }

                  foreach($listaBP as $bustaPaga) {
                        echo '<tr>';
                        echo '<td>' . getMonthName($bustaPaga[0]) . '</td>';
                        echo '<td>' . getViewed($bustaPaga[5], $bustaPaga[1]) . '</td>';
                        $urlFile = './buste-paga/' . $_SESSION['user_idd'] . '/' . $id_anno . '/' . strtoupper($bustaPaga[4]) .
                               ' ' . strtoupper($bustaPaga[3]) . '_BP ' . strtoupper(getMonthName($bustaPaga[0])) .  ' ' .
                               $id_anno . ' ' . str_pad($bustaPaga[2], 5, "0", STR_PAD_LEFT) . '.pdf';
                        echo '<td><a href="' . $urlFile . '" class="btn btn-primary btn-xs" target="_blank" onclick="makeViewed(' . $bustaPaga[5] . ')">
                                      <span class="glyphicon glyphicon-download-alt"></span> Download</a></td>';
                        echo '</tr>';
                  }

            if(count($listaBP) != 0) { ?>
                </tbody>
            </table>
<?php    } ?>
        </section>
    </section>
<!--FINE CONTENUTO PRINCIPALE-->


    <!--INIZIO FOOTER-->
    <footer class="site-footer">
        <div class="text-center">
            Powered by Servicetech S.r.l. - Attiva dal 01/07/2020
            <a href="" class="go-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--FINE FOOTER-->
</section>

<!-- js-->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/jquery-1.8.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="assets/js/jquery.sparkline.js"></script>
<script src="assets/js/sparkline-chart.js"></script>
<script src="assets/js/zabuto_calendar.js"></script>

<script type="text/javascript">
function makeViewed(idBusta){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("bp-view-" + idBusta).className = 'label label-success';
                document.getElementById("bp-view-" + idBusta).innerHTML = 'Visualizzato';
            }
    };
    xmlhttp.open("GET", "./assets/php/hr-make-viewed-bp.php?id=" + idBusta, true);
    xmlhttp.send();
}
</script>

    </body>
</html>
