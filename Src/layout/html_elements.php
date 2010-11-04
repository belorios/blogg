<?php
   
	function sideBox($html, $align='right') {
		$float = ($align == 'left') ? "float:left;" : "float:right;";
		return "
			<div id='pageBody_Content_Sidebox' style='$float'>
				{$html}	
			</div>
		";
	}
	
	function html_Menu($items) {
		
		return "
			<ul>
				$items
			</ul>
		";
		
	}
	
	function html_Menu_Items($link, $name, $current=false) {
		$class = ($current != false) ? "class='CurrentPage'" : null;
		return "
			<li $class><a href='$link'>$name</a></li>
		";
		
	}
	
	function html_errorMessage($messages) {
		
		return "
			<div class='errorMessage'>
				<b>Följande fel påträffades: </b>
				<p>
					{$messages}
				</p>
			</div>
		";
	}
	
	function html_Body($body) {
		return "<div id='pageBody_Content_Big'>$body</div>";
	}
	
	function post_Layout($id, $header, $content, $date, $author, $authorId, $comments, $extra=null, $class=null) {
		
		if ($author != "") {
			$author = "<a href='".PATH_SITE."/visaAnvandare/id-$authorId'>$author</a>";
		}
		else {
			$author = "okänd författare";
		}
		
		$comment = "<span class='mark'>$comments</span> kommentar";
		if ($comments != 1) {
			$comment .= "er";
		}
		
		return "
			<h2 class='Post_header'><a href='".PATH_SITE."/lasInlagg/id-$id'>$header</a></h2>
			<div class='Post_comments'><a href='".PATH_SITE."/lasInlagg/id-$id#kommentera'>$comment</a></div>
			<div style='clear:both;'></div>
			<div class='Post $class'>
				<p class='nuller'>&nbsp;</p>
				<div class='date'>$date</div>
				<div class='content'>$content</div>
				<div class='Post_Footer'>Skrevs av $author </div>	
				
			</div>
			$extra
		";
	}
	
	function sidebox_Author($realname, $email, $group, $groupdesc) {
		 
		return sideboxLayout("Författaren", "
			<span class='mark'>$realname</span>
			<a href='mailto:$email' style='font-style: italic;'>$email</a>
			<p>
			Tillhör gruppen <span class='mark'>$group</span>, $groupdesc
			</p>
		");
	}
	
	function sidebox_Login() {
		return sideboxLayout("Logga in", "
			<div id='LoginBox'>
				<form action='".PATH_SITE."/loginprocess' method='post'>
					<p>
						<input type='text' name='uname' value='Användarnamn' onclick='this.value=\"\"' />
					</p>
					<p>
						<input type='password' name='passwd' value='11111' onclick='this.value=\"\"'  />
					</p>
					<div class='righty_buttons' >
						<input type='submit' name='login' value='Logga in' />
					</div>
					<div class='clear'></div>
				</form>
			</div>
		");
	}
	function sidebox_LoggedIn($username, $realname, $menu) {
		
		$menuItems = null;
		foreach ($menu as $item) {
			$menuItems .= "<li><a href='".PATH_SITE."/$item[url]'>$item[desc]</a></li>";
		}
		
		return sideboxLayout("Inloggad som", "
			<span class='mark'>$realname</span><br />
			<ul id='loginMenu'>
				$menuItems
			</ul>
			
		");
	}
	
	function sideboxLayout($header, $body) {
		return "
			<div class='SideBox_Box'> 
				<div class='SideBox_Header'>
					<h2>{$header}</h2>
				</div>
				<div class='body'>
					$body
				</div>
				
			</div>
			
		";
	}
