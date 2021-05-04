<?php

namespace Entities\Academic;

class Year
{
  public $id;
  public $students = [];
  private $sql, $adaData;

  public function __construct($id = null)
  {
     // $this->sql= $ada ?? new \Dependency\Databases\Ada();
     $this->sql= $ada ?? new \Dependency\Databases\Ada();
     $this->adaData= new \Dependency\Databases\AdaData();
     if ($id) $this->byId($id);
     return $this;
  }

  public function byId($id) {

    $this->id = (int)$id;

    $students = $this->sql->select('stu_details', 'id', 'NCYear = ? AND disabled = ?', [$id, 0]);
    foreach($students as $s) $this->students[] = new \Entities\People\Student($this->sql, $s['id']);
    return $this;
  }

  public function getAccessArrangements() {
    $isams = new \Dependency\Databases\isams();
    $hasAccess = [];
    foreach($this->students as &$s) {
      $student = (new \Entities\People\iSamsStudent($isams, $s->misId))->getAccessArrangements();
      $s->accessArrangements = $student->accessArrangements;
      if ($s->accessArrangements) $hasAccess[] = $s;
    }

    return $hasAccess;
  }

}
