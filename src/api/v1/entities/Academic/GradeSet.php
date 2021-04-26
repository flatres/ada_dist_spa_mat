<?php

namespace Entities\Academic;

class GradeSet
{
  public $id;
  public $name;
  public $isActive;
  public $grades;
  private $adaData;

  public function __construct($id = null)
  {
     $this->adaData= new \Dependency\Databases\AdaData();
     if ($id) $this->byId($id);
     return $this;
  }

  public function byId($id) {
    $this->id = (int)$id;
    $d = $this->adaData->select('grade_sets', 'name, isActive', 'id=?', [$id]);
    if (isset($d[0])) {
      $this->name = $d[0]['name'];
      $this->isActive = $d[0]['isActive'];
    }
    $this->grades = $this->adaData->select(
      'grade_set_grades',
      'id, grade, points, ucasPoints, isFail',
      'gradeSetId =? ORDER BY points DESC',
      [$id]
    );
    return $this;
  }

  public function getGradeById($gradeSetGradeId) {
    $g = $this->adaData->select('grade_set_grades', 'grade', 'id=?', [$gradeSetGradeId]);
    if (isset($g[0])) return $g[0]['grade'];
  }

}
