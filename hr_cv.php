<?php

if (isset($_POST['delete']) && isset($_POST['delete']) == "delete") {
    $target_dir = "files/temp/" . $_SESSION['user_idd'] . "/";
    chdir($target_dir);
    unlink($_POST['nome_file']);
    unset($_POST['nome_file']);
}

if (isset($_POST["submit"]) && $_POST["submit"] == "Allega File") {
    $target_dir = "files/temp/" . $_SESSION['user_idd'] . "/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777) ;
    }

    $target_file = $target_dir . $_FILES["fileToUpload"]["name"];
    $uploadOk = 1;
    $alert = '';
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

    if ($_FILES["fileToUpload"]["size"] > 10000000) {
        $alert .= "<p align='center' style='color:red'>Spiacenti, il tuo file è troppo grande.</p>";
        $uploadOk = 0;
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" &&
            $imageFileType != "zip" && $imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx" &&
            $imageFileType != "xls" && $imageFileType != "xlsx" && $imageFileType != "rar") {
        $alert .= "<p align='center' style='color:red'>Spiacenti, il formato selezionato non può essere caricato.</p>";
        $uploadOk = 0;
    }

    if ($uploadOk != 0) {
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        $alert .= "<div class='panel panel-success'><div class='panel-heading'>File caricato con successo</div></div>";
    }

    if (isset($alert) && $alert != "") {
        echo $alert;
    }

  }

if (isset($_POST['nome'])) {
    $db = (new Database())->dbConnection();

    $query = "INSERT INTO cv_esterni (`nome`, `cognome`, `cf`, `ruolo`, `citta`, `eta`, `anno_cv`) VALUES ('" .
            trim($_POST['nome']) . "', '" . trim($_POST['cognome']) . "', '" . trim($_POST['cf']) . "', '" . trim($_POST['ruolo']) . "', '" .
                    trim($_POST['citta']) . "', " . trim($_POST['eta']) . ", " . trim($_POST['anno_cv']) . ")";
    $result = $db->query($query);
    $idCvEsterno = $db->insert_id;

    $listaCompetenze = explode("-", $_POST['competenze']);
    foreach($listaCompetenze as $competenza) {
        $competenzaDetails = explode(",", $competenza);
        $competenzaNome = trim($competenzaDetails[0]);
        $competenzaAnni = trim($competenzaDetails[1]);

        $query = "INSERT INTO cv_esterni_competenze (`id_cv_esterni`, `competenza`, `anni`) VALUES (" .
            $idCvEsterno . ", '" . $competenzaNome . "', " . $competenzaAnni . ")";
        $result = $db->query($query);

        $directory = "files/temp/" . $_SESSION['user_idd'] . "/";
        if (is_dir($directory)) {
            if ($directory_handle = opendir($directory)) {
                while (($file = readdir($directory_handle)) !== false) {
                    if ((!is_dir($file)) && ($file != ".") && ($file != "..")) {
                        if ($file != ".DS_Store") {
                            $originale = "files/temp/" . $_SESSION['user_idd'] . "/" . $file;
                            if (!file_exists("files/cv/" . $idCvEsterno)) {
                                mkdir("files/cv/" . $idCvEsterno, 0777);
                            }
                            $copia = "files/cv/" . $idCvEsterno . "/" . $file;
                            copy($originale, $copia);
                            $estensione = explode(".", $file);
                            unlink("files/temp/" . $_SESSION['user_idd'] . "/" . $file);

                        }
                    }
                }
                closedir($directory_handle);
            }
        }
    }

    echo '<div style="margin-top:10px;" class="alert alert-success" role="alert">
              <strong>Ben fatto!</strong> Il profilo è stato inserito con successo.
            </div>';
}

?>

<!--CONTENUTO PRINCIPALE-->
<section class="">
    <div id="dati-personali" class="col-lg-12 ds" class="left" style="overflow-y: scroll;
            height: 100%; margin-bottom: 70px;">
        <h3>INSERIMENTO CV ESTERNO</h3>
  <form role="form" action="" method="post" enctype="multipart/form-data">
        <div class="form-group" style="margin-top:20px; margin-bottom: 20px;">
            <span style="font-weight:600;">Allegati:</span>
            <br />
            <div class="col-md-9">
                <input type="file"   name="fileToUpload" class="form-control" id="fileToUpload">
                <input type="hidden" name="id_user" value="<?php echo $cod_anagr;?>">
            </div>
            <div class="col-md-3">
                <input type="submit" class="btn btn-primary" title="Allega File" value="Allega File" name="submit" class="myButton" style="height: 25px;border:1px solid #000;font-size:12px;line-height:10px;">
            </div>
            <div class="row">
                <div class="col-md-12">
<?php
                    $directory = "./files/temp/" . $_SESSION['user_idd'];
                    if (is_dir($directory)) {
                        if ($directory_handle = opendir($directory)) {
                            while (($file = readdir($directory_handle)) !== false) {
                                if ( (!is_dir($file)) && ($file != ".") && ($file != "..") )
                                    if ($file != ".DS_Store" && $file != "." && $file != "..") {
                                        echo $file . "<button style='margin-left:1%; border:none; padding:0; background-color:transparent;' type='submit' name='delete'>
                                                        <span style='color:red;' class='glyphicon glyphicon-remove'></span>
                                                    </button>
                                                    <input type='hidden' name='nome_file' value='" . $file . "'><br/>";
                                    }
                            }
                            closedir ($directory_handle);
                        }
                    }
?>
                </div>
            </div>
        </div>
        </form>
        <form action="" method="POST">
            <table class="table table-striped table-advance table-hover" style="margin-top:1%;">
                <tbody>
                <tr>
                    <td class="td-intestazione">Nome</td>
                    <td>
                        <input style="width:25%;" class="form-control" type="text" name="nome" id="nome" autocomplete="off" required>
                    </td>
                </tr>
                <tr>
                    <td class="td-intestazione">Cognome</td>
                    <td>
                        <input style="width:25%;" class="form-control" type="text" name="cognome" id="cognome" autocomplete="off" required>
                    </td>
                </tr>
                <tr>
                    <td class="td-intestazione">Codice Fiscale</td>
                    <td>
                        <input style="width:25%;" class="form-control" type="text" name="cf" id="cf" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td class="td-intestazione">Ruolo</td>
                    <td>
                        <input style="width:25%;" class="form-control" type="text" name="ruolo" id="ruolo" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td class="td-intestazione">Città</td>
                    <td>
                        <input style="width:25%;" class="form-control" type="text" name="citta" id="citta" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td class="td-intestazione">Età</td>
                    <td>
                        <input style="width:25%;" class="form-control" type="number" name="eta" id="eta" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td class="td-intestazione">Anno CV</td>
                    <td>
                        <input style="width:25%;" class="form-control" type="number" name="anno_cv" id="anno_cv" autocomplete="off">
                    </td>
                </tr>

                <tr>
                    <td class="td-intestazione">Competenze Informatiche</td>
                    <td>
                        <div class="row">
                            <div class="col-md-3">
                                <input style="width:100%;" class="form-control" type="text" name="competenza" id="competenza" placeholder="Competenza">
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" name="competenze" id="competenze" value="">
                                <select style="width:100%;" class="form-control" name="anni_competenza" id="anni_competenza">
                                    <option value="" selected disabled>Anni di Esperienza</option>
                                    <?php for($i = 1; $i < 51; $i++) { echo '<option>' . $i . '</option>'; } ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="fa fa-plus-circle fa-2x button-clear" onclick="addCompetence();"></button>
                            </div>
                            <div class="col-md-5" id="lista_competenze"></div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">
                <span class="fa fa-plus"></span> Inserisci
            </button>
        </form>
</section>
<!--FINE CONTENUTO PRINCIPALE-->

<script type="text/javascript">
var competenceClicks = 0;

function addCompetence() {
    competenceClicks++;
    let competenza = document.getElementById('competenza').value;
    let anniCompetenza = document.getElementById('anni_competenza').value

    var button = document.createElement('button');
    let anniString = (anniCompetenza === '1') ? ' Anno ' : ' Anni ';
    button.setAttribute('id', 'competenza' + competenceClicks);
    button.innerHTML = competenza + ' - ' + anniCompetenza + anniString +
        '<span class="glyphicon glyphicon glyphicon-remove-circle"></span>';
    button.className = 'btn btn-primary btn-xs btn-filter';
    button.setAttribute('onclick', "removeCompetence('" + ('competenza' + competenceClicks) + "', '" +
        competenza + "', '" + anniCompetenza + "')");
    document.getElementById('lista_competenze').appendChild(button);

    var input = document.getElementById('competenze');
    if (input.value === '') {
        input.value += competenza + ',' + anniCompetenza;
    } else {
        input.value += '-' + competenza + ',' + anniCompetenza;
    }
    document.getElementById('competenza').value = '';
    document.getElementById('anni_competenza').value = '';
}

function removeCompetence(buttonId, competence, yearsCompetence) {
    // Rimozione bottone filtro
    var button = document.getElementById(buttonId);
    button.parentNode.removeChild(button);
    competenceClicks--;

    // Rimozione dati da input
    var competenceValue = document.getElementById('competenze').value;
    var competenceList = competenceValue.split('-');
    var indexCompetence = competenceList.indexOf(competence + ',' + yearsCompetence);
    if(indexCompetence !== -1) {
        competenceList.splice(indexCompetence, 1);
    }
    document.getElementById('competenze').value = competenceList.join('-');
}
</script>
