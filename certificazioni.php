<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/certificazione.class.php');
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
  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script><link rel="stylesheet" href="css/style.css">
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

                          if(isset($_POST['delete_img'])){
                              $database = new Database();
                              $conn = $database->dbConnection();

                              $target_dir = "files/cert/".$_GET['id']."/".$_POST['id_cert']."/";
                              chdir($target_dir);
                              unlink($_POST['nome_file']);
                              $nome=explode(".",$_POST['nome_file']);

                          $sql_delete="DELETE FROM files WHERE id_messaggio=".$_POST['id_cert']." AND nome =  '".$nome[0]."'";
                          $result_delete_mex = $conn->query($sql_delete);
                              if(!$result_delete_mex){
                                   $alert_allegato = "<div class='panel panel-danger'><div class='panel-heading'>Problema nell'eliminazione del file.</div></div>";
                              } else {
                                  $alert_allegato = "<div class='panel panel-success'><div class='panel-heading'>File correlato eliminato con successo!</div></div>";
                              }
                              unset($_POST['nome_file']);
                          }

                          if(isset($_POST['delete'])) {

                              $certificazione = new Certificazione();
                              $delcertificazione = $certificazione ->delCertificazione($_POST['id_cert']);

                              if($delcertificazione=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> La certificazione è stata eliminata con successo.
</div>';

                              if($delcertificazione=="error") echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
  <strong>Attenzione!</strong> La certificazione non è stata eliminata.
</div>';
                          }

                          if(isset($_POST['modify'])||isset($_POST['save'])) {

                              $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>I seguenti campi sono obbligatori:</strong>';
                              $alert_list="";
                              if($_POST['titolo_certificazione']=="") $alert_list.= '<br>- Titolo della certificazione';
                              if($_POST['da']=="") $alert_list.= '<br>- Data Inizio';
                              if(isset($_POST['senza_scadenza'])) $_POST['a'] = '00/00/0000';
                              if($_POST['ente_certificante']=="") $alert_list.= '<br>- Ente Certificante';
                              $alert_end = '</div>';
                              if($alert_list==""){
                              $certificazione = new Certificazione();
                              if(isset($_POST['modify'])){

                              $insert_certificazione = $certificazione ->updateCertificazione($_POST['id_cert'],$_POST['titolo_certificazione'],$_POST['cod_licenza'],$_POST['url'],$_POST['ente_certificante'],$_POST['da'],$_POST['a']);
                              //echo $insert_certificazione;
                              $alert_allegato="";
                              $target_dir = "files/cert/".$_GET['id']."/".$_POST['id_cert']."/";
                              //echo $target_dir;
                              function error_handler($errno, $errstr) {
                                          global $last_error;
                                          $last_error = $errstr;
                                      }

                                      set_error_handler('error_handler');
                                      if (!mkdir($target_dir, 0755,true))
                                          echo "MKDIR failed, reason: $last_error\n";
                                      restore_error_handler();
                              echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">'.$_FILES["fileToUpload"]["name"].'</div>';
                              $target_file = $target_dir . $_FILES["fileToUpload"]["name"];
                              $database = new Database();
                              $conn = $database->dbConnection();
                              $uploadOk = 1;
                              $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                              $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

                              if ($_FILES["fileToUpload"]["size"] > 10000000) {
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
                              $sql_files="INSERT into files_certificazioni (estensione, nome, titolo, id_certificazione, id_user)
                                                        VALUES ('" . $estensione[count($estensione)-1] . "','" .$_FILES["fileToUpload"]["name"]. "','" .$_POST["titolo_certificazione"]. "','" .$_POST['id_cert']. "','" .$_GET['id']. "')";
                                  $result = $conn->query($sql_files);

                              if($insert_certificazione=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> La certificazione è stata modificata con successo.
</div>';
echo $alert_allegato;
                              unset($_POST);
                              }

                              if(isset($_POST['save'])){

                              $insert_certificazione = $certificazione ->setCertificazione($_GET['id'],$_POST['titolo_certificazione'],$_POST['cod_licenza'],$_POST['url'],$_POST['ente_certificante'],$_POST['da'],$_POST['a']);
                              if($insert_certificazione!="error") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> La certificazione è stata salvata con successo.
</div>';

echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">'.$_FILES["fileToUpload"]["name"].'</div>';
$alert_allegato="";
$target_dir = "files/cert/".$_GET['id']."/".$insert_certificazione."/";
if(!file_exists($target_dir)) {
  mkdir($target_dir, 0777) ;
}
$target_file = $target_dir . $_FILES["fileToUpload"]["name"];
$database = new Database();
$conn = $database->dbConnection();
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

if ($_FILES["fileToUpload"]["size"] > 10000000) {
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
$sql_files="INSERT into files_certificazioni (estensione, nome, titolo, id_certificazione, id_user)
                          VALUES ('" . $estensione[count($estensione)-1] . "','" .$_FILES["fileToUpload"]["name"]. "','" .$_POST["titolo_certificazione"]. "','" .$_POST['id_cert']. "','" .$_GET['id']. "')";
    $result = $conn->query($sql_files);
                              unset($_POST);
                              }
                              } else echo $alert.$alert_list.$alert_end;
                          }

                          if($_GET['id']==$_SESSION['user_idd']||$_SESSION['is_admin']==1){


                              $sql_cert = "SELECT * FROM certificazione WHERE user_id= ".$_SESSION['user_idd'];
                              $result_cert = $conn->query($sql_cert);
         include('top_menu_cv.php');
                          ?>

					  <!--ISTRUZIONE-->
			  <div id="istruzione" class="col-lg-12 ds">

                                                <button style="margin-top:10px; margin-bottom: 20px;" onclick="comparsa_data1('cert');" type="button" class="btn btn-success">Aggiungi Nuova</button>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <table id="cert" class="table table-striped table-advance table-hover" <?php if(!isset($_POST['save'])) { ?> style="display:none; margin-top:1%;" <?php } else {?> style="margin-top:1%;" <?php } ?>>
                              <tbody>
                              <tr>
                                  <td class="td-intestazione">TITOLO CERTIFICAZIONE</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="titolo_certificazione" value="<?php if(isset($_POST['titolo_certificazione'])) echo $_POST['titolo_certificazione'];?>">

                                  </td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">ENTE CERTIFICANTE</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="ente_certificante" value="<?php if(isset($_POST['ente_certificante'])) echo $_POST['ente_certificante'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">CODICE LICENZA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="cod_licenza" value="<?php if(isset($_POST['cod_licenza'])) echo $_POST['cod_licenza'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">URL</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="url" value="<?php if(isset($_POST['url'])) echo $_POST['url'];?>"></td>
                              </tr>


                              <tr>
                                  <td class="td-intestazione">SENZA SCADENZA</td>
                                  <td><input type="checkbox" <?php if(isset($_POST['senza_scadenza'])){?> checked="checked" <?php } ?> name="senza_scadenza" onclick="comparsa_data1('a_cert')"></td>
                              </tr>


                              <tr >
                                  <td class="td-intestazione">INIZIO VALIDITA</td>
                                  <td><div style="width:25%"  id="datepicker_da" class="input-group date" data-date-format="mm-dd-yyyy">
   <input class="form-control"  id="datetimepicker_da" name="da" placeholder="DD/MM/YYYY" type="text" value="<?php if(isset($_POST['da'])) echo $_POST['da'];?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>
                                  </td>

                              </tr>

                              <tr <?php if(isset($_POST['senza_scadenza'])){?> style="display:none;" <?php } ?> id="a_cert">
                                  <td class="td-intestazione">FINE VALIDITA</td>
                                  <td><div style="width:25%" id="datepicker_a" class="input-group date" data-date-format="mm-dd-yyyy">
                                          <input  class="form-control" id="datetimepicker_a" name="a" placeholder="DD/MM/YYYY" type="text" value="<?php if(isset($_POST['a'])) echo $_POST['a'];?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>



        <script type="text/javascript">
           $(function () {
  $("#datepicker_a").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

$(function () {
  $("#datepicker_da").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

        </script>
                                  </td>

                              </tr>
<tr>
  <td colspan="2">
                              <div class="form-group">
                                  <span style="font-weight:600;">Allegati:</span>
                                  <br />
                                  <div class="col-md-9">
                                      <input type="file"   name="fileToUpload" class="form-control" id="fileToUpload">
                                      <input type="hidden" name="id_user" value="<?php echo $_GET['id'];?>">
                                  </div>
                              </div>
                            </td>
</tr>
                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">

                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Certificazione
                                                                      </button>
                                 </td>
								 </tr>


                              </tbody>
                          </table>
                                                </form></div>

                                  <?php
                                  $count=0;

                                          $num = $result_cert->num_rows;
                                          if($num>0){

                                  while($obj_cert= $result_cert->fetch_object()){
                                      $count++;

                                  ?>
                                          <div id="istruzione" class="col-lg-12" style="margin-top:10px;">
             <div class="panel panel-list" >
                 <div class="panel-heading" onclick="comparsa_data1('cert_<?php echo $obj_cert->id;?>')"><h4 style=" margin:0;"><span style="margin-right:10px;" class="glyphicon glyphicon-chevron-down"></span><?php echo $obj_cert->titolo_certificazione;?></h4></div>
      <div class="panel-body" id="cert_<?php echo $obj_cert->id;?>" style="display:none; margin-top:1%;">


                                                                  <form action="" method="post" enctype="multipart/form-data">
                                                    <table id="cert" class="table table-striped table-advance table-hover" >
                              <tbody>
                              <tr>
                                  <td class="td-intestazione">TITOLO CERTIFICAZIONE</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="titolo_certificazione" value="<?php if(isset($obj_cert->titolo_certificazione)) echo $obj_cert->titolo_certificazione;?>">

                                  </td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">ENTE CERTIFICANTE</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="ente_certificante" value="<?php if(isset($obj_cert->ente_certificante)) echo $obj_cert->ente_certificante;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">CODICE LICENZA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="cod_licenza" value="<?php if(isset($obj_cert->cod_licenza)) echo $obj_cert->cod_licenza;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">URL</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="url" value="<?php if(isset($obj_cert->url)) echo $obj_cert->url;?>"></td>
                              </tr>


                              <tr>
                                  <td class="td-intestazione">SENZA SCADENZA</td>
                                  <td><input type="checkbox" <?php if($obj_cert->a=="00/00/0000"){?> checked="checked" <?php } ?> name="senza_scadenza" onclick="comparsa_data1('a_cert<?php echo $obj_cert->id;?>')"></td>
                              </tr>


                              <tr >
                                  <td class="td-intestazione">INIZIO VALIDITA</td>
                                  <td><div style="width:25%"  id="datepicker_da" class="input-group date" data-date-format="mm-dd-yyyy">
   <input class="form-control"  id="datetimepicker_da<?php echo $obj_cert->id;?>" name="da" placeholder="DD/MM/YYYY" type="text" value="<?php if(isset($obj_cert->da)) echo $obj_cert->da;?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>
                                  </td>

                              </tr>

                              <tr <?php if($obj_cert->a=="00/00/0000"){?> style="display:none;" <?php } ?> id="a_cert<?php echo $obj_cert->id;?>">
                                  <td class="td-intestazione">FINE VALIDITA</td>
                                  <td><div style="width:25%" id="datepicker_a" class="input-group date" data-date-format="mm-dd-yyyy">
                                          <input  class="form-control" id="datetimepicker_a<?php echo $obj_cert->id;?>" name="a" placeholder="DD/MM/YYYY" type="text" value="<?php if($obj_cert->a=="00/00/0000") $obj_cert->a=date('d/m/Y');if(isset($obj_cert->a)) echo $obj_cert->a;?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>


        <script type="text/javascript">
           $(function () {
  $("#datepicker_a*").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

$(function () {
  $("#datepicker_da*").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

        </script>
                                  </td>

                              </tr>


                              <!-- Pulsante Modifica -->
								  <tr>
                    <div class="form-group">
                        <span style="font-weight:600;">Allegati:</span>
                        <br />
                        <div class="col-md-9">
                            <input type="file"   name="fileToUpload" class="form-control" id="fileToUpload">
                            <input type="hidden" name="id_user" value="<?php echo $cod_anagr;?>">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
<?php
                                $directory = "./files/cert/" . $_SESSION['user_idd']."/".$obj_cert->id;
                                if (is_dir($directory)) {
                                    if ($directory_handle = opendir($directory)) {
                                        while (($file = readdir($directory_handle)) !== false) {
                                            if ( (!is_dir($file)) && ($file != ".") && ($file != "..") )
                                                if ($file != ".DS_Store" && $file != "." && $file != "..") {
                                                    echo $file . "<button style='margin-left:1%; border:none; padding:0; background-color:transparent;' type='submit' name='delete_img'>
                                                                    <span style='color:red;' class='glyphicon glyphicon-remove'></span>
                                                                </button>
                                                                <input type='hidden' name='nome_file' value='" . $file . "'><br/>";
                                                }
                                        }
                                        closedir ($directory_handle);
                                    }
                                }
?>
                            </div>
                        </div>
                    </div>
								  <td colspan="2" style="text-align:left;">

                                                                      <input type="hidden" name="id_cert" value="<?php echo $obj_cert->id;?>">
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Modifica Certificazione
                                                                      </button>

                                                                      <button type="submit" name="delete" class="btn btn-danger btn-xs">
							     <i class="fa fa-pencil"></i> Elimina Certificazione
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
