<?php
/**
 * An interface to handle systematic literature review data. Not really related to Stendhal.
 */
require_once('scripts/slr.php');
class SystematicLiteratureReviewPage extends Page {
	private static $READONLY_ATTRIBUTES = array('id', 'reviewer', 'timedate');
	private $edited;
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
    $this->edited=$slrtoEdit[0];
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
<?php
startBox((isset($this->edited)?'Edit':'Submit').' slr item');

?>
<form class="slr" method="post" action="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/slr" name="submitslr">
	<input type="hidden" name="action" value="submit"/>

	<table width="100%">
	<tbody>
		<?php
		$metadata = getSlrMetadata();
		for ($i = 0; $i < count($metadata); $i++) {
			$meta = $metadata[$i];
			echo '<tr>';
			$this->writeInputHeader($meta);
			echo '</tr><tr>';
			$this->writeInputBody($meta);
			echo '</tr>';
		}
		?>


		<tr><td><input type="submit" value="Submit"></td></tr>
	</tbody>
	</table>
	<br>
</form>
<?php
		endBox();
	}

	function writeInputHeader($meta) {
		echo '<td><b>'.htmlspecialchars($meta['column_name']).'</b> ';
		if (isset($meta['column_comment']) && trim($meta['column_comment']) != '') {
			echo '('.htmlspecialchars($meta['column_comment']).')';
		}
		echo '</td>';
	}

	function writeInputBody($meta) {
		echo '<td>';
		if (in_array($meta['column_name'], SystematicLiteratureReviewPage::$READONLY_ATTRIBUTES)) {
			echo htmlspecialchars($this->edited[$meta['column_name']]);
		} else if ($meta['column_type'] == "text") {
			echo '<textarea rows="10" style="width:99%" name="'.htmlspecialchars($meta['column_name']).'"';
			if (isset($this->edited[$meta['column_name']])) {
				echo htmlspecialchars($this->edited[$meta['column_name']]);
			}
			echo '</textarea>';
		} else {
			echo '<input name="'.htmlspecialchars($meta['column_name']).'" style="width:99%" ';
			if (isset($this->edited[$meta['column_name']])) {
				echo 'value="'.htmlspecialchars($this->edited[$meta['column_name']]).'"';
			}
			echo '>';
		}
		echo '</td>';
	}
}
$page = new SystematicLiteratureReviewPage();
?>