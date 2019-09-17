<?php
namespace Entities\Tags\Tools;

class TagReader {

  //stored category names between classes to minimise calles to db to retreive name
  private static $categoryName = array();
  private $sql;

  //if no userId then a global tag is assumed
  public function __construct(\Dependency\Mysql $mySql, int $ownerId = null)
  {
    $this->sql= $mySql;
    $this->ownerId = $ownerId;
  }

  //returns an array of tags that include the category name
  public function studentTags($studentId)
  {
    $sql = $this->sql;
    $tags = $sql->select('tag_tagmap', 'tag_id, cat_id', 'student_id=?', array($studentId));
    foreach($tags as &$tag)
    {
      $d = $sql->select('tag_tags', 'name', 'id=?', array($tag['tag_id']));
      $tag['name'] = isset($d[0]) ? $d[0]['name'] : 'no nanme';
      if( isset($this->categoryName) )
      {
        $tag['category'] = $this->categoryNames['c' . $tag['cat_id']];
      } else {
        $tag['category'] = $sql->select('tag_categories', 'name', 'id=?', array($tag['cat_id']))[0]['name'];
        $this->categoryNames['c' . $tag['cat_id']] = $tag['category'];
      }
    }
    return $tags;
  }
  //returns an array of tags that include the category name
  public function studentHouses($studentId)
  {
    $sql = $this->sql;

    $cats = $sql->select('tag_categories', '')
    $tags = $sql->select('tag_tagmap', 'tag_id, cat_id', 'student_id=?', array($studentId));
    foreach($tags as &$tag)
    {
      $d = $sql->select('tag_tags', 'name', 'id=?', array($tag['tag_id']));
      $tag['name'] = isset($d[0]) ? $d[0]['name'] : 'no nanme';
      if( isset($this->categoryName) )
      {
        $tag['category'] = $this->categoryNames['c' . $tag['cat_id']];
      } else {
        $tag['category'] = $sql->select('tag_categories', 'name', 'id=?', array($tag['cat_id']))[0]['name'];
        $this->categoryNames['c' . $tag['cat_id']] = $tag['category'];
      }
    }
    return $tags;
  }


}

 ?>
