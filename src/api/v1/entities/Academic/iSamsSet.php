<?php
// <!-- class to process and return pupil academic subject lists -->

namespace Entities\Academic;

class iSamsSet
{

    private $sql;

    public $id; //isams set ID
    public $setCode;
    public $subjectId, $subjectName, $subjectCode, $isAcademic = false;
    public $academicLevel = '';
    public $isForm = false;
    public $NCYear;
    public $students = [];
    public $isFurtherMaths = false;
    public $isFurtherMathsSet = false; // eg. X5
    public $furtherMathsOtherSet = null;
    private $stop = false;
    public $teachers=[];
    public $examCodes=[];


    //a group of students doing further maths is registered under two sets eg U6-Ma/X and U6-Ma/X5
    // the X5 indicates the teaching block that it is in.
    // for this class, the Ma/X will be considered the normal set and Ma/X5 the further maths set




    public function __construct(\Dependency\Databases\isams $msSql, $id = null, $stop = true) //intSetId
    {
       $this->ada = new \Dependency\Databases\Ada();
       $this->adaModules = new \Dependency\Databases\AdaModules();
       $this->isams = $msSql;
       $this->stop = $stop; //setting this stops an infinite looks of finding further maths sets

       if ($id) $this->byId($id);
       return $this;
    }

    public function byId($id)
    {
      $this->id = (int)$id;
      // look up subject
      $set = $this->isams->select(
        'TblTeachingManagerSets',
        'TblTeachingManagerSetsID as id, intSubject, txtSetCode, txtTeacher',
        'TblTeachingManagerSetsID=?', [$id]);

      if (!isset($set[0])) return $this;
      $set = $set[0];

      $this->setCode = $string = str_replace(' ', '', $set['txtSetCode']);
      $this->subjectId = (int)$set['intSubject'];
      $subject = new \Entities\Academic\iSamsSubject($this->isams, $set['intSubject']);
      $this->subjectName = $subject->name;
      $this->subjectCode = $subject->code;
      $this->examCodes[] = $subject->code;

      if (strpos($this->setCode, 'Ma/x') !== false || strpos($this->setCode, 'Ma/y') !== false || strpos($this->setCode, 'Ma/z') !== false) {
        //is a further maths set
        $this->processFurtherMaths();
      }

      $this->isAcademicSubject($subject->name, $subject->code, $this->setCode);

      $this->getNCYear();

      $this->getTeachers($set['txtTeacher']);


      return $this;
    }

    private function getTeachers($txtTeacher) {
      if (!$txtTeacher) return;

      //primary teacher
      $isamsTeacher = (new \Entities\People\iSamsTeacher($this->isams))->byUserCode($txtTeacher);
      $this->teachers[] = (new \Entities\People\User())->byMISId($isamsTeacher->id);

      //look for secondary teacher
      $d = $this->isams->select(
        'TblTeachingManagerSetAssociatedTeachers',
        'txtTeacher',
        'intSetID=?', [$this->id]);

      if (isset($d[0])) {
        $isamsTeacher = (new \Entities\People\iSamsTeacher($this->isams))->byUserCode($d[0]['txtTeacher']);
        $this->teachers[] = (new \Entities\People\User())->byMISId($isamsTeacher->id);
      }

    }

    private function processFurtherMaths(){

      $this->examCodes[] = 'FM';

      //set anything with MA/X{number} as Further Maths eg L6-Ma/x5
      //sure there is a more elegent way to do this. Probably RegEx
      $setCode = $this->setCode;
      $xSet = explode('MA/X',  strtoupper($this->setCode));
      if (isset($xSet[1])) {
        if (strlen($xSet[1]) > 0) {
          $this->isFurtherMathsSet = true;
          $setCode = $xSet[0] . 'Ma/x';
          $this->setCode = $setCode . " (FM)";
          $this->subjectName = "Further Mathematics";
          $this->subjectCode = 'FM';
        }
      }

      $ySet = explode('MA/Y',  strtoupper($this->setCode));
      if (isset($ySet[1])) {
        if (strlen($ySet[1]) > 0) {
          $this->isFurtherMathsSet = true;
          $setCode = $ySet[0] . 'Ma/y';
          $this->setCode = $setCode . " (FM)";
          $this->subjectName = "Further Mathematics";
          $this->subjectCode = 'FM';
        }
      }

      $zSet = explode('MA/Z',  strtoupper($this->setCode));
      if (isset($zSet[1])) {
        if (strlen($zSet[1]) > 0) {
          $this->isFurtherMathsSet = true;
          $setCode = $zSet[0] . 'Ma/z';
          $this->setCode = $setCode . " (FM)";
          $this->subjectName = "Further Mathematics";
          $this->subjectCode = 'FM';
        }
      }
      //find the other maths set and embed in this one
      $set = $this->isams->query(
        'SELECT * from TblTeachingManagerSets WHERE txtSetCode LIKE ? AND TblTeachingManagerSetsID <> ?',
        ["$setCode%", $this->id]);

      if ($this->stop) return true; //set must be created by the first further maths set

      if (isset($set[0])){
        $this->furtherMathsOtherSet = new \Entities\Academic\iSamsSet($this->isams, $set[0]['TblTeachingManagerSetsID'], true);
      } else {
        $this->furtherMathsOtherSet = $setCode;
      }
      $this->isFurtherMaths = true;
      return true;
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
        default:
          $NCYear = 0;
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
      $studentIds = $this->isams->select( 'TblTeachingManagerSetLists', 'txtSchoolID as id', 'intSetID=?', [$this->id]);
      foreach($studentIds as $s) {

        $this->students[] = (new \Entities\People\Student($this->ada))->byMISId($s['id']);
      }
      $this->students = sortObjects($this->students, 'lastName', 'ASC');
      return $this->students;
    }


}
