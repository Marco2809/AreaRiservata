<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./class/dbconn.php');
$database = new Database();
$db = $database->dbConnection();

$userIdsArray = array();
$stmtSelect = $db->prepare("SELECT user_idd FROM login");
$stmtSelect->execute();
$stmtSelect->store_result();
$stmtSelect->bind_result($result);
while($stmtSelect->fetch()) {
	array_push($userIdsArray, $result);
}

foreach($userIdsArray as $userId) {
	$stmtSelectUserCommessa = $db->prepare("SELECT rowid FROM user_commesse WHERE id_user = ? LIMIT 1");
	$stmtSelectUserCommessa->bind_param('i', $userId);
	$stmtSelectUserCommessa->execute();
	$stmtSelectUserCommessa->store_result();
	$stmtSelectUserCommessa->bind_result($resultUserCommessa);
	$stmtSelectUserCommessa->fetch();
	
	if($stmtSelectUserCommessa->num_rows == 0) {
		$idCommessa = 51;
		$idRole = 4;
		$stmtInsert = $db->prepare("INSERT INTO user_commesse (id_commessa, id_user, id_role) VALUES (?, ?, ?)");
		$stmtInsert->bind_param('iii', $idCommessa, $userId, $idRole);
		if(!$stmtInsert->execute()) {
			trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
		}
		$stmtInsert->close();
	}
}

/*
$emailList = 'paolo.abritta@service-tech.org; jonathan.alfieri@service-tech.org; ivan.altobelli@service-tech.org; armando.ambrogioni@service-tech.org; maqsudul.amin@service-tech.org; michele.andreoli@service-tech.org; ilaria.angelozzi@service-tech.org; franco.anzoletti@service-tech.org; francesco.arcidiaco@service-tech.org; daniele.ardovini@service-tech.org; ivan.arena@service-tech.org; emanuela.artusa@service-tech.org; paride.aureli@service-tech.org; serena.barbalace@service-tech.org; teresa.bastone@service-tech.org; renato.benedikter@service-tech.org; carmine.bosco@service-tech.org; vincenzo.bruni@service-tech.org; davide.buscemi@service-tech.org; sirian.caldarelli@service-tech.org; andrea.cammarano@service-tech.org; franco.candini@service-tech.org; rodolfo.caprino@service-tech.org; tommaso.carissimo@service-tech.org; maurizio.carlotti@service-tech.org; ernesto.casorelli@service-tech.org; ilaria.chiapponi@service-tech.org; immacolata.cimmino@service-tech.org; patrizia.colella@service-tech.org; bruna.colinelli@service-tech.org; davide.cuzzoli@service-tech.org; fabrizio.dantonio@service-tech.org; federico.scoponi@service-tech.org; andrea.dinnocenzo@service-tech.org; simona.davide@service-tech.org; bianca.decarluccio@service-tech.org; giuseppe.dechiara@service-tech.org; valerio.demarco@service-tech.org; daniele.delpapa@service-tech.org; andrea.difilippo@service-tech.org; nicolo.dilernia@service-tech.org; massimo.dinardo@service-tech.org; francesca.fava@service-tech.org; grazia.fazzino@service-tech.org; andrea.feola@service-tech.org; roberto.festa@service-tech.org; ivano.fortini@service-tech.org; francesco.franza@service-tech.org; andrea.frontini@service-tech.org; piero.gallo@service-tech.org; alberto.gay@service-tech.org; simona.gentile@service-tech.org; pantaleo.giglio@service-tech.org; bruna.gilberti@service-tech.org; marcello.iallonardo@service-tech.org; alessandro.lanzi@service-tech.org; cinzia.latorre@service-tech.org; alessio.lattanzi@service-tech.org; ester.loparco@service-tech.org; piero.lovadina@service-tech.org; fortunato.madonna@service-tech.org; roberto.mangiacapre@service-tech.org; francesca.marchetti@service-tech.org; laura.mariano@service-tech.org; simone.montanari@service-tech.org; pierpaolo.morelli@service-tech.org; claudio.morlupi@service-tech.org; fabio.mosca@service-tech.org; rossana.muscolino@service-tech.org; raffaela.nardelli@service-tech.org; mattia.nobile@service-tech.org; gianluigi.novelli@service-tech.org; sandra.oliva@service-tech.org; massimiliano.onofri@service-tech.org; giovanni.orecchio@service-tech.org; federica.panarisi@service-tech.org; stefano.panebianco@service-tech.org; riccardo.passamonti@service-tech.org; giuseppe.perrone@service-tech.org; agnese.piccioni@service-tech.org; beatrice.pirazzi@service-tech.org; deborah.plutino@service-tech.org; gianluca.reali@service-tech.org; ilaria.riefoli@service-tech.org; giorgia.ristorini@service-tech.org; matteo.romanelli@service-tech.org; luciano.rosati@service-tech.org; settimio.sabbi@service-tech.org; marco.salmi@service-tech.org; omar.santarelli@service-tech.org; claudia.serantoni@service-tech.org; marco.serantoni@service-tech.org; francesca.servidio@service-tech.org; Sebastiano.Siragusa@service-tech.org; paolo.suraci@service-tech.org; lorena.tallarico@service-tech.org; federica.tei@service-tech.org; stefano.testa@service-tech.org; ambra.tomassi@service-tech.org; diego.tomassi@service-tech.org; erika.tranchida@service-tech.org; alessio.turchetti@service-tech.org; roberto.valerio@service-tech.org; paola.zambrano@service-tech.org; domenico.zavattolo@service-tech.org';

$emails = explode("; ", $emailList);
$password = 'Iniziale1$$';
foreach($emails as $email) {
     echo $email;
     $result = '';
     $stmtSelect = $db->prepare("SELECT email FROM login WHERE username = ? LIMIT 1");
     $stmtSelect->bind_param('s', $email);
     $stmtSelect->execute();
     $stmtSelect->store_result();
     $stmtSelect->bind_result($result);
     $stmtSelect->fetch();
        
     if($stmtSelect->num_rows == 0) {
          $stmtInsert = $db->prepare("INSERT INTO login (username, password, email) VALUES (?, ?, ?)");
          $stmtInsert->bind_param('sss', $email, $password, $email);
          if(!$stmtInsert->execute()) {
               trigger_error("Errore! " . $this->db->error, E_USER_WARNING);
          }
          $stmtInsert->close();
     }
     
}
*/
?>