<?php

if(isset($_POST['add-richiesta'])) {

    $position = new Richiesta();

    $editPosition = $position->addRichiesta($_POST['richiestaDescrizione'], $_POST['richiestaCliente'], $_POST['richiestaDa'],$_POST['richiestaEsito'], $_POST['richiestaStart'], $_POST['richiestaEnd'],$_POST['richiestaCitta'], $_POST['richiestaRevenue'], $_POST['richiestaNote']);
    consoleLog($editPosition);
    if($editPosition) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Richiesta Inserita con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Inserimento richiesta non completata.
            </div>';
    }
}


if(isset($_POST['del-richiesta'])) {
    consoleLog($message);
    $position = new Richiesta();
    $editPosition = $position->delRichiesta($_POST['richiestaId']);

    if($editPosition) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Richiesta eliminata con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Cancellazione richiesta non completata.
            </div>';
    }
}

if(isset($_POST['edit-richiesta'])) {
    consoleLog($message);
    $position = new Richiesta();
    $editPosition = $position->editRichiesta($_POST['richiestaId'],$_POST['richiestaDescrizione'], $_POST['richiestaCliente'], $_POST['richiestaDa'],$_POST['richiestaEsito'], $_POST['richiestaStart'], $_POST['richiestaEnd'],$_POST['richiestaCitta'], $_POST['richiestaRevenue'], $_POST['richiestaNote']);

    if($editPosition) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Richiesta modificata con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Modifica richiesta non completata.
            </div>';
    }
}
?>
<section class="">
<div class="panel panel-primary" style="margin-bottom:5%;">
        <div class="calendar-heading">
  <div style="padding-left:1%;padding-right:2%;">
  <span class="legenda-title">Nuova Richiesta</span>
  <a href="#">
  <span class="legenda-title">
  <i class="fa fa-chevron-circle-down fa-2x" style="float:right;margin-top:-0.5%;" onclick="comparsa('body_panel');"></i>
  </span>
  </a>
  </div>
  </div>

<div class="panel-body" <?php if(!isset($_REQUEST['add_richiesta'])) { ?> style="display:none;" <?php } ?> id="body_panel">
<form action="" method="post">
  <table class="table table-striped table-advance table-hover" style="margin-top:1%;">
     <tbody>
        <tr>
           <td class="td-intestazione">DESCRIZIONE*</td>
           <td><textarea style="width:50%;" class="form-control" name="richiestaDescrizione" value="" rows="5"></textarea></td>
        </tr>
        <tr>
           <td class="td-intestazione">CLIENTE*</td>
           <td><input style="width:50%;" class="form-control" type="text" name="richiestaCliente" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">DA*</td>
           <td><input style="width:50%;" class="form-control" type="text" name="richiestaDa" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">ESITO</td>
           <td><input style="width:50%;" class="form-control" type="text" name="richiestaEsito" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">CITTA'</td>
           <td><input style="width:50%;" class="form-control" type="text" name="richiestaCitta" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">START</td>
           <td><input style="width:50%;" class="form-control" type="text" name="richiestaStart" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">END</td>
           <td><input style="width:50%;" class="form-control" type="text" name="richiestaEnd" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">REVENUE</td>
           <td><input style="width:50%;" class="form-control" type="text" name="richiestaRevenue" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">NOTE</td>
           <td><textarea style="width:50%;" class="form-control" name="richiestaNote" value="" rows="3"></textarea></td>
        </tr>
</form>
           </td>
   </tr>

        <!-- Pulsante Modifica -->
        <tr>
          <td colspan="2" style="text-align:left;">
          <button type="submit" name="add-richiesta" class="btn btn-success btn-sm">
          <i class="fa fa-plus"></i>&nbsp;Aggiungi Richiesta
          </button>
          </td>
          </tr>
     </tbody>
  </table>
</form>
</div>
</div>
<section class="">

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="calendar-heading">
			<div style="padding-left:2%;padding-right:2%;">
				<span class="legenda-title">Lista Richieste</span>
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
                        <th><input type="text" class="form-control" placeholder="Descrizione"></th>
                        <th><input type="text" class="form-control" placeholder="Da"></th>
                        <th><input type="text" class="form-control" placeholder="Cliente"></th>
                        <th><input type="text" class="form-control" placeholder="Esito"></th>
                        <th><input type="text" class="form-control" placeholder="Città"></th>
                        <th><input type="text" class="form-control" placeholder="Start"></th>
                        <th><input type="text" class="form-control" placeholder="End"></th>
                        <th><input type="text" class="form-control" placeholder="Revenue"></th>
                        <th><input type="text" class="form-control" placeholder="Note"></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    $infoHRClass = new Richiesta();
                    $infoList = $infoHRClass->getAll();

                    foreach($infoList as $user) {
                        echo '<tr>
                                <td><button type="button" class="btn-link" style="outline:none;" ' .
                        'data-toggle="modal" data-target="#editUserModal"' .
                        'data-richiesta-id="' . $user['id_richiesta'] . '" ' .
                        'data-richiesta-descrizione="' . $user['descrizione'] . '" ' .
                        'data-richiesta-cliente="' . $user['cliente'] . '" ' .
                        'data-richiesta-da="' . $user['da'] . '" ' .
                        'data-richiesta-esito="' . $user['esito'] . '" ' .
                        'data-richiesta-citta="' . $user['citta'] . '" ' .
                        'data-richiesta-start="' . $user['start'] . '" ' .
                        'data-richiesta-end="' . $user['end'] . '" ' .
                        'data-richiesta-revenue="' . $user['revenue'] . '" ' .
                        'data-richiesta-note="' . $user['note'] . '" ' .'>' . $user['descrizione'] . '</td>
                                <td>' . $user['da'] . '</td>
                                <td>' . $user['cliente'] . '</td>
                                <td>' . $user['esito'] . '</td>
                                <td>' . $user['citta'] . '</td>
                                <td>' . $user['start'] . '</td>
                                <td>' . $user['end'] . '</td>
                                <td>' . $user['revenue'] . '</td>
                                <td>' . $user['note'] . '</td>
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
                <center><h4 class="modal-title" id="editUserModalLabel">MODIFICA POSIZIONE</h4></center>
            </div>

            <form method="POST" action="">

                    <div class="modal-body col-md-8 col-md-offset-2">
                        <div class="row">
                            <div class="form-group">
                                <label for="userName">Descrizioe:</label>
                                <input type="text" id="richiestaDescrizione" class="form-control"
                                       name="richiestaDescrizione" value="">
                            </div>
                            <div class="form-group">
                                <label for="userSurname">Cliente:</label>
                                <input type="text" id="richiestaCliente" class="form-control"
                                       name="richiestaCliente" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">Da:</label>
                                <input type="text" id="richiestaDa" class="form-control"
                                       name="richiestaDa" value="">
                            </div>
                            <div class="form-group">
                                <label for="userSurname">Esito:</label>
                                <input type="text" id="richiestaEsito" class="form-control"
                                       name="richiestaEsito" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">Città:</label>
                                <input type="text" id="richiestaCitta" class="form-control"
                                       name="richiestaCitta" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">Start:</label>
                                <input type="text" id="richiestaStart" class="form-control"
                                       name="richiestaStart" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">End:</label>
                                <input type="text" id="richiestaEnd" class="form-control"
                                       name="richiestaEnd" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">Revenue:</label>
                                <input type="text" id="richiestaRevenue" class="form-control"
                                       name="richiestaRevenue" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">Note:</label>
                                <input type="text" id="richiestaNote" class="form-control"
                                       name="richiestaNote" value="">
                            </div>
                            <input type="hidden" id="richiestaId" name="richiestaId">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success" name="edit-richiesta" >
                                <span class="glyphicon glyphicon-pencil"></span> Modifica
                            </button>
                            <button type="submit" class="btn btn-danger" name="del-richiesta" >
                                <span class="glyphicon glyphicon-pencil"></span> Elimina
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

<!--FINE CONTENUTO PRINCIPALE-->
<script type="text/javascript">
function comparsa(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
<script type="text/javascript">
$(document).ready(function () {
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var richiestaId = button.data('richiesta-id');
        var richiestaDescrizione = button.data('richiesta-descrizione');
        var richiestaCliente = button.data('richiesta-cliente');
        var richiestaDa = button.data('richiesta-da');
        var richiestaEsito = button.data('richiesta-esito');
        var richiestaStart = button.data('richiesta-start');
        var richiestaEnd = button.data('richiesta-end');
        var richiestaCitta = button.data('richiesta-citta');
        var richiestaRevenue = button.data('richiesta-revenue');
        var richiestaNote = button.data('richiesta-note');



        $("#richiestaId").attr('value', richiestaId);
        $("#richiestaDescrizione").attr('value', richiestaDescrizione);
        $("#richiestaCliente").attr('value', richiestaCliente);
        $("#richiestaDa").attr('value', richiestaDa);
        $("#richiestaStart").attr('value', richiestaStart);
        $("#richiestaEnd").attr('value', richiestaEnd);
        $("#richiestaCitta").attr('value', richiestaCitta);
        $("#richiestaRevenue").attr('value', richiestaRevenue);
        $("#richiestaNote").attr('value', richiestaNote);

    });
});
</script>
