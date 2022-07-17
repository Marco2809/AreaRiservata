<?php

require_once('../../class/buste_paga.class.php');

$idBP = $_REQUEST["id"];
$bp = new BustePaga();
$code = $bp->makeViewed($idBP);

echo $code;

?>