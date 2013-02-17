<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2013  Hendrik Brummermann

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

class MobilePage extends Page {
	private static $items;
	private static $classes;

	public function writeHttpHeader() {
		$this->write();
		return false;
	}


	private function write() {
		MobilePage::$items = getItems();
		MobilePage::$classes = Item::getClasses();
		$this->writeHeader();
		$this->writeMobileContent();
		$this->writeFooter();
	}

	private function writeHeader() {
		echo '<!DOCTYPE html>
		<html><head><title>Stendhal</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/css/jquery.mobile-1.2.0.min.css" />
		<script src="/css/jquery-1.8.2.min.js"></script>
		<script src="/css/jquery.mobile-1.2.0.min.js"></script>
		</head>
		<body>';
	}

	private function writeMobileContent() {
		$this->writeItemClassesPage();
		foreach (MobilePage::$classes as $class => $temp) {
			$this->writeItemClassPage($class);
		}
	}

	private function writeItemClassesPage() {
		?>
		<div data-role="page" id="page-itemclasses">
		
		<div data-role="header">
		<h1>Stendhal Items</h1>
		</div><!-- /header -->
		
		<div data-role="content">
		<ul data-role="listview" data-inset="true" data-filter="false">
		<?php 
		foreach (MobilePage::$classes as $class => $temp) {
			echo '<li><a href="#page-itemclass-'.htmlspecialchars($class).'">'.htmlspecialchars(ucfirst($class)).'</a></li>';
		}
		?>
		</ul>
		</div><!-- /content -->
		
		<div data-role="footer">
		<h4>Page Footer</h4>
				</div><!-- /footer -->
				</div><!-- /page -->
		<?php
	}

	private function writeItemClassPage($class) {
		echo '<div data-role="page" id="page-itemclass-'.htmlspecialchars($class).'">';
		?>
			
			<div data-role="header">
			<h1>Stendhal Items</h1>
			<a href="#page-itemclasses">Back</a>
			</div><!-- /header -->
			
			<div data-role="content">
			<p>Page content goes here. <a href="#page-itemclass-ammunition"></a></p>
			</div><!-- /content -->
			
			<div data-role="footer">
			<h4>Page Footer</h4>
					</div><!-- /footer -->
					</div><!-- /page -->
			<?php
		}

	private function writeFooter() {
		echo '</body></html>';
	}

	function writeContent() {
	}
	
	function old() {

if(!isset($_GET['class'])) {
  startBox('Items classes');
  ?>
  <form method="get" action="/" id="currentContentSearch">
    <input type="hidden" name="id" value="content/scripts/item">
    <input type="hidden" name="class" value="all">
    <input type="text" name="name" maxlength="60">
    <input type="submit" name="sublogin" value="Search">
  </form>
  <div style="margin-bottom: 10px;">
    <?php echo sizeof($items); ?> items so far.
  </div>
  <?php
  foreach($classes as $class=>$zero) {
    foreach($items as $item) {
   	  if($item->class==$class) {
   	    $choosen=$item;
   	  }
    }
	?>
    <div class="f3cols">
      <?php 
        echo '<a href="'.rewriteURL('/item/'.surlencode($class).'.html').'">';
        echo '<img src="'.$choosen->gfx.'" alt=""><br>';
        echo ucfirst($class). '</a>';?>
    </div>
    <?php
  }  
  ?>
  <div style="clear: left;"></div>
  <?php
  endBox();
  return;
}

?>
<?php
//foreach($classes as $class=>$zero) {
$class=$_GET['class'];
startBox(ucfirst($class).' Items');
  foreach($items as $item) {
	if($item->class==$class) {
	  ?>
  	  <div class="item">
        <?php echo '<a class="item" href="'.rewriteURL('/item/'.surlencode($class).'/'. surlencode($item->name) . '.html').'">'; ?>
 	      <img class="item_image" src="<?php echo $item->gfx; ?>" alt="">
	      <span class="block item_name"><?php echo $item->name; ?></span>
	    </a>
	  </div>
	  <?php
	}
  }
  ?>
  <div style="clear: left;"></div>
  <?php 
  endBox();
	}
}
$page = new MobilePage();
?>