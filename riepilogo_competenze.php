<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/competenza.class.php');
require_once('class/message.class.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AREA RISERVATA - Home</title>

<script type="text/javascript" src="assets/lib/jquery-1.3.2.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<script type="text/javascript">
function comparsa_data1(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
<style>

#datepicker{width:25%;}
#datepicker > span:hover{cursor: pointer;}
</style>
  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <link rel="stylesheet" href="css/style.css">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">

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

                          if(isset($_POST['delete'])) {

                              $competenza = new Competenza();
                              $del_competenza = $competenza ->delCompetenza($_POST['id_competenza']);
                              if($del_competenza=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> La competenza selezionata è stata eliminata con successo.
</div>';

                              if($del_competenza=="error") echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
  <strong>Attenzione!</strong> La competenza selezionata non è stata eliminata.
</div>';

                              unset($_POST);
                          }

                          if (isset($_POST['modify'])||isset($_POST['save'])) {
                                $competenza = new Competenza();

                                if (isset($_POST['modify'])) {
                                    $insert_competenza = $competenza->updateCompetenza($_POST['id_competenza'], $_POST['tipo'], $_POST['descrizione']);
                                    if ($insert_competenza == "ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                                            <strong>Ben fatto!</strong> La competenza è stata modificata con successo.
                                            </div>';
                                    unset($_POST);
                                }

                                if (isset($_POST['save'])) {
                                    $insert_competenza = $competenza->setCompetenza($_GET['id'], $_POST['tipo'], $_POST['descrizione']);
                                    if($insert_competenza == "ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                                            <strong>Ben fatto!</strong> La competenza è stata salvata con successo.
                                            </div>';
                                    unset($_POST);
                                }
                          }

                          if ($_GET['id'] == $_SESSION['user_idd'] || $_SESSION['is_admin'] == 1) {
                                include('top_menu_cv.php');
                          ?>

                                  <?php

                                   $sql_comp1 = "SELECT * FROM riepilogo_competenze WHERE tipo_competenza = 1 AND user_id= ".$_GET['id'];
                              $result_comp1 = $conn->query($sql_comp1);
                                  $obj_comp1= $result_comp1->fetch_object();


                                  ?>
                                          <div id="istruzione" class="col-lg-12" style="margin-top:10px;">
             <div class="panel panel-list" >
                 <div class="panel-heading" onclick="comparsa_data1('comp_1')"><h4 style=" margin:0;"><span style="margin-right:10px;" class="glyphicon glyphicon-chevron-down"></span>COMPETENZE INFORMATICHE</h4></div>
      <div class="panel-body" id="comp_1" style="display:none; margin-top:1%;">

                                                                                           <form action="" method="post">
                                                    <table id="corso" class="table table-striped table-advance table-hover" >
                              <tbody>

                                                      <tr>
                                  <td class="td-intestazione">DESCRIZIONE</td>
                                  <td>
                                      <textarea class="form-control" style="resize:none; width: 40%;" name="descrizione" rows="4" cols="20"><?php if(isset($obj_comp1->descrizione)) echo $obj_comp1->descrizione;?></textarea>
                                  </td>
						      </tr>


                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">
                                                                     <?php if(isset($obj_comp1->descrizione)){ ?>
                                                                      <input type="hidden" name="id_competenza" value="<?php echo $obj_comp1->id_competenza;?>">
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Modifica Competenze
                                                                      </button>
                                                                     <?php } else { ?>
                                                                      <input type="hidden" name="tipo" value="1">
                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Competenze
                                                                      </button>
                                                                     <?php } ?>
                                 </td>
								 </tr>



                              </tbody>
                          </table>
                                                </form></div>
    </div></div>
                                     <?php

                                   $sql_comp2 = "SELECT * FROM riepilogo_competenze WHERE tipo_competenza = 2 AND user_id= ".$_GET['id'];
                              $result_comp2 = $conn->query($sql_comp2);
                                  $obj_comp2= $result_comp2->fetch_object();


                                  ?>
                                          <div id="istruzione" class="col-lg-12" style="margin-top:10px;">
             <div class="panel panel-list" >
                 <div class="panel-heading" onclick="comparsa_data1('comp_2')"><h4 style=" margin:0;"><span style="margin-right:10px;" class="glyphicon glyphicon-chevron-down"></span>ALTRE COMPETENZE</h4></div>
      <div class="panel-body" id="comp_2" style="display:none; margin-top:1%;">

                                                                                           <form action="" method="post">
                                                    <table id="corso" class="table table-striped table-advance table-hover" >
                              <tbody>

                                                      <tr>
                                  <td class="td-intestazione">DESCRIZIONE</td>
                                  <td>
                                      <textarea class="form-control" style="resize:none; width: 40%;" name="descrizione" rows="4" cols="20"><?php if(isset($obj_comp2->descrizione)) echo $obj_comp2->descrizione;?></textarea>
                                  </td>
						      </tr>


                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">
                                                                     <?php if(isset($obj_comp2->descrizione)){ ?>
                                                                      <input type="hidden" name="id_competenza" value="<?php echo $obj_comp2->id_competenza;?>">
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Modifica Competenze
                                                                      </button>
                                                                     <?php } else { ?>
                                                                      <input type="hidden" name="tipo" value="2">
                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Competenze
                                                                      </button>
                                                                     <?php } ?>
                                 </td>
								 </tr>



                              </tbody>
                          </table>
                                                </form></div>
    </div></div>

                                                                 <?php
                                                                 }



                          else {
      ?>
                                          <div style="margin-top:20px;" class='panel panel-danger'><div class='panel-heading'>Spiacenti, non disponi dei privilegi necessari per visualizzare questa pagina.</div></div>
      <?php
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>
    <script type="text/javascript">
    $(function () {
        $('#datetimepicker').datetimepicker();

    });
</script>


    <script src="assets/js/sparkline-chart.js"></script>
	<script src="assets/js/zabuto_calendar.js"></script>

  </body>
</html>
