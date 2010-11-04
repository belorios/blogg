<?php
	
	//Hämtar in inmatade värden och säkrar upp dem
	$user = isset($_POST['uname']) ? $_POST['uname'] : null;
	$pass = isset($_POST['passwd']) ? $_POST['passwd'] : null;
	
	//Skapar nytt användar objekt
	$Users = new Users();
	
	//Kontrollerar om lösenord och användarnamn stämmer överens och utför inloggning
	$redirect = (isset($_SESSION['errorMessagePage'])) ? PATH_SITE . "{$_SESSION['errorMessagePage']}" : "hem";
	if (!$Users->processLogin($user, $pass)) {
		$redirect = PATH_SITE . "/login";
	}
	
	header("Location: $redirect");
	exit;
