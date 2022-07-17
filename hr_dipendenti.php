<?php

if(isset($_REQUEST['add_commessa'])){

//echo $_SESSION['user_idd']."-".$_REQUEST['commessa']."-".$_REQUEST['commessa_ruolo'];
$commessa = new Commessa();
$add_commessa = $commessa->addTempCommessa($_REQUEST['commessa'],$_REQUEST['commessa_ruolo'],$_SESSION['user_idd']);
}

if(isset($_REQUEST['del_commessa'])){

$commessa = new Commessa();
$del_commessa = $commessa->delCommessa($_REQUEST['id_commessa']);

}

if(isset($_POST['delete_cf'])){

    $target_dir = "files/cf/".$_POST['userId']."/";
    chdir($target_dir);
    unlink($_POST['nome_cf']);
    $alert_allegato = "<div class='panel panel-success'><div class='panel-heading'>File correlato eliminato con successo!</div></div>";
    unset($_POST['nome_cf']);
}

if(isset($_POST['delete_doc'])){

    $target_dir = "files/doc/".$_POST['userId']."/";
    chdir($target_dir);
    unlink($_POST['nome_doc']);
    $alert_allegato = "<div class='panel panel-success'><div class='panel-heading'>File correlato eliminato con successo!</div></div>";
    unset($_POST['nome_doc']);
}

if(isset($_POST['edit-dipendente'])) {
  if(isset($_POST['user104'])) $_POST['user104'] = 1;
  else {$_POST['user104']=0;}
  if(isset($_POST['userCatProtetta'])) $_POST['userCatProtetta'] = 1;
  else {$_POST['userCatProtetta']=0;}
  if(isset($_POST['userSub'])) $_POST['userSub'] = 1;
  else {$_POST['userSub']=0;}
    $user = new User();
    $editUser = $user->editEmployee($_POST['userId'], $_POST['userName'], $_POST['userSurname'],
            $_POST['userBirthDate'], $_POST['userCF'], $_POST['userProfile'], $_POST['userEmail'], $_POST['userLuogo'], $_POST['userResidenza'],$_POST['userIndirizzoResidenza'],$_POST['userDomicilio'],$_POST['userIndirizzoDomicilio'],$_POST['userOreGiorno'],$_POST['userQualifica'], $_POST['userMansione'],$_POST['userGiorniSettimana'],$_POST['userScadVisitaMedica'],$_POST['userScadContratto'],$_POST['userScadDistacco'], $_POST['userDataAssunzione'], $_POST['userDU'],$_POST['userEmailPersonale'],$_POST['userScadCF'],$_POST['userDoc'],$_POST['userScadDoc'],$_POST['userLivello'],$_POST['userCorso81'],$_POST['user104'],$_POST['userCatProtetta'],$_POST['userArticolo'],$_POST['userPerc']);
   $info_hr = new InfoHR();
            if(isset($_REQUEST['tel'])) $_REQUEST['tel'] = 1;
            else {$_REQUEST['tel']=0;$_REQUEST['tel_text']="";}
            if(isset($_REQUEST['pc'])) $_REQUEST['pc'] = 1;
            else {$_REQUEST['pc']=0;$_REQUEST['pc_text']="";}
            if(isset($_REQUEST['varie'])) $_REQUEST['varie'] = 1;
            else {$_REQUEST['varie']=0;$_REQUEST['varie_text']="";}
            if(isset($_REQUEST['car'])) $_REQUEST['car'] = 1;
            else {$_REQUEST['car']=0;$_REQUEST['car_text']="";}
    $insert_anagrafica = $info_hr->addBeniHR($_POST['userId'],$_REQUEST['tel'],$_REQUEST['car'],$_REQUEST['pc'],$_REQUEST['varie'],$_REQUEST['tel_text'],$_REQUEST['car_text'],$_REQUEST['pc_text'],$_REQUEST['varie_text']);

    $moduli = new Modulo();
    $delMod = $moduli->deleteUserModules($_POST['userId']);
    foreach($_REQUEST['moduli'] as $mod)
    {
      $explode = explode("-", $mod);
      if($explode[1]==1) $insert_module = $moduli->createUserModule($_POST['userId'],$explode[0]);
    }

    $alert_allegato="";
    $target_dir = "files/cf/".$_POST['userId']."/";
    $target_dir2 = "files/doc/".$_POST['userId']."/";
    //echo $target_dir;
    function error_handler($errno, $errstr) {
                global $last_error;
                $last_error = $errstr;
            }

            set_error_handler('error_handler');
            if(!is_dir($target_dir)){
              if(!mkdir($target_dir, 0777,true))
                  echo "MKDIR failed, reason: $last_error\n";
            }
            if(!is_dir($target_dir2)){
              if(!mkdir($target_dir2, 0777,true))
                      echo "MKDIR failed, reason: $last_error\n";
            }
            restore_error_handler();

    //echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">'.$_FILES["cf_allegato"]["name"].'</div>';
    $target_file = $target_dir . $_FILES["cf_allegato"]["name"];
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["cf_allegato"]["tmp_name"]);

    if ($_FILES["cf_allegato"]["size"] > 10000000) {
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
        move_uploaded_file($_FILES["cf_allegato"]["tmp_name"], $target_file);
        $alert_allegato .= "<div class='panel panel-success'><div class='panel-heading'>File caricato con successo</div></div>";
    }

    echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">'.$_FILES["doc_allegato"]["name"].'</div>';
    $target_file = $target_dir2 . $_FILES["doc_allegato"]["name"];
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["doc_allegato"]["tmp_name"]);

    if ($_FILES["doc_allegato"]["size"] > 10000000) {
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
        move_uploaded_file($_FILES["doc_allegato"]["tmp_name"], $target_file);
        $alert_allegato .= "<div class='panel panel-success'><div class='panel-heading'>File caricato con successo</div></div>";
    }

    if($editUser&&$insert_anagrafica) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Utente modificato con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Modifica utente non completata.
            </div>';
    }
}

