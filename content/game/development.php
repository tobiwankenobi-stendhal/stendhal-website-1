<?php

class DevelopmentPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Development'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

startBox("Stendhal, Arianne, and You"); ?>
<img src = "/images/ariannelogo.png"  style="float:right;" alt="Arianne Logo"  />
Stendhal is an open source project, written and released under the GNU GPL license by the <a href="http://arianne.sf.net">Arianne project</a>. We aim to make it easy to get involved and there are many ways to contribute. If you have an idea for a feature or something you would like different, you're very encouraged to make it happen!
<p>
You can contribute if you are a beginner or experienced Java coder, or just want to learn, artist, map maker, musician, writer, designer, and if you're not sure where you would fit in, you probably do, just take a look around. 
<p>
There are loads of tutorials on our wiki including how to set up the Eclipse development environment and get started with your first Java code for a quest, so it's an ideal project for beginners to programming, supported by a friendly development team.
<?php endBox(); ?>
<?php startBox("Development"); ?>
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
<?php endBox(); ?>
<?php startBox("Video"); ?>
<iframe width="560" height="315" src="https://www.youtube.com/embed/x4OpqCWAYaU" frameborder="0" allowfullscreen></iframe>
<br>0:01 Introduction
<br>1:32 Source code download
<br>2:11 Development portal from stendhalgame.org/development
<br>2:49 Stendhal Quest Contribution
<br>5:07 Graphics, Sound, Items, Maps, Creatures
<br>5:28 Chatting to developers in IRC
<br>7:20 Java Code navigation and design
<br>7:47 Bugs and features tracker
<br>9:50 Patch contribution
<br>10:00 Eclipse as an IDE
<br>10:20 Conclusion
<?php endBox();
	}
}
$page = new DevelopmentPage();