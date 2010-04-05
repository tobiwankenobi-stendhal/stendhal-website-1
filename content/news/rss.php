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


class RssPage extends Page {

	public function writeHttpHeader() {
		header('Content-Type: application/rss+xml', true);
		$this->writeRss();
		return false;
	}

	public function writeRss() {
		$this->writeHeader();
		$news = getNews(' where news.active=1 ', 'created desc');
		foreach($news as $entry) {
			$this->writeEntry($entry);
		}
		$this->writeFooter();
	}

	private function writeHeader() {
		echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<title>Stendhal News</title>
	<link>http://stendhalgame.org</link>
	<description>News feed of the free Stendhal online roleplaying game.</description>
	<language>en</language>
	<copyright>Arianne Project</copyright>
	<pubDate>Mon, 5 Apr 2010 00:00:00 GMT</pubDate>
	<image>
		<url>http://stendhalgame.org/images/favicon.png</url>
		<title>Stendhal News</title>
		<link>http://stendhalgame.org</link>
	</image>
	<atom:link href="http://stendhalgame.org/rss/news.rss" rel="self" type="application/rss+xml" />
<?php
	}

	private function writeEntry($entry) {
		// we do not escape admin input here on purpose.
		// only trusted administrators are allowed to add news and they should
		// be allowed to use full html.
?>
	<item>
		<title><?php 
			echo htmlspecialchars($entry->title);
			if ($entry->updateCount > 0) {
				echo ' [Update No. '.$entry->updateCount.']';
			}
		?></title>
		<description><?php 
			echo htmlspecialchars($entry->extendedDescription);
			echo htmlspecialchars($entry->detailedDescription);
		?></description>
		<link><?php echo 'http://stendhalgame.org'.rewriteURL('/news/'.$entry->getNiceURL());?></link>
		<author>Arianne Project &lt;newsfeed@stendhalgame.org&gt;</author>
		<guid><?php  echo 'http://stendhalgame.org'.rewriteURL('/news/'.$entry->getNiceURL()).'#id-'.$entry->id.'.'.$entry->updateCount;?></guid>
		<pubDate><?php echo date("D, d M Y H:i:s", $entry->date);?></pubDate>
	</item>
<?php
	}

	private function writeFooter() {
?>
</channel>
</rss>
<?php
	}
}

$page = new RssPage();
?>