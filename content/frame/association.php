<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2011  The Arianne Project
 Copyright (C) 2011-2011  Faiumoni n. E.

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


$lang = 'en';
if ($_REQUEST['lang'] == 'de') {
	$lang = 'de';
}
// TODO: guess German
$lang = urlencode($lang);
$dict = array();

function t($msgid) {
	global $dict;
	if (isset($dict[$msgid])) {
		return $dict[$msgid];
	}
	return $msgid;
}

class AssociationFrame extends PageFrame {

	/**
	 * gets the default page in case none is specified.
	 *
	 * @return name of default page
	 */
	function getDefaultPage() {
		return 'content/association/main';
	}

	/**
	 * this method can write additional http headers, for example for cache control.
	 *
	 * @return true, to continue the rendering, false to not render the normal content
	 */
	function writeHttpHeader($page_url) {
		global $protocol;
		if (strpos($page_url, 'content/association/') !==0) {
			header('Location: '.$protocol.'://'.STENDHAL_SERVER_NAME);
			return false;
		}
		return true;
	}

	/**
	 * this method can write additional html headers.
	 */
	function writeHtmlHeader() {
		?>
<style type="text/css">
body {
	background-color:#FFF;
	background-image:none;
}
#header {
	padding: 20px 0 20px 10px;
}
#bodycontainer {
	background-image: url("/images/association/inner_background.jpg");
	background-repeat: no-repeat;
}
#container {
	background-image: none;
	border: none;
}
#leftArea {
	margin: 0 5px 0 0;
}
#rightArea {
	margin: -80px 0 0 5px;
}
.box {
	background-image: url("../images/semi_transparent.png");
	background-color: transparent;
	border-radius: 15px;
}
.boxTitle {
	border-radius: 15px;
	padding-left: 1em;
}
#footerArea {
	border-top: none
}

.versionInformation {
	font-size:60%;
	text-align:right
}
</style>
		<?php
	}

	/**
	 * renders the frame
	 */
	function renderFrame() {
		global $page, $lang;
?>
<body>
<div id="contentArea" style="position:relative; top: 34px; z-index: 1; width:590px">
	<?php
		// The central area of the website.
		$page->writeContent();
	?>
</div>
<div id="bodycontainer" style="width:100%; height:100%; position:fixed; top:0px; z-index:0">
<div id="container" style="position:fixed; top:0px; z-index:0">
	<div id="header">
		<a href="<?php echo STENDHAL_FOLDER;?>/"><img style="border: 0;" src="<?php echo STENDHAL_FOLDER;?>/images/association/logo.png" alt=""></a>
	</div>

	<div id="leftArea">
		<?php startBox(t('Association')); ?>
		<ul id="associationmenu" class="menu">
			<?php 
			echo '<li><a id="menuAssociationAbout" href="'.rewriteURL('/'.$lang.'/about.html').'">'.t('Faiumoni n. E.').'</a></li>'."\n";
			echo '<li><a id="menuAssociationNews" href="'.rewriteURL('/'.$lang.'/news.html').'">'.t('News').'</a></li>'."\n";
			echo '<li><a id="menuAssociationStatue" href="'.rewriteURL('/'.$lang.'/statute.html').'">'.t('Statute').'</a></li>'."\n";
			echo '<li><a id="menuAssociationMembers" href="'.rewriteURL('/'.$lang.'/members.html').'">'.t('Members').'</a></li>'."\n";
			echo '<li><a id="menuAssociationContact" href="'.rewriteURL('/'.$lang.'/contact.html').'">'.t('Contact').'</a></li>'."\n";
			echo '<li><a id="menuAssociationDonations" href="'.rewriteURL('/'.$lang.'/donate.html').'">'.t('Donate').'</a></li>'."\n";
			?>
		</ul>
		<?php endBox(); ?>

		<?php startBox(t('Resources')); ?>
		<ul id="resourcemenu" class="menu">
			<?php
			echo '<li><a id="menuResourceConcept" href="'.rewriteURL('/'.$lang.'/concept.html').'">'.t('Concept').'</a></li>'."\n";
			echo '<li><a id="menuResourceProjects" href="'.rewriteURL('/'.$lang.'/projects.html').'">'.t('Projects').'</a></li>'."\n";
			echo '<li><a id="menuResourceModules" href="'.rewriteURL('/'.$lang.'/modules.html').'">'.t('Modules/Material').'</a></li>'."\n";
			echo '<li><a id="menuResourceChat" href="'.rewriteURL('/'.$lang.'/chat.html').'">'.t('Chat').'</a></li>'."\n";
			echo '<li><a id="menuResourceEvents" href="'.rewriteURL('/'.$lang.'/events.html').'">'.t('Events').'</a></li>'."\n";
			?>
		</ul>
		<?php endBox() ?>

	</div>

	<div id="rightArea">
		<?php
			startBox(t('Language'));
		?>
		<ul id="languagemenu" class="menu">
			<?php
			echo '<li><a id="menuLangDe" href="'.rewriteURL('/de/'.urlencode($_REQUEST['title']).'.html').'">Deutsch</a></li>'."\n";
			echo '<li><a id="menuLangEn" href="'.rewriteURL('/en/'.urlencode($_REQUEST['title']).'.html').'">English</a></li>'."\n";
			?>
		</ul>
		<?php
			endBox();

			startBox(t('Share'));
		?>
		<ul id="sharemenu" class="menu">
			<?php
			echo '<li><a id="menuShareFacebook" href="TODO">'.t('Facebook').'</a></li>'."\n";
			echo '<li><a id="menuShareTwitter" href="TODO">'.t('Twitter').'</a></li>'."\n";
			echo '<li><a id="menuShareEMail" href="'.rewriteURL('/'.$lang.'/email.html').'">'.t('eMail').'</a></li>'."\n";
			?>
		</ul>
		<?php
			endBox();
		?>
	</div>

	<div id="footerArea">
		<span>&copy; 1999-2011 <a href="http://arianne.sourceforge.net">Arianne Project</a>, 2011 Faiumoni n. E.</span>
	</div>
</div>
</div>
</body>
</html>

<?php 
	}
}
$frame = new AssociationFrame();
