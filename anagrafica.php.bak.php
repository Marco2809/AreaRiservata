<?php

     <!-- Barra Avanzamento --> 
      <center>
         <div>
            <img src="img/barra-anagrafica.png" style="max-width:100%; margin-top:3%;">
         </div>
      </center>
      <!-- Fine Barra Avanzamento --> 
<!-- DIV CONTENITORE -->
<center>
         <div class="section">
            <div class="container">
               <div class="row" style="margin-top:0%;padding-top:1%; padding-bottom:1%;">
                  <!-- Dati -->
				  <center>
				  <div class="border">
                  

session_start();
include('dbconn.php');


if (!isset($_SESSION['username'])) {

    header("location: login.htm.php");
}

//Anagrafica
if (!$_POST['nome'] ||
        !$_POST['cognome'] ||
        !$_POST['luogo_nascita'] ||
        !$_POST['data_nascita'] ||
        !$_POST['citta_residenza'] ||
        !$_POST['indirizzo_residenza'] ||
        !$_POST['citta_domicilio'] ||
        !$_POST['indirizzo_domicilio'] ||
        !$_POST['codice_fiscale']
) {
    echo "not ok";
} else {
    print($_POST['nome']) . "<br>";
    print($_POST['cognome']) . "<br>";
    print($_POST['luogo_nascita']) . "<br>";
    print($_POST['data_nascita']) . "<br>";
    print($_POST['citta_residenza']) . "<br>";
    print($_POST['indirizzo_residenza']) . "<br>";
    print($_POST['citta_domicilio']) . "<br>";
    print($_POST['indirizzo_domicilio']) . "<br>";
    print($_POST['codice_fiscale']);
}

//Formazione
if (!$_POST['istituto'] ||
        !$_POST['da'] ||
        !$_POST['a'] ||
        !$_POST['titolo'] ||
        !$_POST['corso'] ||
        !$_POST['voto'] ||
        !$_POST['attivita'] ||
        !$_POST['descrizione'] ||
        !$_POST['esperienza_estera']) {
    echo "not ok";
} else {
    print($_POST['istituto']) . "<br>";
    print($_POST['da']) . "<br>";
    print($_POST['a']) . "<br>";
    print($_POST['titolo']) . "<br>";
    print($_POST['corso']) . "<br>";
    print($_POST['voto']) . "<br>";
    print($_POST['attivita']) . "<br>";
    print($_POST['descrizione']) . "<br>";
    print($_POST['esperienza_estera']);
}

//Esperienza
if (!$_POST['azienda'] || !$_POST['indirizzo'] || !$_POST['mansione'] || !$_POST['da'] || !$_POST['a'] ||!$_POST['note'])
{
echo "not ok";
} else {
    print($_POST['azienda']) . "<br>";
    print($_POST['indirizzo']) . "<br>";
    print($_POST['mansione']) . "<br>";
    print($_POST['da']) . "<br>";
    print($_POST['a']) . "<br>";
    print($_POST['note'])."<br>";
    
}
?>



