<?php
    
	class defaults {
		
		//Hänvisar till vald sida efter valt antal sekunder
		public function redirect($url, $time, $comment=false) {
			
			$comment = ($comment != false) ? "$comment <br />" : null;
			
			//Omvandlar sekunder till millisekunder
			$time = $time*1000;
			return "
				$comment
				Om du inte skickas vidare inom kort, <a href='$url'>Klicka här</a>
				<script type='text/javascript'>
					setTimeout(\"window.location='$url'\",$time)
				</script>
			";
		}
		
		//Hanterar svenska datum
		public function sweDate($dateformat, $date) {
				
			$date = date($dateformat, $date);
			
			$swe = array(
				"Januari", "Februari", "Maj", "Juni", "Juli", "Augusti", "Oktober", "Okt", 
				"Måndag", "Tisdag", "Onsdag", "Torsdag", "Fredag", "Lördag", "Söndag", "Mån", "Tis", "Ons", "Tor", "Fre", "Lör", "Sön"
			);
			$eng = array(
				"january", "february", "may", "juny", "july", "august", "october", "oct",
				"Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday", "Mon", "tue", "wed", "thu", "fri", "sat", "sun"
			);
			
			return str_ireplace($eng, $swe, $date);
		}
		
		//Kortar en sträng till valt antal tecken och avslutar där närmsta mening tar slut
		public function shorten($string, $max_length, $extra=null) {
			if (strlen($string) > $max_length) {
				$string = substr($string, 0, $max_length);
				$pos = strrpos($string, ".");
				
				if($pos === false) {
					$return = substr($string, 0, $max_length).".";
				}
				else {
					$return = substr($string, 0, $pos).".";
				}
			}
			else{
				$return = $string;
			}
				
		   return $return . $extra;
		}
		
		//Ser till att en länk har en korrekt url
		public function correctUrl($url) {
			$site = $url;
			if (!substr($url, 0, 4) != "http") {
				$site = "http://" . $url;
			}
			return "<a href='$site'>$url</a>";
		}
		
	}
