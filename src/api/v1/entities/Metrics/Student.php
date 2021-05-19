<?php

namespace Entities\Metrics;

class Student
{
  public $id;
  public function __construct($studentId)
  {
     if (!$studentId) return;
     $this->ada = new \Dependency\Databases\Ada();
     $this->adaData= new \Dependency\Databases\AdaData();
     $this->id = $studentId;
     $s = $this->adaData->select('student_metrics', 'id', 'studentId=?', [$studentId]);
     if (!$s) {
       $student = new \Entities\People\Student($this->ada, $studentId);
       $this->adaData->insert('student_metrics', 'studentId, houseId, gender', [$studentId, $student->boardingHouseId, $student->gender]);
     }

     return $this;
  }

  // returns the alis predictions for subjects this pupil does
  private function getSubjectsAlis() {

  }

  public function setAlisFromTest($value){
    $this->update('alisBaselineTest', $value);
    return true;
  }

  public function setAlisFromGCSE($value){
    $this->update('alisBaselineGcse', $value);
    return true;
  }

  public function setMidyisBand($value){
    $this->update('midyisBand', $value);
    return true;
  }

  public function setMidyisScore($value){
    $this->update('midyisScore', $value);
    return true;
  }

  public function setGcseMockGPA($value){
    $this->update('gcseMockGpa', $value);
    return true;
  }

  public function setGcseGPA($value){
    $this->update('gcseGpa', $value);
    return true;
  }

  public function gcseMockGpa() {
    return $this->fetch('gcseMockGpa');
  }

  public function gcseGpa() {
    return $this->fetch('gcseGpa');
  }

  public function midyisBand() {
    return $this->fetch('midyisBand');
  }

  public function midyisScore() {
    return $this->fetch('midyisScore');
  }

  public function alisBaselineTest() {
    return $this->fetch('alisBaselineTest');
  }

  public function alisBaselineGcse() {
    return $this->fetch('alisBaselineGcse');
  }

  private function update($field, $value) {
    if (!$this->id) return;
    $this->adaData->update('student_metrics', $field . '=?', 'studentId = ?', [$value, $this->id]);
  }

  private function fetch($field) {
    if (!$this->id) return;
    $r = $this->adaData->select('student_metrics', $field , 'studentId = ?', [$this->id]);
    return $r[0][$field] ?? null;
  }

}