if(isset($_POST['deactive'])) {
  // echo 'ID:'.$_POST['id_user'];
  $user = new InfoHR();
  $editUser = $user->activeUser($_POST['id_user'],'0');
}
?>

<section class="">
	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="calendar-heading">
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
                        <th style="width:26%;"><input type="text" class="form-control" placeholder="Dipendente"></th>
                        <th style="width:26%;"><input type="text" class="form-control" placeholder="Commesse"></th>
                        <th style="width:8%;"><input type="text" class="form-control" placeholder="Ferie"></th>
                        <th style="width:8%;"><input type="text" class="form-control" placeholder="Permessi"></th>
                        <th style="width:8%;"><input type="text" class="form-control" placeholder="CF"></th>
                        <th style="width:8%;"><input type="text" class="form-control" placeholder="Doc"></th>
                        <th style="width:8%;"><input type="text" class="form-control" placeholder="Certificazioni"></th>
                        <th style="width:8%;"><input type="text" class="form-control" placeholder="Azioni"></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    $infoHRClass = new User();
                    $infoList = $infoHRClass->getActiveUsersAndAnagraphics();
                    foreach($infoList as $user) {
                      $cf="";
                      $doc="";
                      $cer = "";
                      $cf_al="";
                      $doc_al="";
                      $utente = new User();
                      $com = $utente->getListaCommesse($user['user_id']);
                      $mod = $utente->getListaModuli($user['user_id']);
                      $ferie = $utente->getFeriePermessi($user['user_id']);

                      $dir1    = 'files/cf/'.$user['user_id'];
                      $files1 = scandir($dir1);
                      $dir2    = 'files/doc/'.$user['user_id'];
                      $files2 = scandir($dir2);

                      $certi = $utente->getListaCert($user['user_id']);

                      foreach($certi as $cert)
                      {
                         $cer.= '<center><a data-toggle="tooltip" data-placement="top" title="'.$cert['titolo'].'" id="cert_tooltip" target="_blank" href="files/cert/'.$user['user_id'].'/'.$cert['id_certificazione'].'/'.$cert['nome'].'"><i class="fa fa-certificate"></i></a></center>';
                      }


                      foreach($files1 as $file)
                      {
                        //echo $file;
                        if($file!="."&&$file!=".."){
                          $cf.= '<center><a data-toggle="tooltip" data-placement="top" title="Codice Fiscale" id="cf_tooltip" target="_blank" href="files/cf/'.$user['user_id'].'/'.$file.'"><i class="fa fa-id-card"></i></a></center>';

                          $cf_al .= $file.",";
                        }


                      }

                      foreach($files2 as $file)
                      {
                      //echo $file;
                        if($file!="."&&$file!=".."){
                          $doc.= '<center><a data-toggle="tooltip" data-placement="top" title="Documento" id="doc_tooltip" target="_blank" href="files/doc/'.$user['user_id'].'/'.$file.'"><i class="fa fa-id-card"></i></a></center>';
                          $doc_al.= $file.",";
                        }


                      }

                      $doc_al = substr($doc_al, 0, -1);
                      $cf_al = substr($cf_al, 0, -1);


                      $style="white";
                      if($user['articolo']==1) $style="yellow";
                      else if($user['articolo']==18) $style="Aquamarine";
                      $active = '<form action="" method="post"><button type="submit" name="deactive" class="btn btn-danger btn-xs" style="margin-bottom: 3px;">
                                    <span class="glyphicon glyphicon-ok"></span> Disattiva
                                </button><input type="hidden" name="id_user" value="'.$user['user_id'].'"></form>';
                        echo '<tr style="background-color:'.$style.'">
                                <td><button type="button" class="btn-link" style="outline:none;" ' .
                        'data-toggle="modal" data-target="#editUserModal"' .
                        'data-toggle="modal" data-target="#editUserModal"' .
                        'data-user-id="' . $user['user_id'] . '" ' .
                        'data-user-name="' . $user['nome'] . '" ' .
                        'data-user-surname="' . $user['cognome'] . '" ' .
                        'data-user-profile="' . $user['is_admin'] . '" ' .
                        'data-user-birth-date="' . $user['data_nascita'] . '" ' .
                        'data-user-luogo="' . $user['luogo'] . '" ' .
                        'data-user-cf="' . $user['cf'] . '" ' .
                        'data-user-residenza="' . $user['residenza'] . '" ' .
                        'data-user-indirizzo-residenza="' . $user['indirizzo_residenza'] . '" ' .
                        'data-user-domicilio="' . $user['domicilio'] . '" ' .
                        'data-user-indirizzo-domicilio="' . $user['indirizzo_domicilio'] . '" ' .
                        'data-user-data-assunzione="' . $user['data_assunzione'] . '" ' .
                        'data-user-ore-giorno="' . $user['ore_giorno'] . '" ' .
                        'data-user-giorni-settimana="' . $user['giorni_settimana'] . '" ' .
                        'data-user-mansione="' . $user['mansione'] . '" ' .
                        'data-user-qualifica="' . $user['qualifica'] . '" ' .
                        'data-user-centoquattro="' . $user['legge_104'] . '" ' .
                        'data-user-protetta="' . $user['cat_protetta'] . '" ' .
                        'data-user-articolo="' . $user['articolo'] . '" ' .
                        'data-user-percentuale="' . $user['percentuale'] . '" ' .
                        'data-user-email-personale="' . $user['email_personale'] . '" ' .
                        'data-user-du="' . $user['d_u'] . '" ' .
                        'data-user-livello="' . $user['livello'] . '" ' .
                        'data-user-corso-81="' . $user['corso_81'] . '" ' .
                        'data-user-scad-visita-medica="' . $user['scad_visita_medica'] . '" ' .
                        'data-user-scad-contratto="' . $user['scad_contratto'] . '" ' .
                        'data-user-sub="' . $user['sub'] . '" ' .
                        'data-user-scad-distacco="' . $user['scad_distacco'] . '" ' .
                        'data-user-scad-cf="' . $user['scad_cf'] . '" ' .
                        'data-user-doc="' . $user['doc'] . '" ' .
                        'data-user-scad-doc="' . $user['scad_doc'] . '" ' .
                        'data-user-email="' . $user['email'] . '" ' .
                        'data-user-car="' . $user['car'] . '" '.
                        'data-user-car-text="' . $user['car_text'] . '" '.
                        'data-user-tel="' . $user['tel'] . '" '.
                        'data-user-tel-text="' . $user['tel_text'] . '" '.
                        'data-user-pc="' . $user['pc'] . '" '.
                        'data-user-pc-text="' . $user['pc_text'] . '" '.
                        'data-user-varie="' . $user['varie'] . '" '.
                        'data-user-cf-al="' . $cf_al . '" '.
                        'data-user-doc-al="' . $doc_al . '" '.
                        'data-user-moduli="0,' . $mod . '" '.
                        'data-user-varie-text="' . $user['varie_text'] . '" '.'>'
                        . $user['cognome'] . ' ' . $user['nome'] . '</td>
                                <td>' . $com . '</td>
                                <td>' . $ferie[0] . '</td>
                                <td>' . $ferie[1] . '</td>
                                <td>' . $cf . '</td>
                                <td>' . $doc . '</td>
                                <td>' . $cer . '</td>
                                <td>' . $active . '</td>
                            </tr>';
                    }
