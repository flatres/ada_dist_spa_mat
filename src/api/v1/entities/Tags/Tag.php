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
    $d = $this->sql->select('tag_tags', 'name, catId', 'id=?', [$id]);
    if ($d) {
      $this->catId = $d[0]['catId'];
      $this->name = $d[0]['name'];
      $e = $this->sql->select('tag_categories', 'name, userId, cumulative', 'id=?', [$id]);
      if ($e) {
        $e = $e[0];
        $this->catName = $e['name'];
        $this->ownerId = $e['userId'];
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
      'name = ? AND catId = ?',
      [$name, $catId]
    );

    return $tag ? $this->byId($tag[0]['id']) : null;
  }

  public function memberIds()
  {
    if (!$this->id) return;
    $d = $this->sql->select('tag_tagmap', 'studentId', 'tagId=?', array($this->id));
    $this->memberIds = [];
    foreach($d as $member){
      $this->memberIds[] = $member['studentId'];
    }
    return $this->memberIds;
  }

  public function newMember(int $catId, int $tagId, int $studentId, string $value = null)
  {
    if (!$studentId || !$tagId || !$catId) return null;
    $data = [$studentId, $tagId, $catId, $value];
    // $isCumulative = $this->isCumulative($catId);
    $isCumulative = false;
    if(!$isCumulative) $this->sql->delete('tag_tagmap', 'studentId=? AND catId=?', [$studentId, $catId]);
    return $this->sql->insert('tag_tagmap', 'studentId, tagId, catId, value', $data);
  }
  
  public function value($catName, $tagName, $studentId, $ownerId = 0)
  {
    $cat = new \Entities\Tags\Category($this->sql);
    $cat->byName($catName, $ownerId);
    if ($cat) {
      $tagResult = $this->byName($cat->id, $tagName);
      if ($tagResult) {
        $mapResult = $this->sql->select('tag_tagmap', 'value', 'studentId=? AND catId=? AND tagId=?', [$studentId, $cat->id, $this->id]);
        return $mapResult[0]['value'] ??  "";
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  public function create(string $categoryName, string $tagName, array $options = [])
  {
    $studentId = $options['studentId'] ?? null;
    $value = $options['value'] ?? null;
    $ownerId = $options['ownerId'] ?? 0;
    

    $cat = new \Entities\Tags\Category($this->sql);
    //create new category if doesn't exist
    $cat->create($categoryName);
    $catId = $cat->id;
    if ($this->doesNameExist($catId, $tagName, $ownerId)) {
      $tagId = $this->byName((int)$catId, $tagName)->id;
    } else {
      $tagId =  $this->sql->insert('tag_tags', 'catId, name', [$catId, $tagName]);
    }

    if ($studentId) $this->newMember($catId, $tagId, $studentId, $value);
    return $this;
  }

  public function members()
  {
    if (!$this->id || count($this->members) > 0) return $this->members;
    $d = $this->sql->select('t_tagmap', 'studentId', 'tagId=?', array($tagId));
    foreach($d as $member){
      $this->members[] = new \Entities\People\Student($this->sql, $member['studentId']);
    }
    return $this->members;
  }

  public function doesNameExist(int $catId, string $tagName)
  {
    return $this->sql->exists(
      'tag_tags',
      'name = ? AND catId = ?',
      array($tagName, $catId)
    );
  }

}

 ?>
