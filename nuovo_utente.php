<?php
require_once('./assets/php/functions.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);


if(isset($_REQUEST['add_commessa'])){

//echo $_SESSION['user_idd']."-".$_REQUEST['commessa']."-".$_REQUEST['commessa_ruolo'];
$commessa = new Commessa();
$add_commessa = $commessa->addTempCommessa($_REQUEST['commessa'],$_REQUEST['commessa_ruolo'],$_SESSION['user_idd']);
}

if(isset($_REQUEST['del_commessa'])){

$commessa = new Commessa();
$del_commessa = $commessa->delCommessaTemp($_REQUEST['id_commessa_temp']);

}


/*if(isset($_POST['edit-dipendente'])) {
    consoleLog($message);
    $user = new User();
    $editUser = $user->editEmployee($_POST['userId'], $_POST['userName'], $_POST['userSurname'],
            $_POST['userBirthDate'], $_POST['userCF'], $_POST['userProfile'], $_POST['userEmail'], $_POST['userLuogo'], $_POST['userResidenza'],$_POST['userIndirizzoResidenza'],$_POST['userDomicilio'],$_POST['userIndirizzoDomicilio'],$_POST['userOreGiorno'],$_POST['userQualifica'], $_POST['userMansione'],$_POST['userGiorniSettimana'],$_POST['userScadVisitaMedica'],$_POST['userScadContratto'],$_POST['userScadDistacco'], $_POST['userDataAssunzione'], $_POST['userDU'],$_POST['userEmailPersonale'],$_POST['userScadCF'],$_POST['userDoc'],$_POST['userScadDoc'],$_POST['userLivello'],$_POST['userCorso81']);

    if($editUser) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Utente modificato con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Modifica utente non completata.
            </div>';
    }

}

if(isset($_REQUEST['del-dipendente'])){

$user = new User();
$del_user = $user->delUser($_REQUEST['del-dipendente']);
echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                            <strong>Ben fatto!</strong> Utente eliminato con successo.
                        </div>';
}*/


if(isset($_REQUEST['add_user'])){


 $alert='<div style="margin-top:10px;" class="alert alert-danger" role="alert"><strong>Risolvere i seguenti errori:<br></strong>';
            $alert_list="";

            if(isset($_REQUEST['firstname']) && $_REQUEST['firstname'] != '') {
                $_REQUEST['firstname'] = $_REQUEST['firstname'];
            } else {
                $alert_list .= "<br>- Inserire il nome!</p>";
            }
              if(isset($_REQUEST['lastname']) && $_REQUEST['lastname'] != '') {
                $_REQUEST['lastname'] = $_REQUEST['lastname'];
            } else {
                 $alert_list .= "<br>- Inserire il cognome!</p>";
            }

if(isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
                $_REQUEST['email']= $_REQUEST['email'];
            } else {
                 $alert_list .= "<br>- Inserire la mail!</p>";
            }

            $alert_end = '</div>';

if($alert_list==""){


$user = new User();
$id_user = $user->createUser($_REQUEST['email'],$_REQUEST['profile']);


//$moduli = new Modulo();
//$modul = $user->createUserModule($id_user,$_REQUEST['moduli']);

$anagrafica = new Anagrafica();
$insert_anagrafica = $anagrafica->setAnagraficaAdmin($id_user,$_REQUEST['firstname'],$_REQUEST['lastname'],$_REQUEST['date_of_birthday'],$_REQUEST['codice_fiscale'],$_REQUEST['luogo'],$_REQUEST['nazionalita'],$_REQUEST['telefono'],$_REQUEST['citta_residenza'],$_REQUEST['indirizzo_residenza'],$_REQUEST['domicilio'],$_REQUEST['indirizzo_domicilio']);


$info_hr = new InfoHR();
if(isset($_REQUEST['tel'])) $_REQUEST['tel'] = 1;
else {$_REQUEST['tel']=0;$_REQUEST['tel_text']="";}
if(isset($_REQUEST['pc'])) $_REQUEST['pc'] = 1;
else {$_REQUEST['pc']=0;$_REQUEST['pc_text']="";}
if(isset($_REQUEST['varie'])) $_REQUEST['varie'] = 1;
else {$_REQUEST['varie']=0;$_REQUEST['varie_text']="";}
if(isset($_REQUEST['car'])) $_REQUEST['car'] = 1;
else {$_REQUEST['car']=0;$_REQUEST['car_text']="";}
if(isset($_REQUEST['104'])) $_REQUEST['104'] = 1;
else {$_REQUEST['104']=0;}
if(isset($_REQUEST['cat_protetta'])) $_REQUEST['cat_protetta'] = 1;
else {$_REQUEST['cat_protetta']=0;}
if(isset($_REQUEST['sub'])) $_REQUEST['sub'] = 1;
else {$_REQUEST['sub']=0;}

$insert_anagrafica = $info_hr->saveEmployeeInfoHR($id_user,$_REQUEST['ore_giorno'],$_REQUEST['qualifica'],$_REQUEST['mansione'],$_REQUEST['giorni_settimana'],$_REQUEST['scad_visita_medica'],$_REQUEST['scad_contratto'],$_REQUEST['sub'],$_REQUEST['scad_distacco'],$_REQUEST['data_assunzione'],$_REQUEST['cat_protetta'],$_REQUEST['du'],$_REQUEST['email_personale'],$_REQUEST['scad_cf'],$_REQUEST['doc'],$_REQUEST['scad_doc'],$_REQUEST['livello'],$_REQUEST['corso_81'],$_REQUEST['104'],$_REQUEST['articolo'],$_REQUEST['percentuale']);

$insert_anagrafica = $info_hr->addBeniHR($id_user,$_REQUEST['tel'],$_REQUEST['car'],$_REQUEST['pc'],$_REQUEST['varie'],$_REQUEST['tel_text'],$_REQUEST['car_text'],$_REQUEST['pc_text'],$_REQUEST['varie_text']);


$group = new Gruppo();
$insert_group = $group->createUserGruppi($id_user,$_REQUEST['gruppi']);

$moduli = new Modulo();
foreach($_REQUEST['moduli'] as $mod)
{
  $explode = explode("-", $mod);
  if($explode[1]==1) $insert_module = $moduli->createUserModule($id_user,$explode[0]);
}

$commessa=new Commessa();
$insert_commessa= $commessa->insertCommessaFromTemp($id_user,$_SESSION['user_idd']);

$alert_allegato="";
$target_dir = "files/doc/".$id_user."/";
$target_dir2 = "files/cf/".$id_user."/";
//echo $target_dir;
function error_handler($errno, $errstr) {
            global $last_error;
            $last_error = $errstr;
        }

        set_error_handler('error_handler');
        if (!mkdir($target_dir, 0777,true))
            echo "MKDIR failed, reason: $last_error\n";
            if (!mkdir($target_dir2, 0777,true))
                echo "MKDIR failed, reason: $last_error\n";
        restore_error_handler();

echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">'.$_FILES["doc_file"]["name"].'</div>';
$target_file = $target_dir . $_FILES["doc_file"]["name"];
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["doc_file"]["tmp_name"]);

if ($_FILES["doc_file"]["size"] > 10000000) {
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
    move_uploaded_file($_FILES["doc_file"]["tmp_name"], $target_file);
    $alert_allegato .= "<div class='panel panel-success'><div class='panel-heading'>File caricato con successo</div></div>";
}

echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">'.$_FILES["cf_file"]["name"].'</div>';
$target_file = $target_dir2 . $_FILES["cf_file"]["name"];
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$check = getimagesize($_FILES["cf_file"]["tmp_name"]);

if ($_FILES["cf_file"]["size"] > 10000000) {
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
    move_uploaded_file($_FILES["cf_file"]["tmp_name"], $target_file);
    $alert_allegato .= "<div class='panel panel-success'><div class='panel-heading'>File caricato con successo</div></div>";
}




 echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                            <strong>Ben fatto!</strong> Utente creato con successo.
                        </div>';

} else {

                echo $alert.$alert_list.$alert_end;

            }



}

