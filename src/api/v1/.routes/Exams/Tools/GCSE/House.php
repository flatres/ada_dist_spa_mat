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

class House
{

    public $txtHouseCode;
    public $genderType;
    public $students = array(); // key s_{txtSchoolID}
    public $results = array();
    public $gradeCounts = array(  'A*'  => 0,
                                  'A'   => 0,
                                  'B'   => 0,
                                  'C'   => 0,
                                  'D'   => 0,
                                  'E'   => 0,
                                  'U'   => 0,
                                  '#9'  => 0,
                                  '#8'  => 0,
                                  '#7'  => 0,
                                  '#6'  => 0,
                                  '#5'  => 0,
                                  '#4'  => 0,
                                  '#3'  => 0,
                                  '#2'  => 0,
                                  '#1'  => 0,
                                  'Q'   => 0
                                );
    public $points = 0;
    public $passes = 0;
    public $fails = 0;
    public $resultCount = 0;
    public $position = 0;
    public $summaryData = array();
    public $boysCount = 0;
    public $girlsCount = 0;

    public function __construct(array $result)
    {
      $this->txtHouseCode = $result['txtHouseCode'];
    }

    public function setResult(\Exams\Tools\GCSE\Result $result)
    {
      $this->results['r_' . $result->id] = $result;

      if ($result->txtGender === 'M') $this->boysCount++;
      if ($result->txtGender === 'F') $this->girlsCount++;

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->resultCount++;

      $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;

    }

    public function setStudent(\Exams\Tools\GCSE\Student &$student)
    {
      $txtSchoolID = $student->txtSchoolID;
      $this->students['s_' . $txtSchoolID] = &$student;
    }

    public function makeSummaryData(int $year)
    {
      $sD = array();

      $gradeCounts = $this->gradeCounts;

      if ($this->girlsCount > 0) $this->genderType = 'girls';
      if ($this->boysCount > 0) $this->genderType = 'boys';
      if ($this->girlsCount > 0 && $this->boysCount > 0 ) $this->genderType = 'mixed';

      $sD['gradeAverage'] = round($this->points / $this->resultCount, 2);
      $sD['candidateCount'] = count($this->students);

      $sixOrMoreAs = 0;
      foreach($this->students as $student){
        if($student->gradeCounts['A*'] + $student->gradeCounts['A'] + $student->gradeCounts['#9'] + $student->gradeCounts['#8'] +  $student->gradeCounts['#7'] > 5) $sixOrMoreAs++;
      }
      $sD['sixOrMoreAs'] = $sixOrMoreAs;
      $sD['gradeCounts'] = $this->gradeCounts;

      $sD['history'] = [$sD];
      $sD['historyKeys'] = ['y_' . $year => $sD];

      $this->summaryData = $sD;

    }
}
