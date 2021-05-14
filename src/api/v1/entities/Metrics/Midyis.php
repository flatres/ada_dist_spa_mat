<?php

namespace Entities\Metrics;

class Midyis
{
  public $studentId;
  public $baseline;
  public $band;
  public $prediction;
  public $exam;
  private $adaData;

  public function __construct($studentId, $examId = null, $adaData = null)
  {
     if (!$studentId) return;
     $this->adaData= $adaData ? $adaData : new \Dependency\Databases\AdaData();
     $this->studentId = $studentId;
     if ($examId) {
       $this->examId = $examId;
       $this->byExamId($examId);
       $this->exam = (new \Entities\Academic\SubjectExam())->byId($examId);
     }
     return $this;
  }

  public function byExamId($examId){
    $this->examId = $examId;
    $r = $this->adaData->select(
      'predictions_midyis',
      'baseline, band, prediction',
      'studentId = ? AND examId = ?', [$this->studentId, $this->examId]);

    if($r) {
      $r = $r[0];
      $this->baseline = round($r['baseline'],1);
      $this->band = $r['band'];
      $this->prediction = $r['prediction'];
    }

    return $this;
  }

  public function byExamCode($examCode) {
    $sql = new \Dependency\Databases\Ada();
    $examId = $sql->select('sch_subjects_exams', 'id', 'examCode=?', [$examCode])[0]['id'] ?? null;
    if ($examId) $this->byExamId($examId);
    return $this;
  }


}
