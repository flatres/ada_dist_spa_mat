<?php

namespace Entities\Metrics;

class Alis
{
  public $studentId;
  public $testBaseline;
  public $testPrediction;
  public $gcseBaseline;
  public $gcsePrediction;

  public $band;
  public $exam;

  public function __construct($studentId, $examId = null)
  {
     if (!$studentId) return;
     $this->adaData= new \Dependency\Databases\AdaData();
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
    $data = $this->adaData->select(
      'predictions_alis',
      'baseline, isFromTest, prediction',
      'studentId = ? AND examId = ?', [$this->studentId, $this->examId]);

    if($data) {
      foreach ($data as $d) {
        $p = (object)$d;
        if ($p->isFromTest == 0) {
          $this->gcseBaseline = $p->baseline;
          $this->gcsePrediction = $p->prediction;
        } else {
          $this->testBaseline = $p->baseline;
          $this->testPrediction = $p->prediction;
        }
      }
    }

    return $this;
  }


}
