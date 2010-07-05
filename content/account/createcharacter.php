<?php
class CreateCharacterPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Create Character'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<style type="text/css">
				.outfitpanel {border: 1px solid black; width: 8.5em; height: 256px; padding: 2em; float: left; margin-right: 2em; margin-bottom: 2em}
				.next {float: left; margin-top: 2em}
				.outfitpart {float: left; display: block; width:48px; height: 64px; background: 0 128px;}
			</style>';
	}

	function writeContent() {
		if (!isset($_SESSION['username'])) {
			startBox("Create Character");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url=/account/create-character.html">login</a> to create a character.</p>';
			endBox();
		} else {
			$this->process();
		}
	}
	
	function process() {
		// TODO: <body onload="updateAll();">
		startBox("Create Character");
?>

<form action="">

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

<span id="canvas" style="border: 1px solid #AAA; float: left; display: block; width: 48px; height: 64px"></span>


<input id="outfitcode" name="outfitcode" type="hidden" value="01010101">
<label for="name">Name: </label><input id="name" name="name" type="text">

<br><br>
<input type="submit" value="Create Character">
</form>



<script type="text/javascript">
currentOutfit = [1, 0, 0, 1];
maxOutfit = [44, 21, 15, 53];
outfitNames = ["hair", "head", "player_base", "dress"];

function down(i) {
	if (currentOutfit[i] > 0) {
		currentOutfit[i]--;
		update(i);
	}
}

function up(i) {
	if (currentOutfit[i] < maxOutfit[i] - 1) {
		currentOutfit[i]++;
		update(i);
	}
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

function updateAll(i) {
	for (i = 0; i < 4; i++) {
		document.getElementById("outfit" + i).style.backgroundImage = "url('/data/sprites/outfit/" + outfitNames[i] + "_" + currentOutfit[i] + ".png')";
	}
	outfitCode = formatNumber(currentOutfit[0]) + formatNumber(currentOutfit[1]) + formatNumber(currentOutfit[3]) + formatNumber(currentOutfit[2]);
	document.getElementById("outfitcode").value = outfitCode;
	document.getElementById("canvas").style.backgroundImage = "url('/images/outfit/" + outfitCode + ".png')";
}
</script>
<?php
		endBox();
	}
}

$page = new CreateCharacterPage();

?>