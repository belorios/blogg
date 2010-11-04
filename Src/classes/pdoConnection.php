<?php
	
	class pdoConnection {
	
		private $host, $username, $password;
		
		public function __construct() {
		
			#Defines the connection variables from the configfile
			$this->host =    	"mysql" .
			':host=' . 	DB_HOST   .
			';dbname=' .DB_SCHEMA;
			$this->username = DB_USER;
			$this->password = DB_PASS;
			
		}
		
		public function getConnection($xtraerr=true) {
			try {
				$pdo = new PDO($this->host, $this->username, $this->password);
				if ($xtraerr == true) {
					$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				}
				return $pdo;
			}
			catch ( Exception $ex ) {  
				echo "Error: ".$ex->getMessage(); 
			}
		}
		
		public function getFault($db) {
			$error = $db->errorInfo();
			if ($db->errorCode() !== '00000') {
				return "$error[2] <br />";
			}
			else {
				return "$error[0]";
			}
			
		} 
	
	
	}