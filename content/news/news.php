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
		$newsId = getNewsIdFromUrl();
		if ($newsId <= 0) {
			header('HTTP/1.0 404 No found', true, 404);
			return;
		}

		// TODO: send Location 301 redirect on invalid url text
		
		// read the news posting from the database
		$newsList = getNews("' where news.id='".mysql_real_escape_string($newsId)."' AND news.active=1");
		if (length($news) == 0) {
			header('HTTP/1.0 404 No found', true, 404);
		}

		$this->news = $newsList[0];
		return;
	}

	// TODO: write right title
	// TODO: write news item
	
	public function writeHtmlHeader() {
		echo '<title>News Archive'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
?>

<div id="newsArea">
  <?php
  foreach(getNews(' where news.active=1 ', 'created desc', '') as $i) {
   $i->show();
  }
  ?>
</div>
<?php
	}
}
$page = new NewsPage();
?>