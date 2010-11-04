<?php
	
	//Script webpath
	$webPath = $_SERVER['SERVER_NAME'] . str_ireplace("/index.php", "", $_SERVER['SCRIPT_NAME']);
	
	//Settings for the database connection	
	define("DB_USER",   "user");
	define("DB_PASS",   "secret");
	define("DB_HOST",   "localhost");
	define("DB_SCHEMA", "blogInterWebs");
	define("DB_PREFIX", "blog_");
	
	//Pathes
	define("PATH_PAGES"  , dirname(__FILE__) . "/Pages/");
	define("PATH_SOURCE" , dirname(__FILE__) . "/Src/");
	define("PATH_CLASSES", PATH_SOURCE . "classes/");
	define("PATH_LAYOUT" , PATH_SOURCE . "layout/");
	define("PATH_FUNC"   , PATH_SOURCE . "func/");
	define("PATH_MOD"    , PATH_SOURCE . "mod/");
	define("PATH_RSS"	 	, PATH_SOURCE . "rss/feed.xml");

	define("PATH_SITE_LOC" 	  , "http://$webPath");	
	define("PATH_SITE" 		  , PATH_SITE_LOC . "/index.php");
	define("PATH_SITE_LAYOUT" , PATH_SITE_LOC . "/Src/layout/");
	define("PATH_CSS" 		  , PATH_SITE_LAYOUT . "css/");	
	define("PATH_SITE_RSS"	  , PATH_SITE_LOC . "/Src/rss/feed.xml");
	
	//Default values
	define("APP_HEADER",      "Blogg my InterWebZ");
	define("APP_DESCRIPTION", "Pretty darn anything");
	define("APP_FOOTER",      "Blogg my InterWebZ");
	define("APP_VALIDATION", "
		Validates &nbsp;
		<a href=\"http://validator.w3.org/check?uri=referer\">(X)HTML 5</a> &nbsp; 
		<a href=\"http://jigsaw.w3.org/css-validator/check/referer?profile=css3\">CSS3</a> &nbsp;
		<a href=\"http://validator.w3.org/checklink/checklink?uri=http%3A%2F%2Fwww.student.bth.se%2F~krlb10%2Fdbwebb1%2Fmom08%2F&amp;hide_type=all&amp;depth=&amp;check=Check\">Links</a>
	");
	define("APP_STYLE" , PATH_CSS . "std.css");	
	
	//Menu array
	$menuArr = array(
		PATH_SITE => "Hem",
		PATH_SITE . "/install" => "Installera",
		PATH_SITE . "/visafiler" => "Visa filer",
		PATH_SITE . "/andrastil/lila" => "Byt stilmapp",
		PATH_SITE . "/ombloggen" => "Om bloggen",
		PATH_SITE_RSS => "RSS",
	);
	
	define("APP_MENU", serialize($menuArr));
