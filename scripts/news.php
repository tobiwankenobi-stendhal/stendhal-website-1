<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008  Miguel Angel Blanch Lardin

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
  * A class representing a news item without comments.
  */
class News {
	public $id;

	/** Title of the news item */
	public $title;

	/** Date in ISO format YYYY-MM-DD HH:mm */
	public $date;

	/** One line description of the news item. */
	public $oneLineDescription;

	/** Extended description of the news item that follow the one line one. */
	public $extendedDescription;

	/** Images of the news item */
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
		$heading = '<div class="newsDate">'.$this->date.'</div><div class="newsTitle">';
		if (!$detail) {
			$heading .= '<a style="newsTitle" href="'.rewriteURL('/news/'.$this->getNiceURL()).'">'.$this->title.'</a>';
		} else {
			$heading .= $this->title;
		}
		$heading .= '</div>';
		
		startBox($heading);

		// image for type of news
		if (isset($this->typeImage) && strlen($this->typeImage) > 0) {
			echo '<div class="newsIcon" style="float: right; padding-left: 2em"><img src="'.$folder.htmlspecialchars($this->typeImage).'" title="'.htmlspecialchars($this->typeTitle).'" alt=""></div>';
		}

		// render one line description
		if (isset($this->oneLineDescription) && strlen($this->oneLineDescription) > 0) {
			echo '<div class="newsContent">'.$this->oneLineDescription.'</div>';
		}

		// render news posting (add more link if there is a detail version)
		echo '<div class="newsContent newsTeaser">'.$this->extendedDescription;
		if (!$detail) {
			if (isset($this->detailedDescription) && (trim($this->detailedDescription) != '')) {
				echo ' <a href="'.rewriteURL('/news/'.$this->getNiceURL()).'" title="Read More...">(read more)</a>';
			}
		}
		echo '</div>';

		// in detail view, include the details
		if ($detail) {
			echo '<div class="newsContent newsDetail">'.$this->detailedDescription.'</div>';
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
		$res = preg_replace('/[ _,;.:<>|!?\'"()] /', ' ', $res);
		$res = preg_replace('/[ _,;.:<>|!?\'"()]/', '-', $res);
		return urlencode($res.'.html');
	}
};



/**
  * Returns a list of news. Note: All parameters need to be SQL escaped.
  */
function getNews($where='', $sortby='created desc', $cond='limit 5') {

	$sql = 'SELECT news.id As news_id, news.title As title, news.created As created, '
		.'news.shortDescription As shortDescription, '
		.'news.extendedDescription As extendedDescription, '
		.'news.detailedDescription As detailedDescription, news.active As active, '
		.'news_type.id As type_id, news_type.title As type_title, news_type.image_url As image_url, ' 
		.'news.updateCount As updateCount '
		.'FROM news LEFT JOIN news_type ON news.news_type_id=news_type.id '.$where.' order by '.$sortby.' '.$cond;

	$result = mysql_query($sql, getWebsiteDB());
	$list=array();
	
	while($row=mysql_fetch_assoc($result)) {
		$resultimages = mysql_query('select * from news_images where news_id="'.$row['id'].'" order by created desc', getWebsiteDB());
		$images=array();

		while($rowimages=mysql_fetch_assoc($resultimages)) {      
			$images[]=$rowimages['url'];
		}
		mysql_free_result($resultimages);

		$list[]=new News(
			$row['news_id'],
			$row['title'],
			$row['created'],
			$row['shortDescription'],
			$row['extendedDescription'],
			$images,
			$row['detailedDescription'],
			$row['active'],
			$row['type_id'],
			$row['type_title'],
			$row['image_url'],
			$row['updateCount']
		);
	}

	mysql_free_result($result);
	
	return $list;
}


function addNews($title, $oneline, $body, $images, $details, $type) {
	$title=mysql_real_escape_string($title);
	$oneline=mysql_real_escape_string($oneline);
	$body=mysql_real_escape_string($body);
	$details=mysql_real_escape_string($details);
	$type=mysql_real_escape_string($type);

	$query="insert into news (title, shortDescription, extendedDescription, active, detailedDescription, news_type_id) values "
		."('$title', '$oneline', '$body', 1, '$details', '$type')";
	mysql_query($query, getWebsiteDB());
	if(mysql_affected_rows()==0) {
		echo '<span class="error">There has been a problem while inserting news:'.mysql_affected_rows().'</span>';
		echo '<span class="error_cause">'.$query.'</span>';
		return;
	}

	$result=mysql_query('select LAST_INSERT_ID() As lastid from news;', getWebsiteDB());
	while($rowimages=mysql_fetch_assoc($result)) {      
		$newsid=$rowimages['lastid'];
	}
	mysql_free_result($result);

	foreach(explode("\n",$images) as $image) {
		mysql_query('insert into news_images values(null,'.$newsid.',"'.mysql_real_escape_string($image).'",null, null', getWebsiteDB());
	}
}


function deleteNews($id) {
    $id=mysql_real_escape_string($id);
    
	$query='delete from news where id="'.mysql_real_escape_string($id).'"';
    mysql_query($query, getWebsiteDB());
    if(mysql_affected_rows()==0) {
        echo '<span class="error">There has been a problem while deleting news.</span>';
        echo '<span class="error_cause">'.$query.'</span>';
        return;
    }
}

function updateNews($id, $title, $oneline, $body, $images, $details, $type) {
	$id=mysql_real_escape_string($id);
	$title=mysql_real_escape_string($title);
	$oneline=mysql_real_escape_string($oneline);
	$body=mysql_real_escape_string($body);
	$details=mysql_real_escape_string($details);
	$type=mysql_real_escape_string($type);
	
	$query="UPDATE news SET title='".$title."', shortDescription='".$oneline."',extendedDescription='".$body
		."', detailedDescription='".$details."', news_type_id='".$type."', updateCount=updateCount+1 WHERE id='".$id."'";
	mysql_query($query, getWebsiteDB());
	if(mysql_affected_rows()==0) {
		echo '<span class="error">There has been a problem while updating news.</span>';
		echo '<span class="error_cause">'.$query.'</span>';
	}
}
/**
  * Returns a list of news between adate and bdate both inclusive
  */
function getNewsBetween($adate, $bdate) {
  return getNews('where date between '.mysql_real_escape_string($adate).' and '.mysql_real_escape_string($bdate));
}







/**
  * A class representing a news item without comments.
  */
class NewsType {
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

function getNewsTypes() {
	$sql = 'SELECT * FROM news_type ORDER BY title';

	$result = mysql_query($sql, getWebsiteDB());
	$list = array();

	while($row = mysql_fetch_assoc($result)) {
		$list[]=new NewsType(
			$row['id'],
			$row['title'],
			$row['image']
		);
	}

	mysql_free_result($result);
	return $list;
}
?>