?>
<!--CONTENUTO PRINCIPALE-->
	  <section class="">
	  <div class="panel panel-primary" style="margin-bottom:5%;">
            <div class="calendar-heading">
			<div style="padding-left:1%;padding-right:2%;">
			<span class="legenda-title">Nuovo Dipendente</span>
			</div>
			</div>

 <div class="panel-body" id="body_panel">
      <table class="table table-striped table-advance table-hover" style="margin-top:1%;">
         <tbody>
           <?php

           $sql="SELECT * FROM commesse_temp as u INNER JOIN commesse as c ON u.id_commessa = c.id_commessa  WHERE u.id_session=".$_SESSION['user_idd'];
           $result = $conn->query($sql);

           if($result->num_rows>0){
           ?>
           <tr id="first">
                          <td class="td-intestazione">Commesse:</td>
                          <td>
           <?php while($obj_com= $result->fetch_object()){ ?>
           <form action="" method="post">
            <?php echo $obj_com->commessa; ?>
           <input type="submit" class="btn btn-danger btn-sm" name="del_commessa" value="Elimina">
           <input type="hidden" name="id_commessa_temp" value="<?php echo $obj_com->id_temp;?>">
           <br>
           </form>
           <?php } ?>
                          </td>
                  </tr>
           <?php } ?>
         </tbody>
       </table>
        <form action="" method="post">
       <table class="table table-striped table-advance table-hover" style="margin-top:1%;">
          <tbody>
           <tr id="first">
                          <td class="td-intestazione">COMMESSE</td>
                          <td colspan="2">

                             <select id="commessa" class="form-control" name="commessa" style="width:25%;margin-top:1%;">
                                 <option>Seleziona una commessa...</option>
           <?php
               $commessa = new Commessa();
               $allJobs = $commessa->getAll();

               foreach($allJobs as $job) {
                   echo '<option value="' . $job->id_commessa . '">' .
                        $job->nome_commessa . '</option>';
               }
           ?>
                         </select>
           <select id="commessa_ruolo" class="form-control" name="commessa_ruolo" style="width:25%;margin-top:1%;">
           <option value="4">Appartiene</option>
           <option value="2">Responsabile</option>
           </select>
           <input type="submit" class="btn btn-success btn-sm" name="add_commessa" value="Aggiungi">

                          </td>
                  </tr>
                </tbody>
              </table>
              </form>
                  <form action="" method="post" enctype="multipart/form-data" novalidate>
                    <table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                       <tbody>
            <tr id="first">
               <td class="td-intestazione">NOME*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="firstname" name="firstname" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">COGNOME*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="lastname" name="lastname" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">DATA DI NASCITA</td>
               <td>
                  <div style="width:50%;" id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                     <input class="form-control" id="datetimepicker" name="date_of_birthday" placeholder="gg/mm/aaaa" type="text" value="">
                     <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                  </div>

				 <!-- QUESTO SCRIPT DA PROBLEMI SUL RESPONSIVE

				 <script type="text/javascript">
                     $(function () {
                     $("#datepicker").datepicker({
                     autoclose: true,
                     todayHighlight: true,
                     format: 'dd/mm/yyyy'
                     });
                     });
                  </script> -->

               </td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">LUOGO DI NASCITA*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="luogo" name="luogo" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">NAZIONALITA*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="nazionalita" name="nazionalita" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">TELEFONO*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="telefono" name="telefono" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">CITTA' DI RESIDENZA*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="citta_residenza" name="citta_residenza" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">INDIRIZZO DI RESIDENZA*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="indirizzo_residenza" name="indirizzo_residenza" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">CITTA' DI DOMICILIO*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="domicilio" name="domicilio" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">INDIRIZZO DI DOMICILIO*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="indirizzo_domicilio" name="indirizzo_domicilio" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">EMAIL*</td>
               <td><input style="width:50%;" class="form-control" type="text" id="email" name="email" value="" required></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">CODICE FISCALE</td>
               <td><input style="width:50%;" class="form-control" type="text" id="codice_fiscale" name="codice_fiscale" value=""></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">ALLEGA CF</td>
               <td><input style="width:50%;" class="form-control" type="file" id="cf_file" name="cf_file"></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">DOCUMENTO</td>
               <td><input style="width:50%;" class="form-control" type="text" id="doc" name="doc" value=""></td>
            </tr>
            <tr id="first">
               <td class="td-intestazione">ALLEGA DOC</td>
               <td><input style="width:50%;" class="form-control" type="file" id="doc_file" name="doc_file"></td>
            </tr>

 <tr id="first">
               <td class="td-intestazione">PROFILO</td>
               <td><select name="profile" style="width:50%;margin-top:1%;" class="form-control">
                   <option value="0">Utente</option>
                   <option value="1">Amministratore</option>
                   <option value="2">Responsabile</option>
              </select>  </td>
            </tr>

            <tr id="first">
               <td class="td-intestazione">GRUPPI E PROFILI</td>
