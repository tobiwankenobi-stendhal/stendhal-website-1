<?php

class DevelopmentPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Development'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

startBox("Development"); ?>  
<ul id="dmenu" >  
	<li><a href="/wiki/StendhalCodeDesign"><img src="/images/buttons/c_code_button.png">Code</a> - details of code designs and conventions</li>
	<li><a href="/hudson/"><img src="/images/buttons/c_code_button.png"><!-- TODO: add a nice icon -->Hudson</a> - continuous integration and testing</li>
	<li><a href="/wiki/StendhalRPProposal"><img src="/images/buttons/rpsystem_button.png">RP System</a> - proposed new system which is in planning stages</li>
	<li><a href="/wiki/StendhalRefactoringGraphics"><img src="/images/buttons/c_gfx_button.png">Graphics</a> - artists, this one is for you</li>
	<li><a href="/wiki/StendhalOpenTasks#SFX"><img src="/images/buttons/c_snd_button.png">Sounds &amp; Music</a> </li>
	<li><a href="/wiki/Stendhal_Quest_Contribution"><img src="/images/buttons/quests_button.png">Quests</a> - planned or ideas</li>
	<li><a href="/wiki/HowToAddItemsStendhal"><img src="/images/buttons/items_button.png">Items</a></li>
	<li><a href="/wiki/StendhalRefactoringAtlas"><img src="/images/buttons/atlas_button.png">Maps</a> - for map areas which need improvement</li>
	<li><a href="/wiki/HowToAddCreaturesStendhal"><img src="/images/buttons/creatures_button.png">Creatures</a> - mostly for your information</li>
</ul>
If you plan to help with Stendhal development it is a very good idea to talk about it with the developers and contributors at the <a href="<?php echo rewriteURL('/development/chat.html');?>">irc channel #arianne on freenode.</a>.  
<?php endBox();

	}
}
$page = new DevelopmentPage();
?>
  
