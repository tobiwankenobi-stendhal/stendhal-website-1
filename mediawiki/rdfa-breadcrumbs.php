<?php

// License: CC-BY 2010 Hendrik Brummermann <nhb_web@nexgo.de>


# Define a setup function
$wgHooks['ParserFirstCallInit'][] = 'efRDFaBreadcrumbs_Setup';
# Add a hook to initialise the magic word
$wgHooks['LanguageGetMagic'][]       = 'efRDFaBreadcrumbs_Magic';

$wgExtensionCredits['parserhook'][] = array(
	'name' => 'RDFa_Breadcrumbs',
	'version' => '1.0',
	'url' => 'http://www.mediawiki.org/wiki/Extension:RDFa_Breadcrumbs',
	'author' => 'Hendrik Brummermann',
	'description' => 'Breadcrumbs with RDFa markup',
);


function efRDFaBreadcrumbs_Setup( &$parser ) {
	# Set a function hook associating the "example" magic word with our function
	$parser->setFunctionHook('breadcrumbs', 'RDFaBreadcrumbs_Render' );
	return true;
}

function efRDFaBreadcrumbs_Magic( &$magicWords, $langCode ) {
	# Add the magic word
	# The first array element is whether to be case sensitive, in this case (0) it is not case sensitive, 1 would be sensitive
	# All remaining elements are synonyms for our parser function
	$magicWords['breadcrumbs'] = array( 0, 'breadcrumbs' );
	# unless we return true, other parser functions extensions won't get loaded.
	return true;
}

function RDFaBreadcrumbs_Render($parser) {
	$output = '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
	for ($i = 1; $i < func_num_args(); $i++) {
		if ($i > 1) {
			$output .= ' &gt; ';
		}
		$output .= '<span typeof="v:Breadcrumb">';
		$item = trim(func_get_arg($i));
		$output .= '<a href="'.htmlspecialchars(getURLFromLink($item))
			.'" rel="v:url" property="v:title">';
		$output .= htmlspecialchars(getTextFromLink($item));
		$output .= '</a></span>';
	}
	$output = $output . '</div>';
	return array($output, 'noparse' => true, 'isHTML' => true);
}

function getURLFromLink($link) {
	global $wgArticlePath;
	if (strpos($link, '[[') === 0) {
		// internal link
		$delimiter = '|';
		$start = 2;
		$pos2 = strpos($link, $delimiter);
		if ($pos2 === FALSE) {
			$pos2 = strlen($link) - 2;
		}
		$url = substr($link, $start, $pos2 - $start);
		return trim(preg_replace('/\$1/', urlencode(preg_replace('/[ +]/', '_', trim($url))), $wgArticlePath));
	} else if (strpos($link, '[') === 0) {
		// external link
		$delimiter = ' ';
		$start = 1;
		$pos2 = strpos($link, $delimiter);
		if ($pos2 === FALSE) {
			$pos2 = strlen($link) - 1;
		}
		$url = substr($link, $start, $pos2 - $start);
		return trim($url);
	} else {
		return trim(preg_replace('/\$1/', urlencode(preg_replace('/[ +]/', '_', trim($link))), $wgArticlePath));
	}
}

function getTextFromLink($link) {
	if (strpos($link, '[[') === 0) {
		// internal link
		$delimiter = '|';
		$start = 1;
	} else if (strpos($link, '[') === 0) {
		// external link
		$delimiter = ' ';
		$start = 0;
	} else {
		return trim($link);
	}
	$pos1 = strpos($link, $delimiter);
	if ($pos1 === FALSE) {
		$pos1 = $start;
	}
	$pos2 = strpos($link, ']');
	return trim(substr($link, $pos1 + 1, $pos2 - $pos1 - 1));
}
