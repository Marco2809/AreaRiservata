<?php
require_once('./assets/php/functions.php');
/*
echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

echo ini_get('display_errors');

*/

if(isset($_POST['edit-project'])) {
    consoleLog($message);
    $user = new User();
    $editUser = $user->editEmployee($_POST['userId'], $_POST['userName'], $_POST['userSurname'],
            $_POST['userBirthDate'], $_POST['userCF'], $_POST['userProfile'], $_POST['userEmail']);
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

if(isset($_REQUEST['del-project'])){

$user = new User();
$del_user = $user->delUser($_REQUEST['del-dipendente']);
echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                            <strong>Ben fatto!</strong> Utente eliminato con successo.
                        </div>';
}


if(isset($_REQUEST['add_project'])){

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


$anagrafica = new Anagrafica();
$insert_anagrafica = $anagrafica->setAnagraficaAdmin($id_user,$_REQUEST['firstname'],$_REQUEST['lastname'],$_REQUEST['date_of_birthday'],$_REQUEST['codice_fiscale']);


$group = new Gruppo();
$insert_group = $group -> createUserGruppi($id_user,$_REQUEST['gruppi']);

$commessa=new Commessa();
$insert_commessa= $commessa->insertCommessaFromTemp($id_user,$_SESSION['user_idd']);

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
			<span class="legenda-title">Nuovo Progetto</span>
			<a href="#">
			<span class="legenda-title">
			<i class="fa fa-chevron-circle-down fa-2x" style="float:right;margin-top:-0.5%;" onclick="comparsa('body_panel');"></i>
			</span>
			</a>
			</div>
			</div>

 <div class="panel-body" <?php if(!isset($_REQUEST['add_project'])&&!isset($_REQUEST['del_project'])) { ?> style="display:none;" <?php } ?> id="body_panel">
   <form action="" method="post">
      <table class="table table-striped table-advance table-hover" style="margin-top:1%;">
         <tbody>
            <tr>
               <td class="td-intestazione">TITOLO*</td>
               <td><input style="width:50%;" class="form-control" type="text" name="projectTitle" value=""></td>
            </tr>
            <tr>
               <td class="td-intestazione">DESCRIZIONE*</td>
               <td><input style="width:50%;" class="form-control" type="text" name="projectDescription" value=""></td>
            </tr>
            <tr>
               <td class="td-intestazione">DATA DI INIZIO</td>
               <td>
                  <div style="width:50%;" id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                     <input class="form-control" id="datetimepicker" name="projectDateBegin" placeholder="gg/mm/aaaa" type="text" value="">
                     <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                  </div>
               </td>
            </tr>
            <tr>
               <td class="td-intestazione">DATA DI FINE</td>
               <td>
                  <div style="width:50%;" id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                     <input class="form-control" id="datetimepicker" name="projectDateEnd" placeholder="gg/mm/aaaa" type="text" value="">
                     <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                  </div>
               </td>

            </tr>
            <!-- Pulsante Modifica -->
            <tr>
              <td colspan="2" style="text-align:left;">
              <button type="submit" name="add_user" class="btn btn-success btn-sm">
              <i class="fa fa-plus"></i>&nbsp;Aggiungi Progetto
              </button>
              </td>
              </tr>
         </tbody>
      </table>
   </form>
   </div>
</div>

<!--  LISTA DIPENDENTI  -->

<div class="row" style="margin-bottom:5%;">
<div class="col-md-12">
<div class="panel panel-primary filterable" style="margin-bottom:5%;">
  <div class="legenda-heading">
              <div style="padding-left:2%;padding-right:2%;">
                      <span class="legenda-title">Lista Progetti</span>
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
              <th><input type="text" class="form-control" placeholder="Id."></th>
              <th><input type="text" class="form-control" placeholder="Progetto"></th>
              <th><input type="text" class="form-control" placeholder="Descrizione"></th>
              <th><input type="text" class="form-control" placeholder="Azioni"></th>
          </tr>
      </thead>
      <tbody>

<?php

//<a href="amministrazione.php?action=edit&id='.$dipendente['user_id'].'"><i class="fa fa-pencil fa-2x" style="color:#77c803;"></i></a>
            $user = new Project();
            $dipendenti = $user->getAllProjects();
            foreach($dipendenti as $dipendente) {
                echo '<tr><form action="" method="POST">' .
                     '<td>' . $dipendente['id'] . '</td>' .
                     '<td><button type="button" class="btn-link" style="outline:none;" ' .
                        'data-toggle="modal" data-target="#editProjectModal"' .
                        'data-project-id="' . $dipendente['id'] . '" ' .
                        'data-project-title="' . $dipendente['titolo'] . '" ' .
                        'data-project-description="' . $dipendente['descrizione'] . '" ' .
                        'data-project-date-begin="' . $dipendente['data_inizio'] . '" ' .
                        'data-project-date-end="' . $dipendente['data_fine'] . '">' .
                        $dipendente['titolo'] .'</button></td>' .
                     '<td>' . $dipendente['descrizione'] . '</td>' .
                     '<td>
<button  name="del-project" type="submit" value="' . $dipendente['id'] . '"'
                        . 'style="background-color:transparent; border:none; outline:none;">' .
                     '<i class="fa fa-times fa-2x" style="color:#fd6c6e;"></i></button></td>' .
                     '</form></tr>';
            }
?>
      </tbody>
  </table>
</div>
</div>

<!-- EDIT USER MODAL -->

<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog"
     aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <center><h4 class="modal-title" id="editProjectModalLabel">MODIFICA PROGETTO</h4></center>
            </div>

            <form method="POST" action="">

                    <div class="modal-body col-md-8 col-md-offset-2">
                        <div class="row">
                            <div class="form-group">
                                <label for="projectTitle">Titolo:</label>
                                <input type="text" id="projectTitle" class="form-control"
                                       name="projectTitle" value="">
                            </div>
                            <div class="form-group">
                                <label for="projectDescription">Descrizione:</label>
                                <input type="text" id="projectDescription" class="form-control"
                                       name="projectDescription" value="">
                            </div>
                            <div class="form-group">
                                <label for="projectDateBegin">Data di Inizio:</label>
                                <input type="text" id="projectDateBegin" class="form-control"
                                       name="projectDateBegin" value="">
                            </div>
                            <div class="form-group">
                                <label for="projectDateEnd">Data di Fine:</label>
                                <input type="text" id="projectDateEnd" class="form-control"
                                       name="projectDateEnd" value="">
                            </div>

                            <input type="hidden" id="projectId" name="projectId">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success" name="edit-project" >
                                <span class="glyphicon glyphicon-pencil"></span> Modifica
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <span class="glyphicon glyphicon-menu-left"></span> Indietro
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

</section>
<!--FINE CONTENUTO PRINCIPALE-->
<script type="text/javascript">
function comparsa(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
<script type="text/javascript">
$(document).ready(function () {
    $('#editProjectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var projectId = button.data('project-id');
        var projectTitle = button.data('project-title');
        var projectDescription = button.data('project-description');
        var projectDateBegin = button.data('project-date-begin');
        var projectDateEnd = button.data('project-date-end');

        $("#projectId").attr('value', projectId);
        $("#projectTitle").attr('value', projectTitle);
        $("#projectDescription").attr('value', projectDescription);
        $("#projectDateBegin").attr('value', projectDateBegin);
        $("#projectDateEnd").attr('value', projectDateEnd);
    });
});
</script>
