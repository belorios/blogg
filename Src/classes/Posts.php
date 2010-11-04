<?php

	class Posts {
		
		private $db, $prefix, $dateformat;
		
		public function __construct($db=false) {
			if ($db != false) {
				$this->db = $db;	
			}
			else {
				$this->getConnection();
			}
			
			$this->prefix = DB_PREFIX;
			$this->dateformat = "H:m, j F Y";
		}
		
		public function getConnection() {
			if (!is_object($this->db)) {
				$pdo = new pdoConnection();
				$this->db = $pdo->getConnection();
			}
 		}
		
		//Hämtar ut datan från posterna och behandlar den
		public function returnPost($row, $dateformat, $small=false) {
			
			$defaults  = new defaults();
			
			if ($small == true) {
				$content = $defaults->shorten($row['content'], 500, "<p><a href='".PATH_SITE."/lasInlagg/id-$row[idPosts]'>Läs mer</a></p>");
			}
			else {
				$content = $row['content'];
			}
			
			$dateformat = ($dateformat == false) ? $this->dateformat : $dateformat;
			$date = $defaults->sweDate($dateformat, $row['creationDate']);
			$result = array(
				"id"  	   => $row['idPosts'],
				"authorId" => $row['author'],
				"author"   => $row['realname'],
				"date"     => $date,
				"content"  => $content,
				"header"   => $row['header'],
			);
			
			if (isset($row['Comments'])) {
				$result["comments"] = $row['Comments'];
			}
			
			return $result;
		}
		
		//Hämtar ut alla posten efter användare
		public function getPostsByUser($id, $limit=false, $dateformat=false) {
			
			$limit = ($limit != false) ? "LIMIT 0,$limit" : null;
		
			$get = $this->db->prepare("
				SELECT P.*, U.realname, COUNT(C.idComments) as Comments 
				FROM {$this->prefix}Posts P 
				JOIN {$this->prefix}Users U on idUsers = P.author 
				LEFT JOIN {$this->prefix}Comments C on P.idPosts = C.idPosts 
				WHERE P.author = :id 
				GROUP BY P.idPosts 
				ORDER BY creationDate DESC
				$limit
			");
			$get->bindParam(':id', $id, PDO::PARAM_INT);
			
			
			if ($get->execute()) {
				$result = array();
				foreach ($get->fetchAll() as $row) {
					$result[] = $this->returnPost($row, $dateformat);
				}
				return $result;
			}
			else {
				$fail = "Kunde inte spara inlägget";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}
		}
		
		//Hämtar ut en ensam post
		public function getPost($id, $dateformat=false) {
			$get = $this->db->prepare("
				SELECT P.*, U.realname 
				FROM {$this->prefix}Posts P 
				LEFT JOIN {$this->prefix}Users U on idUsers = author 
				WHERE idPosts = ?
			");
			$get->execute(array($id)); 
			
			if ($result = $get->fetch()) {
				return $this->returnPost($result, $dateformat);
			}
			else {
				throw new Exception ("Kunde inte hämta inlägget");
			}
			
		}
		
		//Hämtar ut alla inlägg
		public function getAllPosts($small=false, $dateformat=false) {
			
			$query = "
				SELECT P.*, U.realname, COUNT(C.idComments) as Comments
				FROM {$this->prefix}Posts P
				LEFT JOIN {$this->prefix}Comments C on P.idPosts = C.idPosts 
				LEFT JOIN {$this->prefix}Users U on idUsers = P.author 
				GROUP BY P.idPosts 
				ORDER BY creationDate DESC
			";
			
			$get = $this->db->prepare($query);
			$get->execute(); 
			
			$result = array();
			foreach ($get->fetchAll() as $row) {
				$result[] = $this->returnPost($row, $dateformat, $small);
			}
			
			return $result;
			
		}
		
		//Tar bort en post
		public function delPost($id) {
			$query = "
				DELETE FROM {$this->prefix}Posts WHERE idPosts = :id
			";
			
			$get = $this->db->prepare($query);
			$get->bindParam(':id', $id, PDO::PARAM_INT);
			
			//Kontrollerar så att databastransaktionen lyckades
			if (!$get->execute()) {
				$fail = "Kunde inte tabort inlägget";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}
			else {
				$this->unsetSessions();
				$this->createRssFeed();
				return true;
			}
		}
		
		//Uppdaterar en tidigare post
		public function editPost($id, $header, $content) {
			
			$query = "
				UPDATE {$this->prefix}Posts SET
					header  = :header,
					content = :content 
				WHERE idPosts = :id
			";
			
			$get = $this->db->prepare($query);
			$get->bindParam(':id',      $id, 	    PDO::PARAM_INT);
			$get->bindParam(':header',  $header, 	PDO::PARAM_STR);
			$get->bindParam(':content', $content, 	PDO::PARAM_STR);
			
			//Kontrollerar så att databastransaktionen lyckades
			if (!$get->execute()) {
				$fail = "Kunde inte spara inlägget";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}
			else {
				$this->unsetSessions();
				$this->createRssFeed();
				return true;
			}
		}
		
		//Lägger till en post
		public function addPost($header, $content) {
			
			$author = $_SESSION['users']['idUsers'];
			$date   = time();
			
			$query = "
				INSERT INTO {$this->prefix}Posts (header, content, creationDate, author)
				VALUES (:header,:content,:date,:author)
			";
			
			$get = $this->db->prepare($query);
			$get->bindParam(':header',  $header, 	PDO::PARAM_STR);
			$get->bindParam(':content', $content, 	PDO::PARAM_STR);
			$get->bindParam(':date', 	 $date, 	PDO::PARAM_INT);
			$get->bindParam(':author',  $author, 	PDO::PARAM_INT);
			
      //Kontrollerar så att databastransaktionen lyckades
			if (!$get->execute()) {
				$fail = "Kunde inte spara inlägget";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}
			else {
				$this->unsetSessions();
				$this->createRssFeed();
				return true;
			}
			
		}
		
    //Validerar inmatad data
		public function validatePost($header, $content) {
			
			$this->savePost($header, $content);
			
			$Validation = new Validation();
			
			$fail = array();
			if (!$Validation->checkValues("Heading", $header, 3)) {
				$fail[] = "Rubriken har inte blivit korrekt inmatad (minst 3 tecken) ";
			}
			if (!$Validation->checkValues("Length", $content, 20)) {
				$fail[] = "Innehållet har inte blivit korrekt inmatat (minst 20 tecken) ";
			}
			
			if (count($fail) > 0) {
				$_SESSION['errorMessage'] = $fail;
				return false;
			}
			else {
				return true;
			}
				
		}
		
    //Sparar inmatad post till sessioner
		public function savePost($header, $content) {
			$_SESSION['post']['header']  = $header;
			$_SESSION['post']['content'] = $content;
		}
		
    //Dödar postens sessioner
		private function unsetSessions() {
			$_SESSION['post'] = array();
		}
		
		//Hämtar ut alla inlägg ur databasen och skapar en xml fil för rss flödet
		public function createRssFeed() {
			
			$defaults  = new defaults();
			$query = "SELECT * FROM {$this->prefix}Posts ORDER BY creationDate DESC";
			
      //Plockar ut alla rader och börjar skriva in i filen
			if ($get = $this->db->query($query)) {
				//öppnar filen och trunkerar gammaldata  
				$file = fopen(PATH_RSS, "w");
				
				$items = null;
				foreach ($get->fetchAll() as $row) {
					
					$content = $defaults->shorten(strip_tags($row['content']), 250, "...");
					$date = date('D, d M Y H:i:s O',  $row['creationDate']);
			
					$items .= "
						\t\t<item>
							\t\t\t<title>{$row['header']}</title> \n
							\t\t\t<link>".PATH_SITE."/lasInlagg/id-$row[idPosts]</link> \n
							\t\t\t<description>{$content}</description> \n
							\t\t\t<pubDate>$date</pubDate> \n
						\t\t</item>
					";
				}
        
        //Skriver in allt i filen
				$date = date('D, d M Y H:i:s O', time());
				fwrite($file, "
					<rss version='1.0'> \n
						\t<channel>\n
							\t\t<title>Rss feed för bloggen \"".APP_HEADER."\" </title>\n
							\t\t<description>Denna är rss feed innehåller utsnitt från alla inlägg som görs i bloggen</description>\n
							\t\t<link>".PATH_SITE."</link>\n
							\t\t<lastBuildDate>$date</lastBuildDate>\n
							\t\t<pubDate>$date</pubDate>\n
							$items 
						\t</channel>\n
					</rss>\n
				");
			}
			
			
		}
		
	}
