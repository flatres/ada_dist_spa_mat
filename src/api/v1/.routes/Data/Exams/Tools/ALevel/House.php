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

namespace Exams\Tools\ALevel;

class House
{

    public $txtHouseCode;
    public $students = array(); // key s_{txtSchoolID}
    public $results = array();
    public $gradeCounts = array(  'A*'  => 0,
                                  'A'   => 0,
                                  'B'   => 0,
                                  'C'   => 0,
                                  'D'   => 0,
                                  'E'   => 0,
                                  'U'   => 0,
                                  '#9'  => 0);
    public $points = 0;
    public $passes = 0;
    public $fails = 0;

    public function __construct(array $result)
    {
      $this->txtHouseCode = $result['txtHouseCode'];
    }

    public function setResult(\Exams\Tools\Result &$result)
    {
      $this->results['r_' . $result->id] = $result;

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;

      $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;

    }

    public function setStudent(\Exams\Tools\Student &$student)
    {
      $txtSchoolID = $student->txtSchoolID;
      $this->students['s_' . $txtSchoolID] = &$student;
    }
}