<?php
$num=0;
$sql="SELECT * FROM gruppi";
$result= $conn->query($sql);
 while($obj_gruppi= $result->fetch_object()){
?>
<?php if($num>0) { ?><tr id="first"> <td class="td-intestazione"></td><?php } $num++;?>

<td>
			   <strong><?php echo $obj_gruppi->gruppo;?></strong>
                   <select name="gruppi[]" style="width:50%;margin-top:1%;" class="form-control">
                   <option value="<?php echo $obj_gruppi->id_gruppo;?>-0">Non Appartiene</option>
                   <option value="<?php echo $obj_gruppi->id_gruppo;?>-1">Admin</option>
                   <option value="<?php echo $obj_gruppi->id_gruppo;?>-2">Responsabile</option>
                   <option value="<?php echo $obj_gruppi->id_gruppo;?>-3">Editore</option>
                   <option value="<?php echo $obj_gruppi->id_gruppo;?>-4">Utente</option>
		               <option value="<?php echo $obj_gruppi->id_gruppo;?>-5">HR</option>
              </select>
			   </td>
            </tr>
<?php } ?>


<!-- INIZIO SECONDA PARTE -->
<tr id="second" style="display:none;">
   <td class="td-intestazione">DATA DI ASSUNZIONE</td>
   <td>
      <div style="width:50%;" id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
         <input class="form-control" id="datetimepicker1" name="data_assunzione" placeholder="gg/mm/aaaa" type="text" value="">
         <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
      </div>
   </td>
</tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">ORE GIORNO</td>
      <td><input style="width:50%;" class="form-control" type="text" id="ore_giorno" name="ore_giorno" value="" ></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">GIORNI SETTIMANA</td>
      <td><input style="width:50%;" class="form-control" type="text" id="giorni_settimana" name="giorni_settimana" value="" ></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">MANSIONE</td>
      <td><input style="width:50%;" class="form-control" type="text" id="mansione" name="mansione" value=""></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">QUALIFICA</td>
      <td><input style="width:50%;" class="form-control" type="text" id="qualifica" name="qualifica" value=""></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">EMAIL PERSONALE</td>
      <td><input style="width:50%;" class="form-control" type="text" id="email_personale" name="email_personale" value=""></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">LEGGE 104</td>
      <td><input style="width:50%;" class="form-control" type="checkbox" id="104" name="104" value=""></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">CATEGORIA PROTETTA</td>
      <td><input style="width:50%;" class="form-control" type="checkbox" id="cat_protetta" name="cat_protetta" value=""></td>
   </tr>
   <tr id="second" class="art" style="display:none;">
      <td class="td-intestazione">ARTICOLO</td>
      <td><select name="articolo" id="articolo"><option value=""></option><option value="1">Art. 1</option><option value="18">Art. 18</option></select></td>
   </tr>
   <tr id="second" class="perc" style="display:none;">
      <td class="td-intestazione">PERCENTUALE</td>
      <td><input style="width:50%;" class="form-control" type="text" id="percentuale" name="percentuale" value=""></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">D/U</td>
      <td><input style="width:50%;" class="form-control" type="text" id="du" name="du" value=""></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">LIVELLO</td>
      <td><input style="width:50%;" class="form-control" type="text" id="livello" name="livello" value=""></td>
   </tr>
   <tr id="second" style="display:none;">
      <td class="td-intestazione">CORSO 81</td>
      <td><input style="width:50%;" class="form-control" type="text" id="corso_81" name="corso_81" value=""></td>
   </tr>
<!-- FINE SECONDA PARTE -->

<!-- INIZIO TERZA PARTE -->
<tr id="third" style="display:none;">
   <td class="td-intestazione">SCADENZA VISITA MEDICA</td>
   <td>
      <div style="width:50%;" id="datepicker3" class="input-group date" data-date-format="mm-dd-yyyy">
         <input class="form-control" id="datetimepicker3" name="scad_visita_medica" placeholder="gg/mm/aaaa" type="text" value="">
         <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
      </div>
   </td>
