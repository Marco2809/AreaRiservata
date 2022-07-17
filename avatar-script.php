<?php

session_start();

require_once('class/dbconn.php');
require_once('class/user.class.php');
require_once('assets/php/functions.php');

$avatarPath = dirname(__FILE__) . "/assets/img/avatar/";
$avatarArray = scandir($avatarPath, SCANDIR_SORT_ASCENDING);

foreach($avatarArray as $avatar) {
	if($avatar != "profilo.png" && $avatar != "45.png" && $avatar != "46.png" && 
	  $avatar != "118.png" && $avatar != ".." && $avatar != "." && $avatar != "Fortini2.png" && 
	   $avatar != "Salmi1.png" && $avatar != "Serantoni 1.png") {
		$userClass = new User();
		$cognome = explode(".", $avatar)[0];
		$userId = $userClass->getUserIdBySurname($cognome);
		if($userId != "0") {
			echo $userId . "<br>";
			rename($avatarPath . $cognome . ".png", $avatarPath . $userId . ".png");
		}
	}
}