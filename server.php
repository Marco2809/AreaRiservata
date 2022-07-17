<?php

// Con public ottengo errore in PHP protected è senza nulla.
class SearchEngineWS {
 
        private $con;
        
        public function __construct() {
            
            $this->con = (is_null($this->con)) ? self :: connect() : $this->con; 
           
        }

        static function connect()
        {
            $con = mysql_connect('http://fastdata2.service-tech.org','root','servicetech14');
            $db = mysql_select_db('dolibarr',  $con);
            
            return $con;
        }

        public function sayHello($name){
               
                $sql = "SELECT label FROM llx_asset WHERE cod_asset LIKE '%$name%'";
                $qry = mysql_query($sql);
                $res=  mysql_fetch_array($qry);
	 	$salutation="Ciao etichetta è ".$res['label'].", sarai lieto di sapere che sto funzionando!";
    return $salutation;
	}
        
      /* public function sayCiao($name){
	 	$salutation1="Ciao ".$name.", sarai lieto di sapere che sto funzionando anche io!";
    return $salutation1;
	}
        */
    public function getPropriAsset($username){
	 	$salutation2="Ciao ".$username." ora carico i tuoi asset";
    return $salutation2;
	}
 
}
/* OPZIONALMENTE: Definire la versione del messaggio soap. Il secondo parametro non è obbligatorio. */
$server= new SoapServer("search_engine.wsdl", array('soap_version' => SOAP_1_2));
//$server=new SoapServer("search_engine.wsdl");
 $server->setClass("SearchEngineWS");
// Infine la funzione handle processa una richiesta SOAP e manda un messaggio di ritorno 
// al client che l’ha richiesta.
$server->handle();