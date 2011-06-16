<?php
/*
 Copyright (C) 2011 Faiumoni

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

class EditPage extends Page {
	private $history;
	public function __construct() {
		global $lang;
		$this->history = CMS::readHistory($lang, $_REQUEST['title']);
	}

	public function writeHttpHeader() {
		if (!isset($_SESSION) || !isset($_SESSION['account'])) {
			header('HTTP/1.1 403 Forbidden');
		}
		if (count($this->history) == 0) {
			header('HTTP/1.1 404 Not found');
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>History'.STENDHAL_TITLE.'</title>'."\n";
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {
		global $lang;
		$title = '';
		if (isset($_REQUEST['title'])) {
			$title = $_REQUEST['title'];
		}
		if (!isset($_SESSION) || !isset($_SESSION['account'])) {
			startBox(t('History'));
			$currentPage = '/?id=content/association/history&amp;lang='.urlencode($lang).'&amp;title='.urlencode($title);
			echo '<p>'.t('You need to').' <a href="'.STENDHAL_LOGIN_TARGET.'/?id=content/association/login&amp;url='.urlencode($currentPage).'">'.t('login').'</a> '.t('in order to view the history.').'</p>';
			endBox();
			return;
		}
		if (count($this->history) == 0) {
			startBox(t('History'));
			echo '<p>'.t('Sorry, the requested page does not exist.').'</p>';
			endBox();
			return;
		}

		echo '<form method="GET">';
		startBox(t('History').' '.htmlspecialchars(ucfirst($title)));
		echo '<ul class="changehistory">';
		foreach ($this->history as $entry) {
			echo '<li><a href="">'.htmlspecialchars($entry->lang).'/'.htmlspecialchars($entry->title)
				.'</a>: '.htmlspecialchars($entry->username).' '.htmlspecialchars($entry->timedate).'<br>'
				.'<i>'.htmlspecialchars($entry->commitcomment).'</i></li>';
		}
		echo '</ul>';
		endBox();
	}

	function filterHtml($html) {
		require_once 'lib/htmlpurifier/library/HTMLPurifier.path.php';
		require_once 'HTMLPurifier.includes.php';
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
		$purifier = new HTMLPurifier($config);
		return $purifier->purify($html);
	}
}
$page = new EditPage();
?>