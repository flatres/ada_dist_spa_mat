<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams;

class MLO
{

    private $sql;

    public function __construct(\Dependency\Databases\Ada $ada = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
       return $this;
    }

    public function newMLO($studentId, $mlo, $subjectCode, $teacherId) {

      if ($mlo) {
        $tag = new \Entities\Tags\Tag();

        // only write new tag if new value if different to old, or the first one.
        $oldMlo = $tag->value('MLO', $subjectCode, $studentId, $teacherId);
          if (!$oldMlo || $oldMlo !== $mlo) {
            $tag->create('MLO', $subjectCode, [
              'studentId' => $studentId,
              'value'     => $mlo,
              'cumulative' => true
            ]);
            return true;
          }
      }
      return false;
    }

    //fetches the most recent MLO from this subject and teacher
    public function getSingleMLO($studentId, $subjectCode, $teacherId) {
      $tag = new \Entities\Tags\Tag();
      // echo $studentId . $subjectCode, $teacherId . PHP_EOL;
      return $tag->value('MLO', $subjectCode, $studentId, $teacherId);
    }

    public function getStudentMLO($studentId, $subjectId = null) {
      // $isamsStudent = new
    }

    public function makeProfile(\Entities\People\Student &$student, $examId = null) {
      //get a list of exams and collect MLO Grades
      $exams = [];
      $examsInfo = [];
      $returnExam = [];
      foreach($student->examData['mlo'] as $m){
        $examId = $m['examId'];
        $key = "id" . $examId;
        if (!isset($exams[$key])) {
          $exams[$key] = [];
          $examsInfo[] = new \Entities\Academic\SubjectExam($this->sql, $examId);
        }
        if ($m['mlo']) {
          $exams[$key][] = [
            'points'  => (new \Entities\Exams\Grade($m['mlo']))->points,
            'grade'   => $m['mlo']
          ];
        }
      }
      //extract the highest and lowest mlo for each exam
      foreach($examsInfo as &$e) {
        $key = "id" . $e->id;
        $results = sortArrays($exams[$key], 'points');
        $e->mloMax = $results[0]['grade'] ?? null ;
        $e->mloMin = $results[count($results) - 1]['grade'] ?? null ;
        if ($e->id == $examId) $returnExam = $e;
      }
      $student->exams = $examsInfo;
      return $returnExam;
    }


}
