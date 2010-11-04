<?php

	class Validation {
		
		public function checkEmail($epost) {
			if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $epost)) {	#Adressen är ogiltig pga fel antal symboler eller @ tecken i en sektion
			   	return false;
			}
			#Splittrar adressen i mindre delar
			$epost_array = explode("@", $epost);				#Delar vid @
			$local_array = explode(".", $epost_array[0]);		#Delar på punkt i första sektionen
			for ($i = 0; $i < sizeof($local_array); $i++) {
				if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
					return false;
				}
			} 
			if (!ereg("^\[?[0-9\.]+\]?$", $epost_array[1])) { 	#Kontrollerar så att domänen inte är en ipadress
				$domain_array = explode(".", $epost_array[1]);
				if (sizeof($domain_array) < 2) {				#Kontrollerar antalet delar i adressen
					return false; 
				}
				for ($i = 0; $i < sizeof($domain_array); $i++) {
				  	if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				      	return false;
				   	}
				}
			}
			return true;
		}
		
		#Verifieringsfunktion för strängar
		function checkValues($type, $string, $length) { 
			$out = false;
		 
			if (strlen($string) >= $length) {
				switch ($type) { 
					case "Zip": 
						if (preg_match("/^[0-9]{5}$/",$string)) 
							$out = true;			
			  		break; 
					case "Phone": 
						if (preg_match("/^[\+{0,1}[0-9]{3,4}\-{0,1}[0-9 ]{4,15}$/",$string)) 
							$out = true;
					break; 
					case "User": 
			  			if (preg_match("/^[a-zA-ZåäöÅÄÖ0-9]+$/", $string)) 
							$out = true;
					break;
					case "Street": 
			  			if (preg_match('/^[A-ZÅÄÖa-zåäö]{1}[a-zA-ZÅÄÖåäö ]+([ ]{1}[0-9]+[a-zA-Z]{0,1}){0,1}$/', $string)) 
							$out = true;
					break;
					case "Mail":
						if ($this->checkEmail($string))
							$out = true;
					break;
					case "Pnmbr":
						if (preg_match("/^[0-9]{10}$/",$string)) {
							$sum = "";
							$num = 2;
							for ($i = 0; $i < 9; $i++) 	{
							
								//En siffra kontrolleras.
								$tmp = substr($string, $i, 1) * $num;
						 
								//Siffersumman läggs till totalsumman.
								if ($tmp > 9)
									$sum += 1 + ($tmp % 10);
								else
									$sum += $tmp;
						 
								//"Funktionssiffran" ändras inför nästa kontroll.
								if ($num == 2)
									$num = 1;
								else
									$num = 2;
							}
							
							if (($sum + substr($string, 9, 1)) % 10 == 0)
								$out = true;
						}
							
					break;
					case "Pass": 
			  			if (preg_match("/^[a-zåäöÅÄÖ0-9%&#@£\$\€]+$/i",$string))  
					    	$out = true;
					break;
					case "Heading": 
			  			if (preg_match("/^[a-zåäöÅÄÖ0-9%&#@£\$\€ ]+$/i",$string))  
					    	$out = true;
					break;
					case "Name": 
			  			if (preg_match("/^[a-zåäöÅÄÖ ]+$/i",$string))  
					    	$out = true;
					break;  
					case "Company": 
			  			if (preg_match('/^[a-zåäöÅÄÖ\-_& ]+$/i',$string)) 
					    	$out = true;
					break; 
					case "Site": 
						$test = true;
						$parts = explode(".", $string);
						
						if (count($parts) >= 1) {
							foreach ($parts as $part) {
								if ($test == false) {
									continue;
								}
								elseif (!preg_match("/^[a-zåäöÅÄÖ ]+$/i",$part)) {
									$test = false;
								} 
							}
						}
						else {
							$test = false; 
						}
						$out = $test;	
								
					break; 
					case "Length":
						$out = true;
					break;
		 		} 
			}
			elseif ($length > 0)
				$out = false;
		 
			return $out; 
		}
		
		function CheckSameness($var1, $var2) {
			if ($var1 == $var2)
				return true;
			else
				return false;
		}
	}