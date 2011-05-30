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
	public $accountId;
	public $timedate;

	public function __construct($content, $accountId, $timedate) {
		$this->content = $content;
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


	public static function readNewestVersion($lang, $title) {
		$sql = "SELECT content, account_id, timedate"
			." FROM page_version WHERE id = ("
			." SELECT max(page_version.id) FROM page, page_version"
			." WHERE page.language = '".mysql_real_escape_string($lang). "'"
			." AND page.title = '".mysql_real_escape_string($title). "'"
			." AND page_version.page_id = page.id)";
		$result = mysql_query($sql, getWebsiteDB());
		$row = mysql_fetch_assoc($result);
		$res = null;
		if (isset($row) && $row != null) {
			$res = new CMSPageVersion($row['content'], $row['account_id'], $row['timedate']);
		}
		mysql_free_result($result);
		return $res;
	}

}