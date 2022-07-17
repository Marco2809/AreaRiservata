<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user_supplier.class.php');
require_once('class/supplier.class.php');
require_once('class/supplier_documents.class.php');
require_once('assets/php/functions.php');

date_default_timezone_set('Europe/Rome');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AREA RISERVATA - Albo Fornitori</title>

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
    <script type="text/javascript" src="assets/js/forms.js"></script>
    <script type="text/javascript" src="assets/js/sha512.js"></script>

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
 include('menu.php');
 ?>


      <!-- **********************************************************************************************************************************************************
      CONTENUTO PRINCIPALE
      *********************************************************************************************************************************************************** -->
      <!--INIZIO CONTENUTO PRINCIPALE-->
      <section id="main-content">
          <section class="wrapper">
<?php

$database = new Database();
$conn = $database->dbConnection();

$moduli = new Modulo();
$permessiArray = $moduli->getModulesByUserId($_SESSION['user_idd']);

if(in_array(4, $permessiArray)) {

if(!$_SESSION['is_supplier']) {
?>
             <!--CASELLE MENU-->
 <nav class="navbar navbar-default nav-justified">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="myNavbar">

        <ul class="nav nav-justified">

            <li class="box1">
                <a href="albo-fornitori.php?action=utenti">
                <i class="fa fa-users fa-4x"></i>
                <h5>Gestione Utenti</h5>
                </a>
            </li>

            <li class="box1">
                <a href="albo-fornitori.php?action=ricerca-utenti">
                <i class="fa fa-search fa-4x"></i>
                <h5>Ricerca Utenti</h5>
                </a>
            </li>

            <li class="box1">
                <a href="albo-fornitori.php?action=fornitori">
                <i class="fa fa-globe fa-4x"></i>
                <h5>Gestione Fornitori</h5></a>
            </li>

            <li class="box1">
                <a href="albo-fornitori.php?action=ricerca-fornitori">
                <i class="fa fa-user fa-4x"></i>
                <h5>Ricerca Fornitori</h5>
                </a>
            </li>

            <li class="box1">
                <a href="albo-fornitori.php?action=report">
                <i class="fa fa-pie-chart fa-4x"></i>
                <h5>Report</h5></a>
            </li>
        </ul>
    </div>
  </div>
</nav>
<!--FINE CASELLE MENU-->

<?php }
    if($_GET['action'] == "utenti") {
        include('albo-gestione-utenti.php');
    } else if($_GET['action'] == "modifica-utente") {
        include('albo-modifica-utente.php');
    } else if($_GET['action'] == "ricerca-utenti") {
        include('albo-ricerca-utenti.php');
    } else if($_GET['action'] == "fornitori") {
        include('albo-gestione-fornitori.php');
    } else if($_GET['action'] == "modifica-anagrafica") {
        include('albo-modifica-anagrafica.php');
    } else if($_GET['action'] == "ricerca-fornitori") {
        include('albo-ricerca-fornitori.php');
    } else if($_GET['action'] == "report") {
        include('albo-report.php');
    }
?>

          </section>
      </section>
<!--FINE CONTENUTO PRINCIPALE-->
      <!--INIZIO FOOTER-->
      <div style="height:20px;"></div>
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
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="assets/js/jquery.sparkline.js"></script>
    <script src="assets/js/moment.min.js"></script>

    <!-- Tablesorter -->
    <script type="text/javascript" src="assets/js/tablesorter/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="assets/js/tablesorter/jquery.tablesorter.widgets.js"></script>
    <link rel="stylesheet" href="assets/css/jquery.tablesorter.pager.css">
    <script type="text/javascript" src="assets/js/tablesorter/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript" src="assets/js/tablesorter/pager-custom-controls.js"></script>
    <script type="text/javascript" src="assets/js/tablesorter/pager.js"></script>

    <!-- Date Range Picker -->
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <script type="text/javascript" src="assets/js/daterangepicker.js"></script>

    <script src="assets/js/sparkline-chart.js"></script>
 <script src="assets/js/zabuto_calendar.js"></script>

<?php

} else if($_SESSION['is_supplier']) {

    if($_GET['action'] == "anagrafica") {
        include('albo-anagrafica-fornitore.php');
    } else if($_GET['action'] == "documenti") {
        include('albo-documenti-fornitore.php');
    }
?>

          </section>
      </section>
<!--FINE CONTENUTO PRINCIPALE-->
      <!--INIZIO FOOTER-->
      <div style="height:20px;"></div>
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
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="assets/js/jquery.sparkline.js"></script>
    <script src="assets/js/moment.min.js"></script>

    <!-- Tablesorter -->
    <script type="text/javascript" src="assets/js/tablesorter/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="assets/js/tablesorter/jquery.tablesorter.widgets.js"></script>
    <link rel="stylesheet" href="assets/css/jquery.tablesorter.pager.css">
    <script type="text/javascript" src="assets/js/tablesorter/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript" src="assets/js/tablesorter/pager-custom-controls.js"></script>
    <script type="text/javascript" src="assets/js/tablesorter/pager.js"></script>

    <!-- Date Range Picker -->
    <link rel="stylesheet" href="assets/css/daterangepicker.css">
    <script type="text/javascript" src="assets/js/daterangepicker.js"></script>

    <script src="assets/js/sparkline-chart.js"></script>
    <script src="assets/js/zabuto_calendar.js"></script>

<?php
} else {
    printBlockMessage();
}

?>

      </body>
</html>
