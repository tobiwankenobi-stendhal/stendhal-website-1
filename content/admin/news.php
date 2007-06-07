<?php

if(!isset($_REQUEST['action'])) {
  /*
   * Show all the previous news items, just header and to tickets approbed and deleted.
   */ 
  $news=getNews();
  startBox("Admin on existing news");
  foreach($news as $item) {
    echo '<div class="news_list"><span class="id">'.$item->id.'</span><span class="title">'.$item->title.'</span><span class="date">'.$item->date.'</span></div>';
    }
  endBox();
   
} elseif($_REQUEST['action']=='submit') {
  startBox("Adding news item");
    addNews($_REQUEST['title'],$_REQUEST['onelinedescription'],$_REQUEST['description'],$_REQUEST['images']);
  endBox();
} 

?>

<?php startBox("Submit news item"); ?>
<form class="news" method="post" action="?id=content/admin/news" name="submitnews">
  <input type="hidden" name="action" value="submit"/>
  <table width="100%">
    <tbody>
      <tr>
        <td>Title</td>
      </tr>
      <tr>
        <td><input name="title"></td>
      </tr>
      <tr>
        <td>Short description</td>
      </tr>      
      <tr>      
        <td><input name="onelinedescription"></td>
      </tr>
      <tr>
        <td>Body</td>
      </tr>
      <tr>
        <td><textarea rows="24" name="description"></textarea></td>
      </tr>
      <tr>
        <td>Images</td>
      </tr>
      <tr>
        <td><textarea rows="4" name="images"></textarea></td>
      </tr>
      <tr>
        <td><input type="submit"></td>
      </tr>
    </tbody>
  </table>
  <br>
</form>
<?php endBox(); ?>
