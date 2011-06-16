<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2011  The Arianne Project
 Copyright (C) 2011-2011  Faiumoni n. E.

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

class CMSPageVersion {
	public $id;
	public $content;
	public $title;
	public $lang;
	public $commitcomment;
	public $displaytitle;
	public $accountId;
	public $timedate;

	public function __construct($content, $displaytitle, $accountId, $timedate) {
		$this->content = $content;
		$this->displaytitle = $displaytitle;
		$this->accountId = $accountId;
		$this->timedate = $timedate;
	}
}

/**
 * A mini content management system implemented using Stendhal Website technology
 *
 * @author hendrik
 */
class CMS {


	/**
	 * gets the most recent version of the specified page
	 * 
	 * @param string $lang language
	 * @param string $title page title
	 */
	public static function readNewestVersion($lang, $title) {
		$sql = "SELECT content, displaytitle, account_id, timedate"
			." FROM page_version WHERE id = ("
			." SELECT max(page_version.id) FROM page, page_version"
			." WHERE page.language = '".mysql_real_escape_string($lang). "'"
			." AND page.title = '".mysql_real_escape_string($title). "'"
			." AND page_version.page_id = page.id)";
		$result = mysql_query($sql, getWebsiteDB());
		$row = mysql_fetch_assoc($result);
		$res = null;
		if (isset($row) && $row != null) {
			$res = new CMSPageVersion($row['content'], $row['displaytitle'], $row['account_id'], $row['timedate']);
		}
		mysql_free_result($result);
		return $res;
	}


	public static function save($lang, $title, $content, $commitcomment, $displaytitle, $accountId) {
		$pageId = CMS::getPageIdCreateIfNecessary($lang, $title);
		$sql = "INSERT INTO page_version (page_id, content, commitcomment, displaytitle, account_id) VALUES"
			." ('".mysql_real_escape_string($pageId). "',"
			." '".mysql_real_escape_string($content). "',"
			." '".mysql_real_escape_string($commitcomment). "', "
			." '".mysql_real_escape_string($displaytitle). "', "
			." '".mysql_real_escape_string($accountId). "')";
		mysql_query($sql, getWebsiteDB());
	}


	/**
	 * gets the id of specified page, creating the page if it does not exists
	 * 
	 * @param string $lang language
	 * @param string $title page title
	 * @return id of page
	 */
	public static function getPageIdCreateIfNecessary($lang, $title) {
		$id = CMS::getPageId($lang, $title);
		if (isset($id)) {
			return $id;
		}
		CMS::createPage($lang, $title);
		return CMS::getPageId($lang, $title);
	}


	/**
	 * gets the id of specified page
	 * 
	 * @param string $lang language
	 * @param string $title page title
	 * @return id of page or <code>null</code>.
	 */
	public static function getPageId($lang, $title) {
		$sql = "SELECT id FROM page"
			." WHERE page.language = '".mysql_real_escape_string($lang). "'"
			." AND page.title = '".mysql_real_escape_string($title). "'";
		$result = mysql_query($sql, getWebsiteDB());
		$row = mysql_fetch_assoc($result);
		if (isset($row) && $row != null) {
			$res = $row[id];
		}
		mysql_free_result($result);
		return $res;
	}

	/**
	 * creates a new page
	 *
	 * @param string $lang language
	 * @param string $title page title
	 */
	public static function createPage($lang, $title) {
		$sql = "INSERT INTO page (language, title) VALUES"
			." ('".mysql_real_escape_string($lang). "',"
			." '".mysql_real_escape_string($title). "')";
		mysql_query($sql, getWebsiteDB());
	}
}