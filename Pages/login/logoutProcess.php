<?php
	
	//Skapar användarobjekt och loggar ut användaren
	$users = new Users();
	
	$users->processLogout();
	
	header("Location: " . PATH_SITE);
	exit;