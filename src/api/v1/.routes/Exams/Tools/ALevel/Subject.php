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

class Subject
{

    public $subjectCode;
    // public $students = array(); // key s_{txtSchoolID}
    public $results = [];
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
    public $ucasPoints = 0;
    public $passes = 0;
    public $fails = 0;
    public $resultCount = 0;
    public $position = 0;
    public $summaryData = array();
    public $isGCSE = false;
    public $level;
    public $title;


    public function __construct(array $result)
    {
      $this->subjectCode = $result['subjectCode'];
      $this->subjectName = $result['subjectName'];
      $this->boardName = $result['boardName'];
      $this->boardDesc = $result['boardDesc'];
      $this->intUABCode = $result['intUABCode'];
      $this->title = $result['txtOptionTitle'];
      $this->unknown = $result['subjectCode'] == '-' ? true : false;

      switch($result['txtLevel']) {
          case 'A'  : $level = 'A'; break;
          case 'ASB': $level = 'AS'; break;
          case 'FC': $level = 'Pre U'; break;
          case 'B' : $level = 'EPQ'; break;
          default: $level = 'unknown';
      }
      $this->level = $level;
    }

    public function setResult(\Exams\Tools\ALevel\Result &$result)
    {

      // if($result->NCYear < 11) return;
      $this->results['r_' . $result->id] = &$result;

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->ucasPoints += $result->ucasPoints;
      $this->resultCount++;

      $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;

    }

    public function setStudent(\Exams\Tools\ALevel\Student &$student)
    {
      if($student->NCYear < 11) return;
      $txtSchoolID = $student->txtSchoolID;
      $this->students['s_' . $txtSchoolID] = &$student;
    }

    public function makeSummaryData(int $year)
    {
      $sD = array();

      $gradeCounts = $this->gradeCounts;

      $sD['year'] = $year;
      $sD['gradeAverage'] = round($this->points / $this->resultCount, 2);
      $sD['ucasAverage'] = round($this->ucasPoints / $this->resultCount, 2);
      $sD['candidateCount'] = $this->resultCount;

      $As = $gradeCounts['A*'];
      $sD['%Astar'] = round(100 * $As / $this->resultCount);

      $As = $gradeCounts['A*'] + $gradeCounts['A'];
      $sD['%As'] = round(100 * $As / $this->resultCount);

      $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'];
      $sD['%ABs'] = round(100 * $ABs / $this->resultCount);

      $sD['%passRate'] = round(100 * $this->passes / $this->resultCount);
      
      //add this year to summary data for easy graphing
      $sD['history'] = [$sD];

      $this->summaryData = $sD;

    }
}
