<body <?php echo $page->getBodyTagAttributes()?>>
<div id="container">

	<div id="topMenu"></div>

	<div id="leftArea">

		<?php startBox('Game System'); ?>
		<ul id="gamemenu" class="menu">
			<?php 
			echo '<li><a id="menuAtlas" href="'.$protocol.'://stendhalgame.org/wiki/StendhalAtlas">Atlas</a></li>'."\n";
			echo '</ul>';
		endBox(); ?>

		<?php startBox('Help'); ?>
		<ul id="helpmenu" class="menu">
			<li><a id="menuHelpManual" href="<?php echo $protocol;?>://stendhalgame.org/wiki/StendhalManual">Manual</a></li>
		</ul>
		<?php endBox() ?>

	</div>

	<div id="rightArea">

		<?php startBox('Players'); ?>
		<ul class="menu">
			<li style="white-space: nowrap"><a id="menuPlayerOnline" href="<?php echo rewriteURL('/world/online.html');?>"><b style="color: #00A; font-size:16px;"><?php echo getAmountOfPlayersOnline(); ?></b>&nbsp;Players&nbsp;Online</a></li>
		</ul>
		<?php endBox(); ?>


		<?php startBox('Contribute'); ?>
		<ul id="contribmenu" class="menu">
			<?php
			echo '<li><a id="menuContribWiki" href="'.$protocol.'://stendhalgame.org/wiki/Stendhal">Wiki</a></li>'."\n";
			?>
		</ul>
		<?php endBox(); ?>
	</div>

	<div id="contentArea">
	
	
		<?php
		
			startBox('Setup instructions.');
			echo '<p> This is the default navigation frame.</p><p>You can create your own file in content/frame and specify it in the STENDHAL_FRAME variable in configuration.php.</p>';
			endBox();
		
		
			// The central area of the website.
			$page->writeContent();
		?>
	</div>

	<div id="footerArea">
		<span class="copyright">&copy; 1999-2011 <a href="http://arianne.sourceforge.net">Arianne Project</a></span>
		<span><a id="footerSourceforge" href="http://sourceforge.net/projects/arianne">&nbsp;</a></span>
	</div>

	<div class="time">
		<?php
		// Show the server time
		echo 'Server time: '.date('G:i');
		?>
	</div>
</div>
</body>
</html>