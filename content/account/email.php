<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
Copyright (C) 2008  Miguel Angel Blanch Lardin
Copyright (C) 2008-2010 The Arianne Project

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

/**
 * changes the email address
 *
 * @author hendrik
 */
class EMailPage extends Page {
	private $error;
	private $success = false;
	private $data;

	public function writeHtmlHeader() {
		echo '<title>Change email'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {
		if (!isset($_SESSION['account'])) {
			startBox("Email address");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url=/account/email.html">login</a> to change your email-address.</p>';
			endBox();
		} else {
			$this->process();
			$this->data = Account::getEmailHistory($_SESSION['account']->id);
			$this->showForm();
			$this->showHistory();
		}
	}
	
	function process() {
		if(isset($_POST['save'])) {
			$email = trim($_REQUEST['email']);
			$at = strpos($email, '@');
			if ($at === false) {
				$this->error = 'Please check your email address carefully, it is invalid.';
				return;
			}
			if (strpos($email, '@', $at) === false) {
				$this->error = 'Please check your email address carefully, it is invalid.';
				return;
			}

			$_SESSION['account']->insertEMail($email, false);
			$_SESSION['account']->email = $email;
			$this->success = true;
		}
	}

	function showForm() {
		startBox("Change email");
		if (isset($this->error)) {
			echo '<p class="error">'.htmlspecialchars($this->error).'</p>';
		} else {
			if ($this->success && (count($this->data) > 0) && (!$this->isConfirmed($this->data[0]))) {
				echo '<p>Your email address was saved and a confirmation mail will be sent to you.</p>';
			} else if ((count($this->data) > 0) && (!$this->isConfirmed($this->data[0]))) {
				echo '<p class="warn">Your email address is unconfirmed. Please check your spam folder. Click on save to send a new confirmation request.</p>';
			}
		}
		?>
		<form id="form" action="" method="post"><table>
			<tr><td>email:</td>
			<td><input id="email" name="email" maxlength="100" value="<?php echo htmlspecialchars($_SESSION['account']->email)?>"></td>
			<td colspan="2" align="right"><input type="submit" name="save" value="Save"></td></tr>
		</table></form>
		<?php
		endBox();
	}

	function showHistory() {
		if (count($this->data) == 0) {
			return;
		}

		startBox("History");
		echo '<table class="prettytable">';
		echo '<tr><th>email</th><th>saved</th><th>confirmed</th><th>by</th></tr>';
		foreach ($this->data as $row) {
			echo '<tr><td>' . htmlspecialchars($row['email'])
				.'</td><td>'. htmlspecialchars($row['timedate'])
				.'</td><td>';
			if ($this->isAutoConfirmed($row)) {
				echo 'auto';
			} else if ($this->isConfirmed($row)) {
				echo htmlspecialchars($row['confirmed']);
			} else {
				echo '<span class="warn">unconfirmed</span>';
			}
			echo '</td><td>'.htmlspecialchars($row['address']).'</td></tr>';
		}
		echo '</table>';
		endBox();
	}

	function isConfirmed($row) {
		return isset($row['confirmed']) 
			&& ($row['confirmed'] != '') 
			&& (strpos($row['confirmed'], '0000') === false);
	}
	
	function isAutoConfirmed($row) {
		return (!isset($row['token']) || ($row['token'] == '')) 
			&& $this->isConfirmed($row);
	}
	
}
$page = new EMailPage();