?>

                </tbody>
            </table>
        </div>
    </div>
	</div>
</section>

<!-- EDIT USER MODAL -->

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"
     aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <center><h4 class="modal-title" id="editUserModalLabel">MODIFICA UTENTE</h4></center>
            </div>

            <form method="POST" action="" enctype="multipart/form-data">

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
                            <div class="form-group" id="allegaCf">
                                <label for="allegaCf">Allega CF:</label>
                                <input type="file" id="allegaCf" class="form-control"
                                       name="cf_allegato">
                            </div>
                            <div class="form-group cf_al">
                            </div>
                            <div class="form-group">
                                <label for="userDoc">Documento:</label>
                                <input type="text" id="userDoc" class="form-control"
                                       name="userDoc" value="">
                            </div>
                            <div class="form-group" id="allegaDoc">
                                <label for="allegaCf">Allega Doc:</label>
                                <input type="file" id="allegaDoc" class="form-control"
                                       name="doc_allegato">
                            </div>
                            <div class="form-group doc_al">
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
                                <label for="user104">Legge 104:</label>
                                <input type="checkbox" id="user104" class="form-control"
                                       name="user104" value="">
                            </div>
                            <div class="form-group">
                                <label for="userCatProtetta">Categoria Protetta:</label>
                                <input type="checkbox" id="userCatProtetta" class="form-control"
                                       name="userCatProtetta" value="">
                            </div>
                            <div class="form-group" id="userArticoloDiv">
                                <label for="userArticolo">Articolo:</label>
                                <select name="userArticolo" id="userArticolo"><option value=""></option><option value="1">Art. 1</option><option value="18">Art. 18</option></select>
                            </div>
                            <div class="form-group" id="userPercDiv">
                                <label for="userPerc">Percentuale:</label>
                                <input type="text" id="userPerc" class="form-control"
                                       name="userPerc" value="">
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
                                <label for="userSub">Subappalto:</label>
                                <input type="checkbox" id="userSub" class="form-control"
                                       name="userSub" value="">
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
                                <label for="userScadDoc">Scadenza Documento:</label>
                                <input type="text" id="userScadDoc" class="form-control"
                                       name="userScadDoc" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-body col-md-8 col-md-offset-2" id="fourthMod" style="display:none;">
                        <div class="row">
                          <div class="row">
                            <div class="col-md-2">MACCHINA</div>
                            <div class="col-md-2"><input style="width:50%;" class="form-control" type="checkbox" id="car" name="car" value=""></div>
                            <div class="col-md-8"><input style="width:50%;display:none;" class="form-control" type="text" id="car_text" name="car_text" value=""></div>
                          </div>
                          <div class="row">
                            <div class="col-md-2">PC</div>
                            <div class="col-md-2"><input style="width:50%;" class="form-control" type="checkbox" id="pc" name="pc" value=""></div>
                            <div class="col-md-8"><input style="width:50%;display:none;" class="form-control" type="text" id="pc_text" name="pc_text" value=""></div>
                          </div>
                          <div class="row">
                            <div class="col-md-2">TELEFONO</div>
                            <div class="col-md-2"><input style="width:50%;" class="form-control" type="checkbox" id="tel" name="tel" value=""></div>
                            <div class="col-md-8"><input style="width:50%;display:none;" class="form-control" type="text" id="tel_text" name="tel_text" value=""></div>
                          </div>
                          <div class="row">
                            <div class="col-md-2">VARIE</div>
                            <div class="col-md-2"><input style="width:50%;" class="form-control" type="checkbox" id="varie" name="varie" value=""></div>
                            <div class="col-md-8"><input style="width:50%;display:none;" class="form-control" type="text" id="varie_text" name="varie_text" value=""></div>
                          </div>
                          <!-- PERMESSI -->
                          <div class="row">
                            <br><br>
                            <div class="col-md-2"><b>PERMESSI</b></div>
                            <br><br>
                            <?php
                            $num=0;
                            $sql="SELECT * FROM moduli";
                            $result= $conn->query($sql);

                             while($obj_gruppi= $result->fetch_object()){
                            ?>

                            <?php if($num>0) { ?> <?php } $num++;?>

                            <div class="row">
                            			   <strong><?php echo $obj_gruppi->nome;?></strong>
                            </div>
                            <div class="row">
                                               <select name="moduli[]" style="width:50%;margin-top:1%;" class="form-control">
                                               <option id="modulo<?php echo $obj_gruppi->id_modulo;?>" value="<?php echo $obj_gruppi->id_modulo;?>-0">Non Visibile</option>
                                               <option id="modulo<?php echo $obj_gruppi->id_modulo;?>v" value="<?php echo $obj_gruppi->id_modulo;?>-1">Visibile</option>
                                          </select>
                            			   </div>
                            <?php } ?>
                          </div>
                          <!--<tr>
                             <td class="td-intestazione">PC</td>
                             <td><input style="width:50%;" class="form-control" type="checkbox" id="pc" name="pc" value=""></td>
                             <td><input style="width:50%;display:none;" class="form-control" type="text" id="pc_text" name="pc_text" value=""></td>
                          </tr>
                          <tr>
                             <td class="td-intestazione">TELEFONO</td>
                             <td><input style="width:50%;" class="form-control" type="checkbox" id="tel" name="tel" value=""></td>
                             <td><input style="width:50%;display:none;" class="form-control" type="text" id="tel_text" name="tel_text" value=""></td>
                          </tr>
                          <tr>
                             <td class="td-intestazione">VARIE</td>
                             <td><input style="width:50%;" class="form-control" type="checkbox" id="varie" name="varie" value=""></td>
                             <td><input style="width:50%; display:none;" class="form-control" type="text" id="varie_text" name="varie_text" value=""></td>
                          </tr>
                        </table>-->
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
                          <button type="button" class="btn btn-default" id="prec3Mod" name="prec3Mod" style="display:none;">
                              <span class="fa fa-arrow-left"></span> Precedente
                          </button>
                          <button type="button" class="btn btn-default" id="next1Mod" name="next1Mod">
                              Prossimo <span class="fa fa-arrow-right"></span>
                          </button>
                          <button type="button" class="btn btn-default" id="next2Mod" name="next2Mod" style="display:none;">
                              Prossimo <span class="fa fa-arrow-right"></span>
                          </button>
                          <button type="button" class="btn btn-default" id="next3Mod" name="next3Mod" style="display:none;">
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
<!--FINE CONTENUTO PRINCIPALE-->

