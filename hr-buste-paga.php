<?php

require_once('class/user.class.php');
require_once('class/anagrafica.class.php');
require_once('class/buste_paga.class.php');
require_once('assets/php/FPDF/fpdf.php');
require_once('assets/php/FPDI/fpdi.php');
require_once('assets/php/PHPMailer/PHPMailerAutoload.php');

$code = 0;
$arrayNotFound = array();
$idMese = date('m');
$idAnno = date('Y');

if(isset($_POST['upload-bp'])) {

        $userfile_tmp = $_FILES['bustePaga']['tmp_name'];
        $userfile_name = $_FILES['bustePaga']['name'];

        $filename = 'buste-paga/' . $userfile_name;
        $end_directory = '';

        if (move_uploaded_file($userfile_tmp, 'buste-paga/' . $userfile_name)) {

            $code = 1;
            $end_directory = $end_directory ? $end_directory : './';
            $new_path = preg_replace('/[\/]+/', '/', $end_directory.'/'.substr($filename, 0, strrpos($filename, '/')));

            if (!is_dir($new_path)) {
                mkdir($new_path, 0777, true);
            }

			try {
				$pdf = new FPDI();
				$pagecount = $pdf->setSourceFile($filename);
			} catch(Exception $e) {
				$code = -2;
			}

            // Split each page into a new PDF
            for ($i = 1; $i <= $pagecount; $i++) {
                $new_pdf = new FPDI();
                $new_pdf->AddPage();
                $new_pdf->setSourceFile($filename);
                $new_pdf->useTemplate($new_pdf->importPage($i));

                try {
                    $new_filename = $end_directory . str_replace('.pdf', '', $filename) .
						'_' . $i . ".pdf";
                    $new_pdf->Output($new_filename, "F");

                    // Parse pdf file and build necessary objects.
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($new_filename);

                    $text = $pdf->getText();
                    list($cf, $name) = getCfAndNameFromBP($text);
                    $anagrafica = new Anagrafica();
                    $anagrafica->getAnagraficaFromCF($cf);
                    $idDip = $anagrafica->getIdAnagrafica();
					$nomeDip = parseAccentedCharacters(strtolower(trim($anagrafica->getNome())));
					$cognomeDip = parseAccentedCharacters(trim(strtolower($anagrafica->getCognome())));
					$name = parseAccentedCharacters(strtolower($name));

                    $monthBP = strtoupper(getMonthName($_POST['month-bp']));
                    $yearBP = $_POST['year-bp'];
/*
					print_r("Nome:" . $nomeDip . "<br />");
					print_r("Cognome: " . $cognomeDip . "<br />");
					print_r("Cognome Nome da BP: " . $name . "<br />");
                    print_r("CF da BP: " . $cf . "<br />");*/

                    if($idDip != 0 && strcmp("$cognomeDip $nomeDip", $name) == 0) {
						if (!file_exists('buste-paga/' . $idDip)) {
							mkdir('buste-paga/' . $idDip, 0777, true);
						}
						if (!file_exists('buste-paga/' . $idDip . '/' . $yearBP)) {
							mkdir('buste-paga/' . $idDip . '/' . $yearBP, 0777, true);
						}

						$token = substr(uniqid('', true), -5);

						// Adding file to correct directory
						$bpFilename = 'buste-paga/' . $idDip . '/' . $yearBP . '/' .
							strtoupper($cognomeDip . ' ' . $nomeDip) . '_BP ' . $monthBP . ' ' . $yearBP . ' ' .
							$token . '.pdf';
						rename($new_filename, $bpFilename);

						// Adding reference to db
						$bustePaga = new BustePaga();
						$bustePaga->addBP($idDip, $_POST['month-bp'], $yearBP, $token);
/*
						// Sending email
						$user = new User();
						$email = $user->getEmailByUserId($idDip);
						$mail = new PHPMailer(true);
						$mail->setFrom('hr@service-tech.it', 'HR');
						$mail->addAddress($email);
						$mail->addAttachment($bpFilename);
						$mail->Subject = 'Busta Paga ' . getMonthName($_POST['month-bp']) . ' ' . $_POST['year-bp'];
						$mail->isHTML(true);
						$mail->Body = 'Salve,<br /><br />in allegato la busta paga del mese di ' . getMonthName($_POST['month-bp']) .
                            ' ' . $_POST['year-bp'] . '.<br /><br />Cordiali saluti,<br /><br />' .
							'<em>' .
								'<strong>' .
									'<span style="font-size: 9pt; font-family: Arial, sans-serif; color: #00006c;">' .
										'Pierpaolo Morelli' .
										'<br />' .
										'Miriam Bellucci' .
										'<br /><br />' .
										'Risorse Umane' .
										'<br /><br />' .
										'Service-' .
									'</span>' .
									'<span style="font-size: 9pt; font-family: Arial, sans-serif; color: #23ff06;">' .
										'Tech' .
									'</span>' .
									'<span style="font-size: 9pt; font-family: Arial, sans-serif; color: #00006c;">' .
										' s.r.l.' .
										'<br />' .
									'</span>' .
								'</strong>' .
								'<span style="font-size: 9pt; font-family: Arial, sans-serif; color: #00006c;">' .
									'Sede Legale ed Operativa: Via Simone Martini 143/145' .
									'<br />' .
									'00142 Roma' .
									'<br />' .
									'Tel +39 06 51530559' .
									'<br />' .
									'Fax +39 06 51967591' .
									'<br />' .
									'<a href="mailto:hr@service-tech.it">hr@service-tech.it</a>' .
									'<br />' .
									'<a href="http://www.service-tech.it">http://www.service-tech.it</a>' .
									'<br /><br />' .
								'</span>' .
							'</em>' .
							'<span style="font-size: 9pt; font-family: Arial, sans-serif; color: #00006c;">' .
							'Il contenuto di questa e-mail, o dei suoi allegati, non rappresenta l\'opinione della societ&#224; Service-Tech S.r.l.. Il contenuto di questa e-mail, o degli allegati, &#232; di natura confidenziale e non deve essere rivelato, copiato o usato da chiunque ad eccezione del destinatario. Chi ricevesse questa e-mail per errore &#232; pregato di cancellarla. Avviso di Virus: bench&#232; questa e-mail e i suoi allegati siano controllati preventivamente dal nostro sistema di Antivirus e quindi privi di virus, &#232; sotto la responsabilit&#224; del ricevente assicurarsi che non ve ne siano. La Service-Tech S.r.l. non &#232; responsabile per perdite o danni derivanti dal ricevimento, l\'apertura o l\'uso di questa e-mail.' .
							'</span>';
						if (!$mail->send()) {
                            echo 'Message could not be sent.';
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        }*/
                    } else {
						array_push($arrayNotFound, $cf);
                    }
                } catch (Exception $e) {
                    $code = -1;
                    var_dump($e);
                }
                unlink($new_filename);
            }

            unlink($filename);

        } else {
            $code = -1;
            var_dump("Errore");
        }
}

