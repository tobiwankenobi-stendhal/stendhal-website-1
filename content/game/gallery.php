 <?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
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
class GalleryPage extends Page {
	private $title;

	public function __construct() {
		$this->title = $_REQUEST['title'];
	}

	public function writeHtmlHeader() {
		echo '<title>Gallery '.htmlspecialchars($title).STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		startBox(htmlspecialchars($title));
		$images = $this->getGalleryImages($title);
		var_dump($images);		
		?>
<img class="screenshot" src="http://arianne.sourceforge.net/screens/stendhal/worldsmall.png" alt="Miniature view of stendhal world map"/>
		<?php 
		endBox();
	}

	// TODO: put into scripts-folder
	function getGalleryImages($title) {
		$sql = "SELECT page_title As image, cl_sortkey As description FROM categorylinks, page WHERE categorylinks.cl_to = '"
			.mysql_real_escape_string($title)
			. "' AND categorylinks.cl_from = page.page_id AND page_namespace = 6 AND page_is_redirect = 0";
			$result = mysql_query($sql, getWikiDB());
			
		$list = array();

		while($row = mysql_fetch_assoc($result)) {
			$list[] = $row;
		}

		mysql_free_result($result);
		return $list;
	}
}
$page = new GalleryPage();
?>