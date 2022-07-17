<?php

if(isset($_POST['edit-dipendente'])) {
    $user = new InfoHR();
    $editUser = $user->editEmployeeHR($_POST['userId'], $_POST['userName'], $_POST['userSurname'],
            $_POST['userOreGiorno'], $_POST['userGiorniSettimana'], $_POST['userScadVisitaMedica'],$_POST['userDataAssunzione'], $_POST['userScadContratto']);

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

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="calendar-heading">
			<div style="padding-left:2%;padding-right:2%;">
				<span class="legenda-title">Lista Dipendenti</span>
            </div>
			</div>
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th width="30%"><input type="text" class="form-control" placeholder="Dipendente"></th>
                        <th width="15%"><input type="text" class="form-control" placeholder="Ore Giornaliere"></th>
                        <th width="15%"><input type="text" class="form-control" placeholder="Giorni Settimanali"></th>
                        <th width="23%"><input type="text" class="form-control" placeholder="Elenco Compilazioni" disabled></th>
                        <th width="17%"><input type="text" class="form-control" placeholder="Presenza Completata" disabled></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    $stileLabel = array(
                        "Presente" => array(
                            "colore" => "success",
                            "testo" => "P"
                        ),
                        "Telelavoro" => array(
                            "colore" => "marrone",
                            "testo" => "TL"
                        ),
                        "Permesso" => array(
                            "colore" => "info",
                            "testo" => "Pr"
                        ),
                        "Permesso 104" => array(
                            "colore" => "104",
                            "testo" => "104"
                        ),
                        "Ferie" => array(
                            "colore" => "grigio",
                            "testo" => "F"
                        ),
                        "Malattia" => array(
                            "colore" => "danger",
                            "testo" => "M"
                        ),
                        "Maternità" => array(
                            "colore" => "warning",
                            "testo" => "Ma"
                        ),
                        "Straordinario" => array(
                            "colore" => "primary",
                            "testo" => "S"
                        ),
                        "Permesso Studio" => array(
                            "colore" => "arancio",
                            "testo" => "PS"
                        ),
                        "Congedo Parentale" => array(
                            "colore" => "verde",
                            "testo" => "CP"
                        ),
                        "Lutto" => array(
                            "colore" => "nero",
                            "testo" => "L"
                        ),
                        "Recupero" => array(
                            "colore" => "marrone",
                            "testo" => "R"
                        ),
                        "Donazione Sangue" => array(
                            "colore" => "verde-scuro",
                            "testo" => "DS"
                        ),
                        "Malattia Bambino" => array(
                            "colore" => "blu-scuro",
                            "testo" => "MB"
                        ),
                        "Congedo Straordinario" => array(
                            "colore" => "rosso",
                            "testo" => "CS"
                        ),
                        "Congedo Matrimoniale" => array(
                            "colore" => "viola",
                            "testo" => "CM"
                        ),
                        "Cure Termali" => array(
                            "colore" => "giallo",
                            "testo" => "CT"
                        ),
                        "Reperibilità" => array(
                            "colore" => "verde-chiaro",
                            "testo" => "Rp"
                        ),
                    );

                    $attivitaClass = new Attivita();
                    $attivitaList = $attivitaClass->getTodayPresences();

                    foreach ($attivitaList as $user) {
                        $presenzaText = (count($user['presenze']) > 0) ? '' : '-';
                        $presenzaSommaOre = 0;
                        foreach ($user['presenze'] as $presenza) {
                            $presenzaSommaOre += $presenza['ore'];
                            $presenzaText .= "<button type='button' class='btn btn-sm btn-" . $stileLabel[$presenza['tipo']]['colore'] .
                                "' style='margin-right: 5px;'>" . $stileLabel[$presenza['tipo']]['testo'] . " - " . $presenza['ore'] . "</button>";
                        }

                        echo '<tr>
                                <td style="vertical-align: middle;"><button type="button" class="btn-link" style="outline:none;" ' .
                        'data-toggle="modal" data-target="#editUserModal"' .
                        'data-user-id="' . $user['user_id'] . '" ' .
                        'data-user-name="' . $user['nome'] . '" ' .
                        'data-user-surname="' . $user['cognome'] . '" ' .
                        'data-user-ore-giorno="' . $user['ore_giorno'] . '" ' .
                        'data-user-giorni-settimana="' . $user['giorni_settimana'] . '" ' .
                        'data-user-scad-visita-medica="' . $user['scad_visita_medica'] . '" ' .
						'data-user-data-assunzione="' . $user['data_assunzione'] . '" ' .
                        'data-user-scad-contratto="' . $user['scad_contratto'] . '">' . $user['cognome'] . ' ' . $user['nome'] . '</td>
                                <td style="vertical-align: middle;">' . $user['ore_giorno'] . '</td>
                                <td style="vertical-align: middle;">' . $user['giorni_settimana'] . '</td>
                                <td style="vertical-align: middle;">' . $presenzaText . '</td>
                                <td style="vertical-align: middle;">' . ( ($user['ore_giorno'] <= $presenzaSommaOre) ? "<span style='color: green;' class='fa fa-check fa-2x'></span>"
                                    : "<span style='color: red;' class='fa fa-times fa-2x'></span>") . '</td>
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
                                <label for="userOreGiorno">Ore:</label>
                                <input type="text" id="userOreGiorno" class="form-control"
                                       name="userOreGiorno" value="">
                            </div>
                            <div class="form-group">
                                <label for="userGiorniSettimana">Giorni:</label>
                                <input type="text" id="userGiorniSettimana" class="form-control"
                                       name="userGiorniSettimana" value="">
                            </div>

                            <div class="form-group">
                                <label for="userScadVisitaMedica">Data Scadenza Visita Medica:</label>
                                <input type="text" id="userScadVisitaMedica" class="form-control"
                                       name="userScadVisitaMedica" value="">
                            </div>
							<div class="form-group">
                                <label for="userDataAssunzione">Data Assunzione:</label>
                                <input type="text" id="userDataAssunzione" class="form-control"
                                       name="userDataAssunzione" value="">
                            </div>
							<div class="form-group">
                                <label for="userScadContratto">Scadenza Contratto:</label>
                                <input type="text" id="userScadContratto" class="form-control"
                                       name="userScadContratto" value="">
                            </div>
                            <input type="hidden" id="userId" name="userId">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success" name="edit-dipendente" >
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

<!--FINE CONTENUTO PRINCIPALE-->

<script type="text/javascript">
$(document).ready(function () {
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var userName = button.data('user-name');
        var userSurname = button.data('user-surname');
        var userOreGiorno = button.data('user-ore-giorno');
        var userGiorniSettimana = button.data('user-giorni-settimana');
        var userScadVisitaMedica = button.data('user-scad-visita-medica');
        var userScadContratto = button.data('user-scad-contratto');
		var userDataAssunzione = button.data('user-data-assunzione');


        $("#userId").attr('value', userId);
        $("#userName").attr('value', userName);
        $("#userSurname").attr('value', userSurname);
        $("#userOreGiorno").attr('value', userOreGiorno);
        $("#userGiorniSettimana").attr('value', userGiorniSettimana);
        $("#userScadVisitaMedica").attr('value', userScadVisitaMedica);
        $("#userScadContratto").attr('value', userScadContratto);
		$("#userDataAssunzione").attr('value', userDataAssunzione);
    });
});
</script>
