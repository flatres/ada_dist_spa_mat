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
    public $summaryData = [];
    public $isGCSE = false;
    public $level;
    public $title;
    public $modules = [];


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
          case 'ASB': $level = 'A'; break;
          case 'FC': $level = 'PreU'; break;
          case 'B' : $level = 'EPQ'; break;
          default: $level = 'unknown';
      }
      $this->level = $level;
    }

    public function setModuleResult(array $moduleResult)
    {
        $moduleCode = $moduleResult['txtModuleCode'];
        if (!isset($this->modules['m_' . $moduleCode])) {
          $this->modules['m_' . $moduleCode] = new \Exams\Tools\ALevel\Module($moduleResult);
        } else {
          $this->modules['m_' . $moduleCode]->setResult($moduleResult);
        }
    }
    
    public function setResult(\Exams\Tools\ALevel\Result $result)
    {
      if ($result->txtLevel === 'ASB') return;
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

    public function sortResults()
    {
      if (count($this->results) > 0)  usort($this->results ,'self::sort');
    }
    
    private static function sort($a, $b)
    {
      // if (!isset($a->txtSurname) || !isset($b->txtSurname)) return true;
      return strcmp($a->txtSurname, $b->txtSurname);
    }
    
    public function makeSummaryData(int $year)
    {
      $sD = array();
      
      $this->sortResults();

      $gradeCounts = $this->gradeCounts;
      
      $sD['year'] = $year;
      $sD['gradeAverage'] = $this->resultCount > 0 ? round($this->points / $this->resultCount, 2) : 0;
      $sD['ucasAverage'] = $this->resultCount > 0 ? round($this->ucasPoints / $this->resultCount, 2) : 0;
      $sD['candidateCount'] = $this->resultCount;

      $As = $gradeCounts['A*'];
      $sD['%Astar'] = $this->resultCount > 0 ? round(100 * $As / $this->resultCount) :0 ;
      $sD['board'] = $this->boardName;

      $As = $gradeCounts['A*'] + $gradeCounts['A'];
      $sD['%As'] = $this->resultCount > 0 ? round(100 * $As / $this->resultCount) : 0 ;

      $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'];
      $sD['%ABs'] = $this->resultCount > 0 ? round(100 * $ABs / $this->resultCount) : 0;

      $sD['%passRate'] = $this->resultCount > 0 ? round(100 * $this->passes / $this->resultCount) : 0;
      if (count($this->results) > 0){
        $sD['pointsAvg'] = round($this->points / count($this->results), 2);
      } else {
        $sD['pointsAvg'] = 0;
      }

      $sD['gradeCounts'] = $this->gradeCounts;

      //add this year to summary data for easy graphing
      $sD['history'] = [$sD];

      $this->summaryData = $sD;

    }
}
