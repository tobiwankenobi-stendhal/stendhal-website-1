<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008  Miguel Angel Blanch Lardin
 Copyright (C) 2008-2010  The Arianne Project

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


 /*
 * This file is the PHP code that generate each of the website sections. 
 */

require_once('configuration.php');
require_once('configuration.default.php');
require_once('scripts/mysql.php');

require_once('scripts/account.php');
require_once('scripts/achievement.php');
require_once('scripts/cache.php');
require_once('scripts/cms.php');
require_once('scripts/events.php');
require_once('scripts/grammar.php');
require_once('scripts/inspect.php');
require_once('scripts/items.php');
require_once('scripts/itemlog.php');
require_once('scripts/monsters.php');
require_once('scripts/netstats.php');
require_once('scripts/news.php');
require_once('scripts/npcs.php');
require_once('scripts/playerhistory.php');
require_once('scripts/players.php');
require_once('scripts/screenshots.php');
require_once('scripts/search.php');
require_once('scripts/statistics.php');
require_once('scripts/urlrewrite.php');
require_once('scripts/xml.php');
require_once('scripts/zones.php');


function startBox($title) {
	echo '<div class="box">';
	echo '<div class="boxTitle">'.$title.'</div>';
	echo '<div class="boxContent">';
}

function endBox() {
	echo '</div></div>';
}

/**
 * gets the code to put into the a-tag of an overlib popup
 *
 * @param string $html
 */
function getOverlibCode($html) {
	return ' onmouseover="return overlib(\''.rawurlencode($html).'\', FGCOLOR, \'#000\', BGCOLOR, \'#FFF\','
		. 'DECODE, FULLHTML'
		. ');" onmouseout="return nd();"';
}

/**
 * creates a random string
 */
function createRandomString() {
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$res = '';
	for ($i = 0; $i < 20; $i++) {
		$res .= $characters[mt_rand(0, strlen($characters) - 1)];
	}
	return $res;
}

/**
 * queries the database for an array result, using the cache
 *
 * @param unknown_type $query query to execute
 * @param unknown_type $ttl cache time, use 0 to disable cache
 */
function queryWithCache($query, $ttl, $db) {
	global $cache;
	$list = $cache->fetchAsArray('stendhal_query_'.$query);
	if (!isset($res)) {
		$list=array();
		$result = mysql_query($query, $db);
		while($row=mysql_fetch_assoc($result)) {
			$list[] = $row;
		}
		mysql_free_result($result);
		if ($ttl && $ttl > 0) {
			$cache->store('stendhal_query_'.$query, new ArrayObject($list), $ttl);
		}
	}
	return $list;
}

/**
 * Format the number using (hard-coded) English locale with
 * the given number of digits. Terminating zeros and a possibly
 * terminating decimal point are removed as well.
 *
 * @param value float | integer
 * @param digits integer
 *
 * @return string
 */

function formatNumber($value, $digits = 6) {
	$decimalSeparator =  '.';
	$thousandsSeparator = ',';
	
	$sNumber = number_format($value, $digits, $decimalSeparator, $thousandsSeparator);

	// $sNumber could possibly contain trailing zeros, e.g. '10,000.000000'.
	// Remove the trailing zero, and the decimal point, but no any more zeros.

	list($sBefore, $sAfter) = explode($decimalSeparator, $sNumber);

	if (($sAfter = rtrim($sAfter, '0')) === '') {

		// We have no fraction.

		return $sBefore;
	}

	return $sBefore . $decimalSeparator . $sAfter;
}

function profilePoint($name) {
	if (isset($_REQUEST['_profiler'])) {
		global $profilerReferenceTime;
		echo "\n".'<!--'. number_format(microtime(true) - $profilerReferenceTime, 3). ': '.htmlspecialchars($name).'-->';
		$profilerReferenceTime = microtime(true);
	}
}


/**
 * read pages from the Stendal wiki to embed them into the Stendhal Website.
 * 
 * @author hendrik
 */
class Wiki {
	private $url;
	private $page;

	public function __construct($url) {
		$this->url = $url;
	}

	/**
	 * gets the content of the wiki page
	 * @param string $page page name
	 * @return string content
	 */
	private function get($page) {

		// check file cache
		$md5 = md5($page);
		$path = '/var/www/stendhal/w/images/cache/'.$md5[0].'/'.$md5[0].$md5[1]
			.'/'.str_replace('/', '%2F', urlencode($page)).'.html';
		$content = @file_get_contents($path);
		
		if ($content === false) {
			// do not use ?action=render because that does not write file cache
			$url = 'https://stendhalgame.org/wiki/'.surlencode($page);
			$content = file_get_contents($url);
		}
		return $content;
	}

	/**
	 * encodes an url acording to wiki rules
	 *
	 * @param string $title
	 * @return encoded url
	 */
	private function wikiUrlEncode($title) {
		return str_replace('%2F', '/', urlencode($title));
	}

	public function findPage() {
		$sql = "SELECT page_id, page_title As title FROM a1111_wiki.page_props, a1111_wiki.page" 
		." WHERE pp_propname='externalcanonical' AND pp_value = '" . mysql_real_escape_string($this->url) 
		."' AND page.page_namespace=0 AND page.page_id=page_props.pp_page";
		$res = fetchToArray($sql, getGameDB());
		if (count($res) > 0) {
			$this->page = $res[0];
			return $res[0];
		}
		return null;
	}

	private function clean($content) {
		$start = strpos($content, '<!-- bodycontent -->');
		$end = strrpos($content, '<!-- /bodycontent -->');
		$content = substr($content, $start, $end - $start);

		while (true) {
			$start = strpos($content, '<span class="skip-start"></span>');
			if ($start === false) {
				break;
			}
			$end = strpos($content, '<span class="skip-end"></span>');
			$content = substr($content, 0, $start).substr($content, $end + 30);
		}
		return $content;
	}

	private function rewriteLinks($pageId, $content) {
		$sql = "SELECT page_title, pp_value FROM a1111_wiki.page_props, a1111_wiki.page, a1111_wiki.pagelinks"
			." WHERE pp_propname='externalcanonical' AND page.page_namespace=0 AND page.page_id=page_props.pp_page" 
			." AND pl_namespace=0 AND page_title=pl_title AND pl_from=".intval($pageId);
		$res = fetchToArray($sql, getGameDB());
		
		$prefix = '<a href="';
		foreach ($res as $row) {
			$content = str_replace($prefix.'/wiki/'.$this->wikiUrlEncode($row['page_title']).'"', 
				$prefix.$this->wikiUrlEncode($row['pp_value']).'"', $content);
		}
		return $content;
	}

	/**
	 * renders a wiki page in the Stendhal website
	 *
	 * @param string $page page name
	 * @return prepared content
	 */
	function render() {
		if (!isset($this->page)) {
			return;
		}
		$content = $this->get($this->page['title']);
		$content = $this->clean($content);
		$content = $this->rewriteLinks($this->page['page_id'], $content);
		
		return $content;
	}
}