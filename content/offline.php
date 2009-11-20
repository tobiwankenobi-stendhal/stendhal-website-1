<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2009  The Arianne Project

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

class OfflinePage extends Page {
	function writeContent() {
		startBox("Server is offline");
?>
The server is offline right now.
<p>
This may be the desired behaviour, in case of an update, or it may be the result of some kind of problem.
Please report it at <a href="http://webchat.freenode.net/?channels=arianne">#arianne on irc.freenode.net</a>, if the channel topic there doesn't already mention it.
<?php
		endBox(); 
	}
}
$page = new OfflinePage();
?>