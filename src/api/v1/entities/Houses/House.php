<?php

namespace Entities\Houses;

class House
{
    public $id, $students, $name, $noSpaceName, $nameSafe, $code;
    public $studentIds = [];
    private $sql;

    public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
       if ($id) $this->byId($id);
       return $this;
    }

    public function byId($id) {
      $d = $this->sql->select('sch_houses', 'id, name, code, email', 'id=?', [$id]);
      if($d) {
        $d = $d[0];
        $this->code = $d['code'];
        $this->id = $id;
        $this->name = $d['name'];
        $this->hmEmail = $d['email'];
      }
      return $this;
    }

    public function byCode($code) {
      $code = strtoupper($code);
      $d = $d = $this->sql->select('sch_houses', 'id, name, code, email', 'code=?', [$code]);
      if ($d) $this->byId($d[0]['id']);
      return $this;
    }

    public function byTagId($id){
      $tag = new \Entities\Tags\Tag($this->sql, $id);
      $this->name = $tag->name;
      $this->description = $this->name;
      $this->noSpaceName = str_replace(" ", '_', $this->name);
      $this->id = $this->noSpaceName;
      $this->nameSafe = $this->noSpaceName;
      $students = $tag->memberIds();
      foreach($students as $student){
        $this->students[] = new \Entities\People\Student($this->sql, $student);
      }
      $this->studentIds();
      return $this;
     }

    public function studentIds()
    {
        $d = [];
        foreach($this->students as $student){
          $d[] = $student->id;
        }
        $this->studentIds = $d;
        return $d;
    }

}
