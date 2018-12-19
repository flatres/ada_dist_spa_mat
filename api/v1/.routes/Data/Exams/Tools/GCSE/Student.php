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
    public $summaryData = array();
    public $numericPoints = 0;
    public $numericResultCount = 0;
    public $letterPoints = 0;
    public $letterResultCount = 0;

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

    }

    public function setResult(\Data\Exams\Tools\GCSE\Result &$result)
    {

      // if($result['subjectCode'] == '')
      $this->results['r_' . $result->id] = &$result;
      $this->subject[$result->subjectCode] = &$result;
      $this->{$result->subjectCode} = $result->grade; //useful for constructing tables

      $this->passes += $result->passes;
      $this->fails += $result->fails;
      $this->points += $result->points;
      $this->resultCount++;

      $grade = is_numeric($result->grade) ? "#" . $result->grade : strtoupper($result->grade);

      if( is_numeric($result->grade) ){
        $this->numericPoints += $result->points;
        $this->numericResultCount++;

      } else{
        $this->letterPoints += $result->points;
        $this->letterResultCount++;
      }


      if(!isset($this->gradeCounts[$grade])) $this->gradeCounts[$grade] = 0;
      $this->gradeCounts[$grade]++;
    }

    public function makeSummaryData()
    {
      $summaryData = array();

      $gradeCounts = $this->gradeCounts;

      $this->gradeAverage = round($this->points / $this->resultCount, 2);

      $this->numericGradeAverage = $this->numericResultCount == 0 ? 0 : round($this->numericPoints / $this->numericResultCount, 2);
      $this->letterGradeAverage = $this->letterResultCount == 0 ? 0 : round($this->letterPoints / $this->letterResultCount, 2);

      $this->weightedAverage = ($this->numericPoints * $this->numericResultCount + $this->letterPoints * $this->letterResultCount) / ($this->letterResultCount + $this->numericResultCount);

      //total candidates
      $summaryData[] = array('desc' => 'Total Candidates', 'val' => 1, 'type' => 'Absolute');

      $Astars =  $gradeCounts['A*'] + $gradeCounts['#9'] +  $gradeCounts['#8'];
      $As = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['#9'] + $gradeCounts['#8'] + $gradeCounts['#7'];
      $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['#9'] + $gradeCounts['#8'] + $gradeCounts['#7'] + $gradeCounts['#6'];
      $ABC = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'] + $gradeCounts['#9'] + $gradeCounts['#8'] + $gradeCounts['#7'] + $gradeCounts['#6'] + $gradeCounts['#5'] + $gradeCounts['#4'];

      //percentages*
      $summaryData[] = array('desc' => 'Has A* (9-8)', 'val' => $Astars > 0 ? 1 : 0, 'type' => 'absolute');
      $summaryData[] = array('desc' => 'Has A*A (9-8-7)', 'val' => $As > 0 ? 1 : 0, 'type' => 'absolute');

      $summaryData[] = array('desc' => 'Top Mark 9', 'val' => $gradeCounts['#9'], 'type' => 'Results%');
      $summaryData[] = array('desc' => 'A* (9-8)', 'val' => $Astars, 'type' => 'Results%');
      $summaryData[] = array('desc' => 'A* + A (9-7)', 'val' => $As, 'type' => 'Results%');
      $summaryData[] = array('desc' => 'A* + A + B (9-6)', 'val' => $ABs , 'type' => 'Results%');

      $summaryData[] = array('desc' => 'Failures', 'val' => $this->fails, 'type' => 'Results%');
      // $summaryData[] =  array('desc' => 'Passes', 'val' => $this->passes, 'type' => 'Results%');

      $summaryData[] =  array('desc' => 'No Grade Below B (6)', 'val' => $ABs == $this->resultCount ? 1 : 0, 'type' => 'Students%');
      $summaryData[] =  array('desc' => 'No Grade Below C (4)', 'val' => $ABC == $this->resultCount ? 1 : 0, 'type' => 'Students%');
      // $summaryData[] =  array('desc' => 'Percentage Students with No Grade Below C', 'val' => $this->fails == 0 ? 1 : 0, 'type' => 'Students%');

      $summaryData[] =  array('desc' => '3 or Fewer Passes', 'val' => $this->passes < 4 ? 1 : 0, 'type' => 'Students%');

      $summaryData[] = array('desc' => '9 or more As (9-7)', 'val' => $As > 8 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '6 or more As (9-7)', 'val' => $As > 5 ? 1 : 0, 'type' => 'Students%');

      $summaryData[] = array('desc' => 'Only 7 passes', 'val' => $this->passes == 7 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => 'Only 6 passes', 'val' => $this->passes == 6 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => 'Only 5 passes', 'val' => $this->passes == 5 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => 'Fewer than 4 passes', 'val' => $this->passes < 4 ? 1 : 0, 'type' => 'Students%');

      $summaryData[] = array('desc' => '13 As (9-7)', 'val' => $As == 13 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '12 As (9-7)', 'val' => $As == 12 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '11 As (9-7)', 'val' => $As == 11 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '10 As (9-7)', 'val' => $As == 10 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '9 As (9-7)', 'val' => $As == 9 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '8 As (9-7)', 'val' => $As == 8 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '7 As (9-7)', 'val' => $As == 7 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '6 As (9-7)', 'val' => $As == 6 ? 1 : 0, 'type' => 'Students%');

      /////LETTER GRADES ONLY
      $Astars =  $gradeCounts['A*'];
      $As = $gradeCounts['A*'] + $gradeCounts['A'];
      $ABs = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'];
      $ABC = $gradeCounts['A*'] + $gradeCounts['A'] + $gradeCounts['B'] + $gradeCounts['C'];

      //percentages*
      $summaryData[] = array('desc' => 'A*', 'val' => $Astars, 'type' => 'Results%');
      $summaryData[] = array('desc' => 'A* + A', 'val' => $As, 'type' => 'Results%');
      $summaryData[] = array('desc' => 'A* + A + B', 'val' => $ABs , 'type' => 'Results%');

      $summaryData[] =  array('desc' => 'No Grade Below B', 'val' => $ABs == $this->letterResultCount ? 1 : 0, 'type' => 'Students%');
      $summaryData[] =  array('desc' => 'No Grade Below C ', 'val' => $ABC == $this->letterResultCount ? 1 : 0, 'type' => 'Students%');

      $summaryData[] = array('desc' => '9 or more As', 'val' => $As > 8 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '6 or more As', 'val' => $As > 5 ? 1 : 0, 'type' => 'Students%');

      $summaryData[] = array('desc' => '13 As', 'val' => $As == 13 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '12 As', 'val' => $As == 12 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '11 As', 'val' => $As == 11 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '10 As', 'val' => $As == 10 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '9 As', 'val' => $As == 9 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '8 As', 'val' => $As == 8 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '7 As', 'val' => $As == 7 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '6 As', 'val' => $As == 6 ? 1 : 0, 'type' => 'Students%');


      /////Numeric GRADES
      $Astars =  $gradeCounts['#9'] +  $gradeCounts['#8'];
      $As = $gradeCounts['#9'] + $gradeCounts['#8'] + $gradeCounts['#7'];
      $ABs = $gradeCounts['#9'] + $gradeCounts['#8'] + $gradeCounts['#7'] + $gradeCounts['#6'];
      $ABC = $gradeCounts['#9'] + $gradeCounts['#8'] + $gradeCounts['#7'] + $gradeCounts['#6'] + $gradeCounts['#5'] + $gradeCounts['#4'];

      //percentages*
      $summaryData[] = array('desc' => '(9)', 'val' => $gradeCounts['#9'], 'type' => 'Results%');
      $summaryData[] = array('desc' => '(9-8)', 'val' => $Astars, 'type' => 'Results%');
      $summaryData[] = array('desc' => '(9-7)', 'val' => $As, 'type' => 'Results%');
      $summaryData[] = array('desc' => '(9-6)', 'val' => $ABs , 'type' => 'Results%');

      $summaryData[] =  array('desc' => 'No Grade Below 6', 'val' => $ABs == $this->numericResultCount ? 1 : 0, 'type' => 'Students%');
      $summaryData[] =  array('desc' => 'No Grade Below 4', 'val' => $ABC == $this->numericResultCount ? 1 : 0, 'type' => 'Students%');
      // $summaryData[] =  array('desc' => 'Percentage Students with No Grade Below C', 'val' => $this->fails == 0 ? 1 : 0, 'type' => 'Students%');



      $summaryData[] = array('desc' => '9 or more 9-7', 'val' => $As > 8 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '6 or more As 9-7', 'val' => $As > 5 ? 1 : 0, 'type' => 'Students%');


      $summaryData[] = array('desc' => '13 (9-7)', 'val' => $As == 13 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '12 (9-7)', 'val' => $As == 12 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '11 (9-7)', 'val' => $As == 11 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '10 (9-7)', 'val' => $As == 10 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '9 (9-7)', 'val' => $As == 9 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '8 (9-7)', 'val' => $As == 8 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '7 (9-7)', 'val' => $As == 7 ? 1 : 0, 'type' => 'Students%');
      $summaryData[] = array('desc' => '6 (9-7)', 'val' => $As == 6 ? 1 : 0, 'type' => 'Students%');
      $this->summaryData = $summaryData;

    }

}
