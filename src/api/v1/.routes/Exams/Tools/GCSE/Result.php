<?php

/**
 * Description

 * Usage:

 */
// A Result Object
// grade:"A"
// id:"39550"
// intEnrolmentNCYear:"12"
// subjectCode:"GG"
// txtBoardingHouse:"Littlefield"
// txtForename:"Lucy"
// txtFullName:"Lucy Goodman"
// txtGender:"F"
// txtHouseCode:"LI"
// txtInitialedName:"Goodman, L C"
// txtInitials:"L C"
// txtLeavingBoardingHouse:"Littlefield"
// txtModuleCode:"2031"
// txtOptionTitle:"GCE GEOGRAPHY ADV"
// txtQualification:"GCE"
// txtSchoolID:"111234705547"
// txtSurname:"Goodman"

namespace Exams\Tools\GCSE;

class Result
{
    public $id, $subjectCode, $moduleCode, $grade;
    public $points = 0;
    public $passes = 1;
    public $fails = 0;
    public $level = 'GCSE';
    public $NCYear;
    public $txtGender;
    public $txtForename;
    public $txtSurname;
    public $txtHouseCode;
    public $txtLevel;
    public $txtSchoolID;
    public $txtCandidateNumber;
    public $txtCandidateCode;
    public $surplus;
    public $mark;
    public $total;
    public $title;
    public $isIGCSE;
    public $boardName; //not currently set by anything
    public $isNewSixthForm; //not currently set by anything


    public function __construct(array $result = null)
    {
        if(!$result) return;

        $this->id = $result['id'];

        $this->subjectCode = $result['subjectCode'];
        $this->moduleCode = $result['txtModuleCode'];
        $this->grade = $result['grade'];
        $this->NCYear = $result['NCYear'] ?? 0;
        $this->txtGender = $result['txtGender'];
        $this->txtInitialedName = $result['txtInitialedName'];
        $this->txtForename = $result['txtForename'];
        $this->txtSurname = $result['txtSurname'];
        $this->txtHouseCode = $result['txtHouseCode'];
        $this->txtLevel = $result['txtLevel'];
        $this->txtSchoolID = $result['txtSchoolID'];
        $this->txtSubjectName = $result['subjectName'];
        $this->isNumeric = false;
        $this->isLetter = false;
        $this->mark =  ltrim($result['mark'], '0');
        $this->total = ltrim($result['total'], '0');
        $this->early = $result['early'];
        $this->txtCandidateCode = $result['txtCandidateCode'];
        $this->txtCandidateNumber = $result['txtCandidateNumber'];
        $this->title = $result['txtOptionTitle'];
        $this->isIGCSE = $result['isIGCSE'];

        $points = 0;
        $pass = 1;
        $fail = 0;

        $this->processGrade($this->grade);

        if ($this->isLetter) $this->subjectCode = $this->subjectCode . '.';
        if ($this->isLetter) $this->txtSubjectName = $this->txtSubjectName . '.';

    }

    private function isNumeric($grade)
    {
      if ($grade === 'U') {
          //need to check against module number
          $s = "(txtQualification = 'FSMQ' OR txtQualification = 'GCSE')";
          $sql = new \Dependency\Databases\ISams();
          $results = $sql->select( 'TblExamManagerResultsStore',
                                                    'TblExamManagerResultsStoreID as id, txtModuleCode, txtFirstGrade as grade',
                                                    "txtOptionTitle = ? AND txtModuleCode = ? AND $s AND txtCertificationType='C'",
                                                    [$this->title, $this->moduleCode]);

        foreach($results as $result) {
          $grade = $result['grade'];
          if (is_numeric($grade)) return true;
          if ($grade == 'A' || $grade == 'B' || $grade == 'C') return false;
        }

      } else {
          return is_numeric($grade);
      }

    }

    public function processGrade(string $grade)
    {
      if(!$grade) return;

      if ($this->isNumeric($grade)) $this->isNumeric = true;
      if (!$this->isNumeric($grade)) $this->isLetter = true;

      $points = 0;
      $pass = 0;
      $fail = 0;
      switch(strtoupper($grade))
      { //C is equivalent to a 4 in the system divergence
        //https://www.cgpbooks.co.uk/gcse_grades_9_1_explained
        //https://web.tgsch.uk/wp-content/uploads/2016/12/New-GCSE-Grade-MethodologyFEB2016.pdf
        // case 'A*' : $points = 8; $pass = 1; $fail = 0; break;
        // case 'A'  : $points = 7; $pass = 1; $fail = 0; break;
        // case 'B'  : $points = 6; $pass = 1; $fail = 0; break;
        // case 'C'  : $points = 5; $pass = 1; $fail = 0; break;
        // case 'D'  : $points = 4; $pass = 0; $fail = 1; break;
        // case 'E'  : $points = 3; $pass = 0; $fail = 1; break;
        // case 'F'  : $points = 2; $pass = 0; $fail = 1; break;
        // case 'G'  : $points = 1; $pass = 0; $fail = 1; break;
        // case 'U'  : $points = 0; $pass = 0; $fail = 1; break;
        // case 'X'  : $points = 0; $pass = 0; $fail = 0; break;
        //
        // case '9' : $points = 8.75; $pass = 1; $fail = 0; break;
        // case '8'  : $points = 8; $pass = 1; $fail = 0; break;
        // case '7'  : $points = 7.25; $pass = 1; $fail = 0; break;
        // case '6'  : $points = 6.5; $pass = 1; $fail = 0; break;
        // case '5'  : $points = 5.75; $pass = 1; $fail = 0; break;
        // case '4'  : $points = 5; $pass = 1; $fail = 0; break;
        // case '3'  : $points = 4.25; $pass = 0; $fail = 1; break;
        // case '2'  : $points = 3.5; $pass = 0; $fail = 1; break;
        // case '1'  : $points = 2.75; $pass = 0; $fail = 1; break;

        case 'A*' : $points = 8.5; $pass = 1; $fail = 0; break;
        case 'A'  : $points = 7; $pass = 1; $fail = 0; break;
        case 'B'  : $points = 5.5; $pass = 1; $fail = 0; break;
        case 'C'  : $points = 4; $pass = 1; $fail = 0; break;
        case 'D'  : $points = 3; $pass = 0; $fail = 1; break;
        case 'E'  : $points = 2; $pass = 0; $fail = 1; break;
        case 'F'  : $points = 1.5; $pass = 1; $fail = 0; break;
        case 'G'  : $points = 1; $pass = 1; $fail = 0; break;
        case 'U'  : $points = 0; $pass = 0; $fail = 1; break;
        case 'X'  : $points = 0; $pass = 0; $fail = 1; break;

        case '9' : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '8'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '7'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '6'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '5'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '4'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '3'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '2'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
        case '1'  : $points = (int)$grade; $pass = 1; $fail = 0; break;
      }

      $this->points = $points;
      $this->passes = $pass;
      $this->fails = $fail;

      return $points; //used in spreadsheet key generation
    }

}
