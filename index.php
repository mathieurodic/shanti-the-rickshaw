<!DOCTYPE html>
<html manifest="offline.appcache" prefix="og:http://ogp.me/ns#">
<head>
    <meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	
	<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
	
		if ($_SERVER["HTTP_HOST"] == "localhost"){
			header("Expires: Thu, 01-Jan-70 00:00:01 GMT"); 
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
		}


if (!function_exists('getallheaders')) {
    function getallheaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
    }
}


function is_https()
    {
        if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        {
            return TRUE;
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
        {
            return TRUE;
        }
        elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
        {
            return TRUE;
        }

        return FALSE;
    }

		$headers = getallheaders();
                $base_url = "http" . (is_https() ? "s" : "") . "://" . $_SERVER['HTTP_HOST'];
		$language = "fr";
		if (isset($_GET["fb_locale"])){
			$language = substr($_GET["fb_locale"], 0, 2);
		} elseif (isset($headers["X-Facebook-Locale"])){
			$language = substr($headers["X-Facebook-Locale"], 0, 2);
		} elseif (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
			$language = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
		}
		
		if (!(preg_match("@^[a-z]{2}$@", $language) && is_file("$language.json"))){
			$language = "en";
		}
		
		$texts = json_decode(file_get_contents("$language.json"), true);
	
	?>
	
	
	<!-- Title & description for search engines -->
	<title><?php echo $texts["PAGE_TITLE"] ?></title>
	<?php
		
		foreach (scandir(dirname(__FILE__)) as $filename){
			if (preg_match("@^([a-z]{2})\.json$@", $filename, $match)){
				echo "<meta name=\"description\" lang=\"";
				echo $match[0];
				echo "\" content=\"";
				echo json_decode(file_get_contents("$language.json"), true)["PAGE_DESCRIPTION"];
				echo "\">";
			}
		}


	?>
	
	
	<!-- Open Graph Protocol -->
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo $base_url ?>" />
	<meta property="og:image" content="<?php echo $base_url ?>/social/rickshaw.png" />
	<meta property="og:title" content="<?php echo $texts["PAGE_TITLE"] ?>" />
    <meta property="og:description" content="<?php echo $texts["PAGE_DESCRIPTION"] ?>"/>
	<meta property="og:locale" content="fr_FR" />
	<meta property="og:locale:alternate" content="en_GB" />
	<meta property="og:locale:alternate" content="hu_HU" />
	
	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary">
	<meta name="twitter:url" content="<?php echo $base_url ?>" />
	<meta name="twitter:title" content="<?php echo $texts["PAGE_TITLE"] ?>" />
	<meta name="twitter:description" content="<?php echo $texts["PAGE_DESCRIPTION"] ?>" />
	<meta name="twitter:image" content="<?php echo $base_url ?>/social/rickshaw.png" />
	
	
	<!-- Allow fullscreen mode on iOS devices. (These are Apple specific meta tags.) -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="HandheldFriendly" content="true" />
	
	<!-- Chrome for Android web app tags -->
	<meta name="mobile-web-app-capable" content="yes" />

    <!-- All margins and padding must be zero for the canvas to fill the screen. -->
	<style type="text/css">
		* {
			padding: 0;
			margin: 0;
			font-family: Arial;
		}
		body {
			background: #FFF;
			color: #000;
			-ms-touch-action: none;
			text-align: center;
		}
		
		#share {
			position: fixed;
			left: 32px;
			top: 40px;
		}
		#share img {
			display: block;
			margin-bottom: 12px;
		}
		
		#c2canvasdiv_container {
			margin: 40px auto;
			padding: 24px;
			color: #888;
			box-shadow: inset 2px 2px 8px #CCC;
			display: inline-block;
			text-align: left;
		}
		#c2canvasdiv {
			width: 768px;
			margin: 0 !important;
			background: #000;
			box-shadow: 2px 2px 8px #CCC;
		}
		canvas {
			touch-action-delay: none;
			touch-action: none;
			-ms-touch-action: none;
		}
		h2 {
			margin-top: 48px;
			font-size: 16px;
		}
		ul {
			margin: 8px 16px;
		}
		li {
			margin-top: 8px;
			font-size: 12px;
		}
    </style>
	

</head> 
 
<body> 
	<div id="fb-root"></div>
	
	<script>
	// Issue a warning if trying to preview an exported project on disk.
	(function(){
		// Check for running exported on file protocol
		if (window.location.protocol.substr(0, 4) === "file")
		{
			alert("Exported games won't work until you upload them. (When running on the file:/// protocol, browsers block many features from working for security reasons.)");
		}
	})();
	</script>

	
	<!-- The canvas must be inside a div called c2canvasdiv -->
	<div id="c2canvasdiv_container">
		<div id="c2canvasdiv">
			<!-- The canvas the project will render to.  If you change its ID, don't forget to change the
			ID the runtime looks for in the jQuery events above (ready() and cr_sizeCanvas()). -->
			
			<canvas id="c2canvas" width="768" height="512">
				<!-- This text is displayed if the visitor's browser does not support HTML5.
				You can change it, but it is a good idea to link to a description of a browser
				and provide some links to download some popular HTML5-compatible browsers. -->
				<h1>Your browser does not appear to support HTML5.  Try upgrading your browser to the latest version.  <a href="http://www.whatbrowser.org">What is a browser?</a>
				<br/><br/><a href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Microsoft Internet Explorer</a><br/>
				<a href="http://www.mozilla.com/firefox/">Mozilla Firefox</a><br/>
				<a href="http://www.google.com/chrome/">Google Chrome</a><br/>
				<a href="http://www.apple.com/safari/download/">Apple Safari</a><br/>
				<a href="http://www.google.com/chromeframe">Google Chrome Frame for Internet Explorer</a><br/></h1>
			</canvas>				
		</div>
		
		<h2>Règlement du jeu</h2>
		<ul>
			<li>
				Détail du prix : 1 voyage pour 2 personnes
				en catégorie standard, sans le vol international.
				<br/>Indication de prix : 1600 dollars (environ 1400 euros).
			</li>
			<li>
				Le meilleur score remporte le voyage.
			</li>
			<li>
				Le personnel de Shanti et les créateurs du jeu sont exclus du concours,
				ainsi que les scores obtenus en trichant.
			</li>
			<li>
				Le voyage gagné devra avoir lieu au cours de l'année 2014. 
			</li>
			<li>
				Le nom du/de la gagnant/e sera annoncé par email le 31 janvier 2014 à tous les
				participants ayant laissé leur adresse email.
				L'annonce figurera également sur nos pages Facebook et Twitter. 
			</li>
			<li>
				Une céremonie de récompense sera organisée sur Skype ou en direct.
			</li>
			<li>
				Shanti travel se réserve la possibilité d’utiliser votre emails à des fins commerciales,
				mais ne la divulguera à aucun de ses partenaires.
				L'utilisateur peut à tout moment se désinscrire de la newsletter Shanti
				en cliquant sur le lien de désinscription présent dans chaque email envoyé.
			</li>
		</ul>
		
		<h2>Terms and conditions</h2>
		<ul>
			<li>
				Price detail: 1 travel to India for 2 persons,
				(international flight not included).
				<br/>Price indication: 1600 dollars (1400 euros)
			</li>
			<li>
				The best score wins the trip.
			</li>
			<li>
				Are excluded from the game Shanti Travel staff, game creators and cheaters.
			</li>
			<li>
				The trip should happen during the year 2014.
			</li>
			<li>
				Winner will be announced by email on the 31st of January 2014 to all the
				participants who gave their emails.
				This annoucement will also be posted on Facebook and Twitter.
			</li>
			<li>
				An e-award ceremony will be organised on Skype.
			</li>
			<li>
				Shanti travel can use your email address for commercial purpose,
				but will not share it with any other third party.
				The user can unsubscribe from the newsletter at at any time by
				clicking on the unsubscripition link present in every sent email.
			</li>
		</ul>
		
	</div>
	
	
	
	
	<!-- Pages load faster with scripts at the bottom -->
	
	<!-- Construct 2 exported games require jQuery. -->
	<script src="jquery-2.0.0.min.js"></script>


	
    <!-- The runtime script.  You can rename it, but don't forget to rename the reference here as well.
    This file will have been minified and obfuscated if you enabled "Minify script" during export. -->
	<script src="c2runtime.js"></script>

    <script>
	
		// Resizer correction
		var myResize = function(){
			document.getElementById('c2canvasdiv_container').style.width
			 = document.getElementById('c2canvasdiv').style.width;
		};
	
		// Size the canvas to fill the browser viewport.
		jQuery(window).resize(function() {
			cr_sizeCanvas(jQuery(window).width(), jQuery(window).height());
			myResize();
		});
		
		// Start the Construct 2 project running on window load.
		jQuery(document).ready(function ()
		{			
			// Create new runtime using the c2canvas
			cr_createRuntime("c2canvas");
			myResize();
		});
		
		// Pause and resume on page becoming visible/invisible
		function onVisibilityChanged() {
			if (document.hidden || document.mozHidden || document.webkitHidden || document.msHidden)
				cr_setSuspended(true);
			else
				cr_setSuspended(false);
		};
		
		document.addEventListener("visibilitychange", onVisibilityChanged, false);
		document.addEventListener("mozvisibilitychange", onVisibilityChanged, false);
		document.addEventListener("webkitvisibilitychange", onVisibilityChanged, false);
		document.addEventListener("msvisibilitychange", onVisibilityChanged, false);
		
    </script>
</body> 
</html> 
