<?php
/**
 * pharauroa client framework
 */
class PharauroaClientFramework {
	private $credentials;
	
	/**
	 * creates a new PharauroaClientFramework
	 *
	 * @param $credentials credentials for proxy message authentication
	 */
	function __construct($credentials) {
		$this->credentials = $credentials;
	}
}
?>
