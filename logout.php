<?php

	session_start();
	session_destroy();
	setcookie('login', '', time());
	setcookie('key', '', time()); 
	header ("Location: index.php");


?>