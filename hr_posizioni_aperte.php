<?php

if(isset($_POST['add-position'])) {
    $position = new InfoHR();
    $editPosition = $position->addPositionHR($_POST['positionOggetto'], $_POST['positionDetail'], $_POST['positionLuogo']);

    if($editPosition) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Posizione Inserita con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Inserimento posizione non completata.
            </div>';
    }
}

if(isset($_POST['edit-position'])) {
    $position = new InfoHR();
    $editPosition = $position->editPositionHR($_POST['positionId'], $_POST['positionOggetto'], $_POST['positionDetail'], $_POST['positionLuogo']);

    if($editPosition) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Posizione modificata con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Modifica posizione non completata.
            </div>';
    }
}

if(isset($_POST['del-position'])) {
    $position = new InfoHR();
    $editPosition = $position->delPositionHR($_POST['positionId']);

    if($editPosition) {
        echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
                <strong>Ben fatto!</strong> Posizione eliminata con successo.
            </div>';
    } else {
        echo '<div style="margin-top:10px;" class="alert alert-danger" role="alert">
                <strong>Attenzione!</strong> Cancellazione posizione non completata.
            </div>';
    }
}
?>
<section class="">
<div class="panel panel-primary" style="margin-bottom:5%;">
        <div class="calendar-heading">
  <div style="padding-left:1%;padding-right:2%;">
  <span class="legenda-title">Nuova Posizione</span>
  <a href="#">
  <span class="legenda-title">
  <i class="fa fa-chevron-circle-down fa-2x" style="float:right;margin-top:-0.5%;" onclick="comparsa('body_panel');"></i>
  </span>
  </a>
  </div>
  </div>

<div class="panel-body" <?php if(!isset($_REQUEST['add_position'])) { ?> style="display:none;" <?php } ?> id="body_panel">
<form action="" method="post">
  <table class="table table-striped table-advance table-hover" style="margin-top:1%;">
     <tbody>
        <tr>
           <td class="td-intestazione">POSIZIONE*</td>
           <td><input style="width:50%;" class="form-control" type="text" name="positionOggetto" value=""></td>
        </tr>
        <tr>
           <td class="td-intestazione">DETTAGLI TEST*</td>
           <td><textarea id="positionDetail" name="positionDetail"></textarea></td>
        </tr>
        <tr>
           <td class="td-intestazione">LUOGO*</td>
           <td><input style="width:50%;" class="form-control" type="text" name="positionLuogo" value=""></td>
        </tr>
</form>
           </td>
   </tr>

        <!-- Pulsante Modifica -->
        <tr>
          <td colspan="2" style="text-align:left;">
          <button type="submit" name="add-position" class="btn btn-success btn-sm">
          <i class="fa fa-plus"></i>&nbsp;Aggiungi Posizione
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
				<span class="legenda-title">Lista Posizioni</span>
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
                        <th style="width: 20%;"><input type="text" class="form-control" placeholder="Posizione"></th>
                        <th style="width: 65%;"><input type="text" class="form-control" placeholder="Dettagli"></th>
                        <th style="width: 15%;"><input type="text" class="form-control" placeholder="Luoghi"></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    $infoHRClass = new InfoHR();
                    $infoList = $infoHRClass->getAllPosition();

                    foreach($infoList as $user) {
                        echo '<tr>
                                <td><button type="button" class="btn-link" style="outline:none;" ' .
                        'data-toggle="modal" data-target="#editUserModal"' .
                        'data-position-id="' . $user['positionId'] . '" ' .
                        'data-position-oggetto="' . $user['positionOggetto'] . '" ' .
                        'data-position-detail="' . $user['positionDetail'] . '" ' .
                        'data-position-luogo="' . $user['positionLuogo'] . '" ' .'>' . $user['positionOggetto'] . '</td>
                                <td>' . $user['positionDetail'] . '</td>
                                <td>' . $user['positionLuogo'] . '</td>
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
                                <label for="userName">Posizione:</label>
                                <input type="text" id="positionOggetto" class="form-control"
                                       name="positionOggetto" value="">
                            </div>
                            <div class="form-group">
                                <label for="userSurname">Dettagli:</label>
                                <input type="text" id="positionDetail" class="form-control"
                                       name="positionDetail" value="">
                            </div>
                            <div class="form-group">
                                <label for="userOreGiorno">Luogo:</label>
                                <input type="text" id="positionLuogo" class="form-control"
                                       name="positionLuogo" value="">
                            </div>

                            <input type="hidden" id="positionId" name="positionId">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success" name="edit-position" >
                                <span class="glyphicon glyphicon-pencil"></span> Modifica
                            </button>
                            <button type="submit" class="btn btn-danger" name="del-position" >
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
<script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#positionDetail' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', '|', 'link', 'bulletedList', 'numberedList' ],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragrafo', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Titolo 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Titolo 2', class: 'ck-heading_heading2' }
            ]
        }
    } )
        .catch( error => {
            console.error( error );
        } );
</script>
<script type="text/javascript">
function comparsa(a) {if (document.getElementById(a).style.display=="none"){ document.getElementById(a).style.display="";} else {document.getElementById(a).style.display="none";} }
</script>
<script type="text/javascript">
$(document).ready(function () {
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var positionId = button.data('position-id');
        var positionOggetto = button.data('position-oggetto');
        var positionDetail = button.data('position-detail');
        var positionLuogo = button.data('position-luogo');


        $("#positionId").attr('value', positionId);
        $("#positionOggetto").attr('value', positionOggetto);
        $("#positionLuogo").attr('value', positionLuogo);
        $("#positionDetail").attr('value', positionDetail);

    });
});
</script>
