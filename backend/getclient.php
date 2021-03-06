<?php

require_once 'connectioncheck.php';  // answers if &connectioncheck is part of the URL ans exists

header('Access-Control-Allow-Origin: *', false); // this allows any cross-site scripting
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, if-modified-since'); // this allows any cross-site scripting (needed for chrome)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// necassary to force the browser not to use the cached version - changes here will not arrive the voter otherwise
header("Pragma: no-cache");
/*
header("Expires: Sat, 01 Jan 2005 00:00:00 GMT");
header("Last-Modified: ".gmdate( "D, d M Y H:i:s")."GMT");
*/
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: text/html; charset=utf-8');

$pathToClient = '../webclient/';

$includeJsFiles = Array(
		'tools/BigInt.js',
		'tools/rsa.js',
		'tools/sha256.js',
		'tools/filehandling.js',
		'tools/textencoder.js',
		
		'exception.js',
		'tools/mixed.js',
		'tools/url.js',
		'config/config.js',
		'getelectionconfig.js',
		'listoferrors.js',
		'tools/ua-parser.js',

		'tools/jed.js',
		'i18n/vvvote_de.js',
		'i18n/vvvote_en_US.js',
		'i18n/vvvote_fr.js',
		'tools/i18n.js',
		
		'modules-auth/user-passw-list/module.js',
		'modules-auth/shared-passw/module.js',
		'modules-auth/oauth2/module.js',
		'modules-auth/external-token/module.js',
		'modules-election/blinded-voter/module.js',
		'modules-election/blinded-voter/module-backend.js',
		'modules-tally/publish-only/transportencryption.js',
		'modules-tally/publish-only/module.js',
		'modules-tally/configurable-tally/module.js',
		'page.js',
		'newelection.js',
		'vote.js',
		'getresult.js',
		
		'index.js'

		/* Crypto-tool 
		'tools/jsrsasign-master/ext/jsbn.js',
		'tools/jsrsasign-master/ext/jsbn2.js',
		'tools/jsrsasign-master/ext/prng4.js',
		'tools/jsrsasign-master/ext/rng.js',
		'tools/jsrsasign-master/ext/rsa.js',
		'tools/jsrsasign-master/ext/rsa2.js',
		'tools/jsrsasign-master/ext/base64.js',

		// geprüft, sind notwendig ######## es wird eval() verwendet #########
		#base64: wegen rstring2hex() -->
		'tools/jsrsasign-master/base64x-1.1.js',
		'tools/jsrsasign-master/crypto-1.1.js',
		'tools/jsrsasign-master/core.js',
		'tools/jsrsasign-master/sha256.js',

		'tools/jsrsasign-master/rsasign-1.2.js',
		/* Crypto-tool Ende */
);

$includeCssFiles = Array('standard.css', 'substeps.css', 'working-animation.css', 'style_new.css', 'style_doc.css');

// print HTML-Header 
echo '
		<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width,initial-scale=1.0">
				<title>VVVote</title>';
// print all Javascript files 
echo '<script>';
$output_as_javascript = true; // interpreted by getpublicserverkeys.php
foreach ($includeJsFiles as $f) {
	if ($f == 'config/config.js') { // insert server infos immedeately in front of config.js
		include 'getserverinfos.php';
	}
	readfile($pathToClient . $f);
	echo "\r\n";
}

		
// print placeholder for JSON permission file
echo "\n//placeholder for permission file\n";
echo "//bghjur56zhbvbnhjiu7ztgfdrtzhvcftzujhgfgtgvkjskdhvfgdjfgcfkdekf9r7gdefggdfklhnpjntt\n";
echo '</script>';

// print stylesheets
echo '<style type="text/css">';
foreach ($includeCssFiles as $f) {
	readfile($pathToClient . $f);
}
echo '</style>';

echo '
<script type="text/javascript">
  $(document).ready(function(){
    setTimeout(function(){

      $(".evenTableRow>button, .unevenTableRow>button").click(function(){

        $("tr").not($(this).parents("tr").first()).removeClass("tr_active");
        $("tr").not($(this).parents("tr").first()).removeClass("cont_box");

        $(this).parents("tr").first().toggleClass( "tr_active" );
        setTimeout(function(){
          $(".slideShow").parents("tr").first().toggleClass( "tr_active cont_box" );
        });
      });

    },1000);
    setTimeout(function(){
      $(".votingOption>button").click(function(){

        $(this).toggleClass( "active" );
      });
    },1000);
  });
