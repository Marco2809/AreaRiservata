<?php

/**
* Client che chiede al Web service l’indirizzo internet di un motore di ricerca. 
* PHP 5 mette a disposizione l’oggetto SoapClient per definire un client.
*/
// Se chiamiamo questo file in un browser otteniamo come risposta: www.google.it.
try {
        //$opts = array("http"=>array("user_agent"=>"PHPSoapClient"));
        //$context = stream_context_create($opts);
	$gsearch = new SoapClient('http://demo.service-tech.org/form/search_engine.wsdl',array('connection_timeout'=>5,'trace'=>true,'soap_version'=>SOAP_1_2,'cache_wsdl' => WSDL_CACHE_NONE));
		//$gsearch = new SoapClient('http://fastdata2.service-tech.org/webservices/ws/search_engine.wsdl',array('connection_timeout'=>5,'trace'=>true,'soap_version'=>SOAP_1_2,"cache_wsdl"=>WSDL_CACHE_MEMORY,"stream_context"=>$context));

 ini_set("soap.wsdl_cache_enabled", "0");
var_dump($gsearch->__getFunctions());

        echo("SOAP Client creato con successo!<br>");
            
       
        $result=$gsearch->sayHello($_POST['name']);
	echo("Servizio Disponibile<br>");


        $result2=$gsearch->getPropriAsset($_POST['name']);
        print_r("Stampa del risultato: ".$result2." <br>");
        
        
        
        /*print_r("Stampa del risultato ciao: ".$result1." <br>");
         * 
         */
} catch (SoapFault $exception) {
	print_r($exception);
}
