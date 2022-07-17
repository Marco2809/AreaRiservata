<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../pdfparser-master/vendor/autoload.php');
require_once('../../class/anagrafica.class.php');
require_once('functions.php');
require_once('FPDF/fpdf.php');
require_once('FPDI/fpdi.php');

function split_pdf($filename, $end_directory) {

    $end_directory = $end_directory ? $end_directory : './';
    $new_path = preg_replace('/[\/]+/', '/', $end_directory.'/'.substr($filename, 0, strrpos($filename, '/')));

    if (!is_dir($new_path)) {
        // Will make directories under end directory that don't exist
        // Provided that end directory exists and has the right permissions
        mkdir($new_path, 0777, true);
    }

    $pdf = new FPDI();
    $pagecount = $pdf->setSourceFile($filename);

    // Split each page into a new PDF
    for ($i = 1; $i <= $pagecount; $i++) {
        $new_pdf = new FPDI();
        $new_pdf->AddPage();
        $new_pdf->setSourceFile($filename);
        $new_pdf->useTemplate($new_pdf->importPage($i));

        try {
            $new_filename = $end_directory.str_replace('.pdf', '', $filename).'_'.$i.".pdf";
            $new_pdf->Output($new_filename, "F");
            
            // Parse pdf file and build necessary objects.
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($new_filename);

            $text = $pdf->getText();
            $cf = getCfFromBP($text);
            $anagrafica = new Anagrafica();
            $anagrafica->getAnagraficaFromCF($cf);

            echo $cf . ' => ' . $anagrafica->getIdAnagrafica() . '<br>';
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}

$userfile_tmp = $_FILES['bustePaga']['tmp_name'];
$userfile_name = $_FILES['bustePaga']['name'];

//copio il file dalla sua posizione temporanea alla mia cartella upload
if(move_uploaded_file($userfile_tmp, 'buste/' . $userfile_name)) {
    // Create and check permissions on end directory!
    split_pdf('buste/' . $userfile_name, 'split/');
} else {
    echo 'Upload NON valido!'; 
}

?>