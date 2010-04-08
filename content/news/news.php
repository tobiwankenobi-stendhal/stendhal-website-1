<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2010 the Arianne Project

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


class NewsPage extends Page {
	private $news;

	public function __construct() {
		$newsId = NewsPage::getNewsIdFromUrl();
		if ($newsId <= 0) {
			header('HTTP/1.0 404 No found', true, 404);
			return;
		}

		// read the news posting from the database
		$newsList = getNews(" where news.id='".mysql_real_escape_string($newsId)."' AND news.active=1");
		if (sizeof($newsList) == 0) {
			header('HTTP/1.0 404 No found', true, 404);
		}

		$this->news = $newsList[0];
		return;
	}

	/**
	 * extracts the id from the nice url
	 *
	 * @return id
	 */
	function getNewsIdFromUrl() {
		$url = $_GET['news'];

		if (is_numeric($url)) {
			return abs(intval($url));
		}
		
		$pos = strrpos($url, '-');
		$id = substr($url, $pos + 1);
		$pos = strpos($id, '.');

		// remove optional .html suffix
		if ($pos !== FALSE) {
			$id = substr($id, 0, $pos);
		}
		return intval($id);
	}

	public function writeHttpHeader() {
		if (isset($this->news)) {
			$niceUrl = $this->news->getNiceURL();
			if ($niceUrl != $_GET['news']) {
				header("Location: http://".$_SERVER['HTTP_HOST'].preg_replace("/&amp;/", "&", rewriteURL('/news/'.$niceUrl)), 301);
				return false;
			}
		}
		return true;
	}

	public function writeHtmlHeader() {
		if (isset($this->news)) {
			echo '<title>'.htmlspecialchars($this->news->title).STENDHAL_TITLE.'</title>';
		} else {
			echo '<title>News Not Found'.STENDHAL_TITLE.'</title>';
		}
	}

	function writeContent() {
?>

<div id="newsArea">
	<?php
	if (isset($this->news)) {
		$this->news->show(true);
	} else {
		startBox('News');
		echo 'Not Found';
		endBox();
	}
	?>
	<div>
		<?php startBox('News Archive');
		echo 'Read <a href="'.rewriteURL('/world/newsarchive.html').'">older news</a>.';
		endBox();
		?>
	</div>
</div>
<?php
	}
}
$page = new NewsPage();
?>