<?php
	
	$Users = new Users();
	$Users->checkPrivilegies();
	$Posts = new Posts();
	
	//Hämstar ut data för posten som ska tas bort
	try {
		$getPost = $Posts->getPost($id);
	}
	catch ( Exception $e) {
		$body = "
			<p class='warning'>{$e->getMessage()}</p>
		";
		return;
	}
	
	$header = $getPost['header']; 

	$body = "
		<h1>Tar bort inlägg</h1>
		<p>
			Vill du verkligen ta bort inlägget \"$header\"?
			<form method='post' action='".PATH_SITE."/hanteraInlagg/radera/id-$id'>
				<input type='submit' name='radera' value='Ja' />
				<input type='submit' name='radera' value='Nej' />
			</form>
		</p>
	";
