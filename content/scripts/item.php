<?php 

$name=$_REQUEST['name'];
$isExact=isset($REQUEST['exact']);

$items=getItems();

function renderAmount($amount) {
  $amount=str_replace("[","",$amount);
  $amount=str_replace("]","",$amount);
  list($min,$max)=explode(",",$amount);
  
  if($min!=$max) {
    return "between $min and $max.";
  } else {
  	return "exactly $min.";
  }
}

foreach($items as $m) {
  /*
   * If name of the creature match or contains part of the name.
   */
  if($m->name==$name || (!$isExact and strpos($m->name,$name)!=false)) {
    startBox("Detailed information");
    ?>
    <div class="item">
      <div class="name"><?php echo ucfirst($m->name); ?></div>
      <div class="type">This item is of <?php echo $m->class ?> class</div>
      <img class="item" src="<?php echo $m->gfx; ?>" alt="<?php echo $m->name; ?>"/>
      <div class="description">
        <?php 
          if($m->description=="") {
            echo "No description. Would you like to write one?";
          } else {
            echo $m->description;
          }
        ?>
      </div>
      
      <div class="table">
        <div class="title">Attributes</div>
          <?php
          foreach($m->attributes as $label=>$data) {
            ?>
            <div class="row">
              <div class="label"><?php echo strtoupper($label); ?></div>
              <div class="data"><?php echo $data; ?></div>
            </div>
            <?php
          }
          ?>
        </div>      
                  
      <div class="table">
        <div class="title">Dropped by</div>
          <div style="float: left; width: 100%;">
            <?php
          $monsters=getMonsters();
          foreach($monsters as $monster) {
            foreach($monster->drops as $k) {
              if($k["name"]==$m->name) {
              ?>
              <div class="row">
                <a href="?id=content/scripts/monster&name=<?php echo $monster->name; ?>&exact">
                <img src="<?php echo $monster->showImage(); ?>" alt="<?php echo $monster->name; ?>"/>
                <div class="label"><?php echo $monster->name;; ?></div>
                </a>
                <div class="data">Drops <?php echo renderAmount($k["quantity"]); ?></div>
                <div class="data">Probability: <?php echo $k["probability"]; ?>%</div>
                <div style="clear: left;"></div>
              </div>
              <?php
              }
            } 
          }
          ?>
          </div>
          <div style="clear: left;"></div>
        </div>
      </div>
    <?php      
    endBox();      
  }
}

?>