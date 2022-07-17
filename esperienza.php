<?php
session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('class/esperienza.class.php');
require_once('class/skill.class.php');
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

#datepicker_da{width:25%;}
#datepicker_da > span:hover{cursor: pointer;}
#datepicker_a{width:25%;}
#datepicker_a > span:hover{cursor: pointer;}
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

                              $esperienza = new Esperienza();
                              $delesperienza = $esperienza ->delEsperienza($_POST['id_esp']);
                              if($delesperienza=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> L\'esperienza è stata eliminata con successo.
</div>';

                              if($delesperienza=="error") echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
  <strong>Attenzione!</strong> L\'esperienza è stata eliminata.
</div>';
                          }


                          if(isset($_POST['add_skill'])) {


                              $skill = new Skill();
                              $insert_skill= $skill->setSkill($_GET['id'], $_POST['skill'], $_POST['livello_skill'],"");

                          }

                          if(isset($_POST['add_skill_in'])) {


                              $skill = new Skill();
                              $insert_skill= $skill->setSkill($_GET['id'], $_POST['skill'], $_POST['livello_skill'],$_POST['id_esp']);

                          }

                           if(isset($_POST['del_skill'])) {

                              $skill = new Skill();
                              $del_skill= $skill->delSkill($_POST['del_skill']);

                          }

                          if(isset($_POST['del_skill_in'])) {

                              $skill = new Skill();
                              $del_skill= $skill->delSkill($_POST['del_skill_in']);

                          }


                          if(isset($_POST['modify'])) {

                              $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>I seguenti campi sono obbligatori:</strong>';
                              $alert_list="";
                              if($_POST['titolo_esp']=="") $alert_list.= '<br>- Titolo dell\'esperienza';
                              if($_POST['azienda']=="") $alert_list.= '<br>- Nome Azienda';
                              if(isset($_POST['in_corso'])) $_POST['a'] = '00/00/0000';
                              if(isset($_POST['in_corso'])) $_POST['in_corso'] = 'Si';
                              if(!isset($_POST['in_corso'])) $_POST['in_corso'] = 'No';
                              if($_POST['indirizzo_azienda']=="") $alert_list.= '<br>- Indirizzo Azienda';
                              if($_POST['da']==""||$_POST['da']=="DD/MM/YYYY") $alert_list.= '<br>- Data Inizio';
                              //if($_POST['materie_principali']=="") $alert_list.= '<br>- Materie Principali';
                              $alert_end = '</div>';


                              if($alert_list==""){
                              $esperienza = new Esperienza();

                              $insert_esperienza = $esperienza ->updateEsperienza($_POST['id_esp'],$_GET['id'],$_POST['azienda'],$_POST['indirizzo_azienda'],$_POST['citta_azienda'],$_POST['provincia_azienda'],$_POST['mansione'],$_POST['ruolo'],$_POST['da'],$_POST['a'],$_POST['in_corso'],$_POST['materie_principali'],$_POST['area'],$_POST['sub_area'],$_POST['titolo_esp']);
                              //echo $insert_istruzione;
                              if($insert_esperienza=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> L\'esperienza è stata modificata con successo.
</div>'; else echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
<strong>Ben fatto!</strong> L\'esperienza non è stata modificata con successo. Contattare l\'amministratore
</div>';

                              } else echo $alert.$alert_list.$alert_end;
						  } else if(isset($_POST['save'])) {

                              $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>I seguenti campi sono obbligatori:</strong>';
                              $alert_list="";
                              if($_POST['titolo_esp_new']=="") $alert_list.= '<br>- Titolo dell\'esperienza';
                              if($_POST['azienda_new']=="") $alert_list.= '<br>- Nome Azienda';
                              if(isset($_POST['in_corso_new'])) $_POST['a'] = '00/00/0000';
                              if(isset($_POST['in_corso_new'])) $_POST['in_corso_new'] = 'Si';
                              if(!isset($_POST['in_corso_new'])) $_POST['in_corso_new'] = 'No';
                              if($_POST['indirizzo_azienda_new']=="") $alert_list.= '<br>- Indirizzo Azienda';
                              if($_POST['da_new']==""||$_POST['da_new']=="DD/MM/YYYY") $alert_list.= '<br>- Data Inizio';
                              //if($_POST['materie_principali_new']=="") $alert_list.= '<br>- Materie Principali';
                              $alert_end = '</div>';


                              if($alert_list==""){
                              $esperienza = new Esperienza();

                              $insert_esperienza = $esperienza ->setEsperienza($_GET['id'],$_POST['azienda_new'],$_POST['indirizzo_azienda_new'],$_POST['citta_azienda_new'],$_POST['provincia_azienda_new'],$_POST['mansione_new'],$_POST['ruolo_new'],$_POST['da_new'],$_POST['a_new'],$_POST['in_corso_new'],$_POST['materie_principali_new'],$_POST['area_new'],$_POST['sub_area_new'],$_POST['titolo_esp_new']);
                              if($insert_esperienza=="ok") echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
  <strong>Ben fatto!</strong> L\'esperienza è stata salvata con successo.
</div>'; else echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
  <strong>Attenzione!</strong> L\'esperienza non è stata salvata con successo.
</div>';
                              unset($_POST);
                              } else echo $alert.$alert_list.$alert_end;
}

                          if($_GET['id']==$_SESSION['user_idd']||$_SESSION['is_admin']==1){

                              $sql_dati= "SELECT * FROM anagrafica a,login l WHERE a.user_id=l.user_idd AND a.user_id= ".$_SESSION['user_idd'];
                              $result_dati = $conn->query($sql_dati);

                              $sql_exp= "SELECT * FROM esperienza WHERE user_id= ".$_SESSION['user_idd']." ORDER BY str_to_date(da, '%d/%m/%Y') DESC";
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





					  <!--ESPERIENZA-->
			  <div id="istruzione" class="col-lg-12 ds">

                                                <button style="margin-top:10px;margin-bottom: 20px;" onclick="comparsa_data1('exp');" type="button" class="btn btn-success">Aggiungi Nuova</button>
                                                          <form action="" method="post">
                                                    <table id="exp" class="table table-striped table-advance table-hover" <?php if(isset($_POST['add_skill'])||isset($_POST['del_skill'])){ ?> style="margin-top:1%;" <?php } else if(!isset($_POST['save'])) { ?> style="display:none; margin-top:1%;" <?php } else {?> style="margin-top:1%;" <?php } ?>>
                              <tbody>
                              <tr>
                                  <td class="td-intestazione">AREA DI COMPETENZA</td>
                                  <td><select class="form-control" style="width:25%;" name="area">
                                          <?php $sql_area="SELECT * FROM area_competenza";
                                                $result_area = $conn->query($sql_area);
                                                while($obj_area=  $result_area->fetch_object()){
                                                  ?>
                                          <option value="<?php echo $obj_area->nome;?>" <?php if($_POST['area']==$obj_area->nome){?> selected="selected" <?php } ?>><?php echo $obj_area->nome;?></option>
                                          <?php } ?>
                                      </select>
                                  </td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">TITOLO ESPERIENZA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="titolo_esp_new" value="<?php if(isset($_POST['titolo_esp_new'])) echo $_POST['titolo_esp_new'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">NOME DELL'AZIENDA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="azienda_new" value="<?php if(isset($_POST['azienda_new'])) echo $_POST['azienda_new'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">INDIRIZZO DELL'AZIENDA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="indirizzo_azienda_new" value="<?php if(isset($_POST['indirizzo_azienda_new'])) echo $_POST['indirizzo_azienda_new'];?>"></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">CITTA DELL'AZIENDA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="citta_azienda_new" value="<?php if(isset($_POST['citta_azienda_new'])) echo $_POST['citta_azienda_new'];?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">PROVINCIA DELL'AZIENDA</td>
                                  <td><select class="form-control" style="width:25%;" name="provincia_azienda_new">
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AG") { ?> selected="selected"<?php } ?> value="AG">AGRIGENTO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AL") { ?> selected="selected"<?php } ?>value="AL">ALESSANDRIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AN") { ?> selected="selected"<?php } ?>value="AN">ANCONA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AO") { ?> selected="selected"<?php } ?>value="AO">AOSTA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AR") { ?> selected="selected"<?php } ?>value="AR">AREZZO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AP") { ?> selected="selected"<?php } ?>value="AP">ASCOLIPICENO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AT") { ?> selected="selected"<?php } ?>value="AT">ASTI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AV") { ?> selected="selected"<?php } ?>value="AV">AVELLINO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BA") { ?> selected="selected"<?php } ?>value="BA">BARI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BT") { ?> selected="selected"<?php } ?>value="BT">BARLETTA-ANDRIA-TRANI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BL") { ?> selected="selected"<?php } ?>value="BL">BELLUNO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BN") { ?> selected="selected"<?php } ?>value="BN">BENEVENTO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BG") { ?> selected="selected"<?php } ?>value="BG">BERGAMO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BI") { ?> selected="selected"<?php } ?>value="BI">BIELLA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BO") { ?> selected="selected"<?php } ?>value="BO">BOLOGNA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BZ") { ?> selected="selected"<?php } ?>value="BZ">BOLZANO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BS") { ?> selected="selected"<?php } ?>value="BS">BRESCIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="BR") { ?> selected="selected"<?php } ?>value="BR">BRINDISI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CA") { ?> selected="selected"<?php } ?>value="CA">CAGLIARI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CL") { ?> selected="selected"<?php } ?>value="CL">CALTANISSETTA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CB") { ?> selected="selected"<?php } ?>value="CB">CAMPOBASSO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CI") { ?> selected="selected"<?php } ?>value="CI">Carbonia-Iglesias</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CE") { ?> selected="selected"<?php } ?>value="CE">CASERTA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CT") { ?> selected="selected"<?php } ?>value="CT">CATANIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CZ") { ?> selected="selected"<?php } ?>value="CZ">CATANZARO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CH") { ?> selected="selected"<?php } ?>value="CH">CHIETI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CO") { ?> selected="selected"<?php } ?>value="CO">COMO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CS") { ?> selected="selected"<?php } ?>value="CS">COSENZA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CR") { ?> selected="selected"<?php } ?>value="CR">CREMONA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="KR") { ?> selected="selected"<?php } ?>value="KR">CROTONE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="CN") { ?> selected="selected"<?php } ?>value="CN">CUNEO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="EN") { ?> selected="selected"<?php } ?>value="EN">ENNA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="FM") { ?> selected="selected"<?php } ?>value="FM">FERMO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="FE") { ?> selected="selected"<?php } ?>value="FE">FERRARA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="FI") { ?> selected="selected"<?php } ?>value="FI">FIRENZE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="FG") { ?> selected="selected"<?php } ?>value="FG">FOGGIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="FC") { ?> selected="selected"<?php } ?>value="FC">FORLI’-CESENA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="FR") { ?> selected="selected"<?php } ?>value="FR">FROSINONE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="GE") { ?> selected="selected"<?php } ?>value="GE">GENOVA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="GO") { ?> selected="selected"<?php } ?>value="GO">GORIZIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="GR") { ?> selected="selected"<?php } ?>value="GR">GROSSETO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="IM") { ?> selected="selected"<?php } ?>value="IM">IMPERIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="IS") { ?> selected="selected"<?php } ?>value="IS">ISERNIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="SP") { ?> selected="selected"<?php } ?>value="SP">LASPEZIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="AQ") { ?> selected="selected"<?php } ?>value="AQ">L’AQUILA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="LT") { ?> selected="selected"<?php } ?>value="LT">LATINA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="LE") { ?> selected="selected"<?php } ?>value="LE">LECCE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="LC") { ?> selected="selected"<?php } ?>value="LC">LECCO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="LI") { ?> selected="selected"<?php } ?>value="LI">LIVORNO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="LO") { ?> selected="selected"<?php } ?>value="LO">LODI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="LU") { ?> selected="selected"<?php } ?>value="LU">LUCCA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="MC") { ?> selected="selected"<?php } ?>value="MC">MACERATA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="MN") { ?> selected="selected"<?php } ?>value="MN">MANTOVA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="MS") { ?> selected="selected"<?php } ?>value="MS">MASSA-CARRARA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="MT") { ?> selected="selected"<?php } ?>value="MT">MATERA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VS") { ?> selected="selected"<?php } ?>value="VS">MEDIOCAMPIDANO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="ME") { ?> selected="selected"<?php } ?>value="ME">MESSINA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="MI") { ?> selected="selected"<?php } ?>value="MI">MILANO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="MO") { ?> selected="selected"<?php } ?>value="MO">MODENA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="MB") { ?> selected="selected"<?php } ?>value="MB">MONZAEDELLABRIANZA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="NA") { ?> selected="selected"<?php } ?>value="NA">NAPOLI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="NO") { ?> selected="selected"<?php } ?>value="NO">NOVARA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="NU") { ?> selected="selected"<?php } ?>value="NU">NUORO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="OG") { ?> selected="selected"<?php } ?>value="OG">OGLIASTRA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="OT") { ?> selected="selected"<?php } ?>value="OT">OLBIA-TEMPIO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="OR") { ?> selected="selected"<?php } ?>value="OR">ORISTANO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PD") { ?> selected="selected"<?php } ?>value="PD">PADOVA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PA") { ?> selected="selected"<?php } ?>value="PA">PALERMO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PR") { ?> selected="selected"<?php } ?>value="PR">PARMA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PV") { ?> selected="selected"<?php } ?>value="PV">PAVIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PG") { ?> selected="selected"<?php } ?>value="PG">PERUGIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PU") { ?> selected="selected"<?php } ?>value="PU">PESAROEURBINO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PE") { ?> selected="selected"<?php } ?>value="PE">PESCARA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PC") { ?> selected="selected"<?php } ?>value="PC">PIACENZA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PI") { ?> selected="selected"<?php } ?>value="PI">PISA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PT") { ?> selected="selected"<?php } ?>value="PT">PISTOIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PN") { ?> selected="selected"<?php } ?>value="PN">PORDENONE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PZ") { ?> selected="selected"<?php } ?>value="PZ">POTENZA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="PO") { ?> selected="selected"<?php } ?>value="PO">PRATO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RG") { ?> selected="selected"<?php } ?>value="RG">RAGUSA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RA") { ?> selected="selected"<?php } ?>value="RA">RAVENNA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RC") { ?> selected="selected"<?php } ?>value="RC">REGGIODICALABRIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RE") { ?> selected="selected"<?php } ?>value="RE">REGGIONELL’EMILIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RI") { ?> selected="selected"<?php } ?>value="RI">RIETI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RN") { ?> selected="selected"<?php } ?>value="RN">RIMINI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RM") { ?> selected="selected"<?php } ?>value="RM">ROMA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="RO") { ?> selected="selected"<?php } ?>value="RO">ROVIGO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="SA") { ?> selected="selected"<?php } ?>value="SA">SALERNO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="SS") { ?> selected="selected"<?php } ?>value="SS">SASSARI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="SV") { ?> selected="selected"<?php } ?>value="SV">SAVONA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="SI") { ?> selected="selected"<?php } ?>value="SI">SIENA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="SR") { ?> selected="selected"<?php } ?>value="SR">SIRACUSA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="SO") { ?> selected="selected"<?php } ?>value="SO">SONDRIO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TA") { ?> selected="selected"<?php } ?>value="TA">TARANTO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TE") { ?> selected="selected"<?php } ?>value="TE">TERAMO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TR") { ?> selected="selected"<?php } ?>value="TR">TERNI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TO") { ?> selected="selected"<?php } ?>value="TO">TORINO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TP") { ?> selected="selected"<?php } ?>value="TP">TRAPANI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TN") { ?> selected="selected"<?php } ?>value="TN">TRENTO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TV") { ?> selected="selected"<?php } ?>value="TV">TREVISO</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="TS") { ?> selected="selected"<?php } ?>value="TS">TRIESTE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="UD") { ?> selected="selected"<?php } ?>value="UD">UDINE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VA") { ?> selected="selected"<?php } ?>value="VA">VARESE</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VE") { ?> selected="selected"<?php } ?>value="VE">VENEZIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VB") { ?> selected="selected"<?php } ?>value="VB">VERBANO-CUSIO-OSSOLA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VC") { ?> selected="selected"<?php } ?>value="VC">VERCELLI</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VR") { ?> selected="selected"<?php } ?>value="VR">VERONA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VV") { ?> selected="selected"<?php } ?>value="VV">VIBOVALENTIA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VI") { ?> selected="selected"<?php } ?>value="VI">VICENZA</option>
<option <?php if(isset($_POST['provincia_azienda_new'])&&$_POST['provincia_azienda_new']=="VR") { ?> selected="selected"<?php } ?>value="VT">VITERBO</option>
                                      </select>
                                  </td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">MANSIONE</td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="mansione_new" rows="4" cols="20"><?php if(isset($_POST['mansione_new'])) echo $_POST['mansione_new'];?></textarea></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">RUOLO RICOPERTO</td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="ruolo_new" rows="4" cols="20"><?php if(isset($_POST['ruolo_new'])) echo $_POST['ruolo_new'];?></textarea></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">LAVORI ANCORA QUI</td>
                                  <td><input type="checkbox" <?php if(isset($_POST['in_corso_new'])){?> checked="checked" <?php } ?> name="in_corso_new" onclick="comparsa_data1('a_exp')"></td>
                              </tr>


                              <tr >
                                  <td class="td-intestazione">DA</td>
                                  <td><div id="datepicker_da" class="input-group date" data-date-format="mm-dd-yyyy">
   <input class="form-control" id="datetimepicker_da" name="da_new" placeholder="DD/MM/YYYY" type="text" value="<?php if(isset($_POST['da_new'])) echo $_POST['da_new'];?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>
                                  </td>

                              </tr>

                              <tr <?php if(isset($_POST['in_corso_new'])){?> style="display:none;" <?php } ?> id="a_exp">
                                  <td class="td-intestazione">A</td>
                                  <td><div id="datepicker_a" class="input-group date" data-date-format="mm-dd-yyyy">
   <input class="form-control" id="datetimepicker_a" name="a_new" placeholder="DD/MM/YYYY" type="text" value="<?php if(isset($_POST['a_new'])) echo $_POST['a_new'];?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>
                                  </td>

                              </tr>
                             <script type="text/javascript">
           $(function () {
  $("#datepicker_da").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

$(function () {
  $("#datepicker_a").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

        </script>
                               <!--<tr>
                                  <td class="td-intestazione"></a></td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="materie_principali_new" rows="4" cols="20"><?php if(isset($_POST['materie_principali_new'])) echo $_POST['materie_principali_new'];?></textarea></td>
                              </tr>-->

                                <tr>
                                  <td class="td-intestazione">SKILL</td>

                              <td><?php include ('autocomplete.php'); ?>
<select name="livello_skill" style="margin-top:8px;">
        <option value="Principiante">Principiante</option>
        <option value="Intermedio">Intermedio</option>
        <option value="Avanzato">Avanzato</option>
</select>

<input type="submit" value="Aggiungi Skill" name="add_skill" class="btn btn-success btn-xs">
<input type="hidden" value="open_exp" name="open_exp" >

                              </td>
                                </tr>

         <?php

$sql_skill = "SELECT
              *
              FROM skill WHERE id_esp=0 AND skill_user_id=" . $_GET['id'];


    $result_skill = $conn->query($sql_skill);
    if (!$result_skill) {
        die('Errore caricamento dati : ' . mysql_error());
    } else {
        $c1=0;
        $num = $result_skill->num_rows;
                                          if($num>0){

            ?>
                                <tr>
                                 <td class="td-intestazione">SKILL AGGIUNTE</td>
                                 <td>
                                <?php
            while($obj_skill=  $result_skill->fetch_object()){
            $c1++;

?>
 <div class="col-md-3">
              <input  type="button" class="ric" value="<?php echo $obj_skill->skill . " (".$obj_skill->livello_skill.")";?>  ">
	 <button type="submit" class="btn btn-danger btn-xs" name="del_skill" value="<?php echo $obj_skill->skill_id;?>"><i class="fa fa-remove"></i></button>
       </div>

        <?php }
        }
    }?>
                                     </div></td>
                                                    </tr>
                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">

                                                                      <button type="submit" name="save" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Salva Esperienza
                                                                      </button>
                                 </td>
								 </tr>


                              </tbody>
                          </table>
                                                </form></div>

                                  <?php
                                    $num = $result_exp->num_rows;
                                          if($num>0){
                                  $count=0;

                                  while($obj_exp= $result_exp->fetch_object()){
                                      $count++;

                                  ?>
                                          <div id="esperienza" class="col-lg-12" style="margin-top:10px;">
             <div class="panel panel-list" >
                 <div class="panel-heading" onclick="comparsa_data1('esp_<?php echo $obj_exp->id_esp;?>')"><h4 style=" margin:0;"><span style="margin-right:10px;" class="glyphicon glyphicon-chevron-down"></span><?php echo $obj_exp->titolo_esp?></h4></div>
      <div class="panel-body" id="esp_<?php echo $obj_exp->id_esp;?>" <?php if((isset($_POST['add_skill_in'])||isset($_POST['del_skill_in']))&&$_POST['id_esp']==$obj_exp->id_esp){?> style="margin-top:1%;" <?php } else {?> style="display:none; margin-top:1%;" <?php } ?>>

                                                          <form action="" method="post">
                                                              <table class="table table-striped table-advance table-hover" >
                              <tbody>
                              <tr>
                                  <td class="td-intestazione">AREA DI COMPETENZA</td>
                                  <td><select class="form-control" style="width:25%;" name="area">
                                          <?php $sql_area="SELECT * FROM area_competenza";
                                                $result_area = $conn->query($sql_area);
                                                while($obj_area=  $result_area->fetch_object()){
                                                  ?>
                                          <option value="<?php echo $obj_area->nome;?>" <?php if($obj_exp->area==$obj_area->nome){?> selected="selected" <?php } ?>><?php echo $obj_area->nome;?></option>
                                          <?php } ?>
                                      </select>
                                  </td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">TITOLO ESPERIENZA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="titolo_esp" value="<?php if(isset($_POST['add_skill_in'])){ echo $_POST['titolo_esp']; } else if(isset($obj_exp->titolo_esp)) echo $obj_exp->titolo_esp;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">NOME DELL'AZIENDA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="azienda" value="<?php if(isset($_POST['add_skill_in'])){ echo $_POST['azienda']; } else if(isset($obj_exp->azienda)) echo $obj_exp->azienda;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">INDIRIZZO DELL'AZIENDA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="indirizzo_azienda" value="<?php if(isset($_POST['add_skill_in'])){ echo $_POST['indirizzo_azienda']; } if(isset($obj_exp->indirizzo)) echo $obj_exp->indirizzo;?>"></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">CITTA DELL'AZIENDA</td>
                                  <td><input type="text" class="form-control" style="width:25%;" name="citta_azienda" value="<?php if(isset($_POST['add_skill_in'])){ echo $_POST['citta_azienda']; } else if(isset($obj_exp->citta_azienda)) echo $obj_exp->citta_azienda;?>"></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">PROVINCIA DELL'AZIENDA</td>
                                  <td><select class="form-control" style="width:25%;" name="provincia_azienda">
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AG") { ?> selected="selected"<?php } ?> value="AG">AGRIGENTO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AL") { ?> selected="selected"<?php } ?>value="AL">ALESSANDRIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AN") { ?> selected="selected"<?php } ?>value="AN">ANCONA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AO") { ?> selected="selected"<?php } ?>value="AO">AOSTA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AR") { ?> selected="selected"<?php } ?>value="AR">AREZZO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AP") { ?> selected="selected"<?php } ?>value="AP">ASCOLIPICENO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AT") { ?> selected="selected"<?php } ?>value="AT">ASTI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AV") { ?> selected="selected"<?php } ?>value="AV">AVELLINO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BA") { ?> selected="selected"<?php } ?>value="BA">BARI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BT") { ?> selected="selected"<?php } ?>value="BT">BARLETTA-ANDRIA-TRANI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BL") { ?> selected="selected"<?php } ?>value="BL">BELLUNO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BN") { ?> selected="selected"<?php } ?>value="BN">BENEVENTO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BG") { ?> selected="selected"<?php } ?>value="BG">BERGAMO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BI") { ?> selected="selected"<?php } ?>value="BI">BIELLA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BO") { ?> selected="selected"<?php } ?>value="BO">BOLOGNA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BZ") { ?> selected="selected"<?php } ?>value="BZ">BOLZANO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BS") { ?> selected="selected"<?php } ?>value="BS">BRESCIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="BR") { ?> selected="selected"<?php } ?>value="BR">BRINDISI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CA") { ?> selected="selected"<?php } ?>value="CA">CAGLIARI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CL") { ?> selected="selected"<?php } ?>value="CL">CALTANISSETTA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CB") { ?> selected="selected"<?php } ?>value="CB">CAMPOBASSO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CI") { ?> selected="selected"<?php } ?>value="CI">Carbonia-Iglesias</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CE") { ?> selected="selected"<?php } ?>value="CE">CASERTA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CT") { ?> selected="selected"<?php } ?>value="CT">CATANIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CZ") { ?> selected="selected"<?php } ?>value="CZ">CATANZARO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CH") { ?> selected="selected"<?php } ?>value="CH">CHIETI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CO") { ?> selected="selected"<?php } ?>value="CO">COMO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CS") { ?> selected="selected"<?php } ?>value="CS">COSENZA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CR") { ?> selected="selected"<?php } ?>value="CR">CREMONA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="KR") { ?> selected="selected"<?php } ?>value="KR">CROTONE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="CN") { ?> selected="selected"<?php } ?>value="CN">CUNEO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="EN") { ?> selected="selected"<?php } ?>value="EN">ENNA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="FM") { ?> selected="selected"<?php } ?>value="FM">FERMO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="FE") { ?> selected="selected"<?php } ?>value="FE">FERRARA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="FI") { ?> selected="selected"<?php } ?>value="FI">FIRENZE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="FG") { ?> selected="selected"<?php } ?>value="FG">FOGGIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="FC") { ?> selected="selected"<?php } ?>value="FC">FORLI’-CESENA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="FR") { ?> selected="selected"<?php } ?>value="FR">FROSINONE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="GE") { ?> selected="selected"<?php } ?>value="GE">GENOVA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="GO") { ?> selected="selected"<?php } ?>value="GO">GORIZIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="GR") { ?> selected="selected"<?php } ?>value="GR">GROSSETO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="IM") { ?> selected="selected"<?php } ?>value="IM">IMPERIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="IS") { ?> selected="selected"<?php } ?>value="IS">ISERNIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="SP") { ?> selected="selected"<?php } ?>value="SP">LASPEZIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="AQ") { ?> selected="selected"<?php } ?>value="AQ">L’AQUILA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="LT") { ?> selected="selected"<?php } ?>value="LT">LATINA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="LE") { ?> selected="selected"<?php } ?>value="LE">LECCE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="LC") { ?> selected="selected"<?php } ?>value="LC">LECCO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="LI") { ?> selected="selected"<?php } ?>value="LI">LIVORNO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="LO") { ?> selected="selected"<?php } ?>value="LO">LODI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="LU") { ?> selected="selected"<?php } ?>value="LU">LUCCA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="MC") { ?> selected="selected"<?php } ?>value="MC">MACERATA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="MN") { ?> selected="selected"<?php } ?>value="MN">MANTOVA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="MS") { ?> selected="selected"<?php } ?>value="MS">MASSA-CARRARA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="MT") { ?> selected="selected"<?php } ?>value="MT">MATERA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VS") { ?> selected="selected"<?php } ?>value="VS">MEDIOCAMPIDANO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="ME") { ?> selected="selected"<?php } ?>value="ME">MESSINA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="MI") { ?> selected="selected"<?php } ?>value="MI">MILANO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="MO") { ?> selected="selected"<?php } ?>value="MO">MODENA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="MB") { ?> selected="selected"<?php } ?>value="MB">MONZAEDELLABRIANZA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="NA") { ?> selected="selected"<?php } ?>value="NA">NAPOLI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="NO") { ?> selected="selected"<?php } ?>value="NO">NOVARA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="NU") { ?> selected="selected"<?php } ?>value="NU">NUORO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="OG") { ?> selected="selected"<?php } ?>value="OG">OGLIASTRA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="OT") { ?> selected="selected"<?php } ?>value="OT">OLBIA-TEMPIO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="OR") { ?> selected="selected"<?php } ?>value="OR">ORISTANO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PD") { ?> selected="selected"<?php } ?>value="PD">PADOVA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PA") { ?> selected="selected"<?php } ?>value="PA">PALERMO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PR") { ?> selected="selected"<?php } ?>value="PR">PARMA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PV") { ?> selected="selected"<?php } ?>value="PV">PAVIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PG") { ?> selected="selected"<?php } ?>value="PG">PERUGIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PU") { ?> selected="selected"<?php } ?>value="PU">PESAROEURBINO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PE") { ?> selected="selected"<?php } ?>value="PE">PESCARA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PC") { ?> selected="selected"<?php } ?>value="PC">PIACENZA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PI") { ?> selected="selected"<?php } ?>value="PI">PISA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PT") { ?> selected="selected"<?php } ?>value="PT">PISTOIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PN") { ?> selected="selected"<?php } ?>value="PN">PORDENONE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PZ") { ?> selected="selected"<?php } ?>value="PZ">POTENZA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="PO") { ?> selected="selected"<?php } ?>value="PO">PRATO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RG") { ?> selected="selected"<?php } ?>value="RG">RAGUSA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RA") { ?> selected="selected"<?php } ?>value="RA">RAVENNA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RC") { ?> selected="selected"<?php } ?>value="RC">REGGIODICALABRIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RE") { ?> selected="selected"<?php } ?>value="RE">REGGIONELL’EMILIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RI") { ?> selected="selected"<?php } ?>value="RI">RIETI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RN") { ?> selected="selected"<?php } ?>value="RN">RIMINI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RM") { ?> selected="selected"<?php } ?>value="RM">ROMA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="RO") { ?> selected="selected"<?php } ?>value="RO">ROVIGO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="SA") { ?> selected="selected"<?php } ?>value="SA">SALERNO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="SS") { ?> selected="selected"<?php } ?>value="SS">SASSARI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="SV") { ?> selected="selected"<?php } ?>value="SV">SAVONA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="SI") { ?> selected="selected"<?php } ?>value="SI">SIENA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="SR") { ?> selected="selected"<?php } ?>value="SR">SIRACUSA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="SO") { ?> selected="selected"<?php } ?>value="SO">SONDRIO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TA") { ?> selected="selected"<?php } ?>value="TA">TARANTO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TE") { ?> selected="selected"<?php } ?>value="TE">TERAMO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TR") { ?> selected="selected"<?php } ?>value="TR">TERNI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TO") { ?> selected="selected"<?php } ?>value="TO">TORINO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TP") { ?> selected="selected"<?php } ?>value="TP">TRAPANI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TN") { ?> selected="selected"<?php } ?>value="TN">TRENTO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TV") { ?> selected="selected"<?php } ?>value="TV">TREVISO</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="TS") { ?> selected="selected"<?php } ?>value="TS">TRIESTE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="UD") { ?> selected="selected"<?php } ?>value="UD">UDINE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VA") { ?> selected="selected"<?php } ?>value="VA">VARESE</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VE") { ?> selected="selected"<?php } ?>value="VE">VENEZIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VB") { ?> selected="selected"<?php } ?>value="VB">VERBANO-CUSIO-OSSOLA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VC") { ?> selected="selected"<?php } ?>value="VC">VERCELLI</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VR") { ?> selected="selected"<?php } ?>value="VR">VERONA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VV") { ?> selected="selected"<?php } ?>value="VV">VIBOVALENTIA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VI") { ?> selected="selected"<?php } ?>value="VI">VICENZA</option>
<option <?php if(isset($obj_exp->provincia_azienda)&&$obj_exp->provincia_azienda=="VR") { ?> selected="selected"<?php } ?>value="VT">VITERBO</option>
                                      </select>
                                  </td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">MANSIONE</td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="mansione" rows="4" cols="20"><?php if(isset($_POST['add_skill_in'])){ echo $_POST['mansione']; } else if(isset($obj_exp->mansione)) echo $obj_exp->mansione;?></textarea></td>
                              </tr>

                               <tr>
                                  <td class="td-intestazione">RUOLO RICOPERTO</td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="ruolo" rows="4" cols="20"><?php if(isset($_POST['add_skill_in'])){ echo $_POST['ruolo']; } else if(isset($obj_exp->ruolo)) echo $obj_exp->ruolo;?></textarea></td>
                              </tr>

                              <tr>
                                  <td class="td-intestazione">LAVORI ANCORA QUI</td>
                                  <td><input type="checkbox" <?php if(isset($obj_exp->attuale)&&$obj_exp->attuale=="Si"){?> checked="checked" <?php } ?> name="in_corso" onclick="comparsa_data1('a_exp_<?php echo $obj_exp->id_esp;?>')"></td>
                              </tr>


                              <tr >
                                  <td class="td-intestazione">DA</td>
                                  <td><div id="datepicker_da" class="input-group date" data-date-format="mm-dd-yyyy">
   <input class="form-control" id="datetimepicker_da<?php echo $obj_exp->id_esp;?>" name="da" placeholder="DD/MM/YYYY" type="text" value="<?php if(isset($obj_exp->da)) echo $obj_exp->da;?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>
                                  </td>

                              </tr>

                              <tr <?php if(isset($obj_exp->attuale)&&$obj_exp->attuale=="Si"){?> style="display:none;" <?php } ?> id="a_exp_<?php echo $obj_exp->id_esp;?>">
                                  <td class="td-intestazione">A</td>
                                  <td><div id="datepicker_a" class="input-group date" data-date-format="mm-dd-yyyy">
   <input class="form-control" id="datetimepicker_a<?php echo $obj_exp->id_esp;?>" name="a" placeholder="DD/MM/YYYY" type="text" <?php if($obj_exp->a=="00/00/0000") $obj_exp->a=date('d/m/Y');?> value="<?php if(isset($obj_exp->a)) echo $obj_exp->a;?>"/>
    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