</tr>
<tr id="third" style="display:none;">
   <td class="td-intestazione">SCADENZA CONTRATTO</td>
   <td>
      <div style="width:50%;" id="datepicker4" class="input-group date" data-date-format="mm-dd-yyyy">
         <input class="form-control" id="datetimepicker4" name="scad_contratto" placeholder="gg/mm/aaaa" type="text" value="">
         <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
      </div>
   </td>
</tr>
<tr id="third" style="display:none;">
   <td class="td-intestazione">SUBAPPALTO</td>
   <td><input style="width:50%;" class="form-control" type="checkbox" id="sub" name="sub" value=""></td>
</tr>
<tr id="third" style="display:none;">
   <td class="td-intestazione">SCADENZA DISTACCO</td>
   <td>
      <div style="width:50%;" id="datepicker5" class="input-group date" data-date-format="mm-dd-yyyy">
         <input class="form-control" id="datetimepicker5" name="scad_distacco" placeholder="gg/mm/aaaa" type="text" value="">
         <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
      </div>
   </td>
</tr>
<tr id="third" style="display:none;">
   <td class="td-intestazione">SCADENZA CODICE FISCALE</td>
   <td>
      <div style="width:50%;" id="datepicker6" class="input-group date" data-date-format="mm-dd-yyyy">
         <input class="form-control" id="datetimepicker6" name="scad_cf" placeholder="gg/mm/aaaa" type="text" value="">
         <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
      </div>
   </td>
</tr>
   <tr id="third" style="display:none;">
      <td class="td-intestazione">SCADENZA DOCUMENTO</td>
      <td>
         <div style="width:50%;" id="datepicker7" class="input-group date" data-date-format="mm-dd-yyyy">
            <input class="form-control" id="datetimepicker7" name="scad_doc" placeholder="gg/mm/aaaa" type="text" value="">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
         </div>
      </td>
   </tr>
<!-- FINE TERZA PARTE -->
<!-- INIZIO QUARTA PARTE -->
<tr id="four" style="display:none;">
   <td class="td-intestazione">MACCHINA</td>
   <td><input style="width:50%;" class="form-control" type="checkbox" id="car" name="car" value=""></td>
   <td><input style="width:50%;display:none;" class="form-control" type="text" id="car_text" name="car_text" value=""></td>
</tr>
<tr id="four" style="display:none;">
   <td class="td-intestazione">PC</td>
   <td><input style="width:50%;" class="form-control" type="checkbox" id="pc" name="pc" value=""></td>
   <td><input style="width:50%;display:none;" class="form-control" type="text" id="pc_text" name="pc_text" value=""></td>
</tr>
<tr id="four" style="display:none;">
   <td class="td-intestazione">TELEFONO</td>
   <td><input style="width:50%;" class="form-control" type="checkbox" id="tel" name="tel" value=""></td>
   <td><input style="width:50%;display:none;" class="form-control" type="text" id="tel_text" name="tel_text" value=""></td>
</tr>
<tr id="four" style="display:none;">
   <td class="td-intestazione">VARIE</td>
   <td><input style="width:50%;" class="form-control" type="checkbox" id="varie" name="varie" value=""></td>
   <td><input style="width:50%; display:none;" class="form-control" type="text" id="varie_text" name="varie_text" value=""></td>
</tr>
<!-- PERMESSI -->
<tr id="four" style="display:none;">
  <td class="td-intestazione">PERMESSI</td>
  <?php
  $num=0;
  $sql="SELECT * FROM moduli";
  $result= $conn->query($sql);
   while($obj_gruppi= $result->fetch_object()){
  ?>
  <?php if($num>0) { ?><tr id="four" style="display:none;"> <td class="td-intestazione"></td><?php } $num++;?>

  <td>
  			   <strong><?php echo $obj_gruppi->nome;?></strong>
                     <select name="moduli[]" style="width:50%;margin-top:1%;" class="form-control">
                     <option value="<?php echo $obj_gruppi->id_modulo;?>-0">Non Visibile</option>
                     <option value="<?php echo $obj_gruppi->id_modulo;?>-1">Visibile</option>
                </select>
  			   </td>
              </tr>
  <?php } ?>
</tr>
<!-- FINE QUARTA PARTE -->
              <tr id="next1">
                <td colspan="2" style="text-align:left;">
                <button type="button" name="next1" class="btn btn-success btn-sm">
                <i class="fa fa-arrow-right"></i>&nbsp;Prossimo
                </button>
                </td>
                </tr>

                <tr id="next2" style="display:none;">
                  <td style="text-align:left;">
                  <button type="button" name="back1" id="back1" class="btn btn-success btn-sm">
                  <i class="fa fa-arrow-left"></i>&nbsp;Precedente
                  </button>
                  </td>
                  <td style="text-align:left;">
                  <button type="button" name="next2but" id="next2but" class="btn btn-success btn-sm">
                  <i class="fa fa-arrow-right"></i>&nbsp;Prossimo
                  </button>
                  </td>
                  </tr>
                  <tr id="next3" style="display:none;">
                    <td style="text-align:left;">
                    <button type="button" name="back2" id="back2" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left"></i>&nbsp;Precedente
                    </button>
                    </td>
                    <td style="text-align:left;">
                    <button type="button" name="next3but" id="next3but" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-right"></i>&nbsp;Prossimo
                    </button>
                    </td>
                    </tr>

                  <!-- Pulsante Modifica -->
                  <tr id="add" style="display:none;">
                    <td style="text-align:left;">
                    <button type="button" name="back3" id="back3" class="btn btn-success btn-sm">
                    <i class="fa fa-arrow-left"></i>&nbsp;Precedente
                    </button>
                    </td>
                    <td style="text-align:left;" colspan="2">
                    <input type="submit" name="add_user" class="btn btn-success btn-sm" value="Aggiungi">
                    </td>
                    </tr>
                  </tbody>
               </table>
            </form>
   </div>
