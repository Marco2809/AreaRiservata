<?php
require_once('class/message.class.php');
require_once('class/file.class.php');
require_once('class/user.class.php');


if (isset($_POST['add_message'])) {
    if ($_POST['dipendente'] == "" && $_POST['dipendente_input_value'] == "") {
        $_POST['dipendente'] = 0;
    } else if ($_POST['dipendente_input_value'] !== "" && $_POST['dipendente_input_value'] !== null) {
        $_POST['dipendente'] = $_POST['dipendente_input_value'];
    }

    $messaggio = new Message();
    $messaggio->tipo = $_POST['tipo'];
    $messaggio->titolo = $_POST['titolo'];
    $messaggio->data_fine = $_POST['data_fine'];
    $messaggio->testo = $_POST['testo'];
    $messaggio->id_autore = $_SESSION['user_idd'];
    echo $messaggio->addMessage($_POST['checkbox'], $_POST['dipendente']);
}

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

$user = new User();
$userList = $user->getAllForMessageWrite();

$commesse = new User();
$commesseList = $commesse->getCommesseById($_SESSION['user_idd']);

?>

<div id="write_message" >
     <div class="row centered-form">
        <div class="col-xs-12 col-sm-12 col-md-12 " >
        	<div class="panel panel-default" style="background-color: white;">
            <div class="calendar-heading">
                <div style="padding-left:2%;padding-right:2%;">
                    <span class="legenda-title">Nuovo Messaggio</span>
                </div>
            </div>
                <div class="panel-body">
                    <form role="form" action="dashboard.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <span style="font-weight:600;">Titolo:</span>
                                    <input type="text" name="titolo" id="titolo_messaggio" class="form-control input-sm"  value="<?php if(isset($_POST['titolo'])) echo $_POST['titolo']?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <span style="font-weight:600;">Data Fine:</span><br>
                            <div class="form-row show-inputbtns">
                                <input type="date" name="data_fine" class="form-control" min="<?php echo date('Y').'-'.date('m').'-'.date('d')?>" value="<?php if(isset($_POST['data_fine'])) echo $_POST['data_fine']?>"/>
                            </div>
                        </div>

                        <div class="form-group">
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
                        <div class="form-group">
                            <span style="font-weight:600;">Gruppi Destinatari:</span><br>
                            <?php $database = new Database();
                                $conn = $database->dbConnection();
                                $sql= "SELECT * FROM gruppi WHERE gruppo != 'Admin' ORDER BY id_gruppo ASC";
                                $result = $conn->query($sql);
                                while ($obj_groups = $result->fetch_object()) { ?>
                                    <input class="form-check-input" type="checkbox" id="check_all" name="checkbox[]" value="<?php
                                        echo $obj_groups->id_gruppo; ?>"><?php
                                        echo '&nbsp;<label class="form-check-label" for="check_all" style="margin-right: 15px;">' . $obj_groups->gruppo . '</label>';
                                }
                                foreach($commesseList as $comm)
                                {
                                  ?>
                                  <input class="form-check-input" type="checkbox" id="check_all" name="checkbox[]" value="comm_<?php
                                      echo $comm['id_commessa']; ?>"><?php
                                      echo '&nbsp;<label class="form-check-label" for="check_all" style="margin-right: 15px;">' . $comm['nome_commessa'] . '</label>';
                              }

                                ?>
                        </div>

                        <div class="form-group">
                            <span style="font-weight:600;">Dipendente Destinatario:</span><br />
                            <input type="text" id="dipendente_input" name="dipendente_input" class="form-control input-sm" placeholder="Nome Cognome" autocomplete="off">
                            <input type="hidden" id="dipendente_input_value" name="dipendente_input_value" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <span style="font-weight:600;">Testo:</span>
                            <textarea class="form-control" rows="5" style="max-width:100%; width: 100%;" name="testo"><?php if(isset($_POST['testo'])) echo $_POST['testo']?></textarea>
                        </div>
                        <input type="submit" name="add_message" value="Invia Messaggio" class="btn btn-block" style="background-color:#ffd777; ">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const dipendentiList = <?php echo json_encode($userList); ?>;
    const dipendenteField = document.getElementById('dipendente_input');
    const dipendenteValueField = document.getElementById('dipendente_input_value');

    dipendenteField.addEventListener('keyup', function (event) {
        if (dipendenteField.value !== '') {
            let dipendenteFound = -1;
            for (let idDipendente in dipendentiList) {
                if (dipendentiList[idDipendente].toLowerCase() === dipendenteField.value.toLowerCase()) {
                    dipendenteFound = idDipendente;
                    break;
                }
            }

            if (dipendenteFound !== -1) {
                dipendenteField.style.border = "2px solid green";
                dipendenteValueField.value = dipendenteFound;
            } else {
                dipendenteField.style.border = "2px solid red";
                dipendenteValueField.value = null;
            }
        } else {
            dipendenteField.style.border = "";
        }
    });
</script>
