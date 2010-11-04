<?php

	class Comments {
		
		private $db, $prefix, $dateformat;
		
		public function __construct($db=false) {
			if ($db != false) {
				$this->db = $db;	
			}
			else {
				$this->getConnection();
			}
			
			$this->prefix = DB_PREFIX;
			$this->dateformat = "H:m, j M Y";
		}
		
		public function getConnection() {
			if (!is_object($this->db)) {
				$pdo = new pdoConnection();
				$this->db = $pdo->getConnection();
			}
 		}
		
		//Hämtar ut alla kommentarer för ett inlägg
		public function getComments($id, $dateformat=false) {
			
			$query = "SELECT * FROM {$this->prefix}Comments WHERE idPosts = :id";
			
			$get = $this->db->prepare($query);
			$get->bindParam(':id', $id, PDO::PARAM_INT);
			$get->execute(); 
			
			$result = array();
			
			foreach ($get->fetchAll() as $row) {
				$result[] = $this->returnComment($row, $dateformat);
			}
			
			return $result;
		}
		
		//Adds a comment to a post
		public function addComment($idPosts, $header, $content, $auhtorName, $authorEmail, $authorSite) {
			
			$date   = time();
			
			//Removes ugly html tags
			$content 	  = strip_tags($content); 		//Fix later to support basic BB code instead
			$header  	  = strip_tags($header);
			$auhtorName   = strip_tags($auhtorName);
			$authorEmail  = strip_tags($authorEmail);
			$authorSite   = strip_tags($authorSite);
			
			
			$query = "
				INSERT INTO {$this->prefix}Comments (idPosts, header, content, creationDate, author, authorEmail, authorSite)
				VALUES (:idPosts, :header,:content,:date,:author, :authorEmail, :authorSite)
			";
			
			//Inserts the data to the prepared_statement query
			$get = $this->db->prepare($query);
			$get->bindParam(':idPosts',  		$idPosts, 		PDO::PARAM_INT);
			$get->bindParam(':header',  		$header, 		PDO::PARAM_STR);
			$get->bindParam(':content', 		$content, 		PDO::PARAM_STR);
			$get->bindParam(':date', 	 		$date, 			PDO::PARAM_INT);
			$get->bindParam(':author',  		$auhtorName,	PDO::PARAM_STR);
			$get->bindParam(':authorEmail', 	$authorEmail, 	PDO::PARAM_STR);
			$get->bindParam(':authorSite',  	$authorSite, 	PDO::PARAM_STR);
			
			//Checks if the transaction succeded
			if (!$get->execute()) {
				$fail = "Kunde inte spara kommentaren";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}
			else {
				$this->unsetSessions();
				return true;
			}
		}
		
		//Behandlar och retunerar datan för en kommentar	
		public function returnComment($row, $dateformat) {
			
			$defaults  = new defaults();
			
			$dateformat = ($dateformat == false) ? $this->dateformat : $dateformat;
			$date = $defaults->sweDate($dateformat, $row['creationDate']);
			$result = array(
				"id"  	  => $row['idComments'],
				"name"    => $row['author'],
				"email"   => $row['authorEmail'],
				"site"    => $row['authorSite'],
				"date"    => $date,
				"content" => $row['content'],
				"header"  => $row['header'],
			);
			
			return $result;
		}
		
		//Tar bort en kommentar från db
		public function delComment($id) {
			$query = "
				DELETE FROM {$this->prefix}Comments WHERE idComments = :id
			";
			
			$get = $this->db->prepare($query);
			$get->bindParam(':id', $id, PDO::PARAM_INT);
			
			if (!$get->execute()) {
				$fail = "Kunde inte ta bort kommentaren";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}
			else {
				$this->unsetSessions();
				return true;
			}
		}
		
		//Validerar inmatad data när man lägger till en kommentar
		public function validateComment($header, $content, $auhtorName, $authorEmail, $authorSite) {
			
			$this->saveComment($header, $content, $auhtorName, $authorEmail, $authorSite);
			
			$Validation = new Validation();
			
			$fail = array();
			if (!$Validation->checkValues("Heading", $header, 3) || strlen($header) > 33) {
				$fail[] = "Rubriken har inte blivit korrekt inmatad (minst 3, max 30 tecken) ";
			}
			if (!$Validation->checkValues("Length", $content, 20)) {
				$fail[] = "Innehållet har inte blivit korrekt inmatat (minst 20 tecken) ";
			}
			if (!$Validation->checkValues("Name", $auhtorName, 2)) {
				$fail[] = "Namnet har inte blivit korrekt inmatat (minst 2 tecken) ";
			}
			
			if (strlen($authorSite) > 0) {
				if (!$Validation->checkValues("Site", $authorSite, 5)) {
					$fail[] = "Hemsidan har inte blivit korrekt inmatad (minst 5 tecken) ";
				}
			}
			
			if (strlen($authorEmail) > 0) {
				if (!$Validation->checkValues("Mail", $authorEmail, 5)) {
					$fail[] = "Emailen har inte blivit korrekt inmatad (minst 5 tecken) ";
				}
			}
			
			if (count($fail) > 0) {
				$_SESSION['errorMessage'] = $fail;
				return false;
			}
			else {
				return true;
			}
				
		}
		
		//Sparar inmatad data till sessioner
		public function saveComment($header, $content, $auhtorName, $authorEmail, $authorSite) {
			$_SESSION['comment']['header']  = $header;
			$_SESSION['comment']['content'] = $content;
			$_SESSION['comment']['name'] 	= $auhtorName;
			$_SESSION['comment']['email'] 	= $authorEmail;
			$_SESSION['comment']['site']  	= $authorSite;
		}
		
		//Dödar sessionerna
		private function unsetSessions() {
			$_SESSION['comment'] = array();
		}
		
	}
