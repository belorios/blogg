<?php
	
	$defaults = new defaults;
	
	$body = null;
	
	//Lägger till en post
	if ($action == "skapa") {
		
		$Users = new Users();
		$Users->checkPrivilegies();
		$Posts = new Posts();
		
		$header   = $_POST['heading'];
		$content  = $_POST['content'];
		
		//Validerar inmatade värden
		if ($Posts->validatePost($header, $content)) {
			
			//Skapar posten
			try {
				$Posts->addPost($header, $content);
				$body .= $defaults->redirect(PATH_SITE . "/hem", "2", "Inlägget har nu blivit sparat");
			}
			catch ( exception $e ) {
				$_SESSION['errorMessage'][] = $e->getMessage();
				header("Location: " . PATH_SITE . "/skapaInlagg");
				exit;
			}
		}
		else {
			header("Location: " . PATH_SITE . "/skapaInlagg");
			exit;
		}
		
		
		
	}
	
	//Redigerar en tidigare post
	if ($action == "redigera") {
		
		$Users = new Users();
		$Users->checkPrivilegies();
		$Posts = new Posts();
		
		$header  = $_POST['heading'];
		$content = $_POST['content'];
		
		//Validerar inmatad data
		if ($Posts->validatePost($header, $content)) {
			
			//Matar in datan i db
			try {
				$Posts->editPost($id, $header, $content);
				$body .= $defaults->redirect(PATH_SITE . "/lasInlagg/id-$id", "3", "Inlägget har nu blivit sparat");
			}
			catch ( exception $e ) {
				$_SESSION['errorMessage'][] = $e->getMessage();
				header("Location: " . PATH_SITE . "/redigeraInlagg/id-$id");
				exit;
			}
		}
		else {
			header("Location: " . PATH_SITE . "/redigeraInlagg/id-$id");
			exit;
		}
	}
	
	//Raderar en tidigare post
	if ($action == "radera") {
		
		$Users = new Users();
		$Users->checkPrivilegies();
		$Posts = new Posts();
		
		//Raderar posten
		try {
			$Posts->delPost($id);
			$body .= $defaults->redirect(PATH_SITE . "/hem", "3", "Inlägget har nu blivit borttaget");
		}
		catch ( exception $e ) {
			$_SESSION['errorMessage'][] = $e->getMessage();
			header("Location: " . PATH_SITE . "/raderaInlagg/id-$id");
			exit;
		}
	}
	
	//Skapar en kommentar
	if ($action == "skapaKommentar") {
		
		$Comments = new Comments();
		
		//Hämtar ut all data
		$header  	 = $_POST['heading'];
		$content 	 = $_POST['content'];
		$auhtorName  = $_POST['author'];
		$authorEmail = $_POST['email'];
		$authorSite  = $_POST['site'];
		$redirect    = PATH_SITE . "/lasInlagg/id-$id";
		
		//Validerar datan
		if ($Comments->validateComment($header, $content, $auhtorName, $authorEmail, $authorSite)) {
			
			//Matar in datan i db
			try {
				$Comments->addComment($id, $header, $content, $auhtorName, $authorEmail, $authorSite);
				$body .= $defaults->redirect($redirect, "2", "Kommentaren har nu blivit sparat");
			}
			catch ( exception $e ) {
				$_SESSION['errorMessage'][] = $e->getMessage();
				header("Location: $redirect");
				exit;
			}
		}
		else {
			header("Location: $redirect");
			exit;
		}
	}
	
	//Tar bort en kommentar
	if ($action == "raderaKommentar") {
		
		$Users = new Users();
		$Users->checkPrivilegies();
		$Comments = new Comments();
		
		$redirect = $_SERVER['HTTP_REFERER'];;
		
		//Raderar den från db
		try {
			$Comments->delComment($id);
			$body .= $defaults->redirect($redirect, "3", "Kommentaren har nu blivit borttaget");
		}
		catch ( exception $e ) {
			$_SESSION['errorMessage'][] = $e->getMessage();
			header("Location: $redirect");
			exit;
		}
	}
