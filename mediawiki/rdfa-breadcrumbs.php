<?php
# Define a setup function
$wgHooks['ParserFirstCallInit'][] = 'efRDFaBreadcrumbs_Setup';
# Add a hook to initialise the magic word
$wgHooks['LanguageGetMagic'][]       = 'efRDFaBreadcrumbs_Magic';

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
	$output = '<div xmlns:v="http://rdf.data-vocabulary.org/#">';
	for ($i = 1; $i < func_num_args(); $i++) {
		if ($i > 1) {
			$output .= '&gt;';
		}
		$output .= '<span typeof="v:Breadcrumb">';
		$output .= '<a href="/wiki/'.urlencode(preg_replace('/[ +]/', '_', func_get_arg($i)))
			.'" rel="v:url" property="v:title">';
		$i++;
		$output .= htmlspecialchars(func_get_arg($i));
		$output .= '</a></span>';
	}
	$output = $output . '</div>';
	return array($output, 'noparse' => true, 'isHTML' => true);
}
