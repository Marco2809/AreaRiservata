<?php

require_once('class/user.class.php');
require_once('assets/php/PHPMailer/PHPMailerAutoload.php');

$code = 0;
$user = new User();
$emailArray = $user->getAllEmail();

if(isset($_POST['email-form'])) {
    
    if($_POST['mode'] == 'separate') {
        foreach($_POST['employees'] as $email) {
            $mail = new PHPMailer;
            $mail->setFrom('hr@service-tech.org', 'HR');
            $mail->addAddress($email);
            $mail->Subject = $_POST['object'];
            $mail->Body = convertUTF($_POST['message']);
            if (isset($_FILES['attachment']) &&
                    $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
                $mail->AddAttachment($_FILES['attachment']['tmp_name'],
                        $_FILES['attachment']['name']);
            }
            $code = 1;
            if(!$mail->send()) {
                $code = -1;
            }
        }
    } else if($_POST['mode'] == 'group') {
        $mail = new PHPMailer;
        $mail->setFrom('hr@service-tech.org', 'HR');
        
        foreach($_POST['employees'] as $email) {
            $mail->addAddress($email);
        }
        
        $mail->Subject = $_POST['object'];
        $mail->Body = $_POST['message'];
        if (isset($_FILES['attachment']) &&
                $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $mail->AddAttachment($_FILES['attachment']['tmp_name'],
                    $_FILES['attachment']['name']);
        }
        if(!$mail->send()) {
          $code = -1;
        } else {
          $code = 1;
        }
    }
}

?>

<section class="">
    
<?php
    if($code == 1) {
        echo '<div class="alert alert-success">
                <strong>Invio completato!</strong> L\'e-mail è stata inviata con successo.
            </div>';
    } else if($code == -1) {
        echo '<div class="alert alert-danger">
                <strong>Attenzione!</strong> L\'invio dell\'email non è stato completato a causa di un errore.
            </div>';
    }
?>

    <div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="calendar-heading">
                <div style="padding-left:2%;padding-right:2%;">
                    <span class="legenda-title">Creazione E-Mail</span>
                </div>
            </div>
            <div class="panel-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="object">Oggetto:</label>
                        <input type="text" class="form-control" 
                               id="object" name="object" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Messaggio:</label>
                        <textarea class="form-control" rows="5" 
                            id="message"  name="message"></textarea>
                    </div>
                    <label for="attachment">Allegato:</label><br>
                    <label class="custom-file">
                        <input type="file" id="attachment" name="attachment" 
                            class="custom-file-input" multiple>
                        <span class="custom-file-control"></span>
                    </label>
                    <br><br>
                    <label for="mode">Modalità di Invio:</label>
                    <div class="panel panel-default" style="margin-top:15px;">
                        <div class="panel-body">
                            <label class="radio-inline">
                                <input type="radio" name="mode" value="separate"
                                       checked>Invio separato
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="mode" value="group">
                                Invio di gruppo
                            </label>
                        </div>
                    </div>
                    <label for="employees">Dipendenti:</label>
                    <div class="panel panel-default" style="margin-top:15px;">
                        <div class="panel-body">
                            <div style="margin-bottom: 10px;">
                                <button type="button" class="btn btn-danger btn-sm" 
                                        id="select-all" value="true">Deseleziona Tutti</button>
                            </div>
<?php
                            foreach($emailArray as $email) {
                                echo '<div class="col-md-4">';
                                echo '<div class="checkbox">';
                                echo '<label><input type="checkbox" id="employees" '
                                    . 'name="employees[]" value="' . $email . '" checked>' . $email . '</label>';
                                echo '</div></div>';
                            }
?>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit" name="email-form">Invia</button>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    $('#select-all').click(function(event) {  
      if(this.value == 'false') {
          this.value = 'true';
          this.innerText = 'Deseleziona Tutti';
          this.className = 'btn btn-danger btn-sm';
        // Iterate each checkbox
        $(':checkbox').each(function() {
          this.checked = true;                        
        });
      }
      else {
        this.value = 'false';
        this.innerText = 'Seleziona Tutti';
        this.className = 'btn btn-success btn-sm';
        // Iterate each checkbox
        $(':checkbox').each(function() {
          this.checked = false;
        });
      }
    });
});
</script>";