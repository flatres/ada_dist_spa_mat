<?php
namespace Entities\Tags;

class Tag {

  public $id, $name, $catName, $ownerId;
  public $isCumulative;
  public $members = [];
  public $memberIds = [];

  private $sql;

  //if no userId then a global tag is assumed
  public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
  {
    $this->sql= $ada ?? new \Dependency\Databases\Ada();
    if ($id) $this->byId($id);
  }

  //returns an array of objects containing the tag id and name
  public function byId(int $id)
  {
    $this->id = $id;
    $d = $this->sql->select('tag_tags', 'name, cat_id', 'id=?', [$id]);
    if ($d) {
      $this->catId = $d[0]['cat_id'];
      $this->name = $d[0]['name'];
      $e = $this->sql->select('tag_categories', 'name, user_id, cumulative', 'id=?', [$id]);
      if ($e) {
        $e = $e[0];
        $this->catName = $e['name'];
        $this->ownerId = $e['user_id'];
        $this->isCumulative = $e['cumulative'];
      }
    }
    return $this;
  }

  public function byName(int $catId, string $name)
  {
    $tag = $this->sql->select(
      'tag_tags',
      'id',
      'name = ? AND cat_id = ?',
      [$name, $catId]
    );

    return $tag ? $this->byId($tag[0]['id']) : null;
  }

  public function memberIds()
  {
    if (!$this->id) return;
    $d = $this->sql->select('tag_tagmap', 'student_id', 'tag_id=?', array($this->id));
    $this->memberIds = [];
    foreach($d as $member){
      $this->memberIds[] = $member['student_id'];
    }
    return $this->memberIds;
  }

  public function newMember(int $catId, int $tagId, int $userId, string $value = null)
  {
    $data = [$userId, $tagId, $catId, $value];
    // $isCumulative = $this->isCumulative($catId);
    $isCumulative = false;
    if(!$isCumulative) $this->sql->delete('tag_tagmap', 'student_id=? AND cat_id=?', $data);
    return $this->sql->insert('tag_tagmap', 'student_id, tag_id, cat_id, value', $data);
  }

  public function create(string $catName, string $tagName, int $studentId = null, string $value = null, array $options = [])
  {
    $cat = new \Entities\Tags\Tag($this->sql);
    //create new category if doesn't exist
    $cat->create($categoryName);
    if ($this->doesNameExist($cat->id, $tagName)) {
      $tagId = $this->byName((int)$catId, $tagName)->id;
      $tag->newMember($catId, $tagId, $studentId);
    } else {
      $tagId = $this->newTag((int)$catId, $tagName, $studentId)->id;
    }

    $id = $this->sql->insert('tag_tags', 'cat_id, name', array($catId, $tagName));
    if ($studentId) $this->newMember($catId, $id, $studentId, $value);
    return $this->byId($id);
  }

  public function members()
  {
    if (!$this->id || count($this->members) > 0) return $this->members;
    $d = $this->sql->select('t_tagmap', 'user_id', 'tag_id=?', array($tagId));
    foreach($d as $member){
      $this->members[] = new \Entities\People\Student($this->sql, $member['user_id']);
    }
    return $this->members;
  }

  public function doesNameExist(int $catId, string $tagName)
  {
    return $this->sql->exists(
      'tag_tags',
      'name = ? AND cat_id = ?',
      array($tagName, $catId)
    );
  }

}

 ?>
