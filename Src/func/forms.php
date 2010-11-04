<?php

	function postsForm($header, $action, $contheader, $content, $id=false) {
		
		$idString = ($id != false) ? "/id-$id" : null;
		
		return "
			<h1>$header</h1>
			<div style='width: 405px'>
				<form method='post' action='".PATH_SITE."/hanteraInlagg/$action{$idString}'>
					<p>
						Rubrik <br />
						<input type='text' name='heading' value='$contheader' />
					</p>
					<p>
						Innehåll <br />
						<textarea name='content'>$content</textarea>
					</p>
					<div class='righty_buttons'>
						<input type='reset'  value='Töm' /> &nbsp;
						<input type='submit' name='addPost' value='Posta' />
					</div>
				</form>
			</div>
		";
		
	}
	
	function commentsForm($id, $header, $comment, $name, $email, $site) {
		return "
			
			<div style='width: 405px; margin: 20px 3px 0px 3px;'>
				<b>Skriv kommentar</b>
				<form method='post' action='".PATH_SITE."/hanteraInlagg/skapaKommentar/id-$id'>
					
					<div style='float:left; margin: 0px 35px 0px 0px;'>
					<label>Rubrik (*)</label> <br />
					<input type='text' name='heading' value='$header' /> <br />
					
					<label>Namn (*)</label> <br />
					<input type='text' name='author' value='$name' /> <br />
					</div>
					<div style='float:left;'>
					<label>Epostadress</label> <br />
					<input type='text' name='email' value='$email' /> <br />
					
					<label>Hemsida</label> <br />
					<input type='text' name='site' value='$site' /> <br />
					</div>
					<label>Innehåll (*)</label> <br />
					<textarea name='content' style='height: 150px;'>$comment</textarea>
					
					
					<div class='righty_buttons'>
						<input type='reset'  value='Töm' /> &nbsp;
						<input type='submit' name='addPost' value='Skicka' />
					</div>
					<div class='clear'></div>
				</form>
			</div>
		
		";
	}
