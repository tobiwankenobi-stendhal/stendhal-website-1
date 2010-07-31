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

	/**
	 * creates a new account
	 */
	function createAccount($username, $password, $email) {
		// Create message
		$message = new PharauroaMessageP2SCreateAccount();
		$message->init($username, $password, $email);

		// Serialize message
		$serializer = new PharauroaSerializer();
		$message->writeObject($serializer);
		$data = $serializer->getData();

		// connect and write message
		$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($sock, "127.0.0.1", 32160);
		socket_write($sock, $data, strlen($data)); //Send data

		// read annswer and close connection
		$factory = new PharauroaMessageFactory($sock);
		$message = $factory->readMessage();
		socket_close($sock);
		
	}
}
?>
