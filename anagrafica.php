<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/anagrafica.class.php');
require_once('class/message.class.php');
require_once('assets/php/functions.php');
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

if (isset($_POST["carica_immagine"])) {

    $target_dir = "assets/img/avatar/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777);
    }
    $newfilename = $_SESSION['user_idd'] . "." . end(explode(".", $_FILES["fileToUpload"]["name"]));
    $target_file = $target_dir . $newfilename;
    $uploadOk = 1;
    $alert = "";
    $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = $_FILES["fileToUpload"]["tmp_name"];

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 10000000) {
        $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file è troppo grande.</p>";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($FileType != "pdf" &&
        $FileType != "docx" &&
        $FileType != "csv" &&
        $FileType != "xslx" &&
        $FileType != "png" &&
        $FileType != "pptx" &&
        $FileType != "jpg" &&
        $FileType != "jpeg" &&
        $FileType != "gif" &&
        $FileType != "idt" &&
        $FileType != "xls" &&
        $FileType != "txt" &&
        $FileType != "doc"
    ) {
        $alert .= "<p align='center' style='color:red'>Spiacenti, sono permessi solo file XSL, XSLX, TXT, DOC, DOCX, PPTX, PDF, PPJPG, JPEG, JPG, PNG & GIF.</p>";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $alert .= '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                        <strong>Spiacenti</strong>, c\'è stato un errore nel caricamento del file.<br>'.$alert.'</div>';
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $sql_conoscenza = "UPDATE anagrafica
                               SET img_name = '" . $newfilename . "'
                               WHERE user_id ='" . $_SESSION['user_idd'] . "'";
            $result_conoscenza = $conn->query($sql_conoscenza);

            if (!$result_conoscenza) {
                die('Errore di inserimento dati : ' . mysql_error());
            }
            echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                    <strong>Ben fatto!</strong> Il file è stato caricato con successo.
                </div>';
        } else {
            echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                    <strong>Spiacenti</strong>, c\'è stato un errore nel caricamento del file.
                </div>';
        }
    }
}

        if(isset($_POST['password'])) {

            $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>Risolvere i seguenti errori:<br></strong>';
            $alert_list="";

            if(isset($_REQUEST['p1']) && $_REQUEST['p1'] != '') {
                $password = $_REQUEST['p1'];
            } else {
                $alert_list .= "<br>- Inserire la password!</p>";
            }
              if(isset($_REQUEST['p2']) && $_REQUEST['p2'] != '') {
                $c_password = $_REQUEST['p2'];
            } else {
                 $alert_list .= "<br>- Inserire la password di conferma!</p>";
            }

            if ($_REQUEST['p1'] != $_REQUEST['p2'] ) {

                 $alert_list .= "<br>- Le password non coincidono!</p>";
            }

            $alert_end = '</div>';

            if($alert_list == "") {
                //echo 'Old pw --> ' . $password;
                $password = hash('sha512', $password);
                $sql_login = "UPDATE login SET password = '" . $password .
                        "' WHERE user_idd = " . $_GET['id'] . "";
                //echo $sql_login;

              $result_login = $conn->query($sql_login);

              if($result_login)
              {
                 echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                            <strong>Ben fatto!</strong> La password è stata modificata con successo.
                        </div>';

              } else {
                  echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                            <strong>Attenzione!</strong> La password non è stata modificata con successo.
                        </div>';
              }
            } else {

                echo $alert.$alert_list.$alert_end;

            }
        }

                          if(isset($_POST['modify'])||isset($_POST['save'])) {

                              $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>I seguenti campi sono obbligatori:</strong>';
                              $alert_list="";
                              if($_POST['firstname']=="") $alert_list.= '<br>- Nome';
                              if($_POST['lastname']=="") $alert_list.= '<br>- Cognome';
                              if($_POST['city_of_birthday']=="") $alert_list.= '<br>- Citta di nascita';
                              if($_POST['date_of_birthday']=="") $alert_list.= '<br>- Data di nascita';
                              if($_POST['nationality']=="") $alert_list.= '<br>- Nazionalità';
                              if($_POST['city_of_residence']=="") $alert_list.= '<br>- Città di residenza';
                              if($_POST['address_of_residence']=="") $alert_list.= '<br>- Indirizzo di residenza';
                              if($_POST['fiscal_code']=="") $alert_list.= '<br>- Codice Fiscale';
                              if($_POST['phone']=="") $alert_list.= '<br>- Telefono';
                              $alert_end = '</div>';
                              if($alert_list==""){
                              $anagrafica = new Anagrafica();
                              if(isset($_POST['modify'])){

                              $insert_anagrafica = $anagrafica ->updateAnagrafica($_GET['id'], $_POST['firstname'], $_POST['lastname'], $_POST['city_of_birthday'], $_POST['date_of_birthday'], $_POST['nationality'], $_POST['phone'], $_POST['city_of_residence'], $_POST['address_of_residence'], $_POST['city_of_domicile'], $_POST['address_of_domicile'], $_POST['fiscal_code']);
                              //echo $insert_anagrafica;
                              if($insert_anagrafica=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> L\'anagrafica è stata modificata con successo.
</div>';

                              }

                              if(isset($_POST['save'])){

                              $insert_anagrafica = $anagrafica ->setAnagrafica($_GET['id'], $_POST['firstname'], $_POST['lastname'], $_POST['city_of_birthday'], $_POST['date_of_birthday'], $_POST['nationality'], $_POST['phone'], $_POST['city_of_residence'], $_POST['address_of_residence'], $_POST['city_of_domicile'], $_POST['address_of_domicile'], $_POST['fiscal_code']);
                              if($insert_anagrafica=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> L\'anagrafica è stata salvata con successo.
</div>';

                              }
                              } else echo $alert.$alert_list.$alert_end;
                          }

                          if($_GET['id']==$_SESSION['user_idd']||$_SESSION['is_admin']==1){

                              $sql_dati= "SELECT * FROM anagrafica a,login l WHERE a.user_id=l.user_idd AND a.user_id= ".$_SESSION['user_idd'];
                              $result_dati = $conn->query($sql_dati);

                              $sql_exp= "SELECT * FROM esperienza WHERE user_id= ".$_SESSION['user_idd'];
                              $result_exp = $conn->query($sql_exp);

                              $sql_form= "SELECT * FROM formazione WHERE user_id= ".$_SESSION['user_idd'];
                              $result_form = $conn->query($sql_form);

                              $sql_cert = "SELECT * FROM certificazione WHERE user_id= ".$_SESSION['user_idd'];
                              $result_cert = $conn->query($sql_cert);

                              $sql_courses = "SELECT * FROM corsi WHERE user_id= ".$_SESSION['user_idd'];
                              $result_courses = $conn->query($sql_courses);

                              $sql_competenze = "SELECT * FROM riepilogo_competenze WHERE user_id= ".$_SESSION['user_idd'];
                              $result_competenze = $conn->query($sql_competenze);
                         include('top_menu_cv.php');
                          ?>
              <div id="dati-personali" class="col-lg-12 ds" class="left" style="overflow-y: scroll;
    height: 100%;">
						<h3>CAMBIO PASSWORD</h3>
						<form action="" method="POST">
						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                                                    <tbody>
                                                         <tr>
                                  <td class="td-intestazione">NUOVA PASSWORD</td>
                                  <td><input style="width:25%;" class="form-control" type="password" name="password" value="" id="pw1"></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">CONFERMA PASSWORD</td>
                                  <td><input style="width:25%;" class="form-control" type="password" name="c_password" value="" id="pw2"></td>
						      </tr>
                                                      <tr>
								  <td colspan="2" style="text-align:left;">

                                                            <input type="button" name="modify_password" class="btn btn-primary btn-xs"
                                                                   onclick="formhash2(this.form, this.form.pw1, this.form.pw2)" value="Modifica Password">
                                                    </tbody>
                                                </table>
                                                </form>
              </div>

<div id="img-cv" class="col-lg-12 ds" class="left" style="overflow-y: scroll;
    height: 100%;">
						<h3>IMMAGINE PROFILO CV</h3>
						<form action="" method="POST" enctype="multipart/form-data">
						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
 <?php
                                  $obj_dati = $result_dati->fetch_object();
?>
                              <tbody>
      <tr>

                                  <td><div class="form-group">

<?php
if (file_exists("assets/img/avatar/" . $_SESSION['user_idd'] . ".png")) {
    echo '<img style="max-height: 200px; max-width: 150px; margin-bottom: 20px;" src="assets/img/avatar/' . $_SESSION['user_idd'] . '.png">';
} else {
    echo '<img style="max-height: 200px; max-width: 150px; margin-bottom: 20px;" src="assets/img/avatar/avatar_empty.jpg">';
}
?>
            <input type="file" name="fileToUpload" id="fileToUpload" required>
          </div>
          <button class="btn btn-xs btn-primary" id="upload-button" type="submit" name="carica_immagine">Upload image</button></td>
      </tr>

</tbody>
</table>
</form>
</div>
			  <!--DATI PERSONALI-->
                          <div id="dati-personali" class="col-lg-12 ds" class="left" style="overflow-y: scroll;
    height: 100%;">
						<h3>DATI PERSONALI</h3>
						<form action="" method="post">
						<table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                              <tbody>
                              <tr>
                                  <td class="td-intestazione">NOME</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="firstname" value="<?php if(isset($obj_dati->nome)) echo $obj_dati->nome;?>"></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">COGNOME</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="lastname" value="<?php if(isset($obj_dati->cognome)) echo $obj_dati->cognome;?>"></td>
						      </tr>

                                                       <tr>
                                  <td class="td-intestazione">LUOGO DI NASCITA</a></td>
                                  <td>
                                      <input style="width:25%;" class="form-control" type="text" name="city_of_birthday" value="<?php if(isset($obj_dati->luogo_nascita)) echo $obj_dati->luogo_nascita;?>">
                                      </td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">DATA DI NASCITA</a></td>
                                  <td>

            <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
   <input class="form-control" id="datetimepicker" name="date_of_birthday" placeholder="DD/MM/YYYY" type="text" value="<?php if(isset($obj_dati->data_nascita)) echo $obj_dati->data_nascita;?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>


        <script type="text/javascript">
           $(function () {
  $("#datepicker").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

        </script>


                                      </td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">NAZIONALITA</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="nationality" value="<?php if(isset($obj_dati->nazionalita)) echo $obj_dati->nazionalita;?>"></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">INDIRIZZO RESIDENZA</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="address_of_residence" value="<?php if(isset($obj_dati->indirizzo_residenza)) echo $obj_dati->indirizzo_residenza;?>"></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">CITTA RESIDENZA</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="city_of_residence" value="<?php if(isset($obj_dati->citta_residenza)) echo $obj_dati->citta_residenza;?>"></td>
						      </tr>

                                                       <tr>
                                  <td class="td-intestazione">INDIRIZZO DOMICILIO</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="address_of_domicile" value="<?php if(isset($obj_dati->indirizzo_domicilio)) echo $obj_dati->indirizzo_domicilio;?>"></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">CITTA DOMICILIO</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="city_of_domicile" value="<?php if(isset($obj_dati->citta_domicilio)) echo $obj_dati->citta_domicilio;?>"></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">CODICE FISCALE</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="fiscal_code" value="<?php if(isset($obj_dati->codice_fiscale)) echo $obj_dati->codice_fiscale;?>"></td>
						      </tr>

                                                      <tr>
                                  <td class="td-intestazione">TELEFONO</a></td>
                                  <td><input style="width:25%;" class="form-control" type="text" name="phone" value="<?php if(isset($obj_dati->phone)) echo $obj_dati->phone;?>"></td>
						      </tr>

                                                       <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">
                                                                      <?php if($obj_dati->nome!=""){?>
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Modifica Dati Personali
                                                                      </button><?php } else {?>
                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Dati Personali
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
    <script type="text/javascript" src="assets/js/forms.js"></script>
    <script type="text/javascript" src="assets/js/sha512.js"></script>

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
