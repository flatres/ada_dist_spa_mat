<?php

namespace Entities\Academic;

class Year
{
  public $id;
  public $students = [];
  private $sql, $adaData;
  public $subjects = [], $exams = [];

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
    $hasAccess = [];
    foreach($this->students as &$s) {
      $s->getAccessArrangements();
      if ($s->accessArrangements) $hasAccess[] = $s;
    }

    return $hasAccess;
  }

  public function getSubjects(bool $getStudents = false) {
    //first search through all classes in this year and extract subjects
    $classes = $this->sql->select('sch_classes', 'subjectId', 'year=?', [$this->id]);
    $subjects = [];
    foreach($classes as $c) {
      $key = 's_' . $c['subjectId'];
      if (!isset($subjects[$key])) {
        $subject = new \Entities\Academic\Subject($this->sql, $c['subjectId']);
        $subject->getExamsByYear($this->id);
        if ($getStudents) $subject->getStudentsByYear($this->id);
        $subjects[$key] = $subject;
      }
    }
    $this->subjects = array_values($subjects);
    return $this;
  }

  public function getExams(bool $getStudents = false) {
    $this->exams = [];
    $this->getSubjects($getStudents);

    foreach($this->subjects as $s) {
      foreach ($s->exams as &$e) {
        $e->students = $s->students;
        $this->exams[] = $e;
      }
    }
    return $this->exams;
  }

  public function getWyaps(bool $withResultsCount = false) {
    if (!$this->id) return $this;
    $this->getSubjects();
    foreach($this->subjects as &$s) {
      foreach($s->exams as &$e) {
        $wyaps = $s->getWYAPsByExam($this->id, $e->id);
        if ($withResultsCount) {
          foreach($wyaps as &$w) $w->getResultsCount();
        }
        $e->wyaps = $wyaps;
      }
    }
    return $this;
  }

}
