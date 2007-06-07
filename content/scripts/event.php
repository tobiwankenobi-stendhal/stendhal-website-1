<?php
$event_id=$_REQUEST["event_id"];
$events=getEvents('where id='.$event_id);
$choosen=$events[0];
?>

  <?php startBox($choosen->oneLineDescription); ?>
    <div>
      <div><b>Type: </b><?php echo $choosen->type; ?></div>
      <div><b>Place: </b><?php echo $choosen->location; ?></div>
      <div><b>Date: </b><?php echo $choosen->date; ?></div>
    </div>
    <?php echo $choosen->extendedDescription; ?>
    
    <?php foreach($choosen->images as $image) { ?>
      <img src="<?php echo $image; ?>" alt="Raid image"/>
    <?php } ?>
  <?php endBox(); ?>
