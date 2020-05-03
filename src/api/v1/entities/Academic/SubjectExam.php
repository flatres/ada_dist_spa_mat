<?php

namespace Entities\Academic;

class SubjectExam
{
  public $id; //isams set ID
  public $examCode, $subjectId, $examName, $examLevelId, $examLevel;

  public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
  {
     $this->sql= $ada ?? new \Dependency\Databases\Ada();
     if ($id) $this->byId($id);
     return $this;
  }

  public function byId($id) {
    $this->id = (int)$id;
    $exam = $this->sql->select('sch_subjects_exams', 'subjectId, examName, examCode, examLevelId', 'id=?', [$id]);
    if (isset($exam[0])){
      $exam = $exam[0];
      $this->subjectId = $exam['subjectId'];
      $this->examName = $exam['examName'];
      $this->examCode = $exam['examCode'];
      $this->examLevelId = $exam['examLevelId'];

      //going to come back to this.
      // $this->examLevel = $this->sql->select('sch_exam_levels', 'name', 'id=?', $this->examLevelId)[0]['name'] ?? null;

    }
    return $this;
  }

  public function byCode($code) {
    $exam = $this->sql->select('sch_subjects_exams', 'id', 'examCode=?', [$code]);
    if ($exam) $this->byId($exam[0]['id']);
    return $this;
  }

}
