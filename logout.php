<?php

session_start();
require_once('class/dbconn.php');
require_once('class/user.class.php');

$user=new User();
$user->logout();

?>