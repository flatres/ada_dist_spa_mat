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
    private $adaModules;
    private $isams;

    public function __construct($id = null) //intSetId
    {
       // $this->sql= $ada ?? new \Dependency\Databases\Ada();
       $this->adaModules = new \Dependency\Databases\AdaModules();
       $this->isams = new \Dependency\Databases\ISams();

       if ($id) $this->byId($id);
       return $this;
    }

    public function byId($id)
    {
      $this->id = (int)$id;
      // look up subject
      $set = $this->isams->select(
        'TblTeachingManagerSets',
        'TblTeachingManagerSetsID as id, intSubject, txtSetCode',
        'TblTeachingManagerSetsID=?', [$id]);

      if (!isset($set[0])) return $this;
      $set = $set[0];

      $this->setCode = $string = str_replace(' ', '', $set['txtSetCode']);
      $this->subjectId = (int)$set['intSubject'];
      $subject = new \Entities\Academic\iSamsSubject($set['intSubject']);
      $this->subjectName = $subject->name;
      $this->subjectCode = $subject->code;

      //set anything with MA/X{number} as Further Maths eg L6-Ma/x5
      //sure there is a more elegent way to do this. Probably RegEx
      $xSet = explode('MA/X',  strtoupper($this->setCode));
      if (isset($xSet[1])) {
        if (strlen($xSet[1]) > 0) $this->subjectName = "Further Mathematics";
      }

      $ySet = explode('MA/Y',  strtoupper($this->setCode));
      if (isset($ySet[1])) {
        if (strlen($ySet[1]) > 0) $this->subjectName = "Further Mathematics";
      }

      $this->isAcademicSubject($subject->name, $subject->code, $this->setCode);

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


}
