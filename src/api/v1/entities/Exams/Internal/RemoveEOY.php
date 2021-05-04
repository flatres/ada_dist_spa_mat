<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams\Internal;

class RemoveEOY
{
    private $adaData;
    private $ada;
    private $typeId;

    public function __construct(\Dependency\Databases\AdaData $adaData = null)
    {
       $this->adaData=  $adaData ?? new \Dependency\Databases\AdaData();
       $this->typeId = $this->adaData->select('internal_exams_types', 'id', 'name=?', ['Remove - End of Year'])[0]['id'] ?? null;

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

      if($results) {
        $results[0]['percentage'] = round($results[0]['percentage'], 1);
        return (object)$results[0];
      }
      return null;
    }

    // public function makeGcseMockGPA($studentId)
    // {
    //   $results = $this->adaData->select('internal_exams_results', 'grade', 'studentId=? AND typeId =?', [$studentId, $this->typeId]);
    //   $count = count($results);
    //   $points = 0;
    //   $resultObj = new \Exams\Tools\GCSE\Result();
    //   foreach ($results as $r) {
    //     $p = $resultObj->processGrade($r['grade']);
    //     $points += $p;
    //   }
    //
    //   if ($count > 0) {
    //     $gpa = round($points / $count, 2);
    //     $metrics = (new \Entities\Metrics\Student($studentId))->setGcseMockGPA($gpa);
    //
    //   }
    //
    // }

}
