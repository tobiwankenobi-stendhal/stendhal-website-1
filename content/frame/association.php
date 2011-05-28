<?php 

$lang = 'en';
$dict = array();

function t($msgid) {
	global $dict;
	if (isset($dict[$msgid])) {
		return $dict[$msgid];
	}
	return $msgid;
}

?>

<body <?php echo $page->getBodyTagAttributes()?>>
<div id="container">
	<div id="header">
		<a href="<?php echo STENDHAL_FOLDER;?>/"><img style="border: 0;" src="<?php echo STENDHAL_FOLDER;?>/images/logo_association.png" alt=""></a>
	</div>
	<div id="topMenu"></div>

	<div id="leftArea">
		<?php startBox(t('Association')); ?>
		<ul id="associationmenu" class="menu">
			<?php 
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Faiumoni n. E.').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Statute').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Members').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Contact').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Donations').'</a></li>'."\n";
			?>
		</ul>
		<?php endBox(); ?>

		<?php startBox(t('Resources')); ?>
		<ul id="resourcemenu" class="menu">
			<?php
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Concept').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Projects').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Modules/Material').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Chat').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Events').'</a></li>'."\n";
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
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">Deutsch</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">English</a></li>'."\n";
			?>
		</ul>
		<?php
			endBox();

			startBox(t('Share'));
		?>
		<ul id="sharemenu" class="menu">
			<?php
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Facebook').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('Twitter').'</a></li>'."\n";
			echo '<li><a id="menuHelpFAQ" href="'.rewriteURL('/item/').'">'.t('eMail').'</a></li>'."\n";
			?>
		</ul>
		<?php
			endBox();
		?>
	</div>

	<div id="contentArea">
		<?php
			// The central area of the website.
		//	$page->writeContent();
		?>
	</div>

	<div id="footerArea">
		<span class="copyright">&copy; 1999-2011 <a href="http://arianne.sourceforge.net">Arianne Project</a>, 2011 Faiumoni n. E.</span>
	</div>
</div>
</body>
</html>