<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require_once('class/user.class.php');
require_once('class/anagrafica.class.php');
require_once('class/buste_paga.class.php');
require_once('assets/php/FPDF/fpdf.php');
require_once('assets/php/FPDI/fpdi.php');
require_once('assets/php/PHPMailer/PHPMailerAutoload.php');

$code = 0;
$arrayNotFound = array();

if(isset($_POST['upload-bp'])) {
	
		$code = 1;
		$zip = new ZipArchive();
		$filename = "./buste-paga/TestLUL.zip";
	
		var_dump(is_writable("./buste-paga"));

		if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
			echo("Cannot open <$filename>\n");
		}

        $userfile_tmp = $_FILES['bustePaga']['tmp_name'];
	
		$pdf = new FPDI();
		$pagecount = $pdf->setSourceFile($userfile_tmp);
	
	$zip->addFile("hr.php");
/*
		// Split each page into a new PDF
		for ($i = 1; $i <= $pagecount; $i++) {
			$new_pdf = new FPDI();
			$new_pdf->AddPage();
			$new_pdf->setSourceFile($userfile_tmp);
			$new_pdf->useTemplate($new_pdf->importPage($i));

			try {
				$new_filename = "buste-paga/" . $i . ".pdf";
				$new_pdf->Output($new_filename, "F");

				// Parse pdf file and build necessary objects.
				$parser = new \Smalot\PdfParser\Parser();
				$pdf = $parser->parseFile($new_filename);

				$text = $pdf->getText();
				list($cf, $name) = getCfAndNameFromBP($text);
				$anagrafica = new Anagrafica();
				$anagrafica->getAnagraficaFromCF($cf);
				$idDip = $anagrafica->getIdAnagrafica();
				$nomeDip = parseAccentedCharacters(strtolower($anagrafica->getNome()));
				$cognomeDip = parseAccentedCharacters(strtolower($anagrafica->getCognome()));
				$name = parseAccentedCharacters(strtolower($name));

				if($idDip != 0 && strcmp("$cognomeDip $nomeDip", $name) == 0) {
					$tmpNomeDip = str_replace("'", "", $nomeDip);
					$tmpNomeDip = str_replace(" ", "_", $tmpNomeDip);
					$tmpCognomeDip = str_replace("'", "", $cognomeDip);
					$tmpCognomeDip = str_replace(" ", "_", $tmpCognomeDip);
					$zip->addFile($new_filename, $tmpNomeDip . "_" . $tmpCognomeDip . ".pdf");
				} else {
					array_push($arrayNotFound, $cf);
				}
			} catch (Exception $e) {
				$code = -1;
			}
			unlink($new_filename);
		}
	*/
		for ($i = 0; $i < $zip->numFiles; $i++) {
		 	$filename = $zip->getNameIndex($i);
		 	echo $filename . "<br />";
	 	}
	
		echo "Num Files: " . $zip->numFiles . "\n";
		echo "Status:" . $zip->status . "\n";
	
		$res = $zip->close();
	
		if($res) {
			echo "Zip complete<br /><br />";
		} else {
			var_dump($res);
			echo "<br /><br />";
		}
	
		header("Content-Type: application/zip");
		header("Content-Transfer-Encoding: Binary");
		header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
		header("Content-Length: " . filesize($filename));
		ob_end_clean();
		readfile($filename);
		//unlink($filename);
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
                             echo $cf . '<br>';
                       }
                       echo '</div>';
        } else if($code == -1 || $code == -2) {
               echo '<div class="alert alert-success">
                            <strong>Attenzione!</strong> Il caricamento non è stato completato.
                        </div>';
        }
?>

	 <div class="row">
	 <div class="col-md-12">
        <div class="panel panel-primary filterable" style="margin-bottom:5%;">
            <div class="legenda-heading">
                <div style="padding-left:2%;padding-right:2%;">
                    <span class="legenda-title">Upload File Suddivisione LUL</span>
                </div>
            </div>
            <div class="form-group" style="padding:10px;">
                <center>
                    <form method="POST" enctype="multipart/form-data">
                        <div style="margin:20px 0 20px 0;">
                            <input type="file" class="form-control-file" id="bustePaga" 
                                   name="bustePaga" aria-describedby="fileHelp" required>
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