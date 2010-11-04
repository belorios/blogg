<?php

   	$Users = new Users();
	$Users->checkPrivilegies();
	$Posts = new Posts();
	
	//Hämtar ut data för posten 
	try {
		$getPost = $Posts->getPost($id);
	}
	catch ( Exception $e) {
		$body = "
			<p class='warning'>{$e->getMessage()}</p>
		";
		return;
	}
	
	//Visar datan från hämtad post alternativt inskriven data om sådan finns
	$header  = (isset($_SESSION['post']['header']))  ? $_SESSION['post']['header']  : $getPost['header']; 
	$content = (isset($_SESSION['post']['content'])) ? $_SESSION['post']['content'] : $getPost['content']; 
	
	//Hämtar och visar formuläret
	require_once(PATH_FUNC . "forms.php");
	$body = postsForm("Redigerar inlägget $header", "redigera", $header, $content, $id);