</div>

<!--  LISTA DIPENDENTI  -->

<!--<div class="row" style="margin-bottom:5%;">
<div class="col-md-12">
<div class="panel panel-primary filterable" style="margin-bottom:5%;">
  <div class="legenda-heading">
              <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Lista Dipendenti</span>
      <div class="pull-right">
        <button class="btn btn-info btn-xs btn-filter button-clear" style="color:#fff;"><span class="fa fa-search fa-2x"></span>
<span class="span-title">&nbsp; Cerca</span>
</button>
      </div>
  </div>
              </div>
  <table class="table">
      <thead>
          <tr class="filters">
              <th><input type="text" class="form-control" placeholder="Num."></th>
              <th><input type="text" class="form-control" placeholder="Dipendente"></th>
              <th><input type="text" class="form-control" placeholder="Data di Nascita"></th>
              <th><input type="text" class="form-control" placeholder="Azioni"></th>
          </tr>
      </thead>
      <tbody>-->

<?php

//<a href="amministrazione.php?action=edit&id='.$dipendente['user_id'].'"><i class="fa fa-pencil fa-2x" style="color:#77c803;"></i></a>
            /*$user = new User();
            $dipendenti = $user->getAllUsersAndAnagraphics();
            foreach($dipendenti as $dipendente) {
                echo '<tr><form action="" method="POST">' .
                     '<td>' . $dipendente['user_id'] . '</td>' .
                     '<td><button type="button" class="btn-link" style="outline:none;" ' .
                        'data-toggle="modal" data-target="#editUserModal"' .
                        'data-user-id="' . $dipendente['user_id'] . '" ' .
                        'data-user-name="' . $dipendente['nome'] . '" ' .
                        'data-user-surname="' . $dipendente['cognome'] . '" ' .
                        'data-user-profile="' . $dipendente['is_admin'] . '" ' .
                        'data-user-birth-date="' . $dipendente['data_nascita'] . '" ' .
                        'data-user-luogo="' . $dipendente['luogo'] . '" ' .
                        'data-user-residenza="' . $dipendente['residenza'] . '" ' .
                        'data-user-indirizzo-residenza="' . $dipendente['indirizzo_residenza'] . '" ' .
                        'data-user-domicilio="' . $dipendente['domicilio'] . '" ' .
                        'data-user-indirizzo-domicilio="' . $dipendente['indirizzo_domicilio'] . '" ' .
                        'data-user-data-assunzione="' . $dipendente['data_assunzione'] . '" ' .
                        'data-user-ore-giorno="' . $dipendente['ore_giorno'] . '" ' .
                        'data-user-giorni-settimana="' . $dipendente['giorni_settimana'] . '" ' .
                        'data-user-mansione="' . $dipendente['mansione'] . '" ' .
                        'data-user-qualifica="' . $dipendente['qualifica'] . '" ' .
                        'data-user-email-personale="' . $dipendente['email_personale'] . '" ' .
                        'data-user-du="' . $dipendente['d_u'] . '" ' .
                        'data-user-livello="' . $dipendente['livello'] . '" ' .
                        'data-user-corso-81="' . $dipendente['corso_81'] . '" ' .
                        'data-user-scad-visita-medica="' . $dipendente['scad_visita_medica'] . '" ' .
                        'data-user-scad-contratto="' . $dipendente['scad_contratto'] . '" ' .
                        'data-user-scad-distacco="' . $dipendente['scad_distacco'] . '" ' .
                        'data-user-scad-cf="' . $dipendente['scad_cf'] . '" ' .
                        'data-user-doc="' . $dipendente['doc'] . '" ' .
                        'data-user-scad-doc="' . $dipendente['scad_doc'] . '" ' .
                        'data-user-email="' . $dipendente['email'] . '" ' .
                        'data-user-cf="' . $dipendente['codice_fiscale'] . '">' .
                        $dipendente['nome'] . ' ' . $dipendente['cognome'] . '</button></td>' .
                     '<td>' . $dipendente['data_nascita'] . '</td>' .
                     '<td>
<a target="_blank" href="tcpdf/examples/PDF_create.php?id='.$dipendente['user_id'].'"><i class="fa fa-file-pdf-o fa-2x" style="color:#fd6c6e;"></i></a>
<button  name="del-dipendente" type="submit" value="' . $dipendente['user_id'] . '"'
                        . 'style="background-color:transparent; border:none; outline:none;">' .
                     '<i class="fa fa-times fa-2x" style="color:#fd6c6e;"></i></button></td>' .
                     '</form></tr>';
            }*/
?>
      <!--</tbody>
  </table>
</div>
</div>-->

<!-- EDIT USER MODAL -->

