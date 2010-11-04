<?php

	$Users = new Users();
	$Posts = new Posts();
	
	$layout='2col_std';
	
	try {
		$AllPosts = $Posts->getPostsByUser($id);
		$UserData = $Users->getUserData($id);
	}
	catch ( exception $e ) {
		$_SESSION['errorMessage'][] = $e->getMessage();
		return;
	}
	
	$body = "<h1>Inlägg skrivna av $UserData[realname]</h1>";
	
	foreach ($AllPosts as $post) {
		$body .= post_Layout($post['id'], $post['header'], $post['content'], $post['date'], $post['author'], $post['authorId'], $post['comments']);
	}
	
	$sideBox = 
		sideboxLayout("Författaren", "
			<span class='mark'>$UserData[realname]</span>
			<a href='mailto:$UserData[email]' style='font-style: italic;'>$UserData[email]</a>
			<p>
			Tillhör gruppen <span class='mark'>$UserData[idGroups]</span>, $UserData[groupdesc]
			</p>
		")
	;