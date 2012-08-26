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
			if ($this->success) {
				echo '<p>Your email address was saved and a confirmation mail will be sent to you.</p>';
			} else {
				// TODO: if unconfirmed, tell user
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
		// TODO show history
	}
}
$page = new EMailPage();
