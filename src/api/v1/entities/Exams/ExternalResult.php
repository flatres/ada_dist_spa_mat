<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams;

class ExternalResult
{
    public function __construct(\Dependency\Databases\Ada $ada = null, \Dependency\Databases\AdaData $adaData = null)
    {
       $this->ada = $ada ?? new \Dependency\Databases\Ada();
       $this->adaData = $adaData ?? new \Dependency\Databases\AdaData();
       return $this;
    }

    public function save($result, $isGCSE, $sessionId) {
      $result->subjectCode = str_replace('.', '', $result->subjectCode);

      if ($result->subjectCode === 'S1') return;
      // if ($result->subjectCode === 'S1' || $result->subjectCode === 'S2') return;
      $misId = $result->txtSchoolID;
      $student = (new \Entities\People\Student($this->ada))->byMISId($misId);

      if (!$student->id && !$result->txtSchoolID) return; //eg a member of staff taking an exam

      $exam = (new \Entities\Academic\SubjectExam($this->ada))->byCode($result->subjectCode);
      $board = (new \Entities\Exams\Board($this->ada))->byCode($result->boardName);
      $level = (new \Entities\Exams\Level($this->ada))->byCode($result->level);

      //get the boarding house from the result as student may not be current and so not in AD
      $bh = new \Entities\Houses\House($this->ada);
      $bh->byCode($result->txtHouseCode);

      //only enter a subject Code if the subject couldn't be found (for debgging)
      $subjectCode = $exam->subjectId ? null : $result->subjectCode;

      if ($result->early) {
        $early = $result->early == true ? 1 : 0;
      } else {
        $early = 0;
      }
      if ($result->isNewSixthForm) {
        $isNew = $result->isNewSixthForm == true ? 1 : 0;
      } else {
        $isNew = 0;
      }
      // if (!$exam->subjectId) {
      //   echo $result->subjectCode . $result->title . PHP_EOL;
      // }

      $this->adaData->insert(
        'exams_results',
        'subjectId,
        examCode,
        levelId,
        examId,
        studentId,
        misId,
        sessionId,
        houseId,
        boardId,
        result,
        points,
        surplusScore,
        ucasPoints,
        gender,
        NCYear,
        title,
        moduleCode,
        takenEarly,
        isIGCSE,
        isNewSixthForm',
        [
          $exam->subjectId,
          $subjectCode,
          $level->id,
          $exam->id,
          $student->id,
          $result->txtSchoolID,
          $sessionId,
          $bh->id,
          $board->id,
          $result->grade,
          $result->points,
          $result->surplus,
          $result->ucasPoints ?? null,
          $result->txtGender,
          $result->NCYear,
          $result->title,
          $result->moduleCode,
          $early,
          $isGCSE ? 1 : 0,
          $isNew
        ]
      );
    }
}
