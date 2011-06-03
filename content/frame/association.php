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
		// TODO: remove this
		header('X-XRDS-Location: '.STENDHAL_LOGIN_TARGET.'/?id=content/account/openid-provider&xrds');
		// TODO END
		return 'content/association/main';
	}

	/**
	 * renders the frame
	 */
	function renderFrame() {
		global $page, $lang;
?>
<body>
<div id="container">
	<div id="header">
		<a href="<?php echo STENDHAL_FOLDER;?>/"><img style="border: 0;" src="<?php echo STENDHAL_FOLDER;?>/images/logo_association.png" alt=""></a>
	</div>
	<div id="topMenu"></div>

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

	<div id="contentArea">
		<?php
			// The central area of the website.
			$page->writeContent();
		?>
	</div>

	<div id="footerArea">
		<span class="copyright">&copy; 1999-2011 <a href="http://arianne.sourceforge.net">Arianne Project</a>, 2011 Faiumoni n. E.</span>
	</div>
</div>
</body>
</html>

<?php 
	}
}
$frame = new AssociationFrame();
