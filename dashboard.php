<?php

session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/message.class.php');
require_once('class/info_hr.class.php');
require_once('class/buste_paga.class.php');
require_once('class/user_ferie_permessi.class.php');

if(isset($_POST['add_allegato'])){

    $database = new Database();
    $conn = $database->dbConnection();

    $alert_allegato="";
    $target_dir = "files/".$_POST['update_id_mex']."/";
if(!file_exists($target_dir)) {
    mkdir($target_dir, 0777) ;
}

$target_file = $target_dir . $_FILES["fileToUpload"]["name"];

$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

if ($_FILES["fileToUpload"]["size"] > 1000000) {
    $alert_allegato .= "<div class='panel panel-danger'><div class='panel-heading'>Spiacenti, il tuo file è troppo grande.</div></div>";
    $uploadOk = 0;
}

if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "JPEG" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "zip" && $imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "xls") {
    $alert_allegato .= "<div class='panel panel-danger'><div class='panel-heading'>Spiacenti, il formato selezionato non può essere caricato.</div></div>";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    $alert_allegato .= "<div class='panel panel-danger'><div class='panel-heading'>Spiacenti, il tuo file non è stato caricato.</div></div>";
} else {
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    $alert_allegato .= "<div class='panel panel-success'><div class='panel-heading'>File caricato con successo</div></div>";
}

$estensione=  explode(".", $_FILES["fileToUpload"]["name"]);
$sql_files="INSERT into files (estensione, nome, id_messaggio)
                          VALUES ('" . $estensione[count($estensione)-1] . "','" .$estensione[0]. "','" .$_POST['update_id_mex']. "')";
    $result = $conn->query($sql_files);

}

if(isset($_POST['delete_allegato'])){
    $database = new Database();
    $conn = $database->dbConnection();

    $target_dir = "files/".$_POST['update_id_mex']."/";
    chdir($target_dir);
    unlink($_POST['nome_file']);
    $nome=explode(".",$_POST['nome_file']);

$sql_delete="DELETE FROM files WHERE id_messaggio=".$_POST['update_id_mex']." AND nome =  '".$nome[0]."'";
$result_delete_mex = $conn->query($sql_delete);
    if(!$result_delete_mex){
         $alert_allegato = "<div class='panel panel-danger'><div class='panel-heading'>Problema nell'eliminazione del file.</div></div>";
    } else {
        $alert_allegato = "<div class='panel panel-success'><div class='panel-heading'>File correlato eliminato con successo!</div></div>";
    }
    unset($_POST['nome_file']);
}

if(isset($_POST['update_mex'])){
    $updated_mex = new Message();
    $updated_mex->testo = $_POST['testo_messaggio'];
    $updated_mex->titolo = $_POST['titolo_messaggio'];

    $res_update = $updated_mex->updateMessage($_POST['update_id_mex']);
}

if(isset($_POST['delete_message'])){
    $del_mex = new Message();
    $res_del= $del_mex->delMessage($_POST['id_mex']);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AREA RISERVATA - Home</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/calendario.css">
    <style>
        body {
            background-color: white;
        }
    </style>
  </head>
  <body>
    <section id="container" >
      <?php include('menu.php'); ?>
      <!-- **********************************************************************************************************************************************************
      CONTENUTO PRINCIPALE
      *********************************************************************************************************************************************************** -->

      <!--INIZIO CONTENUTO PRINCIPALE-->
      <section id="main-content">
          <section class="wrapper">

      <!-- **********************************************************************************************************************************************************
      SIDEBAR SINISTRA
      *********************************************************************************************************************************************************** -->

            <div class="row clearfix">
              <div class="col-lg-4 ds" style="float: right;">
                  <?php include('write_message.php'); ?>
              </div>

              <div class="col-lg-8 main-chart" style="float: left;">
                  <img src="assets/img/banner.jpg" class="img-responsive">
                      <div class="collapse navbar-collapse" id="myNavbar" style="padding: 3%">
                          <ul class="nav nav-justified">
                            <li class="box1">
                                <i class="fa fa-calendar fa-4x"></i>
                                <h4 style="padding: 10px 0 0 0;"><strong>Data</strong><br /><?php echo date('d/m/Y'); ?></h4>
                            </li>
<?php              // if ($_SESSION['user_idd'] !== 366) { ?>
<?php               if (false) { ?>
                                  <li class="box1">
                                      <i class="fa fa-refresh fa-4x"></i>
                                      <h4 style="padding: 10px 0 0 0;"><strong>Giorni di Ferie</strong><br/ ><?php
                                          $userFeriePermessi = new UserFeriePermessi();
                                          $giorniFerie = $userFeriePermessi->getGiorniFerieByIdUser($_SESSION['user_idd']);
                                          $arrayGiorniFerie = explode(".", $giorniFerie);
                                          if ($arrayGiorniFerie[1] == '00') {
                                              echo $arrayGiorniFerie[0];
                                          } else {
                                              echo $giorniFerie;
                                          }
                                          ?></h4>
                                  </li>
                                  <li class="box1">
                                      <i class="fa fa-power-off fa-4x"></i>
                                      <h4 style="padding: 10px 0 0 0;"><strong>Ore di Permesso</strong><br /><?php
                                          $orePermesso = $userFeriePermessi->getOrePermessoByIdUser($_SESSION['user_idd']);
                                          $arrayOrePermesso = explode(".", $orePermesso);
                                          if($arrayOrePermesso[1] == '00') {
                                              echo $arrayOrePermesso[0];
                                          } else {
                                              echo $orePermesso;
                                          }
                                          ?></h4>
                                  </li>
                              <?php               } ?>
                              <li class="box1">
                                  <i class="fa fa-envelope-o fa-4x"></i>
                                  <a href="https://webmail.aruba.it/index.html" target="_blank"><h4><strong>ST Mail</strong></h4></a>
                              </li>
<?php               if ($_SESSION['user_idd'] !== 366) { ?>
                                  <li class="box1">
                                      <i class="fa fa-file-o fa-4x"></i>
                                      <?php
                                      $bp = new BustePaga();
                                      $bpNotRead = $bp->getBPNotReadByUserId($_SESSION['user_idd']);
                                      if($bpNotRead > 0) { ?>
                                          <span class="badge badge-bp notify"><?php echo $bpNotRead;?></span>
                                      <?php } ?>
                                      <a href="buste-paga.php"><h4><strong>Buste Paga</strong></h4></a>
                                  </li>
<?php               } ?>
                          </ul>
                      </div>
<center>
<?php
  $userClass = new User();
  $currentUser = $userClass->getUserLoginById($_SESSION['user_idd']);

  if ($currentUser['primo_accesso'] == 0) {
      $userClass->updateFirstUserAccess($_SESSION['user_idd']);
?>
  <div class="alert alert-danger">
      Esegui la prima impostazione della tua password: <a href="anagrafica.php?id=<?php echo $_SESSION['user_idd']; ?>" style="color: blue;">Cambia Password</a>
  </div>
<?php
  }
?>
  <div class="row ds" style="padding-top: 0;">
    <!--ULTIMI 5 MESSAGGI-->
    <div class="col-lg-12 col-md-12-col-sm-12" style="margin-bottom: 20px;">
      <?php if(isset($res_del)) echo $res_del; ?>
      <?php if(!isset($_GET['id_mex']))  include('last_5_messages.php');
           else if(isset($_GET['id_mex'])) include ('single_message'); ?>

      <!-- ULTIMI 5 FILES -->
      <?php if(!isset($_GET['id_mex'])) include('last_5_files.php'); ?>
    </div>

<?php   if ($_SESSION['user_idd'] !== 366 && $_SESSION['user_idd'] !== 118 && $_SESSION['user_idd'] !== 3) { ?>
      <!-- PANEL ULTIME NEWS TECNOLOGICHE -->
      <div class="col-lg-6 col-md-6 col-sm-6 ">
          <script type="text/javascript" src="https://feed.mikle.com/js/fw-loader.js" data-fw-param="47729/"></script>
      </div>
      <!-- /FINE PANEL ULTIME NEWS TECNOLOGICHE-->

      <!-- PANEL ULTIME NEWS ITALIA E MONDO -->
      <div class="col-md-6 col-sm-6" >
          <script type="text/javascript" src="https://feed.mikle.com/js/fw-loader.js" data-fw-param="47733/"></script>
      </div>
      <!-- FINE PANEL ULTIME NEWS ITALIA E MONDO -->
<?php } else {
  $infoHRClass = new InfoHR();
  $monthlyMedicalExpiries = $infoHRClass->getMonthlyMedicalExpiries();
  $monthlyContractExpiries = $infoHRClass->getMonthlyContractExpiries();
  $monthlyDetachmentExpiries = $infoHRClass->getMonthlyDetachmentExpiries();
  $monthlySubExpiries = $infoHRClass->getMonthlySubExpiries();
?>
      <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="panel panel-primary filterable" style="margin-bottom:5%;">
              <div class="calendar-heading">
                  <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Scadenze Contratti</span>
                  </div>
              </div>
<?php           if (count($monthlyContractExpiries) > 0) { ?>
              <table class="table">
                  <thead>
                  <tr class="filters">
                      <th>Nome</th>
                      <th>Cognome</th>
                      <th>Data</th>
                  </tr>
                  </thead>
                  <tbody>
<?php
                  foreach ($monthlyContractExpiries as $contractExpiry) {
                      echo "<tr>";
                      echo "<td>" . $contractExpiry['nome'] . "</td>";
                      echo "<td>" . $contractExpiry['cognome'] . "</td>";
                      echo "<td>" . $contractExpiry['scadContratto'] . "</td>";
                      echo "</tr>";
                  }
?>
                  </tbody>
              </table>
<?php           } else {
                  echo '<div style="padding: 40px;">Nessuna scadenza nel mese corrente.</div>';
              } ?>
          </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="panel panel-primary filterable" style="margin-bottom:5%;">
              <div class="calendar-heading">
                  <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Scadenze Visite Mediche</span>
                  </div>
              </div>
<?php           if (count($monthlyMedicalExpiries) > 0) { ?>
              <table class="table">
                  <thead>
                  <tr class="filters">
                      <th>Nome</th>
                      <th>Cognome</th>
                      <th>Data</th>
                  </tr>
                  </thead>
                  <tbody>
<?php
                  foreach ($monthlyMedicalExpiries as $medicalExpiry) {
                      echo "<tr>";
                      echo "<td>" . $medicalExpiry['nome'] . "</td>";
                      echo "<td>" . $medicalExpiry['cognome'] . "</td>";
                      echo "<td>" . $medicalExpiry['scadVisitaMedica'] . "</td>";
                      echo "</tr>";
                  }
?>
                  </tbody>
              </table>
<?php           } else {
                  echo '<div style="padding: 40px;">Nessuna scadenza nel mese corrente.</div>';
              } ?>
          </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6" style="margin-bottom: 50px;">
          <div class="panel panel-primary filterable" style="margin-bottom:5%;">
              <div class="calendar-heading">
                  <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Scadenze Distacchi</span>
                  </div>
              </div>
<?php           if (count($monthlyDetachmentExpiries) > 0) { ?>
                  <table class="table">
                      <thead>
                      <tr class="filters">
                          <th>Nome</th>
                          <th>Cognome</th>
                          <th>Sub</th>
                          <th>Data</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
                      foreach ($monthlyDetachmentExpiries as $detachmentExpiry) {
                          echo "<tr>";
                          echo "<td>" . $detachmentExpiry['nome'] . "</td>";
                          echo "<td>" . $detachmentExpiry['cognome'] . "</td>";
                          echo "<td>" . (($detachmentExpiry['sub'] == 1) ? "Sì" : "No") . "</td>";
                          echo "<td>" . $detachmentExpiry['scadDistacco'] . "</td>";
                          echo "</tr>";
                      }
                      ?>
                      </tbody>
                  </table>
<?php           } else {
                  echo '<div style="padding: 40px;">Nessuna scadenza nel mese corrente.</div>';
              } ?>
          </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="panel panel-primary filterable" style="margin-bottom:5%;">
              <div class="calendar-heading">
                  <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Scadenze Subappalti</span>
                  </div>
              </div>
<?php           if (count($monthlySubExpiries) > 0) { ?>
              <table class="table">
                  <thead>
                  <tr class="filters">
                      <th>Nome</th>
                      <th>Cognome</th>
                      <th>Data</th>
                  </tr>
                  </thead>
                  <tbody>
<?php
                  foreach ($monthlySubExpiries as $subExpiry) {
                      echo "<tr>";
                      echo "<td>" . $subExpiry['nome'] . "</td>";
                      echo "<td>" . $subExpiry['cognome'] . "</td>";
                      echo "<td>" . $subExpiry['scadSub'] . "</td>";
                      echo "</tr>";
                  }
?>
                  </tbody>
              </table>
<?php           } else {
                  echo '<div style="padding: 40px;">Nessuna scadenza nel mese corrente.</div>';
              } ?>
          </div>
      </div>
<?php } ?>
  </div>
            </div>
        </div>

          </section>
      </section>

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
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="assets/js/jquery.sparkline.js"></script>
    <script src="assets/js/common-scripts.js"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            $("#date-popover").popover({html: true, trigger: "manual"});
            $("#date-popover").hide();
            $("#date-popover").click(function (e) {
                $(this).hide();
            });

            $("#my-calendar").zabuto_calendar({
                action: function () {
                    return myDateFunction(this.id, false);
                },
                action_nav: function () {
                    return myNavFunction(this.id);
                },
                ajax: {
                    url: "show_data.php?action=1",
                    modal: true
                },
                legend: [
                    {type: "text", label: "Special event", badge: "00"},
                    {type: "block", label: "Regular event", }
                ]
            });
        });


        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>
    <script>
          $(document).ready(function() {
        //set initial state.
        $('#checkbox_check').val($(this).is(':checked'));

        $('#checkbox_check').change(function() {
            if($(this).is(":checked")) {
                $( "*#check_all" ).prop( "checked", true );
            } else {
                $( "*#check_all" ).prop( "checked", false );
            }

            $('#checkbox_check').val($(this).is(':checked'));
        });
    });
    </script>
    <script src="assets/js/sparkline-chart.js"></script>
    <script src="assets/js/zabuto_calendar.js"></script>

  </body>
</html>
