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
	private $wiki;
	private $pageTitle;

	public function __construct() {
		$this->wiki = new Wiki($_REQUEST["title"]);
		$temp = $this->wiki->findPage();
		if (!isset($temp)) {
			return;
		}
		$title = str_replace('_', ' ', $temp['title']);
		$title = preg_replace('|.*/|', '', $title);
		$this->pageTitle = $title;
	}
	

	public function writeHttpHeader() {
		if (!isset($this->pageTitle)) {
			header('HTTP/1.1 404');
		}
		return true;
	}

	public function writeHtmlHeader() {
		if (!isset($this->pageTitle)) {
			echo '<meta name="robots" content="noindex">';
		}
		echo '<title>'.htmlspecialchars($this->pageTitle).STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if (!isset($this->pageTitle)) {
			startBox(htmlspecialchars('Error'));
			echo '<h1>Page not found</h1>';
			echo 'The requested page does not exist.';
			endBox();

		} else {

			startBox(htmlspecialchars($this->pageTitle));
			echo $this->wiki->render();
			endBox();
		}
	}
}
$page = new WikiPage();
