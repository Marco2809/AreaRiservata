<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/experience.class.php');
require_once('class/message.class.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AREA RISERVATA - Home</title>

<script type="text/javascript" src="assets/lib/jquery-1.3.2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <link rel="stylesheet" href="css/style.css">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
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
        <script type="text/javascript">
    $(document).ready(function() {
        $('#digiclock').jdigiclock({
            // Configuration goes here
            weatherLocationCode: "EUR|SE|SW015|STOCKHOLM";
        });
    });
</script>
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

                          if($_GET['id']==$_SESSION['user_idd']||$_SESSION['is_admin']==1){

                              $sql_dati= "SELECT * FROM anagrafica a,login l WHERE a.user_id=l.user_idd AND a.user_id= ".$_SESSION['user_idd'];
                              $result_dati = $conn->query($sql_dati);

                              $sql_exp= "SELECT * FROM esperienza WHERE user_id= ".$_SESSION['user_idd']." ORDER BY str_to_date(da, '%d/%m/%Y') DESC";
                              $result_exp = $conn->query($sql_exp);

                              $sql_form= "SELECT * FROM formazione WHERE user_id= ".$_SESSION['user_idd'];
                              $result_form = $conn->query($sql_form);

                              $sql_cert = "SELECT * FROM certificazione WHERE user_id= ".$_SESSION['user_idd'];
                              $result_cert = $conn->query($sql_cert);

                              $sql_courses = "SELECT * FROM corsi WHERE user_id= ".$_SESSION['user_idd'];
                              $result_courses = $conn->query($sql_courses);

                              $sql_competenze = "SELECT * FROM riepilogo_competenze WHERE user_id= ".$_SESSION['user_idd'];
                              $result_competenze = $conn->query($sql_competenze);


                          ?>

              <div class="fixed">
              <div class="row" id="menu-riepilogo" >
                  <div class="col-lg-12 main-chart">

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
					<div class="row mtbox">
                  		<div class="col-md-2 col-sm-2 box0">
						<a href="#dati-personali">
                  			<div class="box1">
					  			<i class="fa fa-user fa-2x"></i>
					  			<h5>Profilo</h5>
                  			</div></a>
                  		</div>
                  		<div class="col-md-2 col-sm-2 box0">
						<a href="#istruzione">
                  			<div class="box1">
					  			<i class="fa fa-graduation-cap fa-2x"></i>
					  			<h5>Istruzione</h5></a>
                  			</div>
                  		</div>
                  		<div class="col-md-2 col-sm-2 box0">
                  			<div class="box1">
							<a href="#esperienza">
					  			<i class="fa fa-check fa-2x"></i>
					  			<h5>Esperienza</h5></a>
                  			</div>
                  		</div>
                  		<div class="col-md-2 col-sm-2 box0">
						<a href="#certificazioni">
                  			<div class="box1">
					  			<i class="fa fa-certificate fa-2x"></i>
					  			<h5>Certificazioni</h5></a>
                  			</div>
                  		</div>
                  		<div class="col-md-2 col-sm-2 box0">
						<a href="#corsi">
                  			<div class="box1">
					  			<i class="fa fa-list fa-2x"></i>
					  			<h5>Corsi</h5></a>
                  			</div>
                  		</div>
                      <div class="col-md-2 col-sm-2 box0">
                        <a target="_blank" href="./tcpdf/examples/PDF_create.php?id=<?php echo $_GET['id'];?>">
                      <div class="box1">
                  <i class="fa fa-file-pdf-o fa-2x"></i>
                  <h5>Scarica PDF</h5></a>
                        </div>
                      </div>
                  	</div>
                  	</div><!-- /row mt -->
                    </div>
                    </nav>
					<!--FINE CASELLE MENU-->

					</div><!-- /row -->

                  </div><!-- /col-lg-9 F -->

			  <!--DATI PERSONALI-->
                          <div id="dati-personali" class="col-lg-12 ds" class="left" style="overflow-y: scroll;
    height: 100%;">
						<h3>DATI PERSONALI</h3>

						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>

                                  <?php
                                  while($obj_dati= $result_dati->fetch_object()){
                                  ?>
 <tr>
<td rowspan="6">
<?php
if($obj_dati->img_name!="0"){
  ?>
<center><img src="assets/img/CV/<?php echo $obj_dati->img_name;?>" style="max-height:200px; max-width:150px;" ></center>
<?php
}else{
  ?>
<center><img src="assets/img/avatar/<?php echo $_SESSION['user_idd']; ?>.png" width="200px" class="img-thumbnail"></center>
<?php
}
?>

</td>
                                  <td class="td-intestazione">NOME E COGNOME</td>
                                  <td><?php echo $obj_dati->nome . " " . $obj_dati->cognome;?></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">DATA DI NASCITA</a></td>
                                  <td><?php echo $obj_dati->data_nascita;?></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">NAZIONALITA</a></td>
                                  <td><?php echo $obj_dati->nazionalita;?></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">RESIDENZA</a></td>
                                  <td><?php echo $obj_dati->indirizzo_residenza .",".$obj_dati->citta_residenza;?></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">EMAIL</a></td>
                                  <td><?php echo $obj_dati->email?></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">TELEFONO</a></td>
                                  <td><?php echo $obj_dati->phone?></td>
						      </tr>
                          <?php } ?>

								  <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="3" style="text-align:center;">
                                                                      <button onClick="document.location.href='<?php echo "anagrafica.php?id=".$_GET["id"];?>'"  class="btn btn-primary btn-sm">
                                                                     <i class="fa fa-pencil"></i> Modifica Dati Personali e Password
							     </button>
                                 </td>
								 </tr>
                              </tbody>
                          </table>
						  </div>
					  <!--FINE DATI PERSONALI-->
					  <?php
                                          $num = $result_form->num_rows;
                                          if($num>0){
                                          ?>
					  <!--ISTRUZIONE-->
			  <div id="istruzione" class="col-lg-12 ds">
						<h3>ISTRUZIONE</h3>

						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>
                                  <?php
                                  $count=0;

                                  while($obj_form= $result_form->fetch_object()){
                                      $count++;
                                      if(substr($obj_form->a,0,4) == "0000") $a="Data Attuale";
                                      else $a = substr($obj_form->a,0,4);
                                  ?>
                              <tr>
                                  <td class="td-intestazione"><?php echo substr($obj_form->da,0,4)."-".$a?></a></td>
                                  <td><?php echo $obj_form->titolo;
                                  if($obj_form->corso!="") echo " - ".$obj_form->corso;
                                  if($obj_form->laurea!="") echo " - ".$obj_form->laurea;
                                  ?></td>
                              </tr>
                              <tr>
                                  <td class="td-intestazione">TITOLO STUDI</a></td>
                                  <td><?php echo $obj_form->titolo_studi;?></td>
                              </tr>
                              <?php if($obj_form->voto!=""){?>
                               <tr>
                                  <td class="td-intestazione">VOTO</a></td>
                                  <td><?php echo $obj_form->voto;?></td>
                              </tr>
                              <?php } ?>
                              <?php if($obj_form->descrizione!=""){?>
                               <tr>
                                  <td class="td-intestazione">MATERIE PRINCIPALI</a></td>
                                  <td><?php echo $obj_form->descrizione;?></td>
                              </tr>
                              <?php } ?>

                              <?php if($count!=$num){?><tr ><td colspan="2"><hr></td></tr><?php } ?>

                                  <?php } ?>

							  <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:center;">
								 <button onClick="document.location.href='<?php echo "istruzione.php?id=".$_GET["id"];?>'"  class="btn btn-primary btn-sm">
                                                                     <i class="fa fa-pencil"></i>Modifica Istruzione
							     </button>
                                 </td>
								 </tr>
                              </tbody>
                          </table>
						   </div>
					  <!--FINE ISTRUZIONE-->
					  <?php
                          }
                                          $num = $result_exp->num_rows;
                                          if($num>0){



?>


					  <!--ESPERIENZA-->
			  <div id="esperienza" class="col-lg-12 ds">
						<h3>ESPERIENZA</h3>

						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>
                                   <?php
                                  $count=0;

                                  while($obj_exp= $result_exp->fetch_object()){

									  $sql_skill= "SELECT * FROM skill WHERE id_esp='".$obj_exp->id_esp."'  AND skill_user_id= ".$_SESSION['user_idd'];
                              		$result_skill = $conn->query($sql_skill);

                                      $count++;
                                      if(substr($obj_exp->a,6,4) == "0000") $a="Data Attuale";
                                      else $a = $obj_exp->a;
                                  ?>
                              <tr>
                                  <td class="td-intestazione"><?php echo $obj_exp->da."-".$a?></td>
                                  <td><?php echo $obj_exp->ruolo;
                                  ?></td>
                              </tr>

                                <tr>
                                  <td class="td-intestazione">AREA DI COMPETENZA</td>
                                  <td><?php echo $obj_exp->area;?></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">AZIENDA</td>
                                  <td><?php echo $obj_exp->azienda. " - " . $obj_exp->indirizzo . " - ". $obj_exp->citta_azienda ."(".$obj_exp->provincia_azienda.")";?></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">DESCRIZIONE</td>
                                  <td><?php echo $obj_exp->mansione;?></td>
                              </tr>

								  <?php if($result_skill->num_rows>0) {?>

								  <tr>
                                  <td class="td-intestazione">SKILL</td>
                                  <td>


								<?php
									  while($obj_skill= $result_skill->fetch_object()){

									  echo "- " .$obj_skill->skill ." (".$obj_skill->livello_skill.")";
									  echo "<br>";
									  }
									  ?>
								</td>

                              </tr>
								  	  <?php } ?>

                               <?php if($count!=$num){?><tr ><td colspan="2"><hr></td></tr><?php } ?>
                                  <?php } ?>
							  <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:center;">
								  <button onClick="document.location.href='<?php echo "esperienza.php?id=".$_GET["id"];?>'"  class="btn btn-primary btn-sm">
                                                                     <i class="fa fa-pencil"></i>Modifica Esperienza
							     </button>
                                 </td>
								 </tr>
                              </tbody>
                          </table>
						   </div>
					  <!--FINE ESPERIENZA-->
                                          <?php }

                                          $num = $result_cert->num_rows;
                                          if($num>0){?>

					  <!--CERTIFICAZIONI-->
			  <div id="certificazione" class="col-lg-12 ds">
						<h3>CERTIFICAZIONI</h3>

						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>
                                  <?php
                                  $count=0;

                                  while($obj_cert= $result_cert->fetch_object()){
                                      $count++;
                                      if(substr($obj_cert->a,6,4) == "0000") $a="Data Attuale";
                                      else $a = $obj_cert->a;
                                  ?>
                              <tr>
                                  <td class="td-intestazione"><?php echo $obj_cert->da."-".$a?></td>
                                  <td><?php echo $obj_cert->titolo_certificazione;
                                  ?></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">ENTE CERTIFICANTE</td>
                                  <td><?php echo $obj_cert->ente_certificante;
                                  ?></td>
                              </tr>

				<?php if($count!=$num){?><tr ><td colspan="2"><hr></td></tr><?php } ?>
                                  <?php } ?>
							  <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:center;">
								  <button onClick="document.location.href='<?php echo "certificazioni.php?id=".$_GET["id"];?>'"  class="btn btn-primary btn-sm">
                                                                     <i class="fa fa-pencil"></i>Modifica Certificazioni
							     </button>
                                 </td>
								 </tr>
                              </tbody>
                          </table>
						   </div>
					  <!--FINE CERTIFICAZIONI-->
                                          <?php }
                                          $num = $result_courses->num_rows;
                                          if($num>0){
                                          ?>
					  <!--CORSI-->
			  <div id="corsi" class="col-lg-12 ds">
						<h3>CORSI</h3>

						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>
                                  <?php
                                  $count=0;

                                  while($obj_courses= $result_courses->fetch_object()){
                                      $count++;

                                  ?>
                              <tr>
                                  <td class="td-intestazione">TIPO</td>
                                  <td><?php echo $obj_courses->tipo;?></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">DESCRIZIONE</td>
                                  <td><?php echo $obj_courses->descrizione;?></td>
                              </tr>
                              <?php if($count!=$num){?><tr ><td colspan="2"><hr></td></tr><?php } ?>
                                  <?php } ?>
							  <!-- Pulsante Modifica -->
								  <tr>
                                                                      <td colspan="2" style="text-align:center;">
                                                                       <button onClick="document.location.href='<?php echo "corsi.php?id=".$_GET["id"];?>'"  class="btn btn-primary btn-sm">
                                                                     <i class="fa fa-pencil"></i>Modifica Corsi
							     </button>
                                 </td>
								 </tr>
                              </tbody>
                          </table>
						   </div>
					  <!--FINE CORSI-->
                                          <?php }
                                          $num = $result_competenze->num_rows;
                                          if($num>0){
                                          ?>
					  <!--RIEPILOGO COMPETENZE-->
			  <div id="corsi" class="col-lg-12 ds">
						<h3>RIEPILOGO COMPETENZE</h3>

						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>
                                  <?php
                                  $count=0;

                                  while($obj_competenze= $result_competenze->fetch_object()){
                                      $count++;
                                     if($obj_competenze->tipo_competenza==1) $tipo="Conoscenze Informatiche";
                                     if($obj_competenze->tipo_competenza==2) $tipo="Altre Conoscenze"
                                  ?>
                              <tr>
                                  <td class="td-intestazione">TIPO</td>
                                  <td><?php echo $tipo;?></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">DESCRIZIONE</td>
                                  <td><?php echo $obj_competenze->descrizione;?></td>
                              </tr>
                              <?php if($count!=$num){?><tr ><td colspan="2"><hr></td></tr><?php } ?>
                                  <?php } ?>
							  <!-- Pulsante Modifica -->
								  <tr>
                                                                      <td colspan="2" style="text-align:center;">
                                                                       <button onClick="document.location.href='<?php echo "riepilogo_competenze.php?id=".$_GET["id"];?>'"  class="btn btn-primary btn-sm">
                                                                     <i class="fa fa-pencil"></i>Modifica Competenze
							     </button>
                                 </td>
								 </tr>
                              </tbody>
                          </table>
						   </div>
					  <!--FINE CONOSCENZE-->


      <?php
                          }
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

    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="assets/js/gritter-conf.js"></script>

    <!--script-->
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

    <script src="assets/js/sparkline-chart.js"></script>
	<script src="assets/js/zabuto_calendar.js"></script>

  </body>
</html>
