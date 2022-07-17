<?php

require_once 'class/form.class.php';

if(isset($_POST['uploadModuleSubmit'])) {
    $moduleFileTmp = $_FILES['moduleToUpload']['tmp_name'];
    $moduleFileName = $_FILES['moduleToUpload']['name'];

    $filenameDest = 'files/forms/';

    if (move_uploaded_file($moduleFileTmp, $filenameDest . $moduleFileName)) {
        $newForm = new Form();
        $newForm->setTitolo($_POST['moduleTitle']);
        $newForm->setFile($moduleFileName);
        $newForm->createForm();
    }
}

if(isset($_POST['deleteModuleSubmit'])) {
    $newForm = new Form();
    $newForm->setIDModulo($_POST['deleteModuleSubmit']);
    $newForm->deleteForm();
}

?>

<section class="">

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="calendar-heading">
			<div style="padding-left:2%; padding-right:2%;">
				<span class="legenda-title">Moduli</span>
                <div class="pull-right">
                    <button type="button" class="btn btn-info btn-xs btn-filter button-clear"
                            style="color:#fff;" data-toggle="modal" data-target="#uploadModuleModal">
                        <span class="fa fa-upload fa-2x"></span>
                        <span class="span-title">&nbsp; Carica Modulo</span>
                    </button>
                </div>
            </div>
			</div>
<?php
            $formClass = new Form();
            $formList = $formClass->getAll();

            if (count($formList) > 0) {
?>
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th style="width: 80%;"><input type="text" class="form-control" placeholder="Titolo"></th>
                        <th><input type="text" class="form-control" placeholder="Azioni" disabled></th>
                    </tr>
                </thead>
                <tbody>

<?php
                    foreach($formList as $form) {
                        echo '<tr>';
                        echo '<td>' . $form['titolo'] . '</td>';
                        echo '<td><form method="POST"><a href="files/forms/' . $form['file'] . '" class="btn btn-xs btn-success" download><i class="fa fa-download"></i> Download</a>';
                        echo '<button type="submit" name="deleteModuleSubmit" value="' . $form['idModulo'] . '" class="btn btn-xs btn-danger" style="margin-left: 10px;"><i class="fa fa-remove"></i> Rimuovi</button></form></td>';
                        echo '</tr>';
                    }
?>

                </tbody>
            </table>
<?php
            } else {
                echo '<div style="height: 200px; text-align: center; padding-top: 80px;">Nessun modulo caricato.</div>';
            }
?>
        </div>
    </div>
	</div>
</section>

<!-- EDIT USER MODAL -->

<div class="modal fade" id="uploadModuleModal" tabindex="-1" role="dialog"
     aria-labelledby="uploadModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <center><h4 class="modal-title" id="uploadModuleModalLabel">CARICAMENTO NUOVO MODULO</h4></center>
            </div>

            <form method="POST" enctype="multipart/form-data">

                <div class="modal-body col-md-8 col-md-offset-2">
                    <div class="form-group">
                        <label for="moduleTitle">Titolo:</label>
                        <input type="text" id="moduleTitle" class="form-control"
                               name="moduleTitle" value="">
                    </div>
                    <div style="margin:20px 0 20px 0;">
                        <input type="file" class="form-control-file" id="moduleToUpload"
                               name="moduleToUpload" aria-describedby="fileHelp" required>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: center;">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success" name="uploadModuleSubmit">
                            <span class="glyphicon glyphicon-open"></span> Carica File
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--FINE CONTENUTO PRINCIPALE-->
