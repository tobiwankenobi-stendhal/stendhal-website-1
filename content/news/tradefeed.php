<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2013 Faiumoni e. V.

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
class TradeFeedPage extends Page {

	public function writeHttpHeader() {
		header('Content-Type: application/atom+xml', true); // TODO
		$this->writeFeed();
		return false;
	}

	public function writeFeed() {
		global $cache;
		$feed = $cache->fetch('stendhal-tradefeed');
		if (!isset($feed)) {
			$feed = $this->generateFeed();
			$cache->store('stendhal-tradefeed', $feed, 5*60);
		}
		echo $feed;
	}

	public function generateFeed() {
		$this->generateHeader();
		$news = getNews(' where news.active=1 ', 'created desc', 'limit 20');
		foreach($news as $entry) {
			$this->generateEntry($entry);
		}
		$this->generateFooter();
	}

	private function formatedDate($date) {
		$datetime = new DateTime($date);
		$datetime->setTimezone(new DateTimeZone('GMT'));
		echo $datetime->format('Y-m-d\TH:i:s\Z');
	}

	private function generateHeader() {
		$res = '';
		$res .= '<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom">';
		$res .= '<title>Harold\'s Offers</title>';
		$res .= '<subtitle>Stendhal Trades</subtitle>';
		$res .= '<link href="https://stendhalgame.org/trade" rel="self"/> ';
		$res .= '<updated>'.$this->formatedDate(date()).'</updated>';  // TODO: Use date of last entry
		$res .= '<author>';
		$res .= '<name>Harold</name>';
		$res .= '<email>harold@stendhalgame.org</email>';
		$res .= '</author>';
		$res .= '<id>tag:stendhalgame.org,2013:https://stendhalgame.org/trade</id>';
		return $res;
	}

	private function generateEntry() {
		$res = '';
		$res .= '<entry>';
		$res .= '<id>'.rewriteURL('https://stendhalgame.org/trade/'.$id).'</id>';
		$res .= '<title>'.$title.'</title>';
		$res .= '<updated>'.$this->formatedDate($date).'</updated>';
		$res .= '<published>'.$this->formatedDate($date).'</published>';
		$res .= '<link>'.rewriteURL('https://stendhalgame.org/trade/'.$id.'.html').' rel="alternate"></link>';
		$res .= '<content>'.'</content>';
		$res .= "</entry>\r\n";
		return $res;
	}

	private function generateFooter() {
		echo '</feed>';
	}
}

$page = new TradeFeedPage();