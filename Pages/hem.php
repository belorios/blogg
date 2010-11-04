<?php
    
	$layout='2col_std';
	$body = null;
	$sideboxPosts = null;
	$sideboxUsers = null;
	
	$Posts = new Posts();
	$Users = new Users();
	
	try {
		$AllUsers = $Users->getAllUsers(); 
		$AllPosts = $Posts->getAllPosts(true);
	}
	catch ( exception $e) {
		$_SESSION['errorMessage'][] = $e->getMessage();
		return;
	}
	
	//Hämtar ut alla inlägg
	$i = 1;
	foreach ($AllPosts as $post) {
		
		$extra = ($i == 1) ? "first" : null;
		$body .= post_Layout($post['id'], $post['header'], $post['content'], $post['date'], $post['author'], $post['authorId'], $post['comments'], null, $extra);
		if ($i <= 10) {
			$sideboxPosts .= "<a href='" . PATH_SITE . "/lasInlagg/id-$post[id]'>$post[header]</a> <br />";	
		}
		
		$i++;	
	}
	
	//Hämtar ut alla författare
	foreach ($AllUsers as $user) {
		$sideboxUsers .= "<a href='".PATH_SITE."/visaAnvandare/id-{$user['idUsers']}'>{$user['realname']} </a> &nbsp;<span style='font-size: 9pt;color: #666;'>({$user['shortdesc']})</span><br />";
	}
	
	$sideBox = 
		sideboxLayout("Författare", "
			$sideboxUsers
		") . 
		sideboxLayout("Senaste inläggen", "
			$sideboxPosts
		")
	;
	$sideBoxFloat = "right";
	
	
