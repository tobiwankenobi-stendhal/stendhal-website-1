<?php
class CreateCharacterPage extends Page {
	private $outfitArray;

	public function writeHtmlHeader() {
		echo '<title>Create Character'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<style type="text/css">
				.outfitpanel {border: 1px solid black; width: 8.5em; height: 256px; padding: 0em; float: left; margin-right: 2em; margin-bottom: 2em}
				.next {float: left; margin-top: 2em}
				.outfitpart {float: left; display: block; width:48px; height: 64px; background-position: 0 128px;}
			</style>';
	}

	public function getBodyTagAttributes() {
		return 'onload="init()"';
	}

	function writeContent() {
		/* TODO
		if (!isset($_SESSION['username'])) {
			startBox("Create Character");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url=/account/create-character.html">login</a> to create a character.</p>';
			endBox();
		} else {*/
			// TODO: init $outfitArray from url
			$this->random();
			$this->process();
		//}
	}

	function random() {
		$maxRndOutfit = array(26, 15, 5, 15);
		for ($i = 0; $i < 4; $i++) {
			$this->outfitArray[$i] = rand(1, $maxRndOutfit[$i]);
		}
	
}
	

	function process() {
		startBox("Create Character");
?>

<form name="createcharacter" action="" style="height:22em; padding: 1em">

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
<label for="name" >Name: </label><input id="name" onchange="key(this)" onkeyup="key(this)" name="name" type="text" maxlength="20" 
<?php 
// TODO: if account name is a valid charactername, and the character does not exist {
	echo 'value="'.htmlspecialchars($_SESSION['username']).'"';?>>
<div id="warn" class="warn">S</div>
<input "name="submit" style="margin-top: 2em" type="submit" value="Create Character">
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
	document.getElementById("canvas").style.backgroundImage = "url('/images/outfit/" + outfitCode + ".png')";
}

function init() {
	updateAll();
	self.focus();
	document.createcharacter.name.focus();
	validate();
}

function updateAll() {
	for (i = 0; i < 4; i++) {
		document.getElementById("outfit" + i).style.backgroundImage = "url('/data/sprites/outfit/" + outfitNames[i] + "_" + currentOutfit[i] + ".png')";
	}
	outfitCode = formatNumber(currentOutfit[0]) + formatNumber(currentOutfit[1]) + formatNumber(currentOutfit[3]) + formatNumber(currentOutfit[2]);
	document.getElementById("outfitcode").value = outfitCode;
	document.getElementById("canvas").style.backgroundImage = "url('/createoutfit.php?offset=" + faceOffset + "&outfit=" + outfitCode + "')";
}

function validate() {
	//document.createcharacter.submit.disabled = (document.createcharacter.name.value.length < 4);
	if (document.createcharacter.name.value.length < 4) {
		document.getElementById("warn").innerHTML = "Name must be more than 4 letters.";
	} else {
		document.getElementById("warn").innerHTML = "";
	}
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

function key(field) {
	field.value = field.value.toLowerCase().replace(/[^a-z]/g,"");
	validate();
}

</script>
<?php
		endBox();
	}
}

$page = new CreateCharacterPage();

?>