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
// ['isNewSixthForm']

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
                            'P3'  => 0,
                            'PU'  => 0
                          ];
                          
    public $gradeCountsGirls = [];
    public $gradeCountsBoys =  [];
    public $gradeCountsNL6 =  [];
    public $gradeCountsLS =  [];
                                    
    public $points = 0;
    public $pointsBoys = 0;
    public $pointsGirls = 0;
    public $pointsNL6 = 0;
    public $pointsLS = 0;
    
    public $passes = 0;
    public $passesBoys = 0;
    public $passesGirls = 0;
    public $passesNL6 = 0;
    public $passesLS = 0;
    
    public $fails = 0;
    public $failsBoys = 0;
    public $failsGirls = 0;
    public $failsNL6 = 0;
    public $failsLS = 0;
    
    public $resultCount = 0;
    public $resultCountBoys = 0;
    public $resultCountGirls = 0;
    public $resultCountNL6 = 0;
    public $resultCountLS = 0;
    
    public $position = 0;
    public $positionBoys = 0;
    public $positionGirls = 0;
    public $positionNL6 = 0;
    public $positionLS = 0;
    
    
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
      $grade = $result->grade;
      $this->gradeCounts[$grade]++;
      $this->resultCount++;
      
      if ($result->txtGender === 'M') {
        $this->passesBoys += $result->passes;
        $this->failsBoys += $result->fails;
        $this->pointsBoys += $result->points;
        $this->gradeCountsBoys[$result->grade]++;
        $this->resultCountBoys++;
      } else {
        $this->passesGirls += $result->passes;
        $this->failsGirls += $result->fails;
        $this->pointsGirls += $result->points;
        $this->gradeCountsGirls[$result->grade]++;
        $this->resultCountGirls++;
      }
      
      if ($result->isNewSixthForm) {
        $this->passesNL6 += $result->passes;
        $this->failsNL6 += $result->fails;
        $this->pointsNL6 += $result->points;
        $this->gradeCountsNL6[$result->grade]++;
        $this->resultCountNL6++;
      } else {
        $this->passesLS += $result->passes;
        $this->failsLS += $result->fails;
        $this->pointsLS += $result->points;
        $this->gradeCountsLS[$result->grade]++;
        $this->resultCountLS++;
      }

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
