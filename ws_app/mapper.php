<?php

$user = array();
$r_user = array();
foreach($u_appo as $u){
    $user['name'] = $u['nome'];
    $user['surname'] = $u['cognome'];
    $user['role'] = $u['r1']." ".$u['r2'];
    $user['email'] = $u['email'];;
    $user['birthday'] = $u['data_nascita'];
    $user['id'] = $u['user_id'];
    array_push($r_user, $user);
}
$response['user'] = $r_user;
?>