<?php
/**
 * An interface to handle systematic literature review data. Not really related to Stendhal.
 */
require_once('scripts/slr.php');
class SystematicLiteratureReviewPage extends Page {
	function writeContent() {

if(getAdminLevel()<1000) {
 die("Ooops!");
}

if(isset($_POST['action'])) {
  if($_REQUEST['action']=='submit') {
    startBox("Adding slr item");
    if (!isset($_REQUEST['paper_bibkey']) || trim($_REQUEST['paper_bibkey']) == '') {
    	die('Sorry you forgot the bibkey, all your data is lost.');
    }
      addSlr($_REQUEST['title'], $_REQUEST['onelinedescription'], $_REQUEST['description'], $_REQUEST['images'], $_REQUEST['details'], $_REQUEST['slrTypeId']);
    endBox();
  }
}

if ((isset($_REQUEST['action'])) && $_REQUEST['action']=='edit') {  
  $id = mysql_real_escape_string($_REQUEST['edit']);  
  $slrtoEdit=getSlr($id);
  if(sizeof($slrtoEdit)==0) {
    startBox("Edit slr item");
      echo '<div class="error">No such slr item</div';
    endBox();
  } else {
    $edited=$slrtoEdit[0];
  } 
}

  /*
   * Show all the previous slr items, just header and to tickets approbed and deleted.
   */ 
  $usernames = array('hendrikus', 'metzgermeister');
  $reviewers = array('hendrik', 'markus');
  $reviewer = str_replace($usernames, $reviewers, $_SESSION['username']);
  $slr=getAllSlr($reviewer);
  startBox("Admin on existing slr");
  foreach($slr as $item) {
    ?>
    <div class="slr_list">
    <span class="date"><?php echo $item['paper_bibkey']; ?></span>
    <span><a href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/slr&amp;action=edit&amp;edit=<?php echo $item['id']; ?>#editform"><?php echo $item['paper_title']; ?></a></span>
    </div>
    <?php
    }
  ?> 
  <?php
  endBox();  
?>

<a name="editform"></a>
<?php startBox((isset($edited)?'Edit':'Submit').' slr item'); ?>
<form class="slr" method="post" action="/?id=content/admin/slr" name="submitslr">
	<?php if(isset($edited)) { ?>
		<input type="hidden" name="action" value="update"/>
		<input type="hidden" name="slr_id" value="<?php echo htmlspecialchars($_REQUEST['edit']); ?>"/>
	<?php } else { ?>
		<input type="hidden" name="action" value="submit"/>
	<?php }?>

	<table width="100%">
	<tbody>
		<tr><td>Title</td></tr>
		<tr><td><input name="title" <?php if(isset($edited)) echo 'value="'.$edited->title.'"'; ?>></td></tr>

		<tr><td>Short description (deprecated)</td></tr>
		<tr><td><input name="onelinedescription" <?php if(isset($edited)) echo 'value="'.$edited->oneLineDescription.'"'; ?>></td></tr>
		<tr><td>Slr Type</td></tr>
		<tr><td>
		
			<select name="slrTypeId">
			<?php 
				$types = getSlrTypes();
				foreach($types as $type) {
					echo '<option value="'.htmlspecialchars($type->id).'"';
					if (isset($edited->typeId) && ($edited->typeId == $type->id)) {
						echo ' selected="selected"';
					}
					echo '>'.htmlspecialchars($type->title).'</option>';
				}
			?>
			</select>
		</td></tr>
		
		<tr><td>Body</td></tr>
		<tr><td><textarea rows="24" name="description"><?php if(isset($edited)) echo $edited->extendedDescription; ?></textarea></td></tr>

		<tr><td>Details (only displayed on its own page)</td></tr>
		<tr><td><textarea rows="24" name="details"><?php if(isset($edited)) echo $edited->detailedDescription; ?></textarea></td></tr>

		<tr><td><input type="submit" value="Submit"></td></tr>
	</tbody>
	</table>
	<br>
</form>
<?php
		endBox();
	}
}
$page = new SystematicLiteratureReviewPage();
?>