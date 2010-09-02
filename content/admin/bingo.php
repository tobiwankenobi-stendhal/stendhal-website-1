<?php
class BingoPage extends Page {
	private $id;
	private $name;
	private $query;
	
	public function __construct() {
		$this->query = "SELECT id, killed FROM kills WHERE id<'".mysql_real_escape_string($_REQUEST['lastid'])."' AND killed_type='C' ORDER BY id DESC LIMIT 1";
		$result = mysql_query($this->query, getGameDB());
		
        $row=mysql_fetch_assoc($result);
		$this->id = $row['id'];
		$this->name = $row['killed'];

        mysql_free_result($result);
	}
	
	public function writeHttpHeader() {
		?>
		<!DOCTYPE title PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">		
		<?php
			$this->writeHtmlHeader();
			$this->writeContent();
		
		return false;
	}
	
	public function writeHtmlHeader() {
		echo '<title>Bingo'.STENDHAL_TITLE.'</title>';
		echo '<meta http-equiv="refresh" content="10; URL=/index.php?id=content/admin/bingo&amp;lastid='.htmlspecialchars($this->id).'">';
	}

	function writeContent() {
		startBox('Bingo');

		$monsters = getMonsters();
		foreach($monsters as $m) {
			if($m->name==$this->name) {
			?>
	<div class="monster">
		<div class="name"><?php echo ucfirst($m->name); ?></div>
		<img class="monster" src="<?php echo $m->gfx; ?>" alt="">
		<div class="level">Level <?php echo $m->level; ?></div>
		<div class="description">
		<?php 
		if($m->description=="") {
			echo 'No description. Would you like to <a href="http://sourceforge.net/tracker/?func=add&amp;group_id=1111&amp;atid=301111">write one</a>?';
		} else {
			echo $m->description;
		}
		?>
		
	</div>
	<?php
			}
		}		
		echo htmlspecialchars($this->id);
		endBox();
	}
}

$page = new BingoPage();
?>