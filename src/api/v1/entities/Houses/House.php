<?php

namespace Entities\Houses;

class House
{
    public $students, $name, $noSpaceName, $nameSafe;
    public $studentIds = [];
    private $sql;
    
    public function __construct(\Dependency\Databases\Ada $ada = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
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
