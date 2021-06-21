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
    public $subject = array(); //typo! Should have been using subjects
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
    public $points = 0;
    public $ucasPoints = 0;
    public $passes = 0;
    public $fails = 0;
    public $resultCount = 0;
    public $summaryData = array();
    public $gradeAverage = 0;
    public $ucasAverage = 0;
    public $modules = [];
    public $txtCandidateNumber;
    public $ethnicGroup;
    public $txtCandidateCode;
    public $USchoolResultCount;

    public function __construct(array $result)
    {
      $this->txtSchoolID = $result['txtSchoolID'];
      $this->txtGender = $result['txtGender'];
      $this->txtInitialedName = $result['txtInitialedName'];
      $this->txtForename = $result['txtForename'];
      $this->txtSurname = $result['txtSurname'];
      $this->intEnrolementNCYear = $result['intEnrolmentNCYear'];
      $this->NCYear = $result['NCYear'];
      $this->isNewSixthForm = $result['isNewSixthForm'];
      $this->txtHouseCode = $result['txtHouseCode'];
      $this->txtDOB = $result['txtDOB'];
      $this->ethnicGroup = $result['ethnicGroup'];
      
    }

    public function setResult(\Exams\Tools\ALevel\Result $result)
    {
      $this->results['r_' . $result->id] = $result;
      $this->txtCandidateCode = $result->txtCandidateCode;
      $this->txtCandidateNumber = $result->txtCandidateNumber;

      // if ($result->level === 'AS') return; //otherwise data doesn't dovetail well with new ucas points (no AS levels now)
      $this->subjects[$result->subjectCode] = $result;
      $this->{$result->subjectCode} = $result->grade; //useful for constructing tables

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->ucasPoints += $result->ucasPoints;
      $this->resultCount++;

      $grade = strtoupper($result->grade);
      if($result->level === 'A' || $result->level === 'PreU'){
        $this->USchoolResultCount++;
        if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
        $this->gradeCounts[$grade]++;
      }
    }

    public function setModuleResult(array $moduleResult)
    {
        $subjectCode = $moduleResult['subjectCode'];
        if (!isset($this->modules[$subjectCode])) $this->modules[$subjectCode] = [];
        $this->modules[$subjectCode][] = $moduleResult;
    }

    public function makeSummaryData()
    {
      $summaryData = array();

      $gradeCounts = $this->gradeCounts;
      $this->gradeAverage = 0;

      if ($this->resultCount === 0) return;

      $this->gradeAverage = round($this->points / $this->resultCount, 2);
      $this->ucasAverage = round($this->ucasPoints / $this->resultCount, 2);

      $gradeCounts = $this->gradeCounts;

      //determine which type of exam the subject is primarily and use that count
      $count = $this->resultCount;

      // $sD['%Astar'] = 0;
      // $sD['%As'] = 0;
      // $sD['%ABs'] = 0;
      // $sD['%ABCs'] = 0;
      // $sD['%ABCDs'] = 0;
      // $sD['%ABCDEs'] = 0;
      // $sD['%passRate'] = 0;
      // $sD['%D'] = 0;
      // $sD['%M'] = 0;
      // $sD['%P'] = 0;
      //
      // if (($this->level === 'A' || $this->level === 'AS') && $count > 0) {
      //   $As = $gradeCounts['A*'];
      //   $sD['%Astar'] = round(100 * $As / $count);
      //
      //   $As = $gradeCounts['A*'] + $gradeCounts['A'];
      //   $sD['%As'] = round(100 * $As / $count);
      //
      //   $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'];
      //   $sD['%ABs'] = round(100 * $ABs / $count);
      //
      //   $c = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'];
      //   $sD['%ABCs'] = round(100 * $c / $count);
      //
      //   $c = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'] + $gradeCounts['D'];
      //   $sD['%ABCDs'] = round(100 * $c / $count);
      //
      //   $c = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'] + $gradeCounts['D'] + $gradeCounts['E'];
      //   $sD['%ABCDEs'] = round(100 * $c / $count);
      // }
      //
      // if ($this->level === 'PreU' && $count > 0) {
      //   $D = $gradeCounts['D1'] + $gradeCounts['D2'] + $gradeCounts['D3'];
      //   $sD['%D'] = round(100 * $D / $count);
      //
      //   $M = $gradeCounts['M1'] + $gradeCounts['M2'] + $gradeCounts['M3'];
      //   $sD['%M'] = round(100 * $M / $count);
      //
      //   $P = $gradeCounts['P1'] + $gradeCounts['P2'] + $gradeCounts['P3'];
      //   $sD['%P'] = round(100 * $P / $count);
      // }

      if ($count > 0){
        $points = $this->points;
        $ucas = $this->ucasPoints;
        // $sD['%passRate'] = round(100 * $this->passes / $count);
        $sD['gradeAverage'] = round($points / $count, 2);
        $this->gradeAverage = $sD['gradeAverage'];
        $sD['ucasAverage'] = round($ucas / $count, 2);
        $sD['pointsAvg'] = round($points / $count, 2);
      } else {
        $sD['pointsAvg'] = 0;
        // $sD['%passRate'] = 0;
        $sD['gradeAverage'] = 0;
        $this->gradeAverage = $sD['gradeAverage'];
        $sD['ucasAverage'] = 0;
      }

      $sD['gradeCounts'] = $this->gradeCounts;

      $this->summaryData = $summaryData;
    }

}
