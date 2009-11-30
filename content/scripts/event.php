<?php
class EventPage extends Page {
	private $event_id;
	private $eventss;

	public function __construct() {
		$this->event_id = $_REQUEST["event_id"];
		$this->events=getEvents('where id='.mysql_real_escape_string($this->event_id));
	}

	public function writeHtmlHeader() {
		if(sizeof($this->events) == 0) {
			echo '<title>Non existing event '.STENDHAL_TITLE.'</title>';
			echo '<meta name="robots" content="noindex">';
		} else {
			$choosen = $this->events[0];
			echo '<title>Event '.htmlspecialchars($choosen->oneLineDescription).STENDHAL_TITLE.'</title>';
		}
	}

	function writeContent() {
		$choosen = $this->events[0];
		startBox(htmlspecialchars($choosen->oneLineDescription));
		?>
    <div>
      <div><b>Type: </b><?php echo $choosen->type; ?></div>
      <div><b>Place: </b><?php echo $choosen->location; ?></div>
      <div><b>Date: </b><?php echo $choosen->date; ?></div>
    </div>
		<?php
		echo $choosen->extendedDescription;

		foreach($choosen->images as $image) { ?>
			<img src="<?php echo $image; ?>" alt=""/>
		<?php
		}
		endBox();
	}
}
$page = new EventPage();
?>