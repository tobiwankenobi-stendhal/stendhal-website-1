<?php

class NPCPage extends Page {
	private $name;
	private $npcs;

	public function __construct() {
		$this->name = preg_replace('/_/', ' ', trim($_REQUEST['name']));
		$this->npcs = NPC::getNPCs('where name="'.mysql_real_escape_string($this->name).'"', 'name');
	}

	public function writeHttpHeader() {
		global $protocol;
		if (sizeof($this->npcs)==0) {
			header('HTTP/1.0 404 Not Found');
			return true;
		}
		if ((strpos($_REQUEST['name'], ' ') !== FALSE) || isset($_REQUEST['search'])) {
			header('HTTP/1.0 301 Moved permanently.');
			header('Location: '.$protocol.'://'.$_SERVER['SERVER_NAME'].preg_replace('/&amp;/', '&', rewriteURL('/npc/'.preg_replace('/[ ]/', '_', $this->name.'.html'))));
			return false;
		}

		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>NPC '.htmlspecialchars($this->name).STENDHAL_TITLE.'</title>';
		if(sizeof($this->npcs)==0) {
			echo '<meta name="robots" content="noindex">';
		}
	}

	function writeContent() {
		if (sizeof($this->npcs) == 0) {
			startBox("<h1>No such NPC</h1>");
			echo 'There is no such NPC in Stendhal.<br>Please make sure you spelled it correctly.';
			endBox();
			return;
		}

		$npc=$this->npcs[0];
		startBox('<h1>'.htmlspecialchars($npc->name).'</h1>');

		echo '<div class="table">';
		echo '<div class="title">Details</div>';
		echo '<img class="bordered_image" src="'.htmlspecialchars($npc->imagefile).'" alt="">';
		echo '<div class="statslabel">Name:</div><div class="data">'.htmlspecialchars($npc->name).'</div>';
		echo '<div class="statslabel">Zone:</div><div class="data">';
		if ($npc->pos != '') {
			echo '<a href="/world/atlas.html?poi='.htmlspecialchars($npc->name).'">'.htmlspecialchars($npc->zone).' '.htmlspecialchars($npc->pos).'</a>';
		} else {
			echo htmlspecialchars($npc->zone);
		}
		echo '</div>';

		if ($npc->level > 0) {
			echo '<div class="statslabel">Level:</div><div class="data">'.$npc->level.'</div>';
			echo '<div class="statslabel">HP:</div><div class="data">'.$npc->hp . '/' . $npc->base_hp.'</div>';
		}

		if ((isset($npc->job) && strlen($npc->job) > 0)) {
			echo '<div class="sentence">'.htmlspecialchars(str_replace('#', '', $npc->job)).'</div>';
		}
		if ((isset($npc->description) && strlen($npc->description) > 0)) {
			echo '<div class="sentence">'.htmlspecialchars($npc->description).'</div>';
		}
		echo '</div>';
		endBox();

		$this->writeRelatedPages('N.'.strtolower($npc->name), 'Stendhal_Quest', 'Quests');
	}

	public function getBreadCrumbs() {
		if (sizeof($this->npcs) == 0) {
			return null;
		}

		return array('World Guide', '/world.html',
				'NPC', '/npc/',
				ucfirst($this->name), '/npc/'.$this->name.'.html'
		);
	}
}
$page = new NPCPage();