</div>
                                  </td>

                              </tr>
                             <script type="text/javascript">
           $(function () {
  $("#datepicker_da*").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

$(function () {
  $("#datepicker_a*").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
  });
});

        </script>
                               <!--<tr>
                                  <td class="td-intestazione">NOTE</a></td>
                                  <td><textarea class="form-control" style="resize:none; width: 40%;" name="materie_principali" rows="4" cols="20"><?php if(isset($obj_exp->note)) echo $obj_exp->note;?></textarea></td>
                              </tr>-->

                                <tr>
                                  <td class="td-intestazione">SKILL</td>

                              <td><?php include ('autocomplete.php'); ?>
<select name="livello_skill" style="margin-top:8px;">
        <option value="Principiante">Principiante</option>
        <option value="Intermedio">Intermedio</option>
        <option value="Avanzato">Avanzato</option>
</select>

<input type="submit" value="Aggiungi Skill" name="add_skill_in" class="btn btn-success btn-xs">
<input type="hidden" value="open_exp_<?php echo $obj_exp->id_esp;?>" name="open_exp_up" >
<input type="hidden" value="<?php echo $obj_exp->id_esp;?>" name="id_esp" >

                              </td>
                                </tr>

         <?php

$sql_skill = "SELECT
              *
              FROM skill WHERE id_esp = '".$obj_exp->id_esp."' AND skill_user_id=" . $_GET['id'];


    $result_skill = $conn->query($sql_skill);
    if (!$result_skill) {
        die('Errore caricamento dati : ' . mysql_error());
    } else {
        $c1=0;
        $num = $result_skill->num_rows;
                                          if($num>0){

            ?>
                                <tr>
                                 <td class="td-intestazione">SKILL AGGIUNTE</td>
                                 <td>
                                <?php
            while($obj_skill=  $result_skill->fetch_object()){
            $c1++;

?>
 <div class="col-md-3">
              <input  type="button" class="ric" value="<?php echo $obj_skill->skill . " (".$obj_skill->livello_skill.")";?>  ">
	 <button type="submit" class="btn btn-danger btn-xs" name="del_skill_in" value="<?php echo $obj_skill->skill_id;?>"><i class="fa fa-remove"></i></button>
       </div>

        <?php }
        }
    }?>
                                     </div></td>
                                                    </tr>

                              <!-- Pulsante Modifica -->
								  <tr>
								  <td colspan="2" style="text-align:left;">

                                                                      <input type="hidden" name="id_esp" value="<?php echo $obj_exp->id_esp;?>">
                                                                      <button type="submit" name="modify" class="btn btn-primary btn-xs">
							     <i class="fa fa-pencil"></i> Aggiorna Esperienza
                                                                      </button>

                                                                      <button type="submit" name="delete" class="btn btn-danger btn-xs">
							     <i class="fa fa-pencil"></i> Elimina Esperienza
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
