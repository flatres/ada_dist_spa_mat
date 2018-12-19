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

namespace Data\Exams\Tools\GCSE;



class Subject
{

    public $subjectCode;
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
                                  '#1'  => 0
                                );
    public $points = 0;
    public $passes = 0;
    public $fails = 0;
    public $resultCount = 0;
    public $position = 0;
    public $summaryData = array();
    public $isNumeric = false;

    public function __construct(array $result)
    {
      $this->subjectCode = $result['subjectCode'];
      $this->subjectName = $result['subjectName'];
      $this->boardName = $result['boardName'];
      $this->boardDesc = $result['boardDesc'];
      $this->intUABCode = $result['intUABCode'];
    }

    public function setResult(\Data\Exams\Tools\GCSE\Result &$result)
    {
      $this->results['r_' . $result->id] = &$result;

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->resultCount++;

      $this->isNumeric = is_numeric($result->grade);
      $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;

    }

    public function setStudent(\Data\Exams\Tools\GCSE\Student &$student)
    {
      $txtSchoolID = $student->txtSchoolID;
      $this->students['s_' . $txtSchoolID] = &$student;
    }

    public function makeSummaryData()
    {
      $sD = array();

      $gradeCounts = $this->gradeCounts;

      $sD['gradeAverage'] = round($this->points / $this->resultCount, 2);
      $sD['candidateCount'] = $this->resultCount;

      $As = $gradeCounts['A*'] +  $gradeCounts['#9'] +  $gradeCounts['#8'];
      $sD['%Astar'] = round(100 * $As / $this->resultCount);

      $As = $gradeCounts['A*'] + $gradeCounts['A'] +  $gradeCounts['#9'] + $gradeCounts['#8'] +  $gradeCounts['#7'];
      $sD['%As'] = round(100 * $As / $this->resultCount);

      $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] +  $gradeCounts['#9'] + $gradeCounts['#8'] +  $gradeCounts['#7'] +  $gradeCounts['#6'];
      $sD['%ABs'] = round(100 * $ABs / $this->resultCount);

      $sD['%passRate'] = round(100 * $this->passes / $this->resultCount);

      $this->summaryData = $sD;

    }
}
