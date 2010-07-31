<?php
/**
 * pharauroa client framework
 */
class PharauroaClientFramework {
	private $server;
	private $port;
	private $credentials;
	
	/**
	 * creates a new PharauroaClientFramework
	 *
	 * @param $server name or ip-address of server
	 * @param $port port of server
	 * @param $credentials credentials for proxy message authentication
	 */
	public function __construct($server, $port, $credentials) {
		$this->server = $server;
		$this->port = $port;
		$this->credentials = $credentials;
	}

	/**
	 * creates a new account
	 */
	public function createAccount($username, $password, $email) {
		// Create message
		$message = new PharauroaMessageP2SCreateAccount();
		$message->init($username, $password, $email);
		
		$answer = sendMessage($message);
		// TODO: analyse answer
	}

	/**
	 * sends a message to the server and returns the answer
	 */
	private function sendMessage($message) {
		// Serialize message
		$serializer = new PharauroaSerializer();
		$message->writeObject($serializer);
		$data = $serializer->getData();

		// connect and write message
		$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($sock, $this->server, $this->port);
		socket_write($sock, $data, strlen($data));

		// read annswer and close connection
		$factory = new PharauroaMessageFactory($sock);
		$answer = $factory->readMessage();
		socket_close($sock);

		return $answer;
	}
}
?>
