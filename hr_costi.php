<?php

if(isset($_POST['id_mese'])) {
    $id_mese = $_POST['id_mese'];
    if(substr($id_mese, 0, 1) == '0') {
        $id_mese = substr($id_mese, 1, 1);
    }
}

if(isset($_POST['id_anno'])) {
    $id_anno = $_POST['id_anno'];
}

if(isset($_POST['next'])) {
    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if($id_mese=="01") $id_mese = 1;
    if($id_mese=="02") $id_mese = 2;
    if($id_mese=="03") $id_mese = 3;
    if($id_mese=="04") $id_mese = 4;
    if($id_mese=="05") $id_mese = 5;
    if($id_mese=="06") $id_mese = 6;
    if($id_mese=="07") $id_mese = 7;
    if($id_mese=="08") $id_mese = 8;
    if($id_mese=="09") $id_mese = 9;

    if($id_mese == 12){
        $id_anno++;
        $id_mese = 1;
    }
    else {
        $id_mese= $id_mese + 1;
    }

 }

 if(isset($_POST['prev'])) {

    if(isset($_REQUEST['id_mese']) && $_REQUEST['id_mese'] != "") {
        $id_mese = $_REQUEST['id_mese'];
    }

    if(isset($_REQUEST['id_anno']) && $_REQUEST['id_anno'] != "") {
        $id_anno = $_REQUEST['id_anno'];
    }

    if($id_mese == 1) {
        $id_mese = 12;
        $id_anno = $id_anno - 1;
    } else {
        $id_mese = $id_mese - 1;
    }

 }

if(!isset($id_mese)) {
	$_POST['id_mese'] = date("m");
    $id_mese = date("m");
}

if(!isset($id_anno)) {
	$_POST['id_anno'] = date("Y");
    $id_anno = date("Y");
}

if($id_mese == 1) {
    $mese_cal="Gennaio";
} else if($id_mese == 2) {
    $mese_cal="Febbraio";
} else if($id_mese == 3) {
    $mese_cal="Marzo";
} else if($id_mese == 4) {
    $mese_cal="Aprile";
} else if($id_mese == 5) {
    $mese_cal="Maggio";
} else if($id_mese == 6) {
    $mese_cal="Giugno";
} else if($id_mese == 7) {
    $mese_cal="Luglio";
} else if($id_mese == 8) {
    $mese_cal="Agosto";
} else if($id_mese == 9) {
    $mese_cal="Settembre";
} else if($id_mese == 10) {
    $mese_cal="Ottobre";
} else if($id_mese == 11) {
    $mese_cal="Novembre";
} else if($id_mese == 12) {
    $mese_cal="Dicembre";
}


if(isset($_POST['edit-dipendente'])) {
    consoleLog($message);
    $user = new InfoHR();
    $editUser = $user->editEmployeeCostiHR($_POST['userId'],$_POST['mese'],$_POST['anno'],$_POST['userCostoMensile']);

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

if(isset($_POST['save-dipendente'])) {
    consoleLog($message);
    $user = new InfoHR();
    $editUser = $user->saveEmployeeCostiHR($_POST['userId'],$_POST['mese'],$_POST['anno'],$_POST['userCostoMensile']);

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

?>

<section class="">
    <center>
        <div class="panel panel-primary">
            <div class="calendar-heading">
                <form action="hr.php?action=costi" method="POST">
                    <button type="submit" name="prev" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-left fa-2x" aria-hidden="true"></i></button>

                    <span class="mese-title"><?php echo $mese_cal . " " . $id_anno; ?></span>

                    <button type="submit" name="next" style="background: none; border: 0;"><i class="arrowdx fa fa-chevron-right fa-2x" aria-hidden="true"></i></button>
 					<input type="hidden" name="action" value="costi">
                    <input type="hidden" name="id_mese" value="<?php echo $id_mese;?>">
                    <input type="hidden" name="id_anno" value="<?php echo $id_anno;?>">
                </form>
            </div>
        </div>
    </center>
</section>

<section class="">

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="calendar-heading">
			<div style="padding-left:2%;padding-right:2%;">
				<span class="legenda-title">Lista Dipendenti</span>
                <div class="pull-right">
                  <a target="_blank" href="https://www.service-tech.it/new_area_riservata/costi_aziendali.php?action=costi_upload"><button class="btn btn-info btn-xs btn-filter button-clear" style="color:#fff;"><span class="fa fa-upload fa-2x"></span>
         <span class="span-title">&nbsp; Upload</span>
       </button></a>

                 <button class="btn btn-info btn-xs btn-filter button-clear" style="color:#fff;"><span class="fa fa-search fa-2x"></span>
        <span class="span-title">&nbsp; Cerca</span>
        </button>
                </div>
            </div>
			</div>
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th><input type="text" class="form-control" placeholder="Dipendente"></th>
                        <th><input type="text" class="form-control" placeholder="Costo Mensile"></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    $infoHRClass = new InfoHR();
                    $infoList = $infoHRClass->getAllCostiByMonth($id_mese,$id_anno);

                    foreach($infoList as $user) {
						if($user['costo_mensile'] != "") $action = "modifica";
						else $action = "salva";

                        echo '<tr>
                                <td><button type="button" class="btn-link" style="outline:none;" ' .
                        'data-toggle="modal" data-target="#editUserModal"' .
                        'data-user-id="' . $user['user_id'] . '" ' .
                        'data-user-name="' . $user['nome'] . '" ' .
                        'data-user-surname="' . $user['cognome'] . '" ' .
							'data-user-mese="' . $id_mese . '" ' .
							'data-user-anno="' . $id_anno . '" ' .
						'data-user-action="' . $action . '" ' .
                        'data-user-costo-mensile="' . $user['costo_mensile'] . '" ' .'>' . $user['cognome'] . ' ' . $user['nome'] . '</td>
                                <td>' . $user['costo_mensile'] . '</td>
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

                    <div class="modal-body col-md-8 col-md-offset-2">
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
                                <label for="userCostoMensile">Costo Mensile:</label>
                                <input type="text" id="userCostoMensile" class="form-control"
                                       name="userCostoMensile" value="">
                            </div>
                            <input type="hidden" id="userId" name="userId">
							<input type="hidden" id="mese" name="mese" value="">
							<input type="hidden" id="anno" name="anno" value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success" id="action-dipendente" name="">
								<span class="glyphicon glyphicon-pencil"></span><span id="action-value"></span>
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
$(document).ready(function () {
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var userName = button.data('user-name');
        var userSurname = button.data('user-surname');
        var userCostoMensile = button.data('user-costo-mensile');
        var userMese = button.data('user-mese');
        var userAnno = button.data('user-anno');
		var userAction = button.data('user-action');


        $("#userId").attr('value', userId);
        $("#userName").attr('value', userName);
        $("#userSurname").attr('value', userSurname);
        $("#userCostoMensile").attr('value', userCostoMensile);
        $("#mese").attr('value', userMese);
        $("#anno").attr('value', userAnno);
		if(userAction=="modifica"){ $("#action-dipendente").attr('name', 'edit-dipendente');$("#action-value").html(' Modifica');  }
		else{ $("#action-dipendente").attr('name', 'save-dipendente'); $("#action-value").html(' Salva');}
    });
});
</script>
