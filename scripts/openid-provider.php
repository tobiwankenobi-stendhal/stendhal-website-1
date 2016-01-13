<?php
/**
 * openid provider
 */
require_once 'lib/openid/provider.php';

class MySQLBasedOpenidProvider extends LightOpenIDProvider {

	private $attrMap = array(
//		'namePerson/first'    => 'First name',
//		'namePerson/last'     => 'Last name',
		'namePerson/friendly' => 'Nickname (login)'
		);

		private $attrFieldMap = array(
//		'namePerson/first'    => 'firstName',
//		'namePerson/last'     => 'lastName',
		'namePerson/friendly' => 'login'
		);

	function setup($identity, $realm, $assoc_handle, $attributes) {
		if (!isset($_SESSION['account'])) {
			header('Location: '.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&url=/?'.urlencode($_SERVER['QUERY_STRING']));
			exit();
		}

		die('Sorry, openid provider is currently only supported for trusted consumers');
		$this->showConfirmForm($identity, $realm, $assoc_handle, $attributes);
	}

	function showConfirmForm($identity, $realm, $assoc_handle, $attributes) {
		echo '<form action="" method="post">'
		// TODO: csrf-token
		. '<input type="hidden" name="openid.assoc_handle" value="' . $assoc_handle . '">'
		. "<b>".htmlspecialchars($realm)."</b> wishes to authenticate you.";
		if($attributes['required'] || $attributes['optional']) {
			echo " It also requests following information (required fields marked with *):". '<ul>';

			foreach($attributes['required'] as $attr) {
				if(isset($this->attrMap[$attr])) {
					echo '<li>'
					. '<input type="checkbox" name="attributes[' . $attr . ']"> '
					. $this->attrMap[$attr] . '(*)</li>';
				}
			}

			foreach($attributes['optional'] as $attr) {
				if(isset($this->attrMap[$attr])) {
					echo '<li>'
					. '<input type="checkbox" name="attributes[' . $attr . ']"> '
					. $this->attrMap[$attr] . '</li>';
				}
			}
			echo '</ul>';
		}
		echo '<br>'
		. '<button name="once">Allow once</button> '
		. '<button name="always">Always allow</button> '
		. '<button name="cancel">cancel</button> '
		. '</form>';
	}

	function checkid($realm, &$attributes) {
		if(isset($_POST['cancel'])) {
			$this->cancel();
		}

		if (!isset($_SESSION['account'])) {
			return false;
		}
		$account = $_SESSION['account'];

		if (in_array($realm, explode(',', STENDHAL_TRUESTED_OPENID_CONSUMERS))) {
			$attributes['namePerson/friendly'] = $account->username;
			return STENDHAL_LOGIN_TARGET.'/a/'.surlencode(strtolower($account->username));
		}

		$sql = "SELECT attribute FROM openid_allowedsites WHERE player_id = :player_id AND realm = :realm";
		$stmt = DB::game()->prepare($sql);
		$stmt->execute(array(
			':player_id' => $account->id,
			':realm' => $realm
		));
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
			if ($row['attributes'] == 'namePerson/friendly') {
				$attributes['namePerson/friendly'] = $account->username;
			}
			// TODO: 'contact/email', but only if verified
		}

		// nothing saved and not confirmed? Go away
		if (count($attributes) == 0) {
			if (!isset($_POST['once']) && !isset($_POST['always'])) {
				return false;
			}
		}

		// save, if user requested to remember
		if(isset($_POST['always']) && count($attributes) == 0) {
			$sql = "INTO openid_allowedsites (player_id, realm, attribute) VALUES(:player_id, :realm, 'namePerson/friendly')";
			$stmt = DB::game()->prepare($sql);
			$stmt->execute(array(
				':player_id' => $account->id,
				':realm' => $realm
			));
		}

		return STENDHAL_LOGIN_TARGET.'/a/'.surlencode(strtolower($account->username));
	}

	function assoc_handle() {
		return createRandomString();
	}

	function setAssoc($handle, $data) {
		$data = serialize($data);
		$sql = "UPDATE openid_associations SET data=:data WHERE handle=:handle";
		$stmt = DB::game()->prepare($sql);
		$stmt->execute(array(
				':data' => $data,
				':handle' => $handle
		));
		
		if ($stmt->rowCount == 0) {
			$sql = "INSERT INTO openid_associations (handle, data) VALUES(:handle, :data)";
			$stmt = DB::game()->prepare($sql);
			$stmt->execute(array(
					':data' => $data,
					':handle' => $handle
			));
		}
	}

	function getAssoc($handle) {
		$sql = "SELECT data FROM openid_associations WHERE handle=:handle";
		$stmt = DB::game()->prepare($sql);
		$stmt->execute(array(
				':handle' => $handle
		));
		$data = $stmt->fetch(PDO::FETCH_NUM);
		if(!$data) {
			return false;
		}
		return unserialize($data[0]);
	}

	function delAssoc($handle) {
		$sql = "DELETE FROM openid_associations WHERE handle=:handle";
		$stmt = DB::game()->prepare($sql);
		$stmt->execute(array(
				':handle' => $handle
		));
	}

}
