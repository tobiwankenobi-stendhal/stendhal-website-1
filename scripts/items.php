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
/*
 * A class representing an item.
 */
class Item {
  public static $classes=array();
  public static $items=array();
  
  /* Name of the item */
  public $name;
  /* Description of the item */
  public $description;
  /* Class of the item */
  public $class;
  /* GFX URL of the item. */
  public $gfx;
  /* Attributes of the item as an array attribute=>value */
  public $attributes;
  /* Where the item can be wore as an array slot=>item */
  public $equipableat;

  function __construct($name, $description, $class, $gfx,$attributes, $equipableat) {
    $this->name=$name;
    $this->description=$description;
    $this->class=$class;
    self::$classes[$class]=0;
    $this->gfx=$gfx;
    $this->attributes=$attributes;
    $this->equipableat=$equipableat;
  }
  
  function showImage() {
  	return $this->gfx;
  }
  
  function getClasses() {
    return self::$classes;
  }
}

function getItem($name) {
  $items=getItems();
  foreach($items as $i) {
    if($i->name==$name) {
      return $i;
    }
  }

  return null;
}

/**
  * Returns a list of Items
  */
function getItems() {
  if(sizeof(Item::$items)!=0) {
    return Item::$items;
  }
  
  $itemsXMLConfigurationFile="data/conf/items.xml";
  $itemsXMLConfigurationBase='data/conf/';

  $itemfiles = XML_unserialize(implode('',file($itemsXMLConfigurationFile)));
  $itemfiles = $itemfiles['groups'][0]['group'];

  $list=array();

  foreach( $itemfiles as $file )
  {
	if(isset($file['uri']))
    {
	  $items =  XML_unserialize(implode('',file($itemsXMLConfigurationBase.$file['uri'])));
	  $items = $items['items'][0]['item'];
	    
      for($i=0;$i<sizeof($items)/2;$i++) {
        $name=$items[$i.' attr']['name'];
    
        if(isset($items[$i]['description'])) {
          $description=$items[$i]['description']['0'];
        } else {
          $description='';
	    }
    
        $class=$items[$i]['type']['0 attr']['class'];
        $gfx='data/sprites/items/'.$class.'/'.$items[$i]['type']['0 attr']['subclass'].'.png';
        $gfx='itemimage.php?url='.$gfx;
    
        $attributes=array();
        if(is_array($items[$i]['attributes'][0])) {
          foreach($items[$i]['attributes'][0] as $attr=>$val) {
            $attributes[$attr]=$val['0 attr']['value'];
          }
        }
	  
    
        /* DEBUGGING
        echo '<h1>Item: '.$name.'</h1><br>';
        echo 'Description: "'.$description.'"<br>';
        echo 'Class: "'.$class.'"<br>';
        echo 'GFX: "'.$gfx.'"<br>';    
        echo '<img src="'.$gfx.'"/><br>';
        echo 'Attributes: <br>';
        print_r($attributes);
        */

        $list[]=new Item($name, $description, $class, $gfx, $attributes, null);
      }
	}
  }
  
  function compare($a, $b) {
    return strcmp($a->name,$b->name);
  }
  /*
   * Sort it alphabetically.
   */
  usort($list, 'compare');
  
  Item::$items=$list;
  return $list;
}

?>