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

class Statistics
{
    public $allStudents = array(); //key s_{txtSchoolID}
    public $houseResults = array(); // key h_{houseCode}
    public $subjectResults = array(); //key s_{subjectCode}
    public $upperIntake = array(); //key s_{txtSchoolID}
    public $lowerIntake = array(); //key s_{txtSchoolID}
    public $maleStudents = array(); //key s_{txtSchoolID}
    public $femaleStudents = array();
    public $summaryData = array();
    public $earlyResults = array();
    public $currentHundredResults = array(); //doesn't include those in the remove or new in sixth form

    private $error = false;

    public function __construct(\Dependency\Databases\ISams $sql, \Sockets\Console $console)
    {
       $this->sql= $sql;
       $this->console = $console; //for caching student data
       $this->console->publish("Building Statistics");

    }

    public function makeStatistics(array $results, bool $isGCSE)
    {
      $i = 0;
      $this->isGCSE = $isGCSE;

      $results = $this->checkForDoubleAwards($results);
      $count = count($results);
      $this->console->publish("Sorting Results $i / $count", 1);

      foreach($results as $result){
        if($result['NCYear'] != 11 || $result['isNewSixthForm'] || $result['subjectCode'] == 'ADD') continue;

        $this->currentHundredResults[] = $result;

        $i++;
        if($i % 100 == 0) $this->console->replace("Sorting Results $i / $count");

        $objResult = new \Exams\Tools\ALevel\Result($result);

        if($result['early']) $this->earlyResults[] = $objResult;

        $txtHouseCode = $result['txtHouseCode'];
        if(!isset($this->houseResults[$txtHouseCode])) $this->newHouse($result);
        $this->houseResults[$txtHouseCode]->setResult($objResult);

        $txtHouseCode = $result['subjectCode'];
        if(!isset($this->subjectResults[$txtHouseCode])) $this->newSubject($result);
        $this->subjectResults[$txtHouseCode]->setResult($objResult);

        //must go after subject and houses as new student will add the student to these arrays
        $txtSchoolID = $result['txtSchoolID'];
        if(!isset($this->allStudents['s_' . $txtSchoolID])) $this->newStudent($result);
        $this->allStudents['s_' . $txtSchoolID]->setResult($objResult);

      }

      $this->makeSummaryData($this->currentHundredResults);

      $this->console->replace("Sorting Results $count / $count");

      ksort($this->houseResults);
      ksort($this->subjectResults);

      unset($this->console);
      unset($this->sql);
      return $this;
    }

    private function makeSummaryData($results)
    {
      $this->console->replace("Generating Summary Data");
      $summaryData = array();
      $resultCount = count($results);

      $this->resultCount = $resultCount;
      $maleResults = 0;
      $femaleResults = 0;
      $this->star = 0;

      foreach($results as $result){
        if($result['txtGender'] == 'M') $maleResults++;
        if($result['txtGender'] == 'F') $femaleResults++;
      }

      foreach($this->allStudents as $student){
        $resultCount = $student->txtGender == 'M' ? $maleResults : $femaleResults;
        if($resultCount == 0) continue; //prevents div 0

        $student->makeSummaryData($resultCount);
        $gender = $student->txtGender;
        $gender == "M" ? $this->maleStudents[] = &$student :  $this->femaleStudents[] = &$student;

        for ($i=0; $i < count($student->summaryData); $i++) {
          if(!isset($this->summaryData[$i])) $this->summaryData[] = array('M_val' => 0, "F_val" => 0, );

          $this->summaryData[$i]['desc'] = $student->summaryData[$i]['desc'];
          $this->summaryData[$i][$gender . '_val'] += $student->summaryData[$i]['val'];

          // $this->summaryData[$i]['total_val'] += $student->summaryData[$i]['val'] ;

        }
      }

      //round values
      foreach($this->summaryData as &$data){
        $data['M_val'] = round($data['M_val']);
        $data['F_val'] = round($data['F_val']);
      }

      return $this->summaryData;
    }

    private function checkForDoubleAwards(array $results)
    {
      $this->console->publish('Processing double awards');
      $newResults = array();
      foreach($results as $result){
        //Double Science Award
        if($this->contains("double", $result['txtOptionTitle']) && $result['subjectCode'] = "SC"){
          //create two copies and extract grades
          $sci1 = $result;
          $sci2 = $result;
          $sci1['subjectCode'] = "S1";
          $sci2['subjectCode'] = "S2";
          $sci1['subjectName'] = "Science 1";
          $sci2['subjectName'] = "Science 2";
          $sci1['grade'] = $result['grade'][0];
          // if(!$result['grade'][1]) echo '---'.$result['grade'].'---';
          $sci2['grade'] = isset($result['grade'][1]) ? $result['grade'][1] : $result['grade'][0];
          if($sci1['grade'] == '*') $sci1['grade'] = 'A*';
          if($sci2['grade'] == '*') $sci2['grade'] = 'A*';
          $newResults[] = $sci1;
          $newResults[] = $sci2;

        } else {
          $newResults[] = $result;
        }
      }
      return $newResults;
    }

    private function newStudent($result)
    {
      $student = new \Exams\Tools\ALevel\Student($result);
      $key = 's_' . $result['txtSchoolID'];
      $this->allStudents[$key] = &$student;

      $this->houseResults[$result['txtHouseCode']]->setStudent($student);
      $this->subjectResults[$result['subjectCode']]->setStudent($student);

      return $student;
    }

    private function newHouse($result)
    {
      $house = new \Exams\Tools\ALevel\House($result);
      $key = $result['txtHouseCode'];
      $this->houseResults[$key] = $house;
      return $house;
    }

    private function newSubject($result)
    {
      $subject = new \Exams\Tools\ALevel\Subject($result);
      $key = $result['subjectCode'];
      $this->subjectResults[$key] = $subject;
      return $subject;
    }

    function contains($needle, $haystack)
    {
        return stripos($haystack, $needle) !== false;
    }


}
