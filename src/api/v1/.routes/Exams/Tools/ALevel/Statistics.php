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
    public $averages = [];
    public $earlyResults = array();
    public $year;

    private $error = false;
    private $typeKey;
    private $joinKey;
    private $genderKey;
    private $sql;
    private $console;
    public $moduleResults;

    public function __construct(\Dependency\Databases\ISams $sql, \Sockets\Console $console, $moduleResults)
    {
       $this->sql= $sql;
       $this->console = $console; //for caching student data
       $this->console->publish("Building Statistics");
       $this->moduleResults = $moduleResults;

    }

    public function makeStatistics(array $session, array $results, \Exams\Tools\Cache $cache)
    {
      $i = 0;
      $this->isGCSE = false;
      $this->cache = $cache;

      $results = $this->checkForDoubleAwards($results);
      $count = count($results);
      $this->console->publish("Sorting Results $i / $count", 1);

      foreach($results as $result){
        // if($result['NCYear'] != 13) continue;

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
      $year = $session['year'];
      $this->year = $year;
      $this->processModules();
      $this->makeSummaryData($results);
      $this->makeHouseSummaryData();
      $this->makeSubjectSummaryData($year);
      $this->makeSchoolData($year);
      $this->makeHistoricalData($year);

      $this->console->replace("Sorting Results $count / $count");
      ksort($this->houseResults);
      ksort($this->subjectResults);

      unset($this->console);
      unset($this->sql);
      unset($this->results);
      return $this;
    }
    
    private function processModules()
    {
      $count = count($this->moduleResults);
      $this->console->publish("Processing $count Module Results...", 1);
      foreach ($this->moduleResults as &$moduleResult){
        //try to determine the subject code
        $objSubject = new \Exams\Tools\SubjectCodes($moduleResult['txtModuleCode'], $moduleResult['txtOptionTitle'], $this->sql);
        
        if($objSubject->subjectCode == '-') {
          $this->console->error('!!WARNING!! Couldnt match subject code to ' . $objSubject->txtOptionTitle);
          $this->error = true;
          continue;
        }
        if (!isset($this->subjectResults[$objSubject->subjectCode])){
          $this->console->error('!!WARNING!! Could not find subject ' . $objSubject->txtOptionTitle . 'with code ' . $objSubject->subjectCode);
          continue;
        }
        $moduleResult['subjectCode'] = $objSubject->subjectCode;
        //merge in student data from the all sudents
        if (isset($this->allStudents['s_' . $moduleResult['txtSchoolID']])){
          $s = &$this->allStudents['s_' . $moduleResult['txtSchoolID']];
          $moduleResult['txtGender'] = $s->txtGender;
          $moduleResult['txtInitialedName'] = $s->txtInitialedName;
          $moduleResult['txtForename'] = $s->txtForename;
          $moduleResult['txtSurname'] = $s->txtSurname;
          $moduleResult['txtHouseCode'] = $s->txtHouseCode;
          $s->setModuleResult($moduleResult);
        }
        $this->subjectResults[$objSubject->subjectCode]->setModuleResult($moduleResult);
      }
      
      //sort results
      foreach($this->subjectResults as &$subject){
        foreach($subject->modules as &$module){
          $module->sortResults();
        }
      }
      unset($this->moduleResults);
    }
    // https://stackoverflow.com/questions/6086267/how-to-merge-two-arrays-by-summing-the-merged-values
    private function combineGradeCounts($gradeCounts, $student)
    {
      $a1 = $gradeCounts;
      $a2 = $student->gradeCounts;
      $sums = array();
      foreach (array_keys($a1 + $a2) as $key) {
          $sums[$key] = @($a1[$key] + $a2[$key]);
      }
      return $sums;
    }

    private function makeSchoolData()
    {
      $this->console->publish('Making School Summary Data');
      $data = [];
      $gradeCounts = [ 'A*'  => 0,
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
      $boysAvg = $girlsAvg = $allAvg = $newAvg = 0;
      $boysCount = $girlsCount = $allCount = $newCount = 0;
      $boysGradeCounts = $gradeCounts;
      $girlsGradeCounts = $gradeCounts;
      $newGradeCounts = $gradeCounts;
      $allGradeCounts = $gradeCounts;

      foreach ($this->allStudents as $student) {
        $avg = $student->points;
        if ($student->txtGender === 'M') {
          $boysAvg += $avg;
          $boysCount++;
          $boysGradeCounts = $this->combineGradeCounts($boysGradeCounts, $student);
        } else {
          $girlsAvg += $avg;
          $girlsCount++;
          $girlsGradeCounts = $this->combineGradeCounts($girlsGradeCounts, $student);
        }
        if ($student->isNewSixthForm === true) {
          $newAvg += $avg;
          $newCount++;
          $newGradeCounts = $this->combineGradeCounts($newGradeCounts, $student);
        }
        $allAvg += $avg;
        $allCount++;
        $allGradeCounts = $this->combineGradeCounts($allGradeCounts, $student);
      }
      $data['year'] = $this->year;
      $data['boysAvg'] = $boysCount > 0 ? round($boysAvg / $boysCount, 2) : 0;
      $data['girlsAvg'] = $girlsCount > 0 ? round($girlsAvg / $girlsCount, 2) : 0;
      $data['allAvg'] = $allCount > 0 ? round($allAvg / $allCount, 2) : 0;
      $data['newAvg'] = $newCount > 0 ? round($newAvg / $newCount, 2) : 0;

      $gradeCounts = [];
      $gradeCounts['boys'] = $boysGradeCounts;
      $gradeCounts['girls'] = $girlsGradeCounts;
      $gradeCounts['new'] = $newGradeCounts;
      $gradeCounts['all'] = $allGradeCounts;
      $data['gradeCounts'] = $gradeCounts;

      $this->averages = $data;
      $this->history = [$data];
    }

    private function makeHistoricalData(int $year)
    {
      $this->console->publish('Getting Historical Data');
      $sessions = $this->sql->select( 'TblExamManagerCycles',
                                    'TblExamManagerCyclesID as id, intYear, intActive, intResultsActive, intFormatMonth, intFormatYear',
                                    'intYear < ? AND intFormatMonth = 6 ORDER BY intYear DESC',
                                    [$year]
                                  );
      foreach ($sessions as $session) {
          $data = $this->cache->read($session['id'], false);
          $year = $session['intYear'];
          if (!$data) {
              $this->console->error('Cached data not found for ' . $year);
              continue;
          }
          $this->console->publish('Cached data found for ' . $year);
          $statistics = $data['statistics']['data'];

          $this->history[] = $statistics['averages'] ?? null;
          //subject data
          $subjects = $statistics['subjectResults'];
          foreach ($subjects as $subject) {
            $code = $subject['subjectCode'];
            $summaryData = $subject['summaryData'];
            $summaryData['year'] = (int)$year;
            if (isset($this->subjectResults[$code])) {
              unset($summaryData['history']);
              $this->subjectResults[$code]->summaryData['history'][] = $summaryData;
            }
          }

          // house Data
          $houses= $statistics['houseResults'];
          foreach ($houses as $house) {
            $code = $house['txtHouseCode'];
            $summaryData = $house['summaryData'];
            $summaryData['year'] = (int)$year;
            if (isset($this->houseResults[$code])) {
              unset($summaryData['history']);
              $this->houseResults[$code]->summaryData['history'][] = $summaryData;
            }
          }
      }
    }

    private function makeSummaryData($results)
    {
      $this->console->replace("Generating Summary Data");
      $summaryData = array();
      $resultCount = count($results);

      $this->resultCount = $resultCount;
      $maleResults = 0;
      $femaleResults = 0;
      $totalResults = 0;
      $maleStudents = 0;
      $femaleStudents = 0;
      $totalStudents = 0;
      $this->star = 0;

      foreach($results as $result){
        if($result['txtGender'] == 'M') $maleResults++;
        if($result['txtGender'] == 'F') $femaleResults++;
        $totalResults++;
      }

      foreach($this->allStudents as $student){
        $student->txtGender == 'M' ? $maleStudents++ : $femaleStudents++;
        $totalStudents++;
      }

      unset($student);

      foreach($this->allStudents as $student){

        $studentSummaryData = $student->makeSummaryData();

        $gender = $student->txtGender;
        $gender == "M" ? $this->maleStudents[] = &$student :  $this->femaleStudents[] = &$student;

        for ($i=0; $i < count($student->summaryData); $i++) {
          if(!isset($this->summaryData[$i])) $this->summaryData[] = array('M_val' => 0, "F_val" => 0, "total_val" => 0);

          $this->summaryData[$i]['desc'] = $student->summaryData[$i]['desc'];
          $this->summaryData[$i][$gender . '_val'] += $student->summaryData[$i]['val'];
          $this->summaryData[$i]['total_val'] += $student->summaryData[$i]['val'];
          $this->summaryData[$i]['type'] = $student->summaryData[$i]['type'];

        }
      }

      //round values and make percentages if needed
      foreach($this->summaryData as &$data){

        if($data['type'] == 'Results%'){
            $data['M_val%'] = $maleResults == 0 ? 0 : 100 * $data['M_val'] / $maleResults;
            $data['F_val%'] = $femaleResults == 0 ? 0 : 100 * $data['F_val'] / $femaleResults;
            $data['total_val%'] = $totalResults == 0 ? 0 : 100 * $data['total_val'] / $totalResults;
        }

        else if($data['type'] == 'Students%'){
            $data['M_val%'] = $maleStudents == 0 ? 0 : 100 * $data['M_val'] / $maleStudents;
            $data['F_val%'] = $femaleStudents == 0 ? 0 : 100 * $data['F_val'] / $femaleStudents;
            $data['total_val%'] = $totalStudents == 0 ? 0 : 100 * $data['total_val'] / $totalStudents;
        }
        else {
            // $data['M_val%'] = null;
            // $data['F_val%'] = null;
            // $data['total_val%'] = null;
        }


        $data['M_val'] = round($data['M_val']);
        if(isset($data['M_val%'])) $data['M_val%'] = round($data['M_val%']);
        $data['F_val'] = round($data['F_val']);
        if(isset($data['F_val%'])) $data['F_val%'] = round($data['F_val%']);
        $data['total_val'] = round($data['total_val']);
        if(isset($data['total_val%'])) $data['total_val%'] = round($data['total_val%']);

      }

      $this->weightedAvgSubjectPoints = $this->makeWeightedAvgSubjectPoints();

      return $this->summaryData;
    }

    private function makeSubjectSummaryData()
    {
      foreach($this->subjectResults as &$subject){
        $subject->makeSummaryData($this->year);
      }
      //ua sort keeps keys so can later sort into alphabetical order
      usort($this->subjectResults ,'self::pointsSort');
      //usort got rid of the keys so put back : tried uasort but kept freezing
      $newSubjectArray = array();
      for ($i=0; $i < count($this->subjectResults); $i++) {
        $this->subjectResults[$i]->position = $i + 1;
        $newSubjectArray[$this->subjectResults[$i]->subjectCode] = $this->subjectResults[$i];
      }
      $this->subjectResults = $newSubjectArray;
    }

    private function makeHouseSummaryData()
    {
      foreach($this->houseResults as $house){
        $house->makeSummaryData($this->year);
      }

      //fetch one of the data arrays so that it's structure can be used
      $data = $this->houseResults['C1']->data;
      foreach($data as $typeKey => &$type){
        foreach($type as $joinKey => &$joins){
          foreach($joins as $genderKey => &$gender){
              $this->typeKey = $typeKey;
              $this->joinKey = $joinKey;
              $this->genderKey = $genderKey;
              foreach($this->houseResults as &$house){
                $house->typeKey = $typeKey;
                $house->joinKey = $joinKey;
                $house->genderKey = $genderKey;
              }

              usort($this->houseResults ,'self::pointsSortHouse');
              $newHouseArray = array();
              for ($i=0; $i < count($this->houseResults); $i++) {
                $this->houseResults[$i]->data[$typeKey][$joinKey][$genderKey]['position'] = $i + 1;
                $newHouseArray[$this->houseResults[$i]->txtHouseCode] = $this->houseResults[$i];
              }
              $this->houseResults = $newHouseArray;
          }
        }
      }

      foreach($this->houseResults as &$house){
        $house->summaryData['data'] = $house->data;
      }

    }

    // https://stackoverflow.com/questions/6053994/using-usort-in-php-with-a-class-private-function
    private static function pointsSortHouse($a, $b)
    {
     $typeKey = $a->typeKey;
     $joinKey = $a->joinKey;
     $genderKey = $a->genderKey;
     return $a->data[$typeKey][$joinKey][$genderKey]['pointsAvg'] < $b->data[$typeKey][$joinKey][$genderKey]['pointsAvg'];
    }

    private static function pointsSort($a, $b)
    {
     return $a->summaryData['gradeAverage'] < $b->summaryData['gradeAverage'];
    }

    private function makeWeightedAvgSubjectPoints()
    {
      $total = 0;
      $weight = 0;
      foreach($this->subjectResults as $subject){
        $weight += $subject->points;
        $total += count($subject->results);
      }
      $avg = $total == 0 ? 0 : $weight / $total;
      return round($avg, 2);
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
