<?php

class DevelopmentPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Development'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

startBox("Development"); ?>
<ul id="dmenu" >
	<li><a href="/wiki/StendhalCodeDesign"><img src="/images/buttons/c_code_button.png" alt="">Code</a> - details of code designs and conventions</li>
	<li><a href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/inspect"><img src="/images/buttons/faq_button.png" alt="">Render Inspect</a> - easy readable form of /script DeepInspect.class</li>
	<li><a href="/jenkins/"><img src="/images/buttons/c_code_button.png" alt="">Jenkins</a> - continuous integration and testing</li>
	<li><a href="/wiki/StendhalRPProposal"><img src="/images/buttons/rpsystem_button.png" alt="">RP System</a> - proposed new system which is in planning stages</li>
	<li><a href="/wiki/StendhalRefactoringGraphics"><img src="/images/buttons/c_gfx_button.png" alt="">Graphics</a> - artists, this one is for you</li>
	<li><a href="/wiki/StendhalOpenTasks#SFX"><img src="/images/buttons/c_snd_button.png" alt="">Sounds &amp; Music</a> </li>
	<li><a href="/wiki/Stendhal_Quest_Contribution"><img src="/images/buttons/quests_button.png" alt="">Quests</a> - planned or ideas</li>
	<li><a href="/wiki/HowToAddItemsStendhal"><img src="/images/buttons/items_button.png" alt="">Items</a></li>
	<li><a href="/wiki/StendhalRefactoringAtlas"><img src="/images/buttons/atlas_button.png" alt="">Maps</a> - for map areas which need improvement</li>
	<li><a href="/wiki/HowToAddCreaturesStendhal"><img src="/images/buttons/creatures_button.png" alt="">Creatures</a> - mostly for your information</li>
</ul>
If you plan to help with Stendhal development it is a very good idea to talk about it with the developers and contributors at the <a href="<?php echo rewriteURL('/development/chat.html');?>">irc channel #arianne on freenode.</a>.  
<?php endBox();

	}
}
$page = new DevelopmentPage();