</script>';

// print the main content take from index.html - logo125x149.svg is included somewhere in the middle of the following text 
echo <<<EOT

</head>

<body onload="onWebsiteLoad(); onToggleTechInfosSwitch(); //startVoting(true); //test();" onClick="// rng_seed_time(); // better random" onKeyPress="// rng_seed_time(); // better random">
	<div id="errorDiv" style="display:none"></div>
	<!--  <div id="diagnosisControlDiv" style="display:none"></div>   -->
	<div class="wraper">
			<div id="errorDiv" style="display:none"></div>
			<!--  <div id="diagnosisControlDiv" style="display:none"></div>   -->
			<header>
				<div class="container">
					<div class="row">
						<div class="col-md-11">
							
							<nav class="navbar">
								<div class="navbar-header">
									<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									</button>
									<a href="/" class="navbar-brand" id="logo" tabindex="-1">
EOT;

									readfile($pathToClient . 'logo_hd47x51.svg');

echo <<<EOT
									</a>
									<span id="ciSubHead" class="slogan"></span>
								</div>
								
								<div class="collapse navbar-collapse top_menu" id="navbar-collapse-1">
									<ul class="nav navbar-nav">
										<li><a id="newElectionLink" href="javascript:page = newElectionPage; page.display(); // handleNewElection();"  >Neue Abstimmung anlegen</a></li>
										<li><a id="takepartLink"    href="javascript:page = votePage;        page.display(); // startVoting(true);"    >An Abstimmung teilnehmen</a></li>
										<li><a id="fetchresult"     href="javascript:page = getResultPage;   page.display(); // startLoadingResult();" >Abstimmungsergebnis abrufen</a></li>
									</ul>
								</div>
							</nav>
						</div>
						<div class="col-md-1">
							<select id="locale_select" onChange="changeLanguage(this.value)">
								<option selected="selected" value="de">De</option>
								<option value="en_US">En</option>
								<option value="fr">Fr</option>
							</select>
						</div>
					</div>
				</div>
			</header>
			<div class="container">
				<div class="row filter_row">
					<div class="col-md-3">
						<h1 id="pagetitle" class="vvvote_title">An Abstimmung teilnehmen</h1>
					</div>
					<div class="col-md-9">
						<div id="steps">
							<div id="idstepstitle">Vorgehensweise</div>
							<ul id="stepslist">
								<li><span id="step1" class="curr">1. Wahlunterlagen holen</span></li>
								<li><span id="step2">2. Autorisierung</span></li>
								<li><span id="step3"><a onclick="startStep3();">3. Stimme abgeben</a></span></li>
								<li><span id="step4"><a onclick="startStep4();">4. Abstimmungsergebnis holen</a></span></li>
							</ul>
						</div>
					</div>
					
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="all">


		<div id="maincontent">
			<!-- this div is replaced by the html of the according auth-module -->
			<div id="loadedmaincontent">
			<script type="text/javascript">
				// document.write('');
			</script>
			</div>
		</div>

		<div id="techinfosswitch">
			<input type="checkbox" class="hidden check" name="techinfocheckbox" id="techinfocheckbox" value="techinfocheckbox" onclick="onToggleTechInfosSwitch();">
			<label class="orange_but" for="techinfocheckbox" id="idtechinfocheckbox"> </label>
		</div>

		<div id="techinfos" style="display:none;">
			<div id="additiontechinfos"></div>
			<div id="log">
				<h1>Log:</h1>
				<textarea id="logtextarea" name="log"></textarea>
			</div>
		</div>


	</div>
	</div>
		</div>
	</div>
	</div>
	<footer>
	<div class="footer_wraper">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="copyright">
<div class="logo_foot" style="width: 30px;display: inline-block;vertical-align: middle;">						
EOT;

readfile($pathToClient . 'logo_auto.svg');

echo <<<EOT
</div>
						<span>powered by <a href="/">VVVote</a></span>
							</div>
						</div>
					</div>
				</div>
				</div>
			</footer>
</body>
</html>

EOT;
