<?php
/**
 * An interface to handle systematic literature review data. Not really related to Stendhal.
 */

/**
  * A class representing a slr item without comments.
  */
class Slr {
	public $id;

	/** Title of the slr item */
	public $title;

	/** Date in ISO format YYYY-MM-DD HH:mm */
	public $date;

	/** One line description of the slr item. */
	public $oneLineDescription;

	/** Extended description of the slr item that follow the one line one. */
	public $extendedDescription;

	/** Images of the slr item */
	public $images;

	/** description for detail page */
	public $detailedDescription;

	/** active */
	public $active;

	/** id of type */
	public $typeId;

	/** name of type */
	public $typeTitle;

	/** image of type */
	public $typeImage;

	/** counts the number of updates */
	public $updateCount;

	function __construct($id, $title, $date, $shortDesc, $longDesc, $images, $detailedDescription, $active, $typeId, $typeTitle, $typeImage, $updateCount) {
		$this->id=$id;
		$this->title=$title;
		$this->date=$date;
		$this->oneLineDescription=$shortDesc;
		$this->extendedDescription=$longDesc;
		$this->images=$images;
		$this->detailedDescription = $detailedDescription;
		$this->active = $active;
		$this->typeId = $typeId;
		$this->typeTitle = $typeTitle;
		$this->typeImage = $typeImage;
		$this->updateCount = $updateCount;
	}

	function show($detail=false) {
		// link the title unless we are in detail view
		$heading = '<div class="slrDate">'.$this->date.'</div><div class="slrTitle">';
		if (!$detail) {
			$heading .= '<a style="slrTitle" href="'.rewriteURL('/slr/'.$this->getNiceURL()).'">'.$this->title.'</a>';
		} else {
			$heading .= $this->title;
		}
		$heading .= '</div>';
		
		startBox($heading);

		// image for type of slr
		if (isset($this->typeImage) && strlen($this->typeImage) > 0) {
			echo '<div class="slrIcon" style="float: right; padding-left: 2em"><img src="'.$folder.htmlspecialchars($this->typeImage).'" title="'.htmlspecialchars($this->typeTitle).'" alt=""></div>';
		}

		// render one line description
		if (isset($this->oneLineDescription) && strlen($this->oneLineDescription) > 0) {
			echo '<div class="slrContent">'.$this->oneLineDescription.'</div>';
		}

		// render slr posting (add more link if there is a detail version)
		echo '<div class="slrContent slrTeaser">'.$this->extendedDescription;
		if (!$detail) {
			if (isset($this->detailedDescription) && (trim($this->detailedDescription) != '')) {
				echo ' <a href="'.rewriteURL('/slr/'.$this->getNiceURL()).'" title="Read More...">(read more)</a>';
			}
		}
		echo '</div>';

		// in detail view, include the details
		if ($detail) {
			echo '<div class="slrContent slrDetail">'.$this->detailedDescription.'</div>';
		}
		endBox();
		/* END NOTE */
	}

	/**
	 * gets a nice url
	 *
	 * @return nice url
	 */
	function getNiceURL() {
		$res = strtolower($this->title.'-'.$this->id);
		$res = preg_replace('/[ _,;.:<>|!?\'"] /', ' ', $res);
		$res = preg_replace('/[ _,;.:<>|!?\'"]/', '-', $res);
		return urlencode($res.'.html');
	}
};



/**
  * Returns a list of slr for a specified reviewer
  */
function getSlr($reviewer) {
	$sql = "SELECT * FROM slr WHERE id in (SELECT max(id) FROM slr WHERE reviewer='".mysql_real_escape_String($reviewer)."' GROUP BY reviewer,paper_bibkey) ORDER BY paper_bibkey;";
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