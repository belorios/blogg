<?php
	
	$Users = new Users();
	$Users->checkPrivilegies();
	
	//Plockar ut tillfällig data
	$header  = (isset($_SESSION['post']['header']))  ? $_SESSION['post']['header']  : null; 
	$content = (isset($_SESSION['post']['content'])) ? $_SESSION['post']['content'] : null; 
	
	//Skapar formen
	require_once(PATH_FUNC . "forms.php");
	$body = postsForm("Skapar nytt inlägg", "skapa", $header, $content);
