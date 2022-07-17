<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/questionario.class.php');
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

 <script language="javascript">
    function toggleMe(obj, a){
      var e=document.getElementById(a);
      if(obj=="altro"){
        e.style.display="";
      }else{
    e.style.display="none";
}
    }
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

                          if(isset($_POST['modify'])||isset($_POST['save'])) {

                              $questionario = new Questionario();
                              if(isset($_POST['modify'])){

                              $insert_questionario = $questionario ->updateQuestionario($_GET['id'],$_POST['tempo'],$_POST['soddisfazione'],$_POST['ambiente'],$_POST['ambiente_altro'],$_POST['ruolo_ambiente'],$_POST['ruolo_ambiente_altro'],$_POST['area'],$_POST['area_altro'],$_POST['crescita']);
                              //echo $insert_anagrafica;
                              if($insert_questionario=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> Il Questionario è stato modificato con successo.
</div>';

                              }

                              if(isset($_POST['save'])){

                              $insert_questionario = $questionario ->setQuestionario($_GET['id'],$_POST['tempo'],$_POST['soddisfazione'],$_POST['ambiente'],$_POST['ambiente_altro'],$_POST['ruolo_ambiente'],$_POST['ruolo_ambiente_altro'],$_POST['area'],$_POST['area_altro'],$_POST['crescita']);
                              if($insert_questionario=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> Il Questionario è stato salvato con successo.
</div>';

                              }
                              } else echo $alert.$alert_list.$alert_end;


                          if($_GET['id']==$_SESSION['user_idd']||$_SESSION['is_admin']==1){



                              $sql_questionario = "SELECT * FROM questionario WHERE user_id= ".$_SESSION['user_idd'];
                              $result_questionario = $conn->query($sql_questionario);
                         include('top_menu_cv.php');
                          ?>


			  <!--DATI PERSONALI-->
                      <div id="dati-personali" class="col-lg-12 ds" class="left" style="overflow-y: scroll;
    height: 100%;">
						<h3>QUESTIONARIO</h3>
						<form action="" method="post">
						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>
                                  <?php
                                  $obj_dati= $result_questionario->fetch_object();
                                  ?>

                              <tr>
                                  <td class="td-intestazione">DA QUANTO TEMPO LAVORI IN ST?</td>
                                  <td>
                                       <select style="width:25%;" class="form-control" id="tempo" name="tempo">
        <option <?php if(isset($obj_dati->tempo)&&$obj_dati->tempo=="meno di 6 mesi") {?> selected="selected" <?php } ?> value="meno di 6 mesi">Meno di 6 mesi</option>
         <option <?php if(isset($obj_dati->tempo)&&$obj_dati->tempo=="da 6 mesi ad 1 anno") {?> selected="selected" <?php } ?> value="da 6 mesi ad 1 anno">da 6 mesi ad 1 anno</option>
          <option <?php if(isset($obj_dati->tempo)&&$obj_dati->tempo=="da 1 anno a 3 anni") {?> selected="selected" <?php } ?> value="da 1 anno a 3 anni">da 1 anno a 3 anni</option>
           <option <?php if(isset($obj_dati->tempo)&&$obj_dati->tempo=="da 3 anni a 5 anni") {?> selected="selected" <?php } ?> value="da 3 anni a 5 anni">da 3 anni a 5 anni</option>
            <option <?php if(isset($obj_dati->tempo)&&$obj_dati->tempo=="oltre 5 anni") {?> selected="selected" <?php } ?> value="oltre 5 anni">oltre 5 anni</option>
</select>
                                  </td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">QUANTO SEI SODDISFATTO DEL TUO LAVORO IN ST?</td>
                                  <td>
                                      <select style="width:25%;" class="form-control" id="soddisfazione" name="soddisfazione">
        <option  <?php if(isset($obj_dati->soddisfazione)&&$obj_dati->soddisfazione=="molto soddisfatto") {?> selected="selected" <?php } ?> value="molto soddisfatto">Molto Soddisfatto</option>
         <option <?php if(isset($obj_dati->soddisfazione)&&$obj_dati->soddisfazione=="soddisfatto") {?> selected="selected" <?php } ?> value="soddisfatto">Soddisfatto</option>
          <option <?php if(isset($obj_dati->soddisfazione)&&$obj_dati->soddisfazione=="neutrale") {?> selected="selected" <?php } ?> value="neutrale">Neutrale</option>
           <option <?php if(isset($obj_dati->soddisfazione)&&$obj_dati->soddisfazione=="non soddisfatto") {?> selected="selected" <?php } ?> value="non soddisfatto">Non Soddisfatto</option>
            <option <?php if(isset($obj_dati->soddisfazione)&&$obj_dati->soddisfazione=="per niente soddisfatto") {?> selected="selected" <?php } ?> value="per niente soddisfatto">Per Niente Soddisfatto</option>
</select>

                                  </td>
						      </tr>

                                                       <tr>
                                  <td class="td-intestazione">IN QUALE AMBIENTE LAVORI IN ST?</td>
                                  <td>
                                      <select style="width:25%;" class="form-control" id="ambiente" name="ambiente" onchange="toggleMe(this.options[this.selectedIndex].value, 'altro_ambiente');">
        <option <?php if(isset($obj_dati->ambiente)&&$obj_dati->ambiente=="amministrativo") {?> selected="selected" <?php } ?> value="amministrativo">Amministrativo</option>
         <option <?php if(isset($obj_dati->ambiente)&&$obj_dati->ambiente=="commerciale") {?> selected="selected" <?php } ?> value="commerciale">Commerciale</option>
          <option <?php if(isset($obj_dati->ambiente)&&$obj_dati->ambiente=="risorse umane") {?> selected="selected" <?php } ?> value="risorse umane">Risorse Umane</option>
           <option <?php if(isset($obj_dati->ambiente)&&$obj_dati->ambiente=="delivery") {?> selected="selected" <?php } ?> value="delivery">Delivery</option>
            <option <?php if(isset($obj_dati->ambiente)&&$obj_dati->ambiente=="comunicazione") {?> selected="selected" <?php } ?> value="comunicazione">Comunicazione</option>
            <option <?php if(isset($obj_dati->ambiente)&&$obj_dati->ambiente=="altro") {?> selected="selected" <?php } ?> value="altro">Altro</option>
</select>
                                      </td>
						      </tr>

                                                      <tr id="altro_ambiente" <?php if($obj_dati->ambiente != "altro") {?> style="display:none;" <?php } ?>>
                           <td class="td-intestazione">
                             ALTRO:
                           </td>
                           <td>
   <input style="width:25%;" class="form-control"  type="text" name="ambiente_altro" value="<?php if(isset($obj_dati->ambiente_altro)&&$obj_dati->ambiente_altro!="") echo $obj_dati->ambiente_altro; ?>">
</td>
</tr>

                                                      <tr>
                                  <td class="td-intestazione">IL TUO RUOLO NELL'AMBIENTE DOVE LAVORI IN ST?</td>
                                  <td>
         <select style="width:25%;" class="form-control" id="ruolo_ambiente" name="ruolo_ambiente" onchange="toggleMe(this.options[this.selectedIndex].value, 'altro_ruolo');">
        <option <?php if(isset($obj_dati->ruolo_ambiente)&&$obj_dati->ruolo_ambiente=="manager") {?> selected="selected" <?php } ?> value="manager">Manager</option>
         <option <?php if(isset($obj_dati->ruolo_ambiente)&&$obj_dati->ruolo_ambiente=="tecnico") {?> selected="selected" <?php } ?> value="tecnico">Tecnico</option>
          <option <?php if(isset($obj_dati->ruolo_ambiente)&&$obj_dati->ruolo_ambiente=="operativo") {?> selected="selected" <?php } ?> value="operativo">Operativo</option>
           <option <?php if(isset($obj_dati->ruolo_ambiente)&&$obj_dati->ruolo_ambiente=="altro") {?> selected="selected" <?php } ?> value="altro">Altro</option>
</select>
                                      </td>
						      </tr>

                                                       <tr id="altro_ruolo" <?php if($obj_dati->ruolo_ambiente != "altro") {?> style="display:none;" <?php } ?>>
                           <td class="td-intestazione">
                             ALTRO:
                           </td>
                           <td>
   <input style="width:25%;" class="form-control" type="text" name="ruolo_ambiente_altro" value="<?php if(isset($obj_dati->ruolo_ambiente_altro)&&$obj_dati->ruolo_ambiente_altro!="") echo $obj_dati->ruolo_ambiente_altro; ?>">
</td>
</tr>

                                                      <tr>
                                  <td class="td-intestazione">IN QUALE AREA DELL'AZIENDA VORRESTI LAVORARE  E CRESCERE</td>
                                  <td>
                                      <select style="width:25%;" class="form-control" onchange="toggleMe(this.options[this.selectedIndex].value, 'altro_crescita');" name="area">
<option <?php if($obj_dati->area=="Amministrativa"){?>selected="selected"<?php } ?> value="Amministrativa" >Amministrativa</option>
<option <?php if($obj_dati->area=="Commerciale"){?>selected="selected"<?php } ?> value="Commerciale" >Commerciale</option>
<option <?php if($obj_dati->area=="Comunicazione"){?>selected="selected"<?php } ?> value="Comunicazione" >Comunicazione</option>
<option <?php if($obj_dati->area=="Delivery"){?>selected="selected"<?php } ?> value="Delivery" >Delivery</option>
<option <?php if($obj_dati->area=="Risorse Umane"){?>selected="selected"<?php } ?> value="Risorse Umane" >Risorse Umane</option>
<option <?php if($obj_dati->area=="Sys Administrator"){?>selected="selected"<?php } ?> value="Sys Administrator" >Sys Administrator</option>
<option <?php if($obj_dati->area=="Tecnico Hardware"){?>selected="selected"<?php } ?>  value="Tecnico Hardware" >Tecnico Hardware</option>
        <option <?php if($obj_dati->area=="Phisical Network Developer"){?>selected="selected"<?php } ?> value="Phisical Network Developer" >Phisical Network Developer</option>
        <option <?php if($obj_dati->area=="Network Administrator"){?>selected="selected"<?php } ?> value="Network Administrator" >Network Administrator</option>
        <option <?php if($obj_dati->area=="Consulente"){?>selected="selected"<?php } ?> value="Consulente" >Consulente</option>
        <option <?php if($obj_dati->area=="Developer - IT Solutions"){?>selected="selected"<?php } ?> value="Developer - IT Solutions" >Developer - IT Solutions</option>
         <option <?php if($obj_dati->area=="Web Design - Grafica 3D"){?>selected="selected"<?php } ?> value="Web Design - Grafica 3D" >Web Design - Grafica 3D</option>
          <option <?php if($obj_dati->area=="altro"){?>selected="selected"<?php } ?> value="altro">Altro</option>
    </select>

                                  </td>
						      </tr>

                                                          <tr id="altro_crescita" <?php if($obj_dati->area != "altro") {?> style="display:none;" <?php } ?>>
                           <td class="td-intestazione">
                             ALTRO:
                           </td>
                           <td>
   <input style="width:25%;" class="form-control" type="text" name="area_altro" value="<?php if(isset($obj_dati->area_altro)&&$obj_dati->area_altro!="") echo $obj_dati->area_altro; ?>">
</td>
</tr>


                                                      <tr>
                                  <td class="td-intestazione">COME TI VEDI TRA 5 ANNI IN ST?</td>
                                  <td>
                                      <textarea class="form-control" style="resize:none; width: 40%;" name="crescita" rows="4" cols="20"><?php if(isset($obj_dati->crescita)) echo $obj_dati->crescita?></textarea>
                                  </td>
						      </tr>

                                                       <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">
                                                                      <?php if($obj_dati->tempo!=""){?>
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Modifica Questionario
                                                                      </button><?php } else {?>
                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Questionario
                                                                      </button>
                                                                      <?php } ?>
                                 </td>
								 </tr>



                              </tbody>
                          </table>
                                                </form>
						  </div>
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
