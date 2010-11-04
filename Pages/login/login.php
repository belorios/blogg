<?php
	
	//Html formen för inloggning av användare
	
	$body = "
		<h1>Inloggning </h1> 
		<br />
		<div style='margin-left: 20px; width: 185px;' id=''>
			<form method='post' action='loginprocess'>
					<p>
						Användarnamn <br />
						<input type='text' name='uname' />   
					</p>
					<p>
						Lösenord <br />
						<input type='password' name='passwd'  />  <br />
					</p>
				<div class='righty_buttons' >
					<input type='submit' name='login' value='Logga in' />
				</div>
				<div style='clear:both'></div>
			</form>
		
		</div>
	
	";
