<?php

namespace Entities\Academic;

class Subject
{
  public $id;
  public $code;
  public $year;
  public $isForm;
  public $misFormId;
  public $academicLevel;
  public $teachers=[];
  public $subjectId;
  public $classes=[];
  public $exams=[];

  public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
  {
     // $this->sql= $ada ?? new \Dependency\Databases\Ada();
     $this->sql= $ada ?? new \Dependency\Databases\Ada();
     if ($id) $this->byId($id);
     return $this;
  }

  public function byId($id) {

    $this->id = $id;
    $s = $this->sql->select('sch_subjects', 'misId, name, code, isForm', 'id=?', [$id]);
    if ($s[0]) {
      $s = $s[0];
      $this->misId = $s['misId'];
      $this->name = $s['name'];
      $this->code = $s['code'];
      $this->isForm = $s['isForm'] == 0 ? false : true;
    }
    return $this;
  }

  public function getClassesByYear($year) {
    $classes = $this->sql->select('sch_classes', 'id', 'subjectId = ? AND year=?', [$this->id, $year]);
    foreach($classes as $c){
      $this->classes[] = new \Entities\Academic\AdaClass($this->sql, $c['id']);
    }
    return $this->classes;
  }

  public function getExamsByYear($year) {
    $classes = count($this->classes) == 0 ? $this->getClassesByYear($year) : $this->classes;
    $exams = [];

    foreach ($classes as $c) {
      foreach ($c->exams as $e) {
        $key = 'id' . $e->id;
        if (!isset($exams[$key])) $exams[$key] = $e;
      }
    }
    $this->exams = array_values($exams);
    return $this->exams;
  }

  public function getStudentsByYear($year) {
    $classes = count($this->classes) == 0 ? $this->getClassesByYear($year) : $this->classes;
    $students = [];
    foreach($classes as $c) {
      $classStudents = $c->getStudents();
      foreach($classStudents as $s) {
        $key = 'id' . $s->id;
        if (!isset($students[$key])) $students[$key] = $s;
      }
    }
    $students = array_values($students);
    $this->students = sortObjects($students, 'lastName', 'ASC');
    return $this->students;
  }

  public function getStudentsMLOByExam($year, $examId) {
    $students = $this->getStudentsByExam($year, $examId);
    $maxMLOCount = 0;
    $exam = new \Entities\Academic\SubjectExam($this->sql, $examId);
    foreach($students as $s) {
      $s->examData['mlo'] = [];
      $mloCount = 0;
      $s->getClassesByExam($examId);
      foreach($s->classes as $c){
        foreach($c->teachers as $t){
          $mloCount++;
          $mlo = (new \Entities\Exams\MLO($this->sql))->getSingleMLO($s->id, $exam->examCode, $t->id);
          $s->examData['mlo'][] = [
            'teacher' => $t,
            'class'   => $c,
            'mlo'     => $mlo
          ];
          $s->{'mlo' . $mloCount} = $mlo;
        }
      }
      if ($mloCount > $maxMLOCount) $maxMLOCount = $mloCount;
    }
    return [
      'students'  => $students,
      'maxMLOCount' => $maxMLOCount
      ];
  }

  public function getStudentsByExam($year, $examId) {
    $classes = count($this->classes) == 0 ? $this->getClassesByYear($year) : $this->classes;
    $students = [];
    foreach($classes as $c) {
      foreach($c->exams as $e) {
        if ($e->id == $examId) {
          foreach($c->students as $s) {
            $key = 'id' . $s->id;
            if (!isset($students[$key])) $students[$key] = $s;
          }
        }
      }
    }
    $students = array_values($students);
    $students = sortObjects($students, 'lastName', 'ASC');
    return $students;
  }


}
