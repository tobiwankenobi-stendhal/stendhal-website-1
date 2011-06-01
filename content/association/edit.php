<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2009  Miguel Angel Blanch Lardin, The Arianne Project

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
	private $cmsPage;
	public function __construct() {
		global $lang;
		$this->cmsPage = CMS::readNewestVersion($lang, $_REQUEST['title']);
	}

	public function writeHtmlHeader() {
		echo '<title>Edit'.STENDHAL_TITLE.'</title>'."\n";
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<script type="text/javascript" src="/lib/ckeditor/ckeditor.js"></script>';
		$this->includeJs();
	}

	function writeContent() {
		global $lang;
		$title = $_REQUEST['title'];
		if ($title == '') {
			$title = 'Faiumoni';
		}

		startBox(t('Editing').' '.htmlspecialchars(ucfirst($title)));
		echo '<p>'.t('Please skip Heading 1 and start with Heading 2.').'</p>';
		echo '<p>'.t('Back to article').' <a href="'.rewriteURL('/'.$lang.'/'.urlencode($_REQUEST['title']).'.html').'">'.htmlspecialchars(ucfirst($title)).'</a></p>';
		endBox();

		if (isset($_POST['editor'])) {
			$html = $this->filterHtml($_POST['editor']);
			if ($_POST['csrf'] != $_SESSION['csrf']) {
				startBox(t('Error'));
				echo '<p class="error">'.t('Session information was lost. Please save again.').'</p>';
				endBox();
			} else {
				CMS::save($lang, $_REQUEST['title'], $html, $_REQUEST['commitcomment']);
			}
		}

		echo '<form method="POST">';
		echo '<input name="csrf" type="hidden" value="'.htmlspecialchars($_SESSION['csrf']).'">';
		echo '<textarea id="editor" name="editor" style="width:100%; height:30em">';
		if ($_POST['editor']) {
			echo htmlspecialchars($html);
		} else if ($this->cmsPage != null) {
			echo htmlspecialchars($this->cmsPage->content);
		}
		echo '</textarea>';
		echo '<input name="commitcomment" style="width:100%">';
		echo '</form>';
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