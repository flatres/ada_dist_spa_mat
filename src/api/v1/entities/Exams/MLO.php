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


}
