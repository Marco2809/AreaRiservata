<?php

if(isset($_POST['edit-dipendente'])) {
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

if (isset($_POST['active'])){
  $user = new InfoHR();
  $editUser = $user->activeUser($_POST['id_user'],'1');
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
                        <th style="width:70%;"><input type="text" class="form-control" placeholder="Dipendente"></th>
                        <th style="width:15%;"><input type="text" class="form-control" placeholder="Data Assunzione"></th>
                        <th style="width:15%;"><input type="text" class="form-control" placeholder="Azioni"></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    $infoHRClass = new User();
                    $infoList = $infoHRClass->getInactiveUsersAndAnagraphics();

                    foreach($infoList as $user) {

                      $style="white";
                      $active = '<form action="" method="post"><button type="submit" name="active" class="btn btn-success btn-xs" style="margin-bottom: 3px;">
                                    <span class="glyphicon glyphicon-ok"></span> Attiva
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
                        'data-user-residenza="' . $user['residenza'] . '" ' .
                        'data-user-indirizzo-residenza="' . $user['indirizzo_residenza'] . '" ' .
                        'data-user-domicilio="' . $user['domicilio'] . '" ' .
                        'data-user-indirizzo-domicilio="' . $user['indirizzo_domicilio'] . '" ' .
                        'data-user-data-assunzione="' . $user['data_assunzione'] . '" ' .
                        'data-user-ore-giorno="' . $user['ore_giorno'] . '" ' .
                        'data-user-giorni-settimana="' . $user['giorni_settimana'] . '" ' .
                        'data-user-mansione="' . $user['mansione'] . '" ' .
                        'data-user-qualifica="' . $user['qualifica'] . '" ' .
                        'data-user-email-personale="' . $user['email_personale'] . '" ' .
                        'data-user-du="' . $user['d_u'] . '" ' .
                        'data-user-livello="' . $user['livello'] . '" ' .
                        'data-user-corso-81="' . $user['corso_81'] . '" ' .
                        'data-user-scad-visita-medica="' . $user['scad_visita_medica'] . '" ' .
                        'data-user-scad-contratto="' . $user['scad_contratto'] . '" ' .
                        'data-user-scad-distacco="' . $user['scad_distacco'] . '" ' .
                        'data-user-scad-cf="' . $user['scad_cf'] . '" ' .
                        'data-user-doc="' . $user['doc'] . '" ' .
                        'data-user-scad-doc="' . $user['scad_doc'] . '" ' .
                        'data-user-email="' . $user['email'] . '" ' .
                        'data-user-cf="' . $user['codice_fiscale'] . '">'
                        . $user['cognome'] . ' ' . $user['nome'] . '</td>
                                <td>' . $user['data_assunzione'] . '</td>
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
<!--FINE CONTENUTO PRINCIPALE-->

<script type="text/javascript">
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
</script>
