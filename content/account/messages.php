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

class MessagesPage extends Page {

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>Messages'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if(!isset($_SESSION['username'])) {
			startBox("Messages");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url='.rewriteURL('/account/messages.html').'">login</a> to view your personal messages.</p>';
			endBox();
		} else {
			$this->printStoredMessages();
		}
	}
	
	function printStoredMessages() {
	    $playerId = getUserID($_SESSION['username']);
		$messages = getStoredMessages($playerId);

		startBox('Messages');

		echo '<p>This is a list of the recent messages sent to your characters.';

		echo '<table class="prettytable"><tr><th>from</th><th>to</th><th>server time</th><th>message</th></tr>';
		foreach ($messages as $entry) {
		    if ($entry->delivered == 0) {
		      echo '<tr style="font-weight:bold;">';
		    } else {
		      echo '<tr>';
		    }
		    if ($entry->messageType == 'P') {
              echo '<td><a href="'.rewriteURL('/character/'.htmlspecialchars($entry->source).'.html').'">'.htmlspecialchars($entry->source).'</a>';
            } else if ($entry->messageType == 'S') {
              echo '<td><span style="color:orange">Support (<a href="'.rewriteURL('/character/'.htmlspecialchars($entry->source).'.html').'">'.htmlspecialchars($entry->source).'</a>)</span>';
            } else {
                echo '<td>'.htmlspecialchars($entry->source);
            }
			echo '</td><td>'.htmlspecialchars($entry->target);
			if ($entry->timedate == '2010-07-20 00:00:00') {
			    echo '</td><td>unknown';
			} else {
			    echo '</td><td>'.htmlspecialchars($entry->timedate);
			}
			echo '</td><td>'.htmlspecialchars($entry->message)
			    .'</td></tr>';

		}
		echo '</table>';
		endBox(); 

	}
}
$page = new MessagesPage();
?>