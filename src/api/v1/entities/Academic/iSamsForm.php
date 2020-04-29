<?php
// <!-- class to process and return pupil academic subject lists -->

namespace Entities\Academic;

class iSamsForm
{

    private $sql;

    public $id; //isams set ID
    public $formId;
    public $setCode;
    public $subjectId, $subjectName, $subjectCode, $isAcademic = false;
    public $academicLevel = '';
    public $isForm = true;
    public $NCYear;
    public $students = [];
    public $englishLitSet = null; //english take two exams
    public $stop;
    public $teachers=[];
    // private $adaModules;
    // private $isams;

    public function __construct(\Dependency\Databases\isams $msSql, $id = null, $stop = true) //intSetId
    {
       $this->ada =  new \Dependency\Databases\Ada();
       $this->adaModules = new \Dependency\Databases\AdaModules();
       $this->isams = $msSql;

       $this->stop = $stop;
       if ($id) $this->byId($id);
       return $this;
    }

    public function byId($id)
    {
      $this->id = (int)$id;
      // look up subject
      $set = $this->isams->select(
        'TblTeachingManagerSubjectForms',
        'TblTeachingManagerSubjectFormsID as id, intSubject, txtTimetableCode as txtSetCode, txtTeacher',
        'TblTeachingManagerSubjectFormsID=?', [$id]);

      if (!isset($set[0])) return $this;
      $set = $set[0];

      $this->setCode = $string = str_replace(' ', '', $set['txtSetCode']);
      $this->subjectId = (int)$set['intSubject'];
      $subject = new \Entities\Academic\iSamsSubject($this->isams, $set['intSubject']);
      $this->subjectName = $subject->name;
      $this->subjectCode = $subject->code;

      $this->isAcademicSubject($subject->name, $subject->code, $this->setCode);

      $this->getNCYear();

      //english takes two exams
      if ($this->NCYear < 12 && $this->subjectCode === 'EN' && !$this->stop) {
        $this->englishLitSet = new \Entities\Academic\iSamsForm($this->isams, $this->id, true);
        $this->englishLitSet->subjectCode = 'ENLIT';
        $this->englishLitSet->id = $this->englishLitSet->id . '(LIT)';
        $this->englishLitSet->setCode = $this->setCode . " (LIT)";
        $this->setCode = $this->setCode . " (LAN)";
      }

      $this->formId = $this->isams->select('TblTeachingManagerSubjectForms', 'txtForm', 'TblTeachingManagerSubjectFormsID=?', [$this->id])[0]['txtForm'] ?? null;

      $isamsTeacher = (new \Entities\People\iSamsTeacher($this->isams))->byUserCode($set['txtTeacher']);
      $this->teachers[] = (new \Entities\People\User())->byMISId($isamsTeacher->id);
      return $this;
    }

    private function isAcademicSubject($subjectName, $subjectCode, $setCode)
    {
      switch ($subjectName) {
        case 'EPQ' :
          // $this->academicLevel = 'EPQ';
          return false;
        case 'Creative Writing':
        case 'Learning Support' :
        case 'Private Study' :
          return false;
      }
      $this->academicLevel = $this->getAcademicLevel($subjectCode, $setCode);
      return true;
    }

    private function getNCYear(){
      $letter = $this->setCode[0];
      switch ($letter) {
        case 'U':
          $NCYear = 13; break;
        case 'L':
          $NCYear = 12; break;
        case 'H':
          $NCYear = 11; break;
        case 'R':
          $NCYear = 10; break;
        case 'S':
          $NCYear = 9; break;
      }
      $this->NCYear = $NCYear;
    }

    private function getAcademicLevel($subjectCode, $setCode)
    {
      $f = ucfirst($setCode)[0];
      // echo $f;
      if ($f === 'H' || $f === 'S' || $f === 'R') return "GCSE";
      $setCode = strtoupper($setCode);
      if (strpos($setCode, '/G') !== false) return 'GCSE'; //GCSE Language
      if (strpos($setCode, '-JA') !== false) return 'GCSE'; //GCSE Japanese
      if (strpos($setCode, '/DE') !== false) return 'DELE'; //DELE
      if (strpos($setCode, '/DF') !== false) return 'DELF'; //DELF
      if (strpos($setCode, '/DF') !== false) return 'ZERT'; //DELF
      if (strpos($setCode, 'MA/MC') !== false) return 'AS';

      //assume what is left must be A2 or PreU
      $s = $this->adaModules->select('academic_subjects', 'id, isAlevel', 'subjectCode=?', [$subjectCode]);
      if (!isset($s[0])){
        return 'Unknown';
      } else {
        return $s[0]['isAlevel'] == 1 ? 'A' : 'PreU';
      }
    }

    public function getStudents() {
      $formId = $this->formId;
      $studentIds = $this->isams->select(
          'TblPupilManagementPupils',
          'txtSchoolID as id',
          'txtForm=? AND intSystemStatus=?',
           [$formId, 1]);

      foreach($studentIds as $s) {
        $this->students[] = (new \Entities\People\Student($this->ada))->byMISId($s['id']);
      }
      $this->students = sortObjects($this->students, 'lastName');
      return $this->students;
    }


}
