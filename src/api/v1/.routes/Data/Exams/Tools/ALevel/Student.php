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

class Student
{
    public $txtSchoolID, $txtGender, $txtInitialedName, $intEnrolementNCYear;
    public $results = array();
    public $subjects = array();
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
    public $summaryData = array();

    public function __construct(array $result)
    {
      $this->txtSchoolID = $result['txtSchoolID'];
      $this->txtGender = $result['txtGender'];
      $this->txtInitialedName = $result['txtInitialedName'];
      $this->intEnrolementNCYear = $result['intEnrolmentNCYear'];
      $this->NCYear = $result['NCYear'];
      $this->isNewSixthForm = $result['isNewSixthForm'];

    }

    public function setResult(\Exams\Tools\ALevel\Result &$result)
    {

      // if($result['subjectCode'] == '')
      $this->results['r_' . $result->id] = &$result;
      $this->subject[$result->subjectCode] = &$result;
      $this->{$result->subjectCode} = $result->grade;

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;

      $grade = is_numeric($result->grade) ? "#" . $result->grade : strtoupper($result->grade);
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;
    }

    public function makeSummaryData($resultCount)
    {
      $summaryData = array();
      if($resultCount == 0) return $summaryData;

      $gradeCounts = $this->gradeCounts;
      //total candidates
      $summaryData[] = array('desc' => 'Total Candidates', 'val' => 1);

      //percentages*
      $summaryData[] = array('desc' => 'Percentage A*', 'val' => (100 * $gradeCounts['A*']) / $resultCount);
      $summaryData[] = array('desc' => 'Percentage A* + A', 'val' => (100 * ($gradeCounts['A*'] + $gradeCounts['A'])) / $resultCount);
      $summaryData[] = array('desc' => 'Percentage A* + A + B', 'val' => (100 * ($gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'])) / $resultCount);

      $summaryData[] = array('desc' => 'Failures', 'val' => 100 * $this->fails / $resultCount);

      $this->summaryData = $summaryData;
    }

}
