<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2014  The Arianne Project

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

class WikiPage extends Page {

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">';
		echo '<title>'.htmlspecialchars($_REQUEST['title']).STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		startBox('Wiki');
		echo '<h1>'.htmlspecialchars($_REQUEST['title']).'</h1>';
		
		echo Wiki::render($_REQUEST['title']);
		endBox();
	}
}
$page = new WikiPage();
