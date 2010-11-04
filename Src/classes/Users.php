<?php
	
	/*
	 * Class som hanterar allt kring användare
	 */
	
	class Users {
		
		private $db, $pdo, $prefix;
		
		public function __construct($db = false) {
			$this->db     = ($db != false) ? $db : null; 
			$this->prefix = DB_PREFIX;
		}
		
		public function destruct() {
			
		}
		
		//Ansluter till databasen om db inte är ett objekt
		public function dbConnect() {
			
			if (!is_object($this->db)) {
				$this->pdo = new pdoConnection();
				$this->db = $this->pdo->getConnection();
			}
			
		}
		
		//Utför inloggning
		public function processLogin($user, $pass) {
			
			$this->processLogout();
			$this->dbConnect();
			
			//Hashar lösenordet 
			$password = $this->passwdHash($pass);
			
			$query = "
				SELECT username, realname, U.idUsers, idGroups
				FROM {$this->prefix}Users U 
				LEFT JOIN {$this->prefix}GroupUsers GU ON GU.idUsers = U.idUsers
				WHERE
				    username = ? AND passwd = ?
				;
			";
		
			$get = $this->db->prepare($query);
			$get->execute(array($user, $password));
			$row = $get->fetch();
			if ($this->pdo->getFault($this->db) != '00000') {
				$_SESSION['errorMessage'] = $this->pdo->getFault($this->db);
				return false;
			}
			
			//Måste vara en träff resultatet
			if($get->rowCount() == 1) {
			    $_SESSION['userId']    = $row['idUsers'];
			    $_SESSION['username']  = $row['username'];    
				$_SESSION['group']	   = $row['idGroups'];
				$_SESSION['realname']  = $row['realname'];
				$_SESSION['debug']     = true;
				return true;    
			} 
			else {
				$_SESSION['errorMessage'] = "Inloggningen misslyckades";
			   	return false;
			}
		}
		
		
		//Dödar alla sessioner så användaren loggas ut
		public function processLogout() {
			$_SESSION = array();
			if (isset($_COOKIE[session_name()])) {
		  		setcookie(session_name(), '', time()-42000, '/');
			}
			session_destroy();
			session_start(); 
			session_regenerate_id();
		}
		
		
		//Hashar lösenorder
		function passwdHash($string) {
			return sha1($string . sha1($string . "98@£alHAwdk234¥[{", true));
		}
		
		//Kontrollerar om användaren är inloggad och den inloggades rättigheter
		public function checkPrivilegies($grp=false) {
			
			$return = false;
			if (isset($_SESSION['userId'])) {
				if ($this->ctlGroup($grp)) {
					$return = true;
				}
				elseif ($grp == false) {
					$return = true;
				}
				else {
					$_SESSION['errorMessage'] = "Du har inte rätt behörigheter för att kolla på sidan";
				}
			}
			else {
				$_SESSION['errorMessage'] = "Du måste vara inloggad för att komma åt sidan";
				$_SESSION['errorMessagePage'] = (isset($_GET['p'])) ? $_GET['p'] : "?";
				header("Location: ".PATH_SITE."/login");
				exit;
			}

			return $return;
			
		}
		
		//Kontrollerar ens rättigheter
		public function stdGroupsCtl($id) {
			$return = false;
			if (isset($_SESSION['group'])) {
				if ($_SESSION['userId'] == $id|| $_SESSION['group'] == 'adm') {
					$return = true;
				}
			}
			return $return;
		}
		
		//kontrollerar om man har rätt grupp
		public function ctlGroup($grp) {
			
			$return = false;
			if (isset($_SESSION['group'])) {
				if ($_SESSION['group'] == $grp) {
					$return = true;
				}
			}
			return $return;
		}
		
		//Hämtar ut all info om en användare och retunerar resultatet
		public function getUserData($id) {
			
			$this->dbConnect();
			
			if (!is_numeric($id) && $id != null) {
				$_SESSION['errorMessage'] = "Kan inte läsa användaren";
				return;
			}
			
			$query = "
				SELECT U.*, G.* FROM {$this->prefix}Users U
				JOIN {$this->prefix}GroupUsers GU ON GU.idUsers = U.idUsers
				JOIN {$this->prefix}Groups G ON G.idGroups = GU.idGroups
				WHERE U.idUsers = :id
			";
			$get = $this->db->prepare($query);
			$get->bindParam(":id", $id, PDO::PARAM_INT);
			
			if ($get->execute()) {
				return $get->fetch();
			}
			else {
				$fail = "Kunde inte spara inlägget";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}		
		}
		
		//Hämtar ut alla användare och retunerar resultatet
		public function getAllUsers() {
			
			$this->dbConnect();
			
			$query = "
				SELECT U.*, G.* FROM {$this->prefix}Users U
				JOIN {$this->prefix}GroupUsers GU ON GU.idUsers = U.idUsers
				JOIN {$this->prefix}Groups G ON G.idGroups = GU.idGroups
				ORDER BY U.idUsers
			";
			
			$get = $this->db->prepare($query);
			
			if ($get->execute()) {
				return $get->fetchAll();
			}
			else {
				$fail = "Kunde inte spara inlägget";
				if ($_SESSION['debug'] == true)
					$fail .= "<p>Den felande queryn: <br /> <b>$query</b></p>";
				throw new Exception ($fail);
				return false;
			}	
			
		}
	}