<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/message.class.php');
require_once('class/attivita.class.php');
require_once('class/commesse.class.php');
require_once('assets/php/functions.php');
require_once('assets/php/PHPMailer/PHPMailerAutoload.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AREA RISERVATA - Responsabile</title>

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

if(in_array(2, $permessiArray)) {

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
            <a href="responsabile.php?action=convalida">
            <i class="fa fa-calendar-check-o fa-4x"></i>
            <h5>Convalida Attività</h5>
            </a>
            </li>

            <li class="box1">
            <a href="responsabile.php?action=timesheet">
            <i class="fa fa-calendar fa-4x"></i>
            <h5>Timesheet</h5></a>
            </li>

            <li class="box1">
            <a href="responsabile.php?action=approva-ferie">
            <i class="fa fa-plane fa-4x"></i>
            <h5>Approva Ferie</h5></a>
            </li>

            <li class="box1">
            <a href="responsabile.php?action=abilita">
            <i class="fa fa-user-plus fa-4x"></i>
            <h5>Abilita Utente</h5></a>
            </li>

        </ul>
    </div>
  </div>
</nav>
<!--FINE CASELLE MENU-->

<?php
    if($_GET['action'] == "convalida") {
        include('convalida-attivita.php');
    } else if($_GET['action'] == "convalida-dettaglio") {
        include('convalida-dettaglio.php');
    } else if($_GET['action'] == "abilita") {
        include('abilita-utente.php');
    } else if($_GET['action'] == "timesheet") {
        include('timesheet_responsabile.php');
    } else if($_GET['action'] == "dettaglio") {
        include('dettaglio-dipendente-responsabile.php');
    } else if($_GET['action'] == "approva-ferie") {
        include('approva-ferie.php');
    } else if($_GET['action'] == "approva-dettaglio") {
        include('approva-ferie-dettaglio.php');
    }
?>

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


    <!--script-->
    <script src="assets/js/common-scripts.js"></script>

    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>
    <script src="assets/js/filter-table.js"></script>


<?php

} else {
    printBlockMessage();
}

?>
      </body>
</html>
