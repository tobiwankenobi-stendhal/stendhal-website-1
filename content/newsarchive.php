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


class NewsArchivePage extends Page {

	public function writeHtmlHeader() {
		echo '<title>News'.STENDHAL_TITLE.'</title>';
		echo '<link rel="alternate" type="application/rss+xml" title="Stendhal News" href="/rss/news.rss" >';
		
	}

	function writeContent() {
		echo '<div id="newsArea">';
		$limit = '';
		if (isset($_REQUEST['recent'])) {
			$limit = 'LIMIT 3';
		}
		foreach(getNews(' where news.active=1 ', 'created desc', $limit) as $news) {
			$news->show();
		}
		echo '</div>';

		
		startBox('<h2>More News</h2>');
		echo '<ul class="menu">';
		if (isset($_REQUEST['recent'])) {
			echo '<li style="width: 100%"><a id="menuNewsArchive" href="/world/newsarchive.html">Older news</a></li>';
		}
		echo '<li style="width: 100%"><a id="menuNewsRss" href="/rss/news.rss">RSS-Feed for this page</a></li>';
		echo '<li style="width: 100%"><a id="menuNewsTrade" href="/trade/">Harold\'s Trading Announcements</a></li>';
		echo '</ul>';
		endBox();
	}

	public function getBreadCrumbs() {
		return array('Media', '/media.html', 'News', '/world/newsarchive.html');
	}

}

$page = new NewsArchivePage();
