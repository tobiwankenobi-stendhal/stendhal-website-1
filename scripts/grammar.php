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

function a_an($string) {
	// May wish to refine this by adding exceptions from src.games.stendhal.common.Grammar.a_an
	$vowels = array('a', 'e', 'i', 'o', 'u');
	if (in_array(strtolower($string[0]),$vowels)){
		return 'an';
	} else {
		return 'a';
	}
}
