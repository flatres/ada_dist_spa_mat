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
                                  '#1'  => 0,
                                  'Q'   => 0
                                );
    public $points = 0;
    public $pointsBoys = 0;
    public $pointsGirls = 0;
    public $passes = 0;
    public $fails = 0;
    public $resultCount = 0;
    public $resultCountBoys = 0;
    public $resultCountGirls = 0;
    public $position = 0;
    public $surplus = 0;
    public $summaryData = array();
    public $isNumeric = false;
    public $isLetter = false;
    public $isGCSE = true;
    public $title;
    public $unknown = false;
    public $level;
    public $hasEarly = false;
    public $isIGCSE;


    public function __construct(array $result)
    {
      $this->subjectCode = $result['subjectCode'];
      $this->subjectName = $result['subjectName'];
      $this->boardName = $result['boardName'];
      $this->boardDesc = $result['boardDesc'];
      $this->intUABCode = $result['intUABCode'];
      $this->title = $result['txtOptionTitle'];
      $this->unknown = $result['subjectCode'] == '-' ? true : false;
      $this->level = 'GCSE';

    }

    public function setResult(\Exams\Tools\GCSE\Result $result)
    {
      $this->results['r_' . $result->id] = $result;
      $this->isIGCSE = $result->isIGCSE;
      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->resultCount++;
      if ($result->early) $this->hasEarly = true;

      if (is_numeric($result->grade)) $this->isNumeric = true;
      if (!is_numeric($result->grade)) $this->isLetter = true;

      $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;

      if ($result->txtGender === "M") {
        $this->pointsBoys += $result->points;
        $this->resultCountBoys++;
      } else {
        $this->pointsGirls += $result->points;
        $this->resultCountGirls++;
      }

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

      $sD['surplus'] = $this->surplus;
      $sD['gradeAverage'] = round($this->points / $this->resultCount, 2);
      $sD['candidateCount'] = $this->resultCount;

      $g = $this->gradeCounts;
      $sD['%9'] = round(100 *  $g['#9'] / $this->resultCount);
      $sD['%98'] = round(100 * ($g['#9'] + $g['#8']) / $this->resultCount);
      $sD['%97'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7']) / $this->resultCount);
      $sD['%96'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6']) / $this->resultCount);
      $sD['%95'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5']) / $this->resultCount);
      $sD['%94'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4']) / $this->resultCount);
      $sD['%93'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3']) / $this->resultCount);
      $sD['%92'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['#2']) / $this->resultCount);
      $sD['%91'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['#2'] + $g['#1']) / $this->resultCount);

      $As = $gradeCounts['A*'] +  $gradeCounts['#9'] +  $gradeCounts['#8'];
      $sD['%Astar'] = round(100 * $As / $this->resultCount);

      $As = $gradeCounts['A*'] + $gradeCounts['A'] +  $gradeCounts['#9'] + $gradeCounts['#8'] +  $gradeCounts['#7'];
      $sD['%As'] = round(100 * $As / $this->resultCount);

      $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] +  $gradeCounts['#9'] + $gradeCounts['#8'] +  $gradeCounts['#7'] +  $gradeCounts['#6'];
      $sD['%ABs'] = round(100 * $ABs / $this->resultCount);

      $sD['%passRate'] = round(100 * $this->passes / $this->resultCount);

      $sD['boysCount'] = $this->resultCountBoys;
      $sD['girlsCount'] = $this->resultCountGirls;

      $sD['pointsAvgBoys'] = $this->resultCountBoys == 0 ? 0 : round($this->pointsBoys / $this->resultCountBoys,2);
      $sD['pointsAvgGirls'] = $this->resultCountGirls == 0 ? 0 : round($this->pointsGirls / $this->resultCountGirls,2);

      $sD['gradeCounts'] = $this->gradeCounts;
      $sD['year'] = $year;
      $sD['board'] = $this->boardName;

      $sD['history'] = [$sD];
      $sD['historyKeys'] = ['y_' . $year => $sD];

      $this->summaryData = $sD;

    }
}
