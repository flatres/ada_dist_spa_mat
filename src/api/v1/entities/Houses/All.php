<?php

namespace Entities\Houses;

use PhpMyAdmin\Properties\Plugins\ImportPluginProperties;

class All
{
    private $sql;
    public $list = [];

    public function __construct(\Dependency\Databases\Ada $ada = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
       $this->list();
    }
    
    public function list()
    {
      $cat = new \Entities\Tags\Category();
      $houses = $cat->byName('House')->tags;
      foreach($houses as $house){
        $h = new \Entities\Houses\House($this->sql);
        $this->list[] = $h->byTagId($house->id);
      }
      return $this->list;
    }
    //takes a list of students and populates a list of houses with the students put into their corresponding house.
    public function sortStudents(array $students){
      //create a key array of houses
      $houses = [];
      foreach($this->list as $h){
        $h->students = [];
        $houses[$h->nameSafe] = $h;
      }
      unset($h);
      foreach($students as $s){
        $houses[$s->boardingHouseSafe]->students[] = $s;
      }
      foreach($houses as $h){
        $h->count = count($h->students);
      }
      krsort($houses);
      return array_values($houses);
    }
}
