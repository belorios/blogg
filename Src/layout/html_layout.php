<?php
    
	function html_layout($Title, $Heading, $Menu, $Body, $Footer, $Charset='UTF-8', $extra=false, $StyleSheets=false, $JavaScript=false, $MetaTags=false) {
		
		return "
			
			<!DOCTYPE HTML>
			<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en' >
				<head>
					<meta http-equiv='Content-Type' content='text/html; charset=$Charset'>
					<title>$Title</title>
					<link rel='alternate' type='application/rss+xml'  href='".PATH_SITE_RSS."' title='Rss feed'>
					$StyleSheets
					<!--[if IE 7]>
						<link rel='stylesheet' type='text/css' href='".PATH_CSS."ie.css' />
					<![endif]-->

				</head>
				<body>
					<div id='BackgroundContainer'>	
						<div id='BackgroundContainer_Klet'>
							<div id='Container'>
								<div id='LogoBox'>
									<div id='logo_heading'>
										$Heading[Header]
									</div>
									<div id='logo_decoration'></div>
									<div id='logo_desc'>$Heading[Description] <span style='font-size: 18pt'>\"</span></div>
								</div>
								<div id='MenuHolder'>
									$Menu
								</div>
								<div id='pageBody'>
									<div id='pageBody_Top'></div>
									<div id='pageBody_Grow'>
									<p>&nbsp;</p>
										<div id='pageBody_Content'>
											$Body
											<div class='clear'></div>
										</div>
									</div>
									<div id='pageBody_Extra'>
										<div style='float:left'>
											$extra
										</div>
										<div style='float:right'>
											<a href='".PATH_SITE."/andrastil/lila'><img src='".PATH_SITE_LAYOUT."images/buttons/xtra.gif' title='Extra stor storlek på text'  /></a>
											<a href='".PATH_SITE."/andrastil/enkolumn'><img src='".PATH_SITE_LAYOUT."images/buttons/twocol.gif' title='Två kolumns layout'  /></a>
											<a href='".PATH_SITE."/andrastil/tvakolumn'><img src='".PATH_SITE_LAYOUT."images/buttons/onecol.gif' title='En kolumns layout'  /></a>
										</div>
									</div>
									<div id='pageBody_Footer'></div>
								</div>
								<div id='FooterInfo'>
									<div id='FooterInfo_Left'>
										{$Footer['left']}
									</div>
									<div id='FooterInfo_Right'>
										{$Footer['right']}
									</div>
								</div>
							</div>
						</div>
					</div>
				</body>
			</html>
				
		";
	}
