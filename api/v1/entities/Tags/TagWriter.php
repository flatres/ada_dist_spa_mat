<?php
namespace Entity\Tags;

class TagWriter {

  private $sql;

  //if no userId then a global tag is assumed
  public function __construct(\Dependency\Mysql $mySql, int $ownerId = null)
  {
    $this->sql= $mySql;
    $this->global = $ownerId == null;
    $this->ownerId = $ownerId;
  }

  public function newByNames(string $categoryName, string $tagName, string $userId)
  {
    $catId = $this->categoryNameExists($categoryName) ? $this->categoryByName($categoryName) : $this->newCategory($categoryName);
    $tagId = $this->tagNameExists((int)$catId, $tagName) ? $this->tagByName((int)$catId, $tagName) : $this->newTag((int)$catId, $tagName);

    return $this->newTagEntry((int)$catId, (int)$tagId, $userId);

  }

  private function categoryIdExists(int $id)
  {
    return $this->sql->exists(
      'tag_categories',
      'id',
      'id = ?',
      array($id)
    );
  }
  private function categoryNameExists(string $categoryName)
  {
    if ($this->global)
    {
      return $this->sql->exists(
        'tag_categories',
        'id',
        'name = ? AND user_id is NULL',
        array($categoryName)
      );
    } else {

      return $this->sql->exists(
        'tag_categories',
        'id',
        'name = ? AND user_id = ?',
        array($categoryName, $this->ownerId)
      );
    }
  }

  private function categoryByName(string $categoryName)
  {
    if ($this->global)
    {
      $cat = $this->sql->select(
        'tag_categories',
        'id',
        'name = ? AND user_id is NULL',
        array($categoryName)
      );
    } else {
      $cat = $this->sql->select(
        'tag_categories',
        'id',
        'name = ? AND user_id = ?',
        array($categoryName, $this->ownerId)
      );
    }
    return isset($cat[0]) ? $cat[0]['id'] : null;
  }

  private function newCategory(string $name)
  {
    if ($this->global)
    {
      return $this->sql->insert('tag_categories', 'name', array($name));
    } else {
      return $this->sql->insert('tag_categories', 'name, user_id', array($name, $this->ownerId));
    }
  }

  private function tagNameExists(int $catId, string $tagName)
  {
    return $this->sql->exists(
      'tag_tags',
      'id',
      'name = ? AND cat_id = ?',
      array($tagName, $catId)
    );
  }

  private function tagByName(int $catId, string $name)
  {
    $cat = $this->sql->select(
      'tag_tags',
      'id',
      'name = ? AND cat_id = ?',
      array($name, $catId)
    );
    return isset($cat[0]) ? $cat[0]['id'] : null;
  }

  private function newTag(int $catId, string $tagName)
  {
    return $this->sql->insert('tag_tags', 'cat_id, name', array($catId, $tagName));
  }

  private function newTagEntry(int $catId, int $tagId, int $userId)
  {
    $data = array($userId, $tagId, $catId);
    $isCumulative = $this->isCumulative($catId);
    if(!$isCumulative) $this->sql->delete('tag_tagmap', 'student_id=? AND tag_id=? AND cat_id=?', $data);
    return $this->sql->insert('tag_tagmap', 'student_id, tag_id, cat_id', $data);
  }

  private function isCumulative($catId){

    if($this->categoryIdExists($catId)){
        return $this->sql->select('tag_categories', 'cumulative', 'id=?', array($catId))[0]['cumulative'];
    } else {
      return false;
    }

  }

}

 ?>
