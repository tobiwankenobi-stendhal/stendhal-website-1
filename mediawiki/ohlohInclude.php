<?php
/*
 * Ohloh.php - allows inclusion of ohloh stats
 */

# Confirm MW environment
if (defined('MEDIAWIKI')) {

	# Credits
	$wgExtensionCredits['parserhook'][] = array(
		'name'=>'Ohloh Include',
		'author'=>'Hendrik Brummermann',
		'description'=>'Allows inclusion of ohloh stats.',
		'version'=>'0.1'
	);

	class OhlohInclude {

		function setup( ) {
			global $wgParser, $wgVersion;
			$hook = (version_compare($wgVersion, '1.7', '<')?'#ohloh':'ohloh');
			$wgParser->setFunctionHook($hook, array($this, 'parserFunction') );
		}

		function parserFunctionMagic( &$magicWords, $langCode='en' ) {
			$magicWords['ohloh'] = array( 0, 'ohloh' );
			return true;
		}

		function parserFunction($parser, $class, $id, $stat) {
			$class = htmlspecialchars(urlencode($class));
			$id = htmlspecialchars(urlencode($id));
			$stat = htmlspecialchars(urlencode($stat));

			return $parser->insertStripItem(
				'<script type="text/javascript" src="http://www.ohloh.net/'.$class.'/'.$id.'/widgets/'.$stat.'.js"></script>',
				$parser->mStripState);
		}

	}

	# Create global instance and wire it up!
	$wgOhlohInclude = new OhlohInclude();
	$wgExtensionFunctions[] = array($wgOhlohInclude, 'setup');
	$wgHooks['LanguageGetMagic'][] = array($wgOhlohInclude, 'parserFunctionMagic');

}

