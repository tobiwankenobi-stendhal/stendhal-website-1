<?php
/**
 * An interface to handle systematic literature review data. Not really related to Stendhal.
 */


/**
  * Returns a list of slr for a specified reviewer
  */
function getAllSlr($reviewer) {
	$filter = '';
	if (isset($reviewer)) {
		$filter = "WHERE reviewer='".mysql_real_escape_String($reviewer)."'";
	}
	$sql = "SELECT * FROM slr WHERE id in (SELECT max(id) FROM slr ".$filter." GROUP BY reviewer,paper_bibkey) ORDER BY paper_bibkey;";
	return getSlrArray($sql);
}

function getSlr($id) {
	$sql = "SELECT * FROM slr WHERE id ='".mysql_real_escape_String($id)."'";
	return getSlrArray($sql);
}

function getSlrArray($sql) {
	$result = mysql_query($sql, getWebsiteDB());

	$list = array();
	while($row = mysql_fetch_assoc($result)) {
		$list[] = $row;
	}

	mysql_free_result($result);
	return $list;
}


function addSlr($title, $oneline, $body, $images, $details, $type) {
	$title=mysql_real_escape_string($title);
	$oneline=mysql_real_escape_string($oneline);
	$body=mysql_real_escape_string($body);
	$details=mysql_real_escape_string($details);
	$type=mysql_real_escape_string($type);

	$query="insert into slr (title, shortDescription, extendedDescription, active, detailedDescription, slr_type_id) values "
		."('$title', '$oneline', '$body', 1, '$details', '$type')";
	mysql_query($query, getWebsiteDB());
	if(mysql_affected_rows()==0) {
		echo '<span class="error">There has been a problem while inserting slr:'.mysql_affected_rows().'</span>';
		echo '<span class="error_cause">'.$query.'</span>';
		return;
	}

	$result=mysql_query('select LAST_INSERT_ID() As lastid from slr;', getWebsiteDB());
	while($rowimages=mysql_fetch_assoc($result)) {      
		$slrid=$rowimages['lastid'];
	}
	mysql_free_result($result);

	foreach(explode("\n",$images) as $image) {
		mysql_query('insert into slr_images values(null,'.$slrid.',"'.mysql_real_escape_string($image).'",null, null', getWebsiteDB());
	}
}


/**
  * A class representing a slr item without comments.
  */
class SlrType {
	public $id;

	/** Title */
	public $title;

	/** Image */
	public $image;

	function __construct($id, $title, $image) {
		$this->id=$id;
		$this->title=$title;
		$this->image=$image;
	}
}

function getSlrTypes() {
	$sql = 'SELECT * FROM slr_type ORDER BY title';

	$result = mysql_query($sql, getWebsiteDB());
	$list = array();

	while($row = mysql_fetch_assoc($result)) {
		$list[]=new SlrType(
			$row['id'],
			$row['title'],
			$row['image']
		);
	}

	mysql_free_result($result);
	return $list;
}
?>