<?php
class EventPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Events'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

		$event_id=$_REQUEST["event_id"];
		$events=getEvents('where id='.mysql_real_escape_string($event_id));
		$choosen=$events[0];
    startBox($choosen->oneLineDescription); ?>
    <div>
      <div><b>Type: </b><?php echo $choosen->type; ?></div>
      <div><b>Place: </b><?php echo $choosen->location; ?></div>
      <div><b>Date: </b><?php echo $choosen->date; ?></div>
    </div>
    <?php echo $choosen->extendedDescription; ?>
    
    <?php foreach($choosen->images as $image) { ?>
      <img src="<?php echo $image; ?>" alt="Raid image"/>
    <?php }
    endBox();
	}
}
$page = new EventPage();
?>