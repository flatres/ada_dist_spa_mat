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
    public $gradeCounts = [ 'A*'  => 0,
                            'A'   => 0,
                            'B'   => 0,
                            'C'   => 0,
                            'D'   => 0,
                            'E'   => 0,
                            'U'   => 0,
                            'D1'  => 0,
                            'D2'  => 0,
                            'D3'  => 0,
                            'M1'  => 0,
                            'M2'  => 0,
                            'M3'  => 0,
                            'P1'  => 0,
                            'P2'  => 0,
                            'P3'  => 0
                          ];
    public $points = 0;
    public $passes = 0;
    public $fails = 0;
    public $resultCount = 0;
    public $position = 0;
    public $summaryData = array();

    public function __construct(array $result)
    {
      $this->txtHouseCode = $result['txtHouseCode'];
    }

    public function setResult(\Exams\Tools\ALevel\Result &$result)
    {
      $this->results['r_' . $result->id] = $result;

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->resultCount++;

      $grade = $result->grade;
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;

    }

    public function setStudent(\Exams\Tools\ALevel\Student &$student)
    {
      $txtSchoolID = $student->txtSchoolID;
      $this->students['s_' . $txtSchoolID] = &$student;
    }
    
    public function makeSummaryData(int $year)
    {
      $sD = array();
      $sD['year'] = $year;
      $gradeCounts = $this->gradeCounts;

      $sD['gradeAverage'] = round($this->points / $this->resultCount, 2);
      $sD['candidateCount'] = count($this->students);

      $sixOrMoreAs = 0;
      foreach($this->students as $student){
        if($student->gradeCounts['A*'] + $student->gradeCounts['A']) $sixOrMoreAs++;
      }
      $sD['sixOrMoreAs'] = $sixOrMoreAs;
      
      $sD['history'] = [$sD];

      $this->summaryData = $sD;

    }
}
