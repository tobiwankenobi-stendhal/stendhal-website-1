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


class CIReportPage extends Page {

	public function writeHttpHeader() {
		if (isset($_FILES['file'])) {
			header('HTTP/1.0 204 Not Found');
			move_uploaded_file($_FILES['file']['tmp_name'], "/srv/upload/testresults_".date('ymd_His').'_'.$_SERVER["REMOTE_ADDR"].'.txt');
			return false;
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Testresults'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		startBox('<h1>Testresults</h1>');
		$files = glob('/srv/upload/testresults_*');
		echo '<pre>'.htmlspecialchars(file_get_contents($files[count($files)-1])).'</pre>';
		endBox();
	}
}
$page = new CIReportPage();
