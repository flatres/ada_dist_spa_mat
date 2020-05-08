<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams\External;

class GCSE
{
    private $adaData;
    private $ada;
    private $typeId;

    public function __construct(\Dependency\Databases\AdaData $adaData = null)
    {
       $this->adaData=  $adaData ?? new \Dependency\Databases\AdaData();
       $this->typeId = $this->adaData->select('exams_levels', 'id', 'code=?', ['GCSE'])[0]['id'] ?? null;
       // $examCode = $
       return $this;
    }

    public function fetchStudentByExam($studentId, $examId, $examCode)
    {
      if (!$this->typeId) return null;
      $results = $this->adaData->select(
              'exams_results',
              'result',
              'studentId=? AND examId=? AND levelId=?',
              [$studentId, $examId, $this->typeId]);

      if($results) return (object)$results[0];

      if ($examCode === 'PH' || $examCode === 'BI' || $examCode = 'CH') {
        //may have taken double science
        $ada = new \Dependency\Databases\Ada();
        $examId = $ada->select('sch_subjects_exams', 'id', 'examCode=?', ['S2'])[0]['id'] ?? null;
        $results = $this->adaData->select(
                'exams_results',
                'result',
                'studentId=? AND examId=? AND levelId=?',
                [$studentId, $examId, $this->typeId]);

        if($results) return (object)$results[0];
      }

      return null;
    }


}
