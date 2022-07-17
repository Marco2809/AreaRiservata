<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../class/dbconn.php');
require_once('../class/anagrafica.class.php');
$user = new Anagrafica();
$response = array();
$u_appo = $user->getAllUser();
if($u_appo){
    $response['status'] = 1;
    require_once('mapper.php');
} else {
    $response['status'] = 0;
}
echo json_encode($response);
?>