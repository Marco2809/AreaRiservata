<?php

require_once('class/anagrafica.class.php');
require_once('class/info_hr.class.php');

$code = 0;
$arrayNotFound = array();
$idMese = date('m');

if(isset($_POST['upload-costi'])) {
    
    $code = 1;
    $costiFile = $_FILES['costiAziendali']['tmp_name'];
    $costiFileContent = file_get_contents($costiFile);
    $costiArray = explode("\n", $costiFileContent);

    foreach($costiArray as $costo) {
        list($cf, $costoValue) = explode(";", $costo);
        $currentUser = new Anagrafica();
        $currentUser->getAnagraficaFromCF($cf);
        $userId = $currentUser->getIdAnagrafica();
        if($userId != 0) {
            $infoHRClass = new InfoHR();
            $infoHRClass->saveEmployeeCostiHR($currentUser->getIdAnagrafica(), $_POST['month-costi'], 
                    $_POST['year-costi'], $costoValue);
        } else {
            array_push($arrayNotFound, $cf);
        }
    }
    
}

?>

<section class="">

<?php
        if($code == 1 && count($arrayNotFound) == 0) {
               echo '<div class="alert alert-success">
                            <strong>Caricamento completato!</strong> Il caricamento dei costi è stato effettuato con successo.
                        </div>';
        } else if($code == 1 && count($arrayNotFound) > 0) {
               echo '<div class="alert alert-warning">
                            <strong>Attenzione!</strong> Il caricamento dei costi è stato effettuato ma non sono stati trovati gli utenti relativi ai seguenti codici fiscali:<br>';
                       foreach($arrayNotFound as $cf) {
                             echo $cf . '<br>';
                       }
                       echo '</div>';
        }
?>

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="legenda-heading">
                <div style="padding-left:2%;padding-right:2%;">
                    <span class="legenda-title">Upload File Costi Aziendali Mensili (.csv)</span>
                </div>
            </div>
            <div class="form-group" style="padding:10px;">
                <center>
                    <form method="POST" enctype="multipart/form-data">
                        <div style="margin:20px 0 20px 0;">
                            <input type="file" class="form-control-file" id="costiAziendali" 
                                   name="costiAziendali" aria-describedby="fileHelp" required>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group">
                                    <label for="month">Mese:</label>
                                    <select class="form-control" id="month" name="month-costi">
                                        <option value="01"<?php if($idMese == '02') {echo ' selected';}?>>Gennaio</option>
                                        <option value="02"<?php if($idMese == '03') {echo ' selected';}?>>Febbraio</option>
                                        <option value="03"<?php if($idMese == '04') {echo ' selected';}?>>Marzo</option>
                                        <option value="04"<?php if($idMese == '05') {echo ' selected';}?>>Aprile</option>
                                        <option value="05"<?php if($idMese == '06') {echo ' selected';}?>>Maggio</option>
                                        <option value="06"<?php if($idMese == '07') {echo ' selected';}?>>Giugno</option>
                                        <option value="07"<?php if($idMese == '08') {echo ' selected';}?>>Luglio</option>
                                        <option value="08"<?php if($idMese == '09') {echo ' selected';}?>>Agosto</option>
                                        <option value="09"<?php if($idMese == '10') {echo ' selected';}?>>Settembre</option>
                                        <option value="10"<?php if($idMese == '11') {echo ' selected';}?>>Ottobre</option>
                                        <option value="11"<?php if($idMese == '12') {echo ' selected';}?>>Novembre</option>
                                        <option value="12"<?php if($idMese == '01') {echo ' selected';}?>>Dicembre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year">Anno:</label>
                                    <select class="form-control" id="year" name="year-costi">
                                        <option>2017</option>
                                        <option>2018</option>
                                        <option>2019</option>
                                        <option>2020</option>
                                        <option>2021</option>
                                        <option>2022</option>
                                        <option>2023</option>
                                        <option>2024</option>
                                        <option>2025</option>
                                        <option>2026</option>
                                        <option>2027</option>
                                        <option>2028</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" name="upload-costi">
                            <span class="glyphicon glyphicon-open"></span> Carica File
                        </button>
                    </form>
                </center>
            </div>
        </div>
    </div>
</div>
</section>