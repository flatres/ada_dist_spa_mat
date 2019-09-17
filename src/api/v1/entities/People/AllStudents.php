<?php

namespace Entities\People;

class AllStudents
{
    private $sql;
    public $list = [];
    public $studentIds = [];
    public $keyedStudentIds = [];
    public $count;

    public function __construct(\Dependency\Databases\Ada $ada = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
       $this->list();
    }

    public function list()
    {
      $students = $this->sql->select('stu_details', 'id', 'disabled=?', [0]);
      foreach($students as $student) {
        $this->list[] =  new \Entities\People\Student($this->sql, $student['id']);
      }
      $this->count = count($students);
      $this->keyedStudentIds();
      $this->studentIds();
      return $this->list;
    }

    private function studentIds()
    {
        $d = [];
        foreach($this->list as $student){
          $d[] = $student->id;
        }
        $this->studentIds = $d;
        return $d;
    }

    private function keyedStudentIds()
    {
        $d = [];
        foreach($this->list as $student){
          $d['id_' . $student->id] = $student->id;
        }
        $this->keyedStudentIds = $d;
        return $d;
    }
}
