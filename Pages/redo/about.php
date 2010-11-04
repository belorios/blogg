<?php

	$layout = "2col_std";
	
	$body   = "
		<h1>Om bloggen - Redovisning</h1>
		<p>
			Denna bloggen är skriven som ett projektarbete för kursen Databaser och webbapplikationer, intro på BTH. 
		</p>
		<h2>Implementation</h2>
		Detta var ett riktigt roligt projekt att hålla på dem. Det första jag började med var att gå igenom templaten från tidigare 
		i kursen och uppdatera denna till en modell jag själv tyckte fungera bättre, sedan uppdaterade jag designen på templaten. 
		Jag valde att implementera alla funktioner som projektbeskrivningen föreslog, lade också till så att man kan växla mellan en och två 
		kolumnslayout. <br />
		Frångick också här att använda mysli och använde mig istället av PDO då jag trivs bättre att jobba med det. 
		Utöver de funktioner projektbeskrivningen föreskrev fixade jag också till en form av url rewrite (bth servern stödjer inte mod_rewrite?) för 
		snyggare urls. 
		<p>
			Några större problem stötte jag väl inte på mer än att sessionerna dummade sig här på BTH servern, men det fixade jag genom att ändra tiden på dem. 
		</p>
		<h2>Er diagram</h2>
		Ja såhär är databasen uppbyggd.
		<img src='".PATH_SITE_LOC."/Pages/redo/er.png' alt='' />
	";