?>

<section class="">

<?php
        if($code == 1 && count($arrayNotFound) == 0) {
               echo '<div class="alert alert-success">
                            <strong>Caricamento completato!</strong> Il caricamento delle buste paga è stato effettuato con successo.
                        </div>';
        } else if($code == 1 && count($arrayNotFound) != 0) {
               echo '<div class="alert alert-warning">
                            <strong>Attenzione!</strong> Il caricamento delle buste paga è stato effettuato ma non sono stati trovati gli utenti relativi ai seguenti codici fiscali:<br>';
                       foreach($arrayNotFound as $cf) {
                             echo '<li>' . $cf . '</li>';
                       }
                       echo '</div>';
        } else if($code == -1) {
               echo '<div class="alert alert-warning">
                            <strong>Attenzione!</strong> Il caricamento non è stato completato.
                        </div>';
        } else if($code == -2) {
               echo '<div class="alert alert-warning">
                            <strong>Attenzione!</strong> Il file utilizza una compressione non supportata.
                        </div>';
        }
?>

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="legenda-heading">
                <div style="padding-left:2%;padding-right:2%;">
                    <span class="legenda-title">Upload File Buste Paga</span>
                </div>
            </div>
            <div class="form-group" style="padding:10px;">
                <center>
                    <form method="POST" enctype="multipart/form-data">
                        <div style="margin:20px 0 20px 0;">
                            <input type="file" class="form-control-file" id="bustePaga"
                                   name="bustePaga" aria-describedby="fileHelp" required>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-3 col-md-offset-3">
                                <div class="form-group">
                                    <label for="month">Mese:</label>
                                    <select class="form-control" id="month" name="month-bp">
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
                                    <select class="form-control" id="year" name="year-bp">
                                        <option<?php if($idAnno == '2017') {echo ' selected';}?>>2017</option>
                                        <option<?php if($idAnno == '2018') {echo ' selected';}?>>2018</option>
                                        <option<?php if($idAnno == '2019') {echo ' selected';}?>>2019</option>
                                        <option<?php if($idAnno == '2020') {echo ' selected';}?>>2020</option>
                                        <option<?php if($idAnno == '2021') {echo ' selected';}?>>2021</option>
                                        <option<?php if($idAnno == '2022') {echo ' selected';}?>>2022</option>
                                        <option<?php if($idAnno == '2023') {echo ' selected';}?>>2023</option>
                                        <option<?php if($idAnno == '2024') {echo ' selected';}?>>2024</option>
                                        <option<?php if($idAnno == '2025') {echo ' selected';}?>>2025</option>
                                        <option<?php if($idAnno == '2026') {echo ' selected';}?>>2026</option>
                                        <option<?php if($idAnno == '2027') {echo ' selected';}?>>2027</option>
                                        <option<?php if($idAnno == '2028') {echo ' selected';}?>>2028</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" name="upload-bp">
                            <span class="glyphicon glyphicon-open"></span> Carica File
                        </button>
                    </form>
                </center>
            </div>
        </div>
    </div>
</div>
</section>
