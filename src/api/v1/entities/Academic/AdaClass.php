<?php

namespace Entities\Academic;

class AdaClass
{
  public $id; //isams set ID
  public $code;
  public $year;
  public $isForm;
  public $misFormId;
  public $academicLevel;
  public $teachers=[];
  public $students=[];
  public $subjectId;
  public $exams = [];

  public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
  {
     $this->sql= $ada ?? new \Dependency\Databases\Ada();
     if ($id) $this->byId($id);
     return $this;
  }

  public function byId($id) {
    $this->id = $id;
    $class = $this->sql->select('sch_classes', 'misId, subjectId, code, year, isForm, misFormId, academicLevel', 'id=?', [$id]);
    if (isset($class[0])){
      $class = $class[0];
      $this->code = $class['code'];
      $this->year = $class['year'];
      $this->isForm = $class['isForm'] == 1 ? true : false;
      $this->misFormId = $class['misFormId'];
      $this->academicLevel = $class['academicLevel'];
      $this->getExams();
      $this->getStudents();
      $this->getTeachers();
    }
    return $this;
  }

  public function getExams () {
    $exams = $this->sql->select('sch_class_exams', 'examId', 'classId=?', [$this->id]);
    foreach ($exams as $e) {
      $this->exams[] = new \Entities\Academic\SubjectExam($this->sql, $e['examId']);
    }
    return $this->exams;
  }

  public function getTeachers () {
    $teachers = $this->sql->select('sch_class_teachers', 'userId',  'classId = ?', [$this->id]);
    foreach ($teachers as $teacher) {
      $this->teachers[] = new \Entities\People\User($this->sql, $teacher['userId']);
    }
    return $this->teachers;
  }

  public function getStudents () {
    $students = $this->sql->select('sch_class_students', 'studentId',  'classId = ?', [$this->id]);
    foreach ($students as $student) {
      $s = new \Entities\People\Student($this->sql, $student['studentId']);
      $s->classId = $this->id;
      $s->classCode = $this->code;

      $this->students[] = $s;
    }
    return $this->students;
  }

  public function getStudentMLO($studentId){}




}
