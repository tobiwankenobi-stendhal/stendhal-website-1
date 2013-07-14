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
class TradePage extends Page {

	public function writeContent() {
		startBox('New trade offers');
		if (isset($_REQUEST['tradeid'])) {
			$this->writeEntry($_REQUEST['tradeid']);
		} else {
			$this->writeRecent();
		}
		endBox();
	}

	public function writeRecent() {
		global $cache;
		$feed = $cache->fetch('stendhal-trade');
		if (!isset($feed)) {
			$feed = $this->generateFeed();
			$cache->store('stendhal-trade', $feed, 5*60);
		}
		echo $feed;
	}

	public function writeEntry($tradeId) {
		$entries = TradeOffer::getTradeOffer($tradeId);
		if (count($entries) > 0) {
			echo $this->generateEntry($entries[0]);
		} else {
			echo '<p class="error">No such trade offer.</p>';
		}
	}

	public function generateFeed() {
		$entries = TradeOffer::getTradeOffers();
		$res = $this->generateHeader($entries);
		foreach($entries as $entry) {
			$res .= $this->generateEntry($entry);
		}
		$res .= $this->generateFooter();
		return $res;
	}

	private function formatedDate($date) {
		$datetime = new DateTime($date);
		return $datetime->format('Y-m-d H:i');
	}

	private function generateHeader($entries) {
		$date = date(DATE_ATOM);
		if (count($entries) > 0) {
			$date = $entries[0]->timedate;
		}
		$res = '';
		$res .= '<img class="newsIcons" src="/images/outfit/5013401.png">';
		$res .= '<p>I am Harold and I have my little ship in Semos Tavern. On this page, I announce new trade offers. Please note that I won\'t take offers down once the items are sold.</p>';
		$res .= '<p>I suggest that you use a feed reader to access this page. For reference, the current server time is '.date('G:i') . '</p>';
		if (sizeof($entries)==0) {
			$res .= '<p>There are no new trade offers.</p>';
		}
		return $res;
	}

	private function generateEntry($entry) {
		$res = '<p>';
		$res .= '<a href="'.rewriteURL('https://stendhalgame.org/trade/'.htmlspecialchars($entry->id).'.html').'">';
		$res .= $this->formatedDate($entry->timedate) . '</a> ';
		$res .= 'New offer for ' . htmlspecialchars($entry->quantity) . ' ';
		$res .= '<a class="menu" href="'.rewriteURL('/item/'.surlencode(getItem($entry->itemname)->class).'/'.surlencode($entry->itemname).'.html').'"><img src="'.htmlspecialchars(getItem($entry->itemname)->showImage()).'" alt="'.htmlspecialchars($entry->itemname).'" title="'.htmlspecialchars($entry->itemname).'"></a>';
		$res .= 'at ' . htmlspecialchars($entry->price) . '. ' . htmlspecialchars($entry->stats);
		$res .= "</p>\r\n";
		return $res;
	}

	private function generateFooter() {
		return '</feed>';
	}
}

$page = new TradePage();