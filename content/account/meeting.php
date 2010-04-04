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

class MeetingPage extends Page {

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>Meeting'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if(!isset($_SESSION['username'])) {
			startBox("Meeting");
			echo '<p>Please <a href="/?id=login/login&amp;url=content/account/meeting">login</a> to register for the meeting or to edit your registration.</p>';
			endBox();
		} else {
			$this->printForm();
		}
	}
	
	function printForm() {
		$playerId = getUserID($_SESSION['username']);
		saveMeetingRegistration($playerId);
		$registration = getMeetingRegistration($playerId);

		startBox('Meeting Registration');
?>
		<p>Most of the planning for the meeting is coordinated on the 
		<a href="/wiki/Arianne_Project_Meeting_2010">Wiki</a>.
		We would like to ask you for some contact information in order
		to ease the organisation. But the Wiki is public so we provide
		this form to protect your privacy.</p>

		<p>Note: You can modify this form later.</p>

		<?php
			if (isset($_POST["realname"]) || isset($_POST["email"]) || isset($_POST["nickname"])) {
				echo '<p style="font-size: +3; color:green; font-weight: bold; border: 3px solid grey; padding: 1em">Data saved successfully.</p>';
			}
		?>

		<form method="POST" action="/index.php?id=content/account/meeting">

		<table>
		<tr>
			<td><label for="realname">Real Name:</label><span style="color: red">*</span></td>
			<td><input type="text" name="realname" value="<?php echo htmlspecialchars($registration->realname)?>"></td>
		</tr>

		<tr>
			<td><label for="email">E-Mail:</label><span style="color: red">*</span></td>
			<td><input type="text" name="email" value="<?php echo htmlspecialchars($registration->email)?>"></td>
		</tr>

		<tr>
			<td><label for="mobile">Mobile Phone:</label></td>
			<td><input type="text" name="mobile" value="<?php echo htmlspecialchars($registration->mobile)?>"></td>
		</tr>

		<tr>
			<td><label for="country">Country:</label></td>
			<td><input type="text" name="country" value="<?php echo htmlspecialchars($registration->country)?>"></td>
		</tr>

		<tr>
			<td><label for="nickname">Nick Names, Characters:</label></td>
			<td><input type="text" name="nickname" value="<?php echo htmlspecialchars($registration->nickname)?>"></td>
		</tr>

		<tr>
			<td><label for="attent">Attending:</label></td>
			<td>
				<input type="radio" name="attent" id="attent_yes" value="yes" <?php if($registration->attent=="yes") echo 'checked="checked"'?>>
				<label for="attent_yes">Yes</label>
				<input type="radio" name="attent" id="attent_tentative" value="tentative" <?php if($registration->attent=="tentative") echo 'checked="checked"'?>>
				<label for="attent_tentative">Tentative</label>
				<input type="radio" name="attent" id="attent_no" value="no" <?php if($registration->attent=="no") echo 'checked="checked"'?>>
				<label for="attent_no">No</label>
			</td>
		</tr>

		<tr>
			<td><label for="comment">Comment:</label></td>
			<td><textarea name="comment" rows="5" cols="50"><?php echo htmlspecialchars($registration->comment)?></textarea></td>
		</tr>

		<tr>
			<td></td>
			<td><input type="submit" value="Save"></td>
		</tr>

		</table>
		</form>
		<p>A red asterix <span style="color: red">*</span> marks a required field.</p>

		<?php

		
		endBox(); 

	}
}
$page = new MeetingPage();

?>