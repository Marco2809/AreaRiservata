<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/istruzione.class.php');
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

                              $istruzione = new Istruzione();
                              $delistruzione = $istruzione ->delIstruzione($_POST['id_form']);
                              if($delistruzione=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> L\'istruzione è stata eliminata con successo.
</div>';

                              if($delistruzione=="error") echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
  <strong>Attenzione!</strong> L\'istruzione non è stata eliminata.
</div>';
                          }

                          if(isset($_POST['modify']) || isset($_POST['save'])) {

                              $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>I seguenti campi sono obbligatori:</strong>';
                              $alert_list="";
                              if($_POST['titolo_studi']=="") $alert_list.= '<br>- Titolo di studio';
                              if($_POST['da']=="") $alert_list.= '<br>- Data Inizio';
                              if(isset($_POST['in_corso'])) $_POST['a'] = '0000';
                              if($_POST['descrizione']=="") $alert_list.= '<br>- Materie Principali';
                              $alert_end = '</div>';
                              if ($alert_list == "") {
                                  $istruzione = new Istruzione();
                                  if (isset($_POST['modify'])) {
                                        $insert_istruzione = $istruzione->updateIstruzione($_POST['id_form'],$_GET['id'], $_POST['titolo_studi'], $_POST['da'], $_POST['a'], $_POST['titolo'], $_POST['paese'], $_POST['corso'], $_POST['laurea'], $_POST['voto'],$_POST['descrizione'],'No',$_POST['ateneo']);
                                        if ($insert_istruzione == "ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                                              <strong>Ben fatto!</strong> L\'istruzione è stata modificata con successo.
                                            </div>';
                                            unset($_POST);
                                  }

                                  if(isset($_POST['save'])) {
                                        $insert_istruzione = $istruzione->setIstruzione($_GET['id'], $_POST['titolo_studi'], $_POST['da'], $_POST['a'], $_POST['titolo'], $_POST['paese'], $_POST['corso'], $_POST['laurea'], $_POST['voto'],$_POST['descrizione'],'No',$_POST['ateneo']);
                                        if($insert_istruzione == "ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                                              <strong>Ben fatto!</strong> L\'istruzione è stata salvata con successo.
                                            </div>';
                                        unset($_POST);
                                  }
                              } else echo $alert . $alert_list . $alert_end;
                          }

                          if($_GET['id']==$_SESSION['user_idd']||$_SESSION['is_admin']==1){

                              $sql_dati= "SELECT * FROM anagrafica a,login l WHERE a.user_id=l.user_idd AND a.user_id= ".$_SESSION['user_idd'];
                              $result_dati = $conn->query($sql_dati);

                              $sql_exp= "SELECT * FROM esperienza WHERE user_id= ".$_SESSION['user_idd'];
                              $result_exp = $conn->query($sql_exp);

                              $sql_form= "SELECT * FROM formazione WHERE user_id= ".$_SESSION['user_idd']." ORDER BY da DESC";
                              $result_form = $conn->query($sql_form);

                              $sql_cert = "SELECT * FROM certificazione WHERE user_id= ".$_SESSION['user_idd'];
                              $result_cert = $conn->query($sql_cert);

                              $sql_courses = "SELECT * FROM corsi WHERE user_id= ".$_SESSION['user_idd'];
                              $result_courses = $conn->query($sql_courses);

                              $sql_competenze = "SELECT * FROM riepilogo_competenze WHERE user_id= ".$_SESSION['user_idd'];
                              $result_competenze = $conn->query($sql_competenze);
           include('top_menu_cv.php');
                          ?>


					  <!--ISTRUZIONE-->
			  <div id="istruzione" class="col-lg-12 ds">

                                                <button style="margin-top:10px; margin-bottom: 20px;" onclick="comparsa_data1('form');" type="button" class="btn btn-success">Aggiungi Nuova</button>
                                                          <form action="" method="post">
                                                    <table id="form" class="table table-striped table-advance table-hover" <?php if(!isset($_POST['save'])) { ?> style="display:none; margin-top:1%;" <?php } else {?> style="margin-top:1%;" <?php } ?>>
                              <tbody>
                              <tr>
                                  <td class="td-intestazione">TITOLO STUDI</a></td>
                                  <td><select class="form-control" style="width:25%;" name="titolo_studi">
                                          <option value="Abilitazione" <?php if($_POST['titolo_studi']=="Abilitazione"){?> selected="selected" <?php } ?>>Abilitazione</option>
                                          <option value="Master" <?php if($_POST['titolo_studi']=="Master"){?> selected="selected" <?php } ?>>Master</option>
                                          <option value="Laurea" <?php if($_POST['titolo_studi']=="Laurea"){?> selected="selected" <?php } ?>>Laurea</option>
                                          <option value="Diploma" <?php if($_POST['titolo_studi']=="Diploma"){?> selected="selected" <?php } ?>>Diploma</option>
                                      </select>
                                  </td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">NOME ATENEO/ISTITUTO</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="ateneo" value="<?php if(isset($_POST['ateneo'])) echo $_POST['ateneo'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">CORSO DI LAUREA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="laurea" value="<?php if(isset($_POST['laurea'])) echo $_POST['laurea'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">CORSO DI STUDI</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="corso" value="<?php if(isset($_POST['corso'])) echo $_POST['corso'];?>"></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">VOTO</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="voto" value="<?php if(isset($_POST['voto'])) echo $_POST['voto'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">STUDI ANCORA IN CORSO</td>
                                  <td><input type="checkbox" <?php if(isset($_POST['in_corso'])){?> checked="checked" <?php } ?> name="in_corso" onclick="comparsa_data1('a_form')"></td>
                              </tr>


                              <tr >
                                  <td class="td-intestazione">DA</td>
                                  <td><select class="form-control" style="width:25%;" name="da">
                                          <?php for($i=date('Y');$i>=date('Y')-50;$i--){?>
                                          <option value="<?php echo $i;?>" <?php if($_POST['da']==$i) { ?> selected="selected" <?php } ?>><?php echo $i;?></option>
                                          <?php } ?>
                                      </select>
                                  </td>

                              </tr>

                              <tr <?php if(isset($_POST['in_corso'])){?> style="display:none;" <?php } ?> id="a_form">
                                  <td class="td-intestazione">A</td>
                                  <td><select class="form-control" style="width:25%;" name="a">
                                          <?php for($i=date('Y');$i>=date('Y')-50;$i--){?>
                                          <option value="<?php echo $i;?>" <?php if($_POST['a']==$i) { ?> selected="selected" <?php } ?>><?php echo $i;?></option>

                                              <?php } ?>
                                      </select>
                                  </td>

                              </tr>

                               <tr>
                                  <td class="td-intestazione">MATERIE PRINCIPALI</a></td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="descrizione" rows="4" cols="20"><?php if(isset($_POST['descrizione'])) echo $_POST['descrizione'];?></textarea></td>
                              </tr>


                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">

                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Istruzione
                                                                      </button>
                                 </td>
								 </tr>


                              </tbody>
                          </table>
                                                </form></div>

                                  <?php
                                  $count=0;


                                          $num = $result_form->num_rows;
                                          if($num>0){

                                  while($obj_form= $result_form->fetch_object()){
                                      $count++;

                                  ?>
                                          <div id="istruzione" class="col-lg-12" style="margin-top:10px;">
             <div class="panel panel-list" >
                 <div class="panel-heading" onclick="comparsa_data1('form_<?php echo $obj_form->id_form;?>')"><h4 style=" margin:0;"><span style="margin-right:10px;" class="glyphicon glyphicon-chevron-down"></span><?php echo $obj_form->titolo_studi;?></h4></div>
      <div class="panel-body" id="form_<?php echo $obj_form->id_form;?>" style="display:none; margin-top:1%;">


                                                <form action="" method="post">
                                                    <table  class="table table-striped table-advance table-hover" >
                              <tbody>
                              <tr>
                                  <td class="td-intestazione">TITOLO STUDI</a></td>
                                  <td><select class="form-control" style="width:25%;" name="titolo_studi">
                                          <option value="Abilitazione" <?php if($obj_form->titolo_studi=="Abilitazione"){?> selected="selected" <?php } ?>>Abilitazione</option>
                                          <option value="Master" <?php if($obj_form->titolo_studi=="Master"){?> selected="selected" <?php } ?>>Master</option>
                                          <option value="Laurea" <?php if($obj_form->titolo_studi=="Laurea"){?> selected="selected" <?php } ?>>Laurea</option>
                                          <option value="Diploma" <?php if($obj_form->titolo_studi=="Diploma"){?> selected="selected" <?php } ?>>Diploma</option>
                                  </td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">NOME ATENEO/ISTITUTO</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="ateneo" value="<?php if(isset($obj_form->ateneo))echo $obj_form->ateneo;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">CORSO DI LAUREA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="laurea" value="<?php if(isset($obj_form->laurea))echo $obj_form->laurea;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">CORSO DI STUDI</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="corso" value="<?php if(isset($obj_form->corso))echo $obj_form->corso;?>"></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">VOTO</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="voto" value="<?php if(isset($obj_form->voto))echo $obj_form->voto;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">STUDI ANCORA IN CORSO</td>
                                  <td><input type="checkbox" name="in_corso" onclick="comparsa_data1('a_form_<?php echo $obj_form->id_form?>')" <?php if($obj_form->a=="0000") {?> checked="checked" <?php } ?>></td>
                              </tr>


                              <tr >
                                  <td class="td-intestazione">DA</td>
                                  <td><select class="form-control" style="width:25%;" name="da">
                                          <?php for($i=date('Y');$i>=date('Y')-50;$i--){?>
                                          <option value="<?php echo $i;?>" <?php if($obj_form->da==$i) { ?> selected="selected" <?php } ?>><?php echo $i;?></option>
                                          <?php } ?>
                                      </select>
                                  </td>

                              </tr>

                              <tr <?php if($obj_form->a=="0000") { ?> style="display:none;" <?php } ?> id="a_form_<?php echo $obj_form->id_form;?>">
                                  <td class="td-intestazione">A</td>
                                  <td><select class="form-control" style="width:25%;" name="a">
                                          <?php for($i=date('Y');$i>=date('Y')-50;$i--){?>
                                          <option value="<?php echo $i;?>" <?php if($obj_form->a==$i) { ?> selected="selected" <?php } ?>><?php echo $i;?></option>

                                              <?php } ?>
                                      </select>
                                  </td>

                              </tr>

                               <tr>
                                  <td class="td-intestazione">MATERIE PRINCIPALI</a></td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="descrizione" rows="4" cols="20"><?php if(isset($obj_form->descrizione)) echo $obj_form->descrizione;?></textarea></td>
                              </tr>


                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">

                                                                      <input type="hidden" name="id_form" value="<?php echo $obj_form->id_form;?>">
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Modifica Istruzione
                                                                      </button>

                                                                      <button type="submit" name="delete" class="btn btn-danger btn-xs">
							     <i class="fa fa-pencil"></i> Elimina Istruzione
                                                                      </button>
                                 </td>
								 </tr>



                              </tbody>
                          </table>
                                                </form></div>
    </div></div>
                                                                 <?php
                                                                 }



                          } ?>

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
