<?php

if(!isset($_SESSION['user_idd'])||$_SESSION['user_idd']=="") header("location: ./login.php");
else if(!isset($_SESSION['name'])||$_SESSION['name']=="") header("location: ./anagrafica.php?id=".$_SESSION['user_idd']);

?>