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
    public $moduleCode;
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
                            'P3'  => 0,
                            'Q'   => 0
                          ];
    public $points = 0;
    public $pointsBoys = 0;
    public $pointsGirls = 0;
    public $ucasPoints = 0;
    public $ucasPointsBoys = 0;
    public $ucasPointsGirls= 0;
    public $passes = 0;
    public $fails = 0;
    public $resultCount = 0;
    public $resultCountBoys = 0;
    public $resultCountGirls = 0;
    public $position = 0;
    public $summaryData = [];
    public $isGCSE = false;
    public $level;
    public $title;
    public $modules = [];
    public $surplus = 0;
    public $subjectName;
    public $boardName;
    public $gcseAvg = 0;
    public $gcseAvgCount = 0;
    public $gcseAvgBoys = 0;
    public $gcseAvgCountBoys = 0;
    public $gcseAvgGirls = 0;
    public $gcseAvgCountGirls = 0;



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

      // if ($result->level === 'A' || $result->level === 'PreU'){
      //   if ($result->NCYear !== '13'){
      //     return;
      //   }
      // }

      // if($result->NCYear < 11) return;
      $this->results['r_' . $result->id] = &$result;
      $this->moduleCode = $result->moduleCode;

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->ucasPoints += $result->ucasPoints;
      $this->resultCount++;

      $this->getGcseAvg($result->txtSchoolID, $result->txtGender);

      $grade = $result->grade;
      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;

      if ($result->txtGender === "M") {
        $this->pointsBoys += $result->points;
        $this->ucasPointsBoys += $result->ucasPoints;
        $this->resultCountBoys++;
      } else {
        $this->pointsGirls += $result->points;
        $this->ucasPointsGirls += $result->ucasPoints;
        $this->resultCountGirls++;
      }

    }

    private function getGCSEAvg($txtSchoolID, $txtGender)
    {
      $sql = new \Dependency\Databases\AdaModules();
      $d = $sql->select('exams_gcse_avg', 'gcseAvg', 'misId=?', [$txtSchoolID]);
      if (isset($d[0])) {
        $this->gcseAvg += $d[0]['gcseAvg'];
        $this->gcseAvgCount++;
        if ($txtGender == 'M') {
          $this->gcseAvgBoys += $d[0]['gcseAvg'];
          $this->gcseAvgCountBoys++;
        } else {
          $this->gcseAvgGirls += $d[0]['gcseAvg'];
          $this->gcseAvgCountGirls++;
        }
      }
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

      $sD['gcseAvg'] = $this->gcseAvgCount > 0 ? round($this->gcseAvg / $this->gcseAvgCount, 2) : 0;

      $sD['gcseAvgBoys'] = $this->gcseAvgCountBoys > 0 ? round($this->gcseAvgBoys / $this->gcseAvgCountBoys, 2) : 0;

      $sD['gcseAvgGirls'] = $this->gcseAvgCountGirls > 0 ? round($this->gcseAvgGirls / $this->gcseAvgCountGirls, 2) : 0;

      $this->sortResults();

      $gradeCounts = $this->gradeCounts;

      //determine which type of exam the subject is primarily and use that count
      $count = $this->resultCount;

      $sD['year'] = $year;
      $sD['surplus'] = $this->surplus;

      $sD['candidateCount'] = $count;
      $sD['board'] = $this->boardName;
      $sD['%Astar'] = 0;
      $sD['%As'] = 0;
      $sD['%ABs'] = 0;
      $sD['%ABCs'] = 0;
      $sD['%ABCDs'] = 0;
      $sD['%ABCDEs'] = 0;
      $sD['%passRate'] = 0;
      $sD['%D'] = 0;
      $sD['%M'] = 0;
      $sD['%P'] = 0;

      if (($this->level === 'A' || $this->level === 'AS') && $count > 0) {
        $As = $gradeCounts['A*'];
        $sD['%Astar'] = round(100 * $As / $count);

        $As = $gradeCounts['A*'] + $gradeCounts['A'];
        $sD['%As'] = round(100 * $As / $count);

        $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'];
        $sD['%ABs'] = round(100 * $ABs / $count);

        $c = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'];
        $sD['%ABCs'] = round(100 * $c / $count);

        $c = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'] + $gradeCounts['D'];
        $sD['%ABCDs'] = round(100 * $c / $count);

        $c = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'] + $gradeCounts['D'] + $gradeCounts['E'];
        $sD['%ABCDEs'] = round(100 * $c / $count);
      }

      if ($this->level === 'PreU' && $count > 0) {
        $D = $gradeCounts['D1'] + $gradeCounts['D2'] + $gradeCounts['D3'];
        $sD['%D'] = round(100 * $D / $count);

        $M = $gradeCounts['M1'] + $gradeCounts['M2'] + $gradeCounts['M3'];
        $sD['%M'] = round(100 * $M / $count);

        $P = $gradeCounts['P1'] + $gradeCounts['P2'] + $gradeCounts['P3'];
        $sD['%P'] = round(100 * $P / $count);
      }

      if ($count > 0){
        $points = $this->points;
        $ucas = $this->ucasPoints;
        $sD['%passRate'] = round(100 * $this->passes / $count);
        $sD['gradeAverage'] = round($points / $count, 2);
        $sD['ucasAverage'] = round($ucas / $count, 2);
        $sD['pointsAvg'] = round($points / $count, 2);
      } else {
        $sD['pointsAvg'] = 0;
        $sD['%passRate'] = 0;
        $sD['gradeAverage'] = 0;
        $sD['ucasAverage'] = 0;
      }

      $sD['boysCount'] = $this->resultCountBoys;
      $sD['girlsCount'] = $this->resultCountGirls;
      $sD['ucasAvgBoys'] = $this->resultCountBoys == 0 ? 0 : round($this->ucasPointsBoys / $this->resultCountBoys,2);
      $sD['ucasAvgGirls'] = $this->resultCountGirls == 0 ? 0 : round($this->ucasPointsGirls / $this->resultCountGirls,2);

      $sD['pointsAvgBoys'] = $this->resultCountBoys == 0 ? 0 : round($this->pointsBoys / $this->resultCountBoys,2);
      $sD['pointsAvgGirls'] = $this->resultCountGirls == 0 ? 0 : round($this->pointsGirls / $this->resultCountGirls,2);



      $sD['gradeCounts'] = $this->gradeCounts;

      //add this year to summary data for easy graphing
      $sD['history'] = [$sD];
      $sD['historyKeys'] = ['y_' . $year => $sD];

      $this->summaryData = $sD;

    }
}
