<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams\Internal;

class GCSEMock
{
    private $adaData;
    private $ada;
    private $typeId;

    public function __construct(\Dependency\Databases\AdaData $adaData = null)
    {
       $this->adaData=  $adaData ?? new \Dependency\Databases\AdaData();
       $this->typeId = $this->adaData->select('internal_exams_types', 'id', 'name=?', ['GCSE Mocks'])[0]['id'] ?? null;

       return $this;
    }

    public function fetchStudentByExam($studentId, $examId)
    {
      if (!$this->typeId) return null;
      $results = $this->adaData->select(
              'internal_exams_results',
              'mark, grade, percentage, rank',
              'studentId=? AND examId=? AND typeId=?',
              [$studentId, $examId, $this->typeId]);

      if($results) return (object)$results[0];
      return null;
    }


}
