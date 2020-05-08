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

       return $this;
    }

    public function fetchStudentByExam($studentId, $examId)
    {
      if (!$this->typeId) return null;
      $results = $this->adaData->select(
              'exams_results',
              'result',
              'studentId=? AND examId=? AND levelId=?',
              [$studentId, $examId, $this->typeId]);

      if($results) return (object)$results[0];
      return null;
    }


}
