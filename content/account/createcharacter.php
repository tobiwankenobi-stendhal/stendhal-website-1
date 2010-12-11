<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2010 The Arianne Project

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class CreateCharacterPage extends Page {
	private $outfitArray;
	private $result;

	/**
	 * this method can write additional http headers, for example for cache control.
	 *
	 * @return true, to continue the rendering, false to not render the normal content
	 */
	public function writeHttpHeader() {
		if (!isset($_SESSION['account'])) {
			// Show link to login box
			return true;
		}

		return $this->process();
	}


	public function writeHtmlHeader() {
		echo '<title>Create Character'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<style type="text/css">
				.outfitpanel {border: 1px solid black; width: 8.5em; height: 256px; padding: 0em; float: left; margin-right: 2em; margin-bottom: 2em}
				.next {float: left; margin-top: 2em}
				.outfitpart {float: left; display: block; width:48px; height: 64px; background-position: 0 128px;}
			</style>';
		echo '<script src="/css/jquery-00000001.js" type="text/javascript"></script>';
	}

	public function getBodyTagAttributes() {
		return 'onload="init()"';
	}

	function writeContent() {
		if (!isset($_SESSION['account'])) {
			startBox("Create Character");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url=/account/create-character.html">login</a> to create a character.</p>';
			endBox();
		} else {
			$this->show();
		}
	}
	
	function process() {
		global $protocol;
		if (! isset($_POST['name'])) {
			return true;
		}

		if ($_POST['csrf'] != $_SESSION['csrf']) {
			return true;
		}

		require_once('scripts/pharauroa/pharauroa.php');
		$clientFramework = new PharauroaClientFramework(STENDHAL_MARAUROA_SERVER, STENDHAL_MARAUROA_PORT, STENDHAL_MARAUROA_CREDENTIALS);
		$template = new PharauroaRPObject();
		$template->put('outfit', $_REQUEST['outfitcode']);

		$this->result = $clientFramework->createCharacter($_SESSION['account']->username, $_REQUEST['name'], $template);

		if ($this->result->wasSuccessful()) {
			// redirect to my characters page
			header('HTTP/1.0 301 Moved permanently.');
			header("Location: ".$protocol."://".$_SERVER['HTTP_HOST'].preg_replace("/&amp;/", "&", rewriteURL('/account/mycharacters.html')));
			return false;
		} else {
			return true;
		}
	}
	

	function show() {
		$this->initOutfitArray();

		if (isset($_POST['name']) && ($_POST['csrf'] != $_SESSION['csrf'])) {
			startBox("Error");
			echo '<p class="error">Session information was lost.</p>';
			endBox();
		}

		if (isset($this->result) && !$this->result->wasSuccessful()) {
			startBox("Error");
			echo '<span class="error">'.htmlspecialchars($this->result->getMessage()).'</span>';
			endBox();
		}

		startBox("Create Character");
?>

<form name="createcharacter" action="<?php echo rewriteURL('/account/create-character.html');?>" method="POST" style="height:22em; padding: 1em"> <!-- onsubmit="return checkForm()"  -->
<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf'])?>">

<div class="outfitpanel">
<div style="clear: both">
<input class="next" type="button" onclick="down(0)" value="&lt;">
<span id="outfit0" class="outfitpart"></span>
<input class="next" type="button" onclick="up(0)" value="&gt;">
</div>

<div style="clear: both">
<input class="next" type="button" onclick="down(1)" value="&lt;">
<span id="outfit1" class="outfitpart"></span>
<input class="next" type="button" onclick="up(1)" value="&gt;">
</div>


<div style="clear: both">
<input class="next" type="button" onclick="down(2)" value="&lt;">
<span id="outfit2" class="outfitpart"></span>
<input class="next" type="button" onclick="up(2)" value="&gt;">
</div>


<div style="clear: both">
<input class="next" type="button" onclick="down(3)" value="&lt;">
<span id="outfit3" class="outfitpart"></span>
<input class="next" type="button" onclick="up(3)" value="&gt;">
</div>

</div>

<div style="float:left; width: 50%">
<span id="canvas" style="border: 1px solid #AAA; display: block; width: 48px; height: 64px"></span>
<input class="next" type="button" onclick="turn(-1)" value="l">
<input class="next" type="button" onclick="turn(1)" value="r">
</div>


<div style="float:left; width: 50%; padding-top: 2em">
<input id="outfitcode" name="outfitcode" type="hidden" value="01010101">
<label for="name" >Name: </label><input id="name" onchange="nameChanged(this)" onkeyup="nameChanged(this)" name="name" type="text" maxlength="20" 
<?php 
	if (isset($_REQUEST['name'])) {
		echo 'value="'.htmlspecialchars($_REQUEST['name']).'"';
	} else {
		$username = Account::convertToValidUsername($_SESSION['account']->username);
		// don't suggest charnames based on the long ugly openid identifiers of google and yahoo
		if (strlen($username) < 20 && !doesCharacterExist($username)) {
			echo 'value="'.htmlspecialchars($username).'"';
		}
	}
?>>
<div id="warn" class="warn">&nbsp;</div>
<input name="submit" style="margin-top: 2em" type="submit" value="Create Character">
</div>
</form>

<script type="text/javascript">
faceOffset = 2;
currentOutfit = [<?php
echo $this->outfitArray[0].', '.$this->outfitArray[1].', '.$this->outfitArray[2].', '.$this->outfitArray[3];
?>];
maxOutfit = [44, 21, 15, 53];


outfitNames = ["hair", "head", "player_base", "dress"];

function down(i) {
	currentOutfit[i]--;
	if (currentOutfit[i] < 0) {
		currentOutfit[i] = maxOutfit[i] - 1;
	}
	update(i);
}

function up(i) {
	currentOutfit[i] = (currentOutfit[i] + 1) % maxOutfit[i];
	update(i);
}

function formatNumber(i) {
	if (i < 10) {
		return "0" + i;
	} else {
		return ""+i;
	}
}

function update(i) {
	document.getElementById("outfit" + i).style.backgroundImage = "url('/data/sprites/outfit/" + outfitNames[i] + "_" + currentOutfit[i] + ".png')";
	outfitCode = formatNumber(currentOutfit[0]) + formatNumber(currentOutfit[1]) + formatNumber(currentOutfit[3]) + formatNumber(currentOutfit[2]);
	document.getElementById("outfitcode").value = outfitCode;
	document.getElementById("canvas").style.backgroundImage = "url('/createoutfit.php?offset=" + faceOffset + "&outfit=" + outfitCode + "')";
}

function init() {
	updateAll();
	self.focus();
	document.createcharacter.name.focus();
}

function updateAll() {
	for (i = 0; i < 4; i++) {
		document.getElementById("outfit" + i).style.backgroundImage = "url('/data/sprites/outfit/" + outfitNames[i] + "_" + currentOutfit[i] + ".png')";
	}
	outfitCode = formatNumber(currentOutfit[0]) + formatNumber(currentOutfit[1]) + formatNumber(currentOutfit[3]) + formatNumber(currentOutfit[2]);
	document.getElementById("outfitcode").value = outfitCode;
	document.getElementById("canvas").style.backgroundImage = "url('/createoutfit.php?offset=" + faceOffset + "&outfit=" + outfitCode + "')";
}

function turn(i) {
	faceOffset = (faceOffset + i) % 4;
	if (faceOffset < 0) {
		faceOffset = 3;
	}
	cssOffset = 4 - faceOffset;

	for (i = 0; i < 4; i++) {
		document.getElementById("outfit" + i).style.backgroundPosition = "0 " + (cssOffset * 64) + "px";
	}
	outfitCode = formatNumber(currentOutfit[0]) + formatNumber(currentOutfit[1]) + formatNumber(currentOutfit[3]) + formatNumber(currentOutfit[2]);
	document.getElementById("canvas").style.backgroundImage = "url('/createoutfit.php?offset=" + faceOffset + "&outfit=" + outfitCode + "')";
}

function validateMinLength(field) {
	if (field.value.length >= 6) {
		document.getElementById("warn").innerHTML = "&nbsp;";
		minLengthOnceReached = true;
		return true;
	} else {
		if (minLengthOnceReached) {
			document.getElementById("warn").innerHTML = "Must be at least 6 letters.";
		}
	}
	return false;
}

var lastRequestedName = "";
var minLengthOnceReached = false;
function nameChanged(field) {
	field.value = field.value.toLowerCase().replace(/[^a-z]/g,"");
	if (lastRequestedName != field.value) {
		lastRequestedName = field.value;
		var res = validateMinLength(field);
		if (res) {
			$.getJSON("<?php echo STENDHAL_FOLDER;?>/index.php?id=content/scripts/api&method=isNameAvailable&ignoreAccount=<?php echo htmlspecialchars($_SESSION['account']->username);?>&param=" + escape(lastRequestedName), function(data) {
				if (lastRequestedName == data.name) {
					if (data.result) {
						document.getElementById("warn").innerHTML = "&nbsp;";
					} else {
						document.getElementById("warn").innerHTML = "This name is not available.";
					}
				}
			});
		}
	}
}


function checkForm() {
	var name = document.getElementById("name");
	if (name.value.length < 6) {
		name.focus();
		alert("Your character name needs to be at least 6 letters long.");
		return false;
	}
	return true;
}
</script>
<?php
		endBox();
	}

	
	function initOutfitArray() {
		if (isset($_REQUEST['outfitcode'])) {
			$code = intval($_REQUEST['outfitcode'], 10);
			$this->outfitArray[2] = $code % 100;
			$this->outfitArray[3] = $code / 100 % 100;
			$this->outfitArray[1] = $code / 10000 % 100;
			$this->outfitArray[0] = $code / 1000000 % 100;
		} else {
			$this->randomOutfit();
		}
	}

	function randomOutfit() {
		$maxRndOutfit = array(26, 15, 5, 15);
		for ($i = 0; $i < 4; $i++) {
			$this->outfitArray[$i] = rand(1, $maxRndOutfit[$i]);
		}
	}

}

$page = new CreateCharacterPage();

?>