<!--<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"
     aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <center><h4 class="modal-title" id="editUserModalLabel">MODIFICA UTENTE</h4></center>
            </div>

            <form method="POST" action="">

                    <div class="modal-body col-md-8 col-md-offset-2" id="firstMod">
                        <div class="row">
                            <div class="form-group">
                                <label for="userName">Nome:</label>
                                <input type="text" id="userName" class="form-control"
                                       name="userName" value="">
                            </div>
                            <div class="form-group">
                                <label for="userSurname">Cognome:</label>
                                <input type="text" id="userSurname" class="form-control"
                                       name="userSurname" value="">
                            </div>
                            <div class="form-group">
                                <label for="userBirthDate">Data di Nascita:</label>
                                <input type="text" id="userBirthDate" class="form-control"
                                       name="userBirthDate" value="">
                            </div>
                            <div class="form-group">
                                <label for="userLuogo">Luogo di Nascita:</label>
                                <input type="text" id="userLuogo" class="form-control"
                                       name="userLuogo" value="">
                            </div>
                            <div class="form-group">
                                <label for="userResidenza">Città di Residenza:</label>
                                <input type="text" id="userResidenza" class="form-control"
                                       name="userResidenza" value="">
                            </div>
                            <div class="form-group">
                                <label for="userIndirizzoResidenza">Indirizzo di Residenza:</label>
                                <input type="text" id="userIndirizzoResidenza" class="form-control"
                                       name="userIndirizzoResidenza" value="">
                            </div>
                            <div class="form-group">
                                <label for="userDomicilio">Città di Domicilio:</label>
                                <input type="text" id="userDomicilio" class="form-control"
                                       name="userDomicilio" value="">
                            </div>
                            <div class="form-group">
                                <label for="userIndirizzoDomicilio">Indirizzo di Domicilio:</label>
                                <input type="text" id="userIndirizzoDomicilio" class="form-control"
                                       name="userIndirizzoDomicilio" value="">
                            </div>
                            <div class="form-group">
                                <label for="userCF">Codice Fiscale:</label>
                                <input type="text" id="userCF" class="form-control"
                                       name="userCF" value="">
                            </div>
                            <div class="form-group">
                                <label for="userProfile">Profilo:</label>
                                <select id="userProfile" name="userProfile" class="form-control">
                                    <option value="0">Utente</option>
                                    <option value="1">Amministratore</option>
                                    <option value="2">Responsabile</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="userEmail">Email:</label>
                                <input type="text" id="userEmail" class="form-control"
                                       name="userEmail" value="">
                            </div>
                            <input type="hidden" id="userId" name="userId">
                        </div>
                    </div>
                    <div class="modal-body col-md-8 col-md-offset-2" id="secondMod" style="display:none;">
                        <div class="row">
                            <div class="form-group">
                                <label for="userDataAssunzione">Data di Assunzione:</label>
                                <input type="text" id="userDataAssunzione" class="form-control"
                                       name="userDataAssunzione" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">Ore Giorno:</label>
                                <input type="text" id="userOreGiorno" class="form-control"
                                       name="userOreGiorno" value="">
                            </div>
                            <div class="form-group">
                                <label for="userGiorniSettimana">Giorni Settimana:</label>
                                <input type="text" id="userGiorniSettimana" class="form-control"
                                       name="userGiorniSettimana" value="">
                            </div>
                            <div class="form-group">
                                <label for="userMansione">Mansione:</label>
                                <input type="text" id="userMansione" class="form-control"
                                       name="userMansione" value="">
                            </div>
                            <div class="form-group">
                                <label for="userQualifica">Qualifica:</label>
                                <input type="text" id="userQualifica" class="form-control"
                                       name="userQualifica" value="">
                            </div>
                            <div class="form-group">
                                <label for="userEmailPersonale">Email Personale:</label>
                                <input type="text" id="userEmailPersonale" class="form-control"
                                       name="userEmailPersonale" value="">
                            </div>
                            <div class="form-group">
                                <label for="userDU">D/U:</label>
                                <input type="text" id="userDU" class="form-control"
                                       name="userDU" value="">
                            </div>
                            <div class="form-group">
                                <label for="userLivello">Livello:</label>
                                <input type="text" id="userLivello" class="form-control"
                                       name="userLivello" value="">
                            </div>
                            <div class="form-group">
                                <label for="userCorso81">Corso 81:</label>
                                <input type="text" id="userCorso81" class="form-control"
                                       name="userCorso81" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-body col-md-8 col-md-offset-2" id="thirdMod" style="display:none;">
                        <div class="row">
                            <div class="form-group">
                                <label for="userScadVisitaMedica">Scadenza Visita Medica:</label>
                                <input type="text" id="userScadVisitaMedica" class="form-control"
                                       name="userScadVisitaMedica" value="">
                            </div>
                            <div class="form-group">
                                <label for="UserScadContratto">Scadenza Contratto:</label>
                                <input type="text" id="UserScadContratto" class="form-control"
                                       name="UserScadContratto" value="">
                            </div>
                            <div class="form-group">
                                <label for="userScadDistacco">Scadenza Distacco:</label>
                                <input type="text" id="userScadDistacco" class="form-control"
                                       name="userScadDistacco" value="">
                            </div>
                            <div class="form-group">
                                <label for="userScadCF">Scadenza Codice Fiscale:</label>
                                <input type="text" id="userScadCF" class="form-control"
                                       name="userScadCF" value="">
                            </div>
                            <div class="form-group">
                                <label for="userDoc">Documento:</label>
                                <input type="text" id="userDoc" class="form-control"
                                       name="userDoc" value="">
                            </div>
                            <div class="form-group">
                                <label for="userScadDoc">Scadenza Documento:</label>
                                <input type="text" id="userScadDoc" class="form-control"
                                       name="userScadDoc" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <div class="col-md-6">
                          <button type="button" class="btn btn-default" id="prec1Mod" name="prec1Mod" style="display:none;">
                              <span class="fa fa-arrow-left"></span> Precedente
                          </button>
                          <button type="button" class="btn btn-default" id="prec2Mod" name="prec2Mod" style="display:none;">
                              <span class="fa fa-arrow-left"></span> Precedente
                          </button>
                          <button type="button" class="btn btn-default" id="next1Mod" name="next1Mod">
                              Prossimo <span class="fa fa-arrow-right"></span>
                          </button>
                          <button type="button" class="btn btn-default" id="next2Mod" name="next2Mod" style="display:none;">
                              Prossimo <span class="fa fa-arrow-right"></span>
                          </button>
                      </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success" name="edit-dipendente" >
                                <span class="glyphicon glyphicon-pencil"></span> Modifica
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <span class="glyphicon glyphicon-menu-left"></span> Esci
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

</section>-->
<!--FINE CONTENUTO PRINCIPALE-->
<script type="text/javascript">
function comparsa(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>

<script type="text/javascript">
$("#next1").click(function(){
  $("#first*").hide();
  $("#next1").hide();
  $("#second*").show();
  $(".art").hide();
  $(".perc").hide();
  $("#next2").show();
});

$("#next2but").click(function(){
  $("#second*").hide();
  $("#next2").hide();
  $("#third*").show();
  $("#next3").show();
});

$("#next3but").click(function(){
  $("#third*").hide();
  $("#next3").hide();
  $("#four*").show();
  $("#add").show();
});

$("#back1").click(function(){
  $("#second*").hide();
  $("#next2").hide();
  $("#first*").show();
  $("#next1").show();
});

$("#back2").click(function(){
  $("#third*").hide();
  $("#next3").hide();
  $("#second*").show();
  $("#next2").show();
});

$("#back3").click(function(){
  $("#four*").hide();
  $("#add").hide();
  $("#third*").show();
  $("#next3").show();
});

$("#next1Mod").click(function(){
  $("#firstMod").hide();
  $("#next1Mod").hide();
  $("#secondMod").show();
  $("#next2Mod").show();
  $("#prec1Mod").show();
});

$("#next2Mod").click(function(){
  $("#secondMod").hide();
  $("#next2Mod").hide();
  $("#prec1Mod").hide();
  $("#thirdMod").show();
  $("#prec2Mod").show();
});

$("#prec1Mod").click(function(){
  $("#prec1Mod").hide();
  $("#secondMod").hide();
  $("#next2Mod").hide();
  $("#firstMod").show();
  $("#next1Mod").show();
});

$("#prec2Mod").click(function(){
  $("#prec2Mod").hide();
  $("#thirdMod").hide();
  $("#next2Mod").show();
  $("#secondMod").show();
  $("#prec1Mod").show();
});

$("#cat_protetta").click(function(){
  if($(this).is(":checked")) {
        $(".art").show();
        $(".perc").show();
    } else {
        $(".art").hide();
        $(".perc").hide();
    }
});

$("#car").click(function(){
  if($(this).is(":checked")) {
        $("#car_text").show();
    } else {
        $("#car_text").hide();
    }
});
$("#pc").click(function(){
  if($(this).is(":checked")) {
        $("#pc_text").show();
    } else {
        $("#pc_text").hide();
    }
});
$("#tel").click(function(){
  if($(this).is(":checked")) {
        $("#tel_text").show();
    } else {
        $("#tel_text").hide();
    }
});
$("#varie").click(function(){
  if($(this).is(":checked")) {
        $("#varie_text").show();
    } else {
        $("#varie_text").hide();
    }
});
</script>
<!-- <script type="text/javascript">

const userNameField = document.getElementById('userName');
const userSurnameField = document.getElementById('userSurname');
const userBirthDateField = document.getElementById('userBirthDate');
const userEmailField = document.getElementById('userEmail');
const userCfField = document.getElementById('userCF');



$(document).ready(function () {
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var userName = button.data('user-name');
        var userSurname = button.data('user-surname');
        var userBirthDate = button.data('user-birth-date');
        var userCF = button.data('user-cf');
        var userProfile = button.data('user-profile');
        var userEmail = button.data('user-email');

        var userLuogo = button.data('user-luogo');
        var userResidenza = button.data('user-residenza');
        var userIndirizzoResidenza = button.data('user-indirizzo-residenza');
        var userDomicilio = button.data('user-domicilio');
        var userIndirizzoDomicilio = button.data('user-indirizzo-domicilio');

        var userDataAssunzione = button.data('user-data-assunzione');
        var userOreGiorno = button.data('user-ore-giorno');
        var userGiorniSettimana = button.data('user-giorni-settimana');
        var userMansione = button.data('user-mansione');
        var userQualifica = button.data('user-qualifica');
        var userEmailPersonale = button.data('user-email-personale');
        var userDU = button.data('user-du');
        var userLivello = button.data('user-livello');
        var userCorso81 = button.data('user-corso-81');

        var userScadVisitaMedica = button.data('user-scad-visita-medica');
        var UserScadContratto = button.data('user-scad-contratto');
        var userScadDistacco = button.data('user-scad-distacco');
        var userScadCF = button.data('user-scad-cf');
        var userDoc = button.data('user-doc');
        var userScadDoc = button.data('user-scad-doc');



        $("#userId").attr('value', userId);
        $("#userName").attr('value', userName);
        $("#userSurname").attr('value', userSurname);
        $("#userBirthDate").attr('value', userBirthDate);
        $("#userCF").attr('value', userCF);
        $("#userProfile").attr('value', userProfile);
        $("#userEmail").attr('value', userEmail);

        $("#userLuogo").attr('value', userLuogo);
        $("#userResidenza").attr('value', userResidenza);
        $("#userIndirizzoResidenza").attr('value', userIndirizzoResidenza);
        $("#userDomicilio").attr('value', userDomicilio);
        $("#userIndirizzoDomicilio").attr('value', userIndirizzoDomicilio);

        $("#userDataAssunzione").attr('value', userDataAssunzione);
        $("#userOreGiorno").attr('value', userOreGiorno);
        $("#userGiorniSettimana").attr('value', userGiorniSettimana);
        $("#userMansione").attr('value', userMansione);
        $("#userQualifica").attr('value', userQualifica);
        $("#userEmailPersonale").attr('value', userEmailPersonale);
        $("#userDU").attr('value', userDU);
        $("#userLivello").attr('value', userLivello);
        $("#userCorso81").attr('value', userCorso81);

        $("#userScadVisitaMedica").attr('value', userScadVisitaMedica);
        $("#UserScadContratto").attr('value', UserScadContratto);
        $("#userScadDistacco").attr('value', userScadDistacco);
        $("#userScadCF").attr('value', userScadCF);
        $("#userDoc").attr('value', userDoc);
        $("#userScadDoc").attr('value', userScadDoc);

        if (userName !== '') {
            userNameField.style.border = "1px solid #ccc";
        } else {
            userNameField.style.border = "2px solid red";
        }

        if (userName !== '') {
            userNameField.style.border = "1px solid #ccc";
        } else {
            userNameField.style.border = "2px solid red";
        }

        if (userBirthDate !== '') {
            userBirthDateField.style.border = "1px solid #ccc";
        } else {
            userBirthDateField.style.border = "2px solid red";
        }

        if (userCF !== '') {
            userCfField.style.border = "1px solid #ccc";
        } else {
            userCfField.style.border = "2px solid red";
        }

        if (userEmail !== '') {
            userEmailField.style.border = "1px solid #ccc";
        } else {
            userEmailField.style.border = "2px solid red";
        }
    });
});
</script> -->
<script>
    const firstNameField = document.getElementById('firstname');
    const lastNameField = document.getElementById('lastname');
    const birthDateField = document.getElementById('datetimepicker');
    const emailField = document.getElementById('email');
    const cfField = document.getElementById('codice_fiscale');
    const luogoField = document.getElementById('luogo');
    const nazionalitaField = document.getElementById('nazionalita');
    const telefonoField = document.getElementById('telefono');
    const indirizzoResidenzaField = document.getElementById('indirizzo_residenza');
    const domicilioField = document.getElementById('domicilio');
    const indirizzoDomicilioField = document.getElementById('indirizzo_domicilio');

    firstNameField.style.border = "2px solid red";
    lastNameField.style.border = "2px solid red";
    birthDateField.style.border = "2px solid red";
    emailField.style.border = "2px solid red";
    cfField.style.border = "2px solid red";
    luogoField.style.border = "2px solid red";
    nazionalitaField.style.border = "2px solid red";
    telefonoField.style.border = "2px solid red";
    indirizzoResidenzaField.style.border = "2px solid red";
    domicilioField.style.border = "2px solid red";
    indirizzoDomicilioField.style.border = "2px solid red";

    firstNameField.addEventListener('keyup', function (event) {
        if (firstNameField.value) {
            firstNameField.style.border = "";
        } else {
            firstNameField.style.border = "2px solid red";
        }
    });

    lastNameField.addEventListener('keyup', function (event) {
        if (lastNameField.value) {
            lastNameField.style.border = "";
        } else {
            lastNameField.style.border = "2px solid red";
        }
    });

    birthDateField.addEventListener('keyup', function (event) {
        if (birthDateField.value) {
            birthDateField.style.border = "";
        } else {
            birthDateField.style.border = "2px solid red";
        }
    });

    emailField.addEventListener('keyup', function (event) {
        if (emailField.value) {
            emailField.style.border = "";
        } else {
            emailField.style.border = "2px solid red";
        }
    });

    cfField.addEventListener('keyup', function (event) {
        if (cfField.value) {
            cfField.style.border = "";
        } else {
            cfField.style.border = "2px solid red";
        }
    });

    luogoField.addEventListener('keyup', function (event) {
        if (luogoField.value) {
            luogoField.style.border = "";
        } else {
            luogoField.style.border = "2px solid red";
        }
    });
    nazionalitaField.addEventListener('keyup', function (event) {
        if (nazionalitaField.value) {
            nazionalitaField.style.border = "";
        } else {
            nazionalitaField.style.border = "2px solid red";
        }
    });
    telefonoField.addEventListener('keyup', function (event) {
        if (telefonoField.value) {
            telefonoField.style.border = "";
        } else {
            telefonoField.style.border = "2px solid red";
        }
    });
    indirizzoResidenzaField.addEventListener('keyup', function (event) {
        if (indirizzoResidenzaField.value) {
            indirizzoResidenzaField.style.border = "";
        } else {
            indirizzoResidenzaField.style.border = "2px solid red";
        }
    });
    domicilioField.addEventListener('keyup', function (event) {
        if (domicilioField.value) {
            domicilioField.style.border = "";
        } else {
            domicilioField.style.border = "2px solid red";
        }
    });
    indirizzoDomicilioField.addEventListener('keyup', function (event) {
        if (indirizzoDomicilioField.value) {
            indirizzoDomicilioField.style.border = "";
        } else {
            indirizzoDomicilioField.style.border = "2px solid red";
        }
    });
</script>
<!-- <script>
    userNameField.addEventListener('keyup', function (event) {
        if (userNameField.value) {
            userNameField.style.border = "";
        } else {
            userNameField.style.border = "2px solid red";
        }
    });

    userSurnameField.addEventListener('keyup', function (event) {
        if (userSurnameField.value) {
            userSurnameField.style.border = "";
        } else {
            userSurnameField.style.border = "2px solid red";
        }
    });

    userBirthDateField.addEventListener('keyup', function (event) {
        if (userBirthDateField.value) {
            userBirthDateField.style.border = "";
        } else {
            userBirthDateField.style.border = "2px solid red";
        }
    });

    userEmailField.addEventListener('keyup', function (event) {
        if (userEmailField.value) {
            userEmailField.style.border = "";
        } else {
            userEmailField.style.border = "2px solid red";
        }
    });

    userCfField.addEventListener('keyup', function (event) {
        if (userCfField.value) {
            userCfField.style.border = "";
        } else {
            userCfField.style.border = "2px solid red";
        }
    });
</script> -->
