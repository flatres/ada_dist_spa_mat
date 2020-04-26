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

        $tag->create('MLO', $subjectCode, [
          'studentId' => $studentId,
          'value'     => $mlo,
          'cumulative' => true
        ]);
      }

    }

    //fetches the most recent MLO from this subject and teacher
    public function getSingleMLO($studentId, $subjectCode, $teacherId) {
      $tag = new \Entities\Tags\Tag();
      // echo $studentId . $subjectCode, $teacherId . PHP_EOL;
      return $tag->value('MLO', $subjectCode, $studentId, 0, $teacherId);
    }


}
