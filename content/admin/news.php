<?php
if(getAdminLevel()<5000) {
 die("Ooops!");
}
   
if(isset($_REQUEST['action'])) {
  if($_REQUEST['action']=='submit') {
    startBox("Adding news item");
      addNews($_REQUEST['title'],$_REQUEST['onelinedescription'],$_REQUEST['description'],$_REQUEST['images']);
    endBox();
  } elseif($_REQUEST['action']=='update') {
    startBox("Updating news item");
      updateNews($_REQUEST['news_id'],$_REQUEST['title'],$_REQUEST['onelinedescription'],$_REQUEST['description'],$_REQUEST['images']);
    endBox();
  } elseif($_REQUEST['action']=='delete') {
    startBox("Deleting news item");
      foreach($_REQUEST['delete'] as $id) {
        deleteNews($id);
      }
    endBox();
  } elseif($_REQUEST['action']=='edit') {    
    $newstoEdit=getNews('where id='.$_REQUEST['edit']);
    if(sizeof($newstoEdit)==0) {
      startBox("Edit news item");
        echo '<div class="error">No such news item</div';
      endBox();
    } else {
      $edited=$newstoEdit[0];
    }
  } 
}

  /*
   * Show all the previous news items, just header and to tickets approbed and deleted.
   */ 
  $news=getNews('', 'created desc','');
  startBox("Admin on existing news");
  ?>
  <form class="news" method="post" action="?id=content/admin/news" name="updatenews">
  <input type="hidden" name="action" value="delete"/>
  <?php
  foreach($news as $item) {
    ?>
    <div class="news_list">
    <input type="checkbox" name="delete[]" value="<?php echo $item->id; ?>">
    <span class="date"><?php echo $item->date; ?></span>
    <span><a href="?id=content/admin/news&action=edit&edit=<?php echo $item->id; ?>"><?php echo $item->title; ?></a></span>
    </div>
    <?php
    }
  ?> 
  <div style="text-align: right;"><input type="submit" value="Delete"></div>
  </form>
  <?php
  endBox();  
?>

<?php startBox((isset($edited)?'Edit':'Submit').' news item'); ?>
<form class="news" method="post" action="?id=content/admin/news" name="submitnews">
  <?php 
   if(isset($edited)) {
   ?>
  <input type="hidden" name="action" value="update"/>
  <input type="hidden" name="news_id" value="<?php echo $_REQUEST['edit']; ?>"/>
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
        <td>Title</td>
      </tr>
      <tr>
        <td><input name="title" <?php if(isset($edited)) echo 'value="'.$edited->title.'"'; ?>></td>
      </tr>
      <tr>
        <td>Short description</td>
      </tr>      
      <tr>      
        <td><input name="onelinedescription" <?php if(isset($edited)) echo 'value="'.$edited->oneLineDescription.'"'; ?>></td>
      </tr>
      <tr>
        <td>Body</td>
      </tr>
      <tr>
        <td><textarea rows="24" name="description"><?php if(isset($edited)) echo $edited->extendedDescription; ?></textarea></td>
      </tr>
      <tr>
        <td>Images</td>
      </tr>
      <tr>
        <td><textarea rows="4" name="images"></textarea></td>
      </tr>
      <tr>
        <td><input type="submit" value="Submit"></td>
      </tr>
    </tbody>
  </table>
  <br>
</form>
<?php endBox(); ?>
