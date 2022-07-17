<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/corso.class.php');
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

                              $corso = new Corso();
                              $del_corso = $corso ->delCorso($_POST['id_corso']);
                              if($del_corso=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> Il corso selezionato è stato eliminato con successo.
</div>';

                              if($del_corso=="error") echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
  <strong>Attenzione!</strong> Il corso selezionato non è stata eliminato.
</div>';

                              unset($_POST);
                          }

                          if(isset($_POST['modify'])||isset($_POST['save'])) {

                              $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>I seguenti campi sono obbligatori:</strong>';
                              $alert_list="";
                              if($_POST['tipo']=="") $alert_list.= '<br>- Tipo';

                              $alert_end = '</div>';
                              if($alert_list==""){
                              $corso = new Corso();
                              if(isset($_POST['modify'])){

                              $insert_corso = $corso ->updateCorso($_POST['id_corso'], $_POST['tipo'], $_POST['descrizione']);
                              //echo $insert_istruzione;
                              if($insert_corso=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> Il corso è stato modificato con successo.
</div>';
                              unset($_POST);
                              }

                              if(isset($_POST['save'])){

                              $insert_corso = $corso ->setCorso($_GET['id'], $_POST['tipo'], $_POST['descrizione']);
                              if($insert_corso=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> Il corso è stato salvato con successo.
</div>';
                              unset($_POST);
                              }
                              } else echo $alert.$alert_list.$alert_end;
                          }

                          if($_GET['id']==$_SESSION['user_idd']||$_SESSION['is_admin']==1){


                              $sql_corso = "SELECT * FROM corsi WHERE user_id= ".$_GET['id'];
                              $result_corso = $conn->query($sql_corso);

           include('top_menu_cv.php');
                          ?>

					  <!--ISTRUZIONE-->
			  <div id="istruzione" class="col-lg-12 ds">

                                                <button style="margin-top:10px; margin-bottom: 20px;" onclick="comparsa_data1('corso');" type="button" class="btn btn-success">Aggiungi Nuova</button>
                                                          <form action="" method="post">
                                                    <table id="corso" class="table table-striped table-advance table-hover" <?php if(!isset($_POST['save'])) { ?> style="display:none; margin-top:1%;" <?php } else {?> style="margin-top:1%;" <?php } ?>>
                              <tbody>

                              <tr>
                                  <td class="td-intestazione">CORSO</td>
                                  <td>
                                      <select style="width:25%;" class="form-control" name="tipo">
                                <option <?php if($_POST['tipo']=="Corso Base"){?> selected="selected" <?php } ?> value="Corso base">Corso Base</option>
                                <option <?php if($_POST['tipo']=="Primo Soccorso"){?> selected="selected" <?php } ?> value="Primo Soccorso">Primo Soccorso</option>
                                <option <?php if($_POST['tipo']=="Anti Incendio"){?> selected="selected" <?php } ?> value="Anti Incendio">Anti Incendio</option>
                                <option <?php if($_POST['tipo']=="Capocantiere"){?> selected="selected" <?php } ?> value="Capocantiere">Capocantiere</option>
                                <option <?php if($_POST['tipo']=="RLS"){?> selected="selected" <?php } ?> value="RLS">RLS</option>
				<option <?php if($_POST['tipo']=="RSSP"){?> selected="selected" <?php } ?> value="RSSP">RSSP</option>
                    </select>
                                  </td>
                              </tr>


                                                      <tr>
                                  <td class="td-intestazione">DESCRIZIONE</td>
                                  <td>
                                      <textarea class="form-control" style="resize:none; width: 40%;" name="descrizione" rows="4" cols="20"><?php if(isset($_POST['descrizione'])) echo $_POST['descrizione'];?></textarea>
                                  </td>
						      </tr>


                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">

                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Corso
                                                                      </button>
                                 </td>
								 </tr>


                              </tbody>
                          </table>
                                                </form></div>

                                  <?php
                                  $count=0;

                                  while($obj_corso= $result_corso->fetch_object()){
                                      $count++;

                                  ?>
                                          <div id="istruzione" class="col-lg-12" style="margin-top:10px;">
             <div class="panel panel-list" >
                 <div class="panel-heading" onclick="comparsa_data1('corso_<?php echo $obj_corso->id_corso;?>')"><h4 style=" margin:0;"><span style="margin-right:10px;" class="glyphicon glyphicon-chevron-down"></span><?php echo $obj_corso->tipo;?></h4></div>
      <div class="panel-body" id="corso_<?php echo $obj_corso->id_corso;?>" style="display:none; margin-top:1%;">

                                                                                           <form action="" method="post">
                                                    <table id="corso" class="table table-striped table-advance table-hover" >
                              <tbody>

                              <tr>
                                  <td class="td-intestazione">CORSO</td>
                                  <td>
                                      <select style="width:25%;" class="form-control" name="tipo">
                                <option <?php if($obj_corso->tipo=="Corso Base"){?> selected="selected" <?php } ?> value="Corso base">Corso Base</option>
                                <option <?php if($obj_corso->tipo=="Primo Soccorso"){?> selected="selected" <?php } ?> value="Primo Soccorso">Primo Soccorso</option>
                                <option <?php if($obj_corso->tipo=="Anti Incendio"){?> selected="selected" <?php } ?> value="Anti Incendio">Anti Incendio</option>
                                <option <?php if($obj_corso->tipo=="Capocantiere"){?> selected="selected" <?php } ?> value="Capocantiere">Capocantiere</option>
                                <option <?php if($obj_corso->tipo=="RLS"){?> selected="selected" <?php } ?> value="RLS">RLS</option>
				<option <?php if($obj_corso->tipo=="RSSP"){?> selected="selected" <?php } ?> value="RSSP">RSSP</option>
                    </select>
                                  </td>
                              </tr>


                                                      <tr>
                                  <td class="td-intestazione">DESCRIZIONE</td>
                                  <td>
                                      <textarea class="form-control" style="resize:none; width: 40%;" name="descrizione" rows="4" cols="20"><?php if(isset($obj_corso->descrizione)) echo $obj_corso->descrizione;?></textarea>
                                  </td>
						      </tr>


                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">

                                                                      <input type="hidden" name="id_corso" value="<?php echo $obj_corso->id_corso;?>">
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Modifica Corso
                                                                      </button>

                                                                      <button type="submit" name="delete" class="btn btn-danger btn-xs">
							     <i class="fa fa-pencil"></i> Elimina Corso
                                                                      </button>
                                 </td>
								 </tr>



                              </tbody>
                          </table>
                                                </form></div>
    </div></div>
                                                                 <?php
                                                                 }

                          ?>

					  <!--FINE DATI PERSONALI-->

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
