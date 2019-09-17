<?php

namespace Entities\Tags;

class AllCats
{
    private $sql;
    public $list = [];

    public function __construct(\Dependency\Databases\Ada $ada = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
    }

    public function list($userId = 0)
    {
      $cats = $this->sql->select('tag_categories', 'id, name, user_id', 'user_id=? ORDER BY name ASC', [$userId]);
      $list = [];
      foreach($cats as $cat){
        $c = new \Entities\Tags\Category($this->sql);
        $list[$cat['name'] . '_'] = $c->byId($cat['id']);
      }
      ksort($list);
      $this->list = array_values($list);
      return $this->list;
    }
}
