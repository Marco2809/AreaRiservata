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
                        <th style="width: 50%"><input type="text" class="form-control" placeholder="Dipendente" disabled></th>
                        <th style="width: 30%"><input type="text" class="form-control" placeholder="Data di Nascita" disabled></th>
                        <th style="width: 20%"><input type="text" class="form-control" placeholder="CV" disabled></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    $userClass = new User();
                    $userList = $userClass->getAllActive();

                    foreach($userList as $user) {
                        echo '<tr>
                                <td><a href="responsabile.php?action=dettaglio&id=' . $user['user_id'] . '">'
                                    . $user['cognome'] . ' ' . $user['nome'] . '</a></td>
                                <td>' . $user['data_nascita'] . '</td>
                                <td>
                                    <a target="_blank" href="tcpdf/examples/PDF_create.php?id=' .
                                        $user["user_id"] . '">
                                        <i class="fa fa-file-pdf-o fa-2x" style="color:#fd6c6e;" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>';
                    }
?>

                </tbody>
            </table>
        </div>
    </div>
	</div>
</section>