<script type="text/javascript">

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

$("#next1").click(function(){
  $("#first*").hide();
  $("#next1").hide();
  $("#second*").show();
  $("#next2").show();
});

$("#next2but").click(function(){
  $("#second*").hide();
  $("#next2").hide();
  $("#third*").show();
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
  $("#add").hide();
  $("#second*").show();
  $("#next2").show();
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
  $("#next3Mod").show();
});

$("#next3Mod").click(function(){
  $("#thirdMod").hide();
  $("#next3Mod").hide();
  $("#prec2Mod").hide();
  $("#fourthMod").show();
  $("#prec3Mod").show();
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
  $("#next3Mod").hide();
  $("#thirdMod").hide();
  $("#next2Mod").show();
  $("#secondMod").show();
  $("#prec1Mod").show();
});
$("#prec3Mod").click(function(){
  $("#prec3Mod").hide();
  $("#next3Mod").hide();
  $("#fourthMod").hide();
  $("#next2Mod").show();
  $("#thirdMod").show();
  $("#prec2Mod").show();
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
<script type="text/javascript">

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
        var cfAl = button.data('user-cf-al');
        var docAl = button.data('user-doc-al');
        var user104 = button.data('user-centoquattro');
        var userCatProtetta = button.data('user-protetta');
        var userArticolo = button.data('user-articolo');
        var userPerc = button.data('user-percentuale');
        var userSub = button.data('user-sub');

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

        var userTel = button.data('user-tel');
        var userTelText = button.data('user-tel-text');
        var userCar = button.data('user-car');
        var userCarText = button.data('user-car-text');
        var userPc = button.data('user-pc');
        var userPcText = button.data('user-pc-text');
        var userVarie = button.data('user-varie');
        var userVarieText = button.data('user-varie-text');
        var userModules = button.data('user-moduli');


        //alert(userCatProtetta+"-"+user104+"-"+userArticolo);
        if(userSub==1) $("#userSub").attr('checked', "checked");
        else $("#userSub").prop('checked', false);
        if(user104==1) $("#user104").attr('checked', "checked");
        else $("#user104").prop('checked', false);

        $("#userCatProtetta").click(function(){
          if($(this).is(":checked")) {
                $("#userPercDiv").show();
                $("#userArticoloDiv").show();
            } else {
                $("#userPercDiv").hide();
                $("#userArticoloDiv").hide();
                $("#userPerc").attr('value', '');
                $("#userArticolo").val('');
            }
        });

        if(userCatProtetta==1){
          $("#userCatProtetta").attr('checked', "checked");
          $("#userPercDiv").show();
          $("#userArticoloDiv").show();
          $("#userPerc").attr('value', userPerc);
          $("#userArticolo").val(userArticolo);
        } else {
          $("#userPercDiv").hide();
          $("#userArticoloDiv").hide();
          $("#userCatProtetta").prop('checked', false);
          $("#userPerc").attr('value', userPerc);
          $("#userArticolo").val(userArticolo);
        }
        if(userModules)
        {
          if(userModules.indexOf("5")!="-1") $("#modulo5v").attr('selected', 'selected');
          if(userModules.indexOf("3")!="-1") $("#modulo3v").attr('selected', 'selected');
          if(userModules.indexOf("2")!="-1") $("#modulo2v").attr('selected', 'selected');
        }

        if(cfAl!="") $("#allegaCf").hide();
        else $("#allegaCf").show();
        if(docAl!="") $("#allegaDoc").hide();
        else $("#allegaDoc").show();
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

        //alert(docAl+","+cfAl);

        if(cfAl==""){
          $(".cf_al").hide();
        }
        else {
          $(".cf_al").html('<a data-toggle="tooltip" data-placement="top" title="Codice Fiscale" id="cf_tooltip" target="_blank" href="files/cf/'+userId+'/'+cfAl+'"><i class="fa fa-id-card"></i></a><button style="margin-left:1%; border:none; padding:0; background-color:transparent;" type="submit" name="delete_cf"><span style="color:red;" class="glyphicon glyphicon-remove"></span></button><input type="hidden" name="nome_cf" value="'+cfAl+'"><br/>');
          $(".cf_al").show();
        }
        if(docAl==""){
          $(".doc_al").hide();
        }
        else{
          $(".doc_al").html('<a data-toggle="tooltip" data-placement="top" title="Codice Fiscale" id="cf_tooltip" target="_blank" href="files/doc/'+userId+'/'+docAl+'"><i class="fa fa-id-card"></i></a><button style="margin-left:1%; border:none; padding:0; background-color:transparent;" type="submit" name="delete_doc"><span style="color:red;" class="glyphicon glyphicon-remove"></span></button><input type="hidden" name="nome_doc" value="'+docAl+'"><br/>');
          $(".doc_al").show();

        }


        if(userTel===1){
          $("#tel").prop('checked', true);
          $("#tel_text").show();
          $("#tel_text").attr('value', userTelText);
        }
        if(userPc===1){
          $("#pc").prop('checked', true);
          $("#pc_text").show();
          $("#pc_text").attr('value', userPcText);
        }
        if(userCar===1){
          $("#car").prop('checked', true);
          $("#car_text").show();
          $("#car_text").attr('value', userCarText);
        }
        if(userVarie===1){
          $("#varie").prop('checked', true);
          $("#varie_text").show();
          $("#varie_text").attr('value', userVarieText);
        }


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
</script>
