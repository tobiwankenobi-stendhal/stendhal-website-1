<?php
if(getAdminLevel()<400) {
 die("Ooops!");
}
   
if(isset($_POST['action'])) {
  if($_REQUEST['action']=='submit') {
    startBox("Adding Event");
      addEvent($_REQUEST['date'],$_REQUEST['location'],$_REQUEST['type'],$_REQUEST['onelinedescription'],$_REQUEST['description'],$_REQUEST['images']);
    endBox();
  } elseif($_REQUEST['action']=='update') {
    startBox("Updating event");
      updateEvent($_REQUEST['event_id'],$_REQUEST['date'],$_REQUEST['location'],$_REQUEST['type'],$_REQUEST['onelinedescription'],$_REQUEST['description'],$_REQUEST['images']);
    endBox();
  } elseif($_REQUEST['action']=='delete') {
    startBox("Deleting event");
      foreach($_REQUEST['delete'] as $id) {
        deleteEvent($id);
      }
    endBox();
  }
}

if ((isset($_REQUEST['action'])) && $_REQUEST['action']=='edit') {  
  $id=mysql_real_escape_string($_REQUEST['edit']);  
  $eventToEdit=getEvents('where id="'.$id.'"');
  if(sizeof($eventToEdit)==0) {
    startBox("Edit events");
      echo '<div class="error">No such event</div';
    endBox();
  } else {
    $edited=$eventToEdit[0];
  }
}

  /*
   * Show all the previous event items, just header and to tickets approbed and deleted.
   */ 
  $events=getEvents('', 'date desc','');
  startBox("Admin on existing events");
  ?>
  <form class="news" method="post" action="?id=content/admin/events" name="updateevents">
  <input type="hidden" name="action" value="delete"/>
  <?php
  foreach($events as $item) {
    ?>
    <div class="events_list">
    <input type="checkbox" name="delete[]" value="<?php echo $item->id; ?>">
    <span class="date"><?php echo $item->date; ?></span>
    <span><a href="?id=content/admin/events&action=edit&edit=<?php echo $item->id; ?>"><?php echo $item->oneLineDescription; ?></a></span>
    </div>
    <?php
    }
  ?> 
  <div style="text-align: right;"><input type="submit" value="Delete"></div>
  </form>
  <?php
  endBox();  
?>

<?php startBox((isset($edited)?'Edit':'Submit').' events'); ?>
<form class="news" method="post" action="?id=content/admin/events" name="submitevents">
  <?php 
   if(isset($edited)) {
   ?>
  <input type="hidden" name="action" value="update"/>
  <input type="hidden" name="event_id" value="<?php echo $_REQUEST['edit']; ?>"/>
   <?php
   } else {
   ?>
  <input type="hidden" name="action" value="submit"/>
  <?php
   }
   ?>
  <table width="100%">
    <tbody>
      <tr>
        <td>Date</td>
        <td>Location</td>
        <td>Raid/Meeting/Gift/Quiz</td>
      </tr>
      <tr>
        <td><input name="date" <?php if(isset($edited)) echo 'value="'.$edited->date.'"'; ?>></td>
        <td><input name="location" <?php if(isset($edited)) echo 'value="'.$edited->location.'"'; ?>></td>
        <td><input name="type" <?php if(isset($edited)) echo 'value="'.$edited->type.'"'; ?>></td>
      </tr>
      <tr>
        <td colspan="3">Short description</td>
      </tr>      
      <tr>      
        <td colspan="3"><input name="onelinedescription" <?php if(isset($edited)) echo 'value="'.$edited->oneLineDescription.'"'; ?>></td>
      </tr>
      <tr>
        <td colspan="3">Extended description</td>
      </tr>
      <tr>
        <td colspan="3"><textarea rows="14" name="description"><?php if(isset($edited)) echo $edited->extendedDescription; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="3">Images</td>
      </tr>
      <tr>
        <td colspan="3"><textarea rows="4" name="images"></textarea></td>
      </tr>
      <tr>
        <td colspan="3"><input type="submit" value="Submit"></td>
      </tr>
    </tbody>
  </table>
  <br>
</form>
<?php endBox(); ?>
