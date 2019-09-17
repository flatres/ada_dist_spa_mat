<?php
namespace Entities\Tags;

class Category {

  public $tagIds = [];
  public $tags = [];
  public $ownerId = 0;
  public $name;

  private $sql;
  private $isGlobal;

  //if no userId then a isGlobal tag is assumed
  public function __construct(\Dependency\Databases\Ada $ada = null)
  {
    $this->sql= $ada ?? new \Dependency\Databases\Ada();
  }

  //returns an array of objects containing the tag id and name
  public function byName(string $catName, int $userId = 0)
  {
    $cat = $this->sql->select('tag_categories', 'id', 'name=? AND user_id=?', ['House', $userId]);
    return $cat ? $this->byId($cat[0]['id']) : $this;
  }

  public function byId($id)
  {
    $this->id = $id;
    $cat = $this->sql->select('tag_categories', 'id, name, user_id', 'id=?', [$id]);

    if (!$cat) return null;

    $this->name = $cat[0]['name'];
    $this->ownerId = $cat[0]['user_id'];
    $this->isGlobal = $this->ownerId == 0 ? true : false;

    $this->tags = [];
    $this->tagIds = [];
    $tags = $this->sql->select('tag_tags', 'id, name', 'cat_id=? ORDER BY name ASC', [$id]);
    foreach($tags as $tag){
      $t = new \Entities\Tags\Tag();
      $this->tags[] = $t->byId($tag['id']);
      $this->tagIds[] = $tag['id'];
    }
    return $this;
  }

  public function newByNames(string $categoryName, string $tagName, int $studentId, int $ownerId = 0)
  {
    $this->isGlobal = $ownerId == 0 ? true : false;
    $this->ownerId = $ownerId;
    $catId = $this->doesNameExist($categoryName) ? $this->byName($categoryName)->id : $this->newCategory($categoryName)->id;
    $this->byId($catId);
    $tag = new \Entities\Tags\Tag($this->sql);
    if ($tag->doesNameExist((int)$catId, $tagName)) {
      $tagId = $tag->byName((int)$catId, $tagName)->id;
      $tag->newMember($catId, $tagId, $studentId);
    } else {
      $tagId = $tag->newTag((int)$catId, $tagName, $studentId)->id;
    }
    return $tagId;

  }

  private function newCategory(string $name)
  {
    if ($this->isGlobal) {
      $id = $this->sql->insert('tag_categories', 'name, user_id', array($name, 0));
    } else {
      $id = $this->sql->insert('tag_categories', 'name, user_id', array($name, $this->ownerId));
    }
    return $this->byId($id);
  }

  private function categoryIdExists(int $id)
  {
    return $this->sql->exists(
      'tag_categories',
      'id = ?',
      array($id)
    );
  }
  private function doesNameExist(string $categoryName)
  {
    if ($this->isGlobal)
    {
      return $this->sql->exists(
        'tag_categories',
        'name = ? AND user_id =?',
        array($categoryName, 0)
      );
    } else {
      return $this->sql->exists(
        'tag_categories',
        'name = ? AND user_id = ?',
        array($categoryName, $this->ownerId)
      );
    }
  }

}

 ?>
