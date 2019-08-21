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
    public $weightedAvgSubjectPoints;
    public $hasLetterGrades = false;
    public $results;
    public $totals;
    public $subjectKeys = []; // used in construcing the chord plot in relationships
    public $subjectNames = [];
    public $gradeCounts = [];

    private $error = false;

    public function __construct(\Dependency\Databases\ISams $sql, \Sockets\Console $console)
    {
       $this->sql= $sql;
       $this->console = $console; //for caching student data

    }

    public function makeStatistics(array $session, array $results, \Exams\Tools\Cache $cache = null)
    {
      $i = 0;

      $this->cache = $cache;

      $results = $this->checkForDoubleAwards($results);
      $count = count($results);
      $this->console->publish("Sorting Results $i / $count", 1);

      foreach($results as $result){
        // if($result['NCYear'] != 11 || $result['isNewSixthForm']) continue;
        // if($result['NCYear'] != 11) continue;

        $this->currentHundredResults[] = $result;

        $i++;
        if($i % 100 == 0) $this->console->replace("Sorting Results $i / $count");

        $objResult = new \Exams\Tools\GCSE\Result($result);
        $this->results[] = $objResult;
        //allows objresult to add a * to letter grade subjects
        $result['subjectCode'] = $objResult->subjectCode;

        if(!is_numeric($objResult->grade)) $this->hasLetterGrades = true;

        if($result['early']) $this->earlyResults[] = $objResult;

        $txtHouseCode = $result['txtHouseCode'];
        if(!isset($this->houseResults[$txtHouseCode])) $this->newHouse($result);
        $this->houseResults[$txtHouseCode]->setResult($objResult);

        $txtSubjectCode = $objResult->subjectCode;
        if(!isset($this->subjectResults[$txtSubjectCode])) $this->newSubject($result, $objResult);
        $this->subjectResults[$txtSubjectCode]->setResult($objResult);

        //must go after subject and houses as new student will add the student to these arrays
        $txtSchoolID = $result['txtSchoolID'];
        if(!isset($this->allStudents['s_' . $txtSchoolID])) $this->newStudent($result);
        $this->allStudents['s_' . $txtSchoolID]->setResult($objResult);

        $subjectCode = $objResult->subjectCode;
        $subjectName = $objResult->txtSubjectName;

        if (!isset($this->subjectKeys[$subjectCode])) $this->subjectKeys[$subjectCode] = $subjectCode;
        if (!isset($this->subjectNames[$subjectCode])) $this->subjectNames[$subjectCode] = $subjectName;

      }

      $year = $session['year'];
      $this->year = $year;

      $this->makeSummaryData($this->currentHundredResults);
      $this->makeSurplusScores();
      $this->makeHouseSummaryData($year);
      $this->makeSubjectSummaryData($year);
      $this->makeSubjectTotals();
      $this->makeSchoolData($year); //must come after totals
      if ($this->cache) $this->makeHistoricalData($year);


      $this->console->replace("Sorting Results $count / $count");

      ksort($this->houseResults);
      ksort($this->subjectResults);

      unset($this->console);
      unset($this->sql);
      unset($this->results);
      return $this;
    }

    private function makeSubjectSummaryData(int $year)
    {
      foreach($this->subjectResults as &$subject){
        $subject->makeSummaryData($year);
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

    private function makeSubjectTotals() {
        //AL
        $subjects = $this->subjectResults;

        $data = array(  'A*'  => 0,
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
                        'Q'   => 0,
                        'X'   => 0,
                        'F'   => 0
                      );
        $entries = 0;
        foreach($subjects as $subject){
          $data = $this->combineGradeCounts($data, $subject);
          $entries += $subject->resultCount;
        }

        $total = $entries;
        $data['entries'] = $entries;
        $data['%A*'] = $total == 0 ? 0 : round(100 * ($data['A*'] + $data['#9'] + $data['#8']) / $total);
        $data['%A*A'] = $total == 0 ? 0 : round(100 * ($data['A*'] + $data['A'] + $data['#9'] + $data['#8'] + $data['#7']) / $total);
        $data['%AB'] = $total == 0 ? 0 : round(100 * ($data['A*'] + $data['A'] + $data['B'] + $data['#9'] + $data['#8'] + $data['#7'] + $data['#6'] + $data['#5']) / $total);
        $data['%AC'] = $total == 0 ? 0 : round(100 * ($data['A*'] + $data['A'] + $data['B'] + $data['C'] + $data['#9'] + $data['#8'] + $data['#7'] + $data['#6'] + $data['#5']) / $total);
        $data['%Pass'] = $total == 0 ? 0 : round(100 * ($data['A*'] + $data['A'] + $data['B'] + $data['C'] + $data['#9'] + $data['#8'] + $data['#7'] + $data['#6'] + $data['#5'] ) / $total);

        $this->totals = $data;
    }

    private function makeHouseSummaryData(int $year)
    {
      foreach($this->houseResults as $house){
        $house->makeSummaryData($year);
      }
      usort($this->houseResults ,'self::pointsSort');
      $newHouseArray = array();
      for ($i=0; $i < count($this->houseResults); $i++) {
        $this->houseResults[$i]->position = $i + 1;
        $newHouseArray[$this->houseResults[$i]->txtHouseCode] = $this->houseResults[$i];
      }
      $this->houseResults = $newHouseArray;
    }
    // https://stackoverflow.com/questions/6053994/using-usort-in-php-with-a-class-private-function
    private static function pointsSort($a, $b)
    {
     return $a->summaryData['gradeAverage'] < $b->summaryData['gradeAverage'];
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

      $gradeCounts = [        'A*'  => 0,
                              'A'   => 0,
                              'B'   => 0,
                              'C'   => 0,
                              'D'   => 0,
                              'E'   => 0,
                              'U'   => 0,
                              '9'  => 0,
                              '8'  => 0,
                              '7'  => 0,
                              '6'  => 0,
                              '5'  => 0,
                              '4'  => 0,
                              '3'  => 0,
                              '2'  => 0,
                              '1'  => 0,
                              'Q'   => 0,
                              'F'   => 0,
                              'X'   => 0
                            ];

      foreach($gradeCounts as $grade => &$value){
        $value = ['grade' => $grade, 'boys'=> 0, 'girls' => 0, 'all' => 0];
      }

      $count = 0;
      foreach($this->subjectResults as $subject){
        foreach($subject->results as $result){
          if ($result->NCYear !== 11) continue;
          $grade = $result->grade;
          $gender = $result->txtGender === 'M' ? 'boys' : 'girls';
          $gradeCounts[$grade][$gender]++;
          $gradeCounts[$grade]['all']++;
          $count++;
        }
      }

      $this->gradeCounts = $gradeCounts;

      // $sD = [];
      // $g9 = $gradeCounts['#9']['all'];
      // $sD['count'] = $count;
      // $sD['%g9'] = round(100 * $Astar / $count);
      //
      // $As = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'];
      // $sD['%As'] = round(100 * $As / $count);
      //
      // $ABs = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'];
      // $sD['%ABs'] = round(100 * $ABs / $count);
      //
      // $AC = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'] + $gradeCounts['C']['all'];
      // $sD['%ABCs'] = round(100 * $AC / $count);
      //
      // $AD = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'] + $gradeCounts['C']['all'] + $gradeCounts['D']['all'];
      // $sD['%ABCDs'] = round(100 * $AD / $count);
      //
      // $AE = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'] + $gradeCounts['C']['all'] + $gradeCounts['D']['all'] + $gradeCounts['E']['all'];
      // $sD['%ABCDEs'] = round(100 * $AE / $count);
      //
      //
      // $count = $countPU;
      // $D1D2 = $gradeCounts['D1']['all'] + $gradeCounts['D2']['all'];
      // $sD['countPU'] = $count;
      // $sD['%D1D2'] = round(100 * $D1D2 / $count);
      //
      // $D1D3 = $D1D2 + $gradeCounts['D3']['all'];
      // $sD['%D1D3'] = round(100 * $D1D3 / $count);
      //
      // $D1M2 = $D1D3 + $gradeCounts['M1']['all'] + $gradeCounts['M2']['all'];
      // $sD['%D1M2'] = round(100 * $D1M2 / $count);
      //
      // $D1M3 = $D1M2 + $gradeCounts['M3']['all'];
      // $sD['%D1M3'] = round(100 * $D1M3 / $count);
      //
      // $D1P2 = $D1M3 + $gradeCounts['P1']['all'] + $gradeCounts['P2']['all'];
      // $sD['%D1P2'] = round(100 * $D1P2 / $count);
      //
      // $D1P3 = $D1P2 + $gradeCounts['P3']['all'];
      // $sD['%D1P3'] = round(100 * $D1P3 / $count);
      //
      // // combined percentages
      // $count = $countPU + $countAL;
      // $sD['L1'] = round(100 * ($Astar + $D1D2) / $count );
      // $sD['L2'] = round(100 * ($As + $D1D3) / $count );
      // $sD['L3'] = round(100 * ($ABs + $D1M2) / $count );
      // $sD['L4'] = round(100 * ($AC + $D1M3) / $count );
      // $sD['L5'] = round(100 * ($AD + $D1P2) / $count );
      // $sD['L6'] = round(100 * ($AE + $D1P3) / $count );
      //
      // $ranges = [];
      // $ranges['L1'] = ['A*', $sD['%Astar'], 'D1-D2', $sD['%D1D2'], $sD['L1']];
      // $ranges['L2'] = ['A*-A', $sD['%As'], 'D1-D3', $sD['%D1D3'], $sD['L2']];
      // $ranges['L3'] = ['A*-B', $sD['%ABs'], 'D1-M2', $sD['%D1M2'], $sD['L3']];
      // $ranges['L4'] = ['A*-C', $sD['%ABCs'], 'D1-M3', $sD['%D1M3'], $sD['L4']];
      // $ranges['L5'] = ['A*-D', $sD['%ABCDs'], 'D1-P2', $sD['%D1P2'], $sD['L5']];
      // $ranges['L6'] = ['A*-E', $sD['%ABCDEs'], 'D1-P3', $sD['%D1P3'], $sD['L6']];
      //
      //
      // $this->ranges = $ranges;

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

    private function makeSchoolData()
    {
      $this->console->publish('Making School Summary Data');
      $data = [];
      $gradeCounts = array(  'A*'  => 0,
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
                                    'Q'   => 0,
                                    'X'   => 0,
                                    'F'   => 0,
                                  );
      $boysAvg = $girlsAvg = $allAvg = $newAvg = 0;
      $boysCount = $girlsCount = $allCount = $newCount = 0;
      $boysResultsCount = $girlsResultsCount = $allResultsCount = $newResultsCount = 0;

      $boysGradeCounts = $gradeCounts;
      $girlsGradeCounts = $gradeCounts;
      $allGradeCounts = $gradeCounts;
      $boys = [];
      $girls = [];

      foreach ($this->results as $result) {
        if ($result->NCYear !== 11) continue;

        $avg = $result->points;

        if ($result->txtGender === 'M') {
          $boysAvg += $avg;
          $boysResultsCount++;
          $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
          $boysGradeCounts[$grade]++;
          $boys['s_' . $result->txtSchoolID] = true;
        } else {
          $girlsAvg += $avg;
          $girlsResultsCount++;
          $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
          $girlsGradeCounts[$grade]++;
          $girls['s_' . $result->txtSchoolID] = true;
        }
        $allAvg += $avg;
        $allResultsCount++;
        $grade = is_numeric($result->grade) ? "#" . $result->grade : $result->grade;
        $allGradeCounts[$grade]++;
      }

      $boysCount = count($boys);
      $girlsCount = count($girls);
      $allCount = $boysCount + $girlsCount;
      $avgResultsPerBoy = $boysCount == 0 ? 0 : $boysResultsCount / $boysCount;
      $avgResultsPerGirl = $girlsCount == 0 ? 0 : $girlsResultsCount / $girlsCount;
      $avgResultsPerAll = $allCount == 0 ? 0 : $allResultsCount / $allCount;

      $data['year'] = $this->year;
      $data['boysAvg'] = $boysCount > 0 ? round($boysAvg / $boysCount, 2) : 0;
      $data['girlsAvg'] = $girlsCount > 0 ? round($girlsAvg / $girlsCount, 2) : 0;
      $data['allAvg'] = $allCount > 0 ? round($allAvg / $allCount, 2) : 0;

      $data['boysAvgPerExam'] = $boysCount > 0 ? round($boysAvg / ($boysCount * $avgResultsPerBoy), 2) : 0;
      $data['girlsAvgPerExam'] = $girlsCount > 0 ? round($girlsAvg / ($girlsCount * $avgResultsPerGirl), 2) : 0;
      $data['allAvgPerExam'] = $allCount > 0 ? round($allAvg / ($allCount * $avgResultsPerAll), 2) : 0;


      $gradeCounts = [];
      $gradeCounts['boys'] = $boysGradeCounts;
      $gradeCounts['girls'] = $girlsGradeCounts;
      $gradeCounts['all'] = $allGradeCounts;
      $data['gradeCounts'] = $gradeCounts;

      $g = $allGradeCounts;
      $sD = [];
      if ($allResultsCount > 0) {
        $sD['%9'] = round(100 *  $g['#9'] / $allResultsCount);
        $sD['%98'] = round(100 * ($g['#9'] + $g['#8'] + $g['A*']) / $allResultsCount);
        $sD['%97'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['A*'] + $g['A']) / $allResultsCount);
        $sD['%96'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6']) / $allResultsCount);
        $sD['%95'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['A*'] + $g['A'] + $g['B'] ) / $allResultsCount);
        $sD['%94'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['A*'] + $g['A'] + $g['B'] + $g['C']) / $allResultsCount);
        $sD['%93'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3']  + $g['A*'] + $g['A'] + $g['B'] + $g['C'] + $g['D']) / $allResultsCount);
        $sD['%92'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['#2']  + $g['A*'] + $g['A'] + $g['B'] + $g['C'] + $g['D'] + $g['E']) / $allResultsCount);
        $sD['%91'] = round(100 * ($g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['#2'] + $g['#1']) / $allResultsCount);
      }

      $data['ranges'] = $sD;

      $data['totals'] = $this->totals;
      $this->averages = $data;
      $this->history = [$data];
      $this->historyKeys = ['y_' . $this->year];
    }

    private function makeSurplusScores()
    {
      $this->console->publish("Marking surplus scores..");

      //calculate the subject surplus for each student (the distance of each subject from that students mean points [not including the subject being studied])
      foreach($this->allStudents as &$student){
        $subjects = $student->subjects;

        foreach($student->subjects as &$subject){
          $cunt = 0;
          $total = 0;
          foreach($subjects as $subject2){
            if ($subject->subjectCode !== $subject2->subjectCode)
            {
              $cunt++;
              $total += ($subject->points - $subject2->points);
            }
          }
          $avg = $cunt === 0 ? 0 : $total / $cunt;
          $subject->surplus = $avg / 1; //1 points in a grade
        }
      }


      unset($student);
      unset($subject);

      foreach($this->subjectResults as &$subject){
        $code = $subject->subjectCode;
        $count = 0;
        $subject->surplus = 0;
        foreach($this->allStudents as $student){
          if (isset($student->subjects[$code])){
            $count++;
            $subject->surplus += $student->subjects[$code]->surplus;
          }
        }
        $subject->surplus = $count == 0 ? 0 : round($subject->surplus / $count, 2);
      }

      unset($student);
      unset($subject);

      //round the student's individual surplus scores now that they have been xml_set_unparsed_entity_decl_handler
      foreach($this->allStudents as &$student){
        foreach($student->subjects as &$subject){
          $subject->surplus = round($subject->surplus, 2);
        }
      }
      $this->console->publish('Done');
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

    private function makeHistoricalData(int $year)
    {
      $this->console->publish('Getting Historical Data');
      $sessions = $this->sql->select( 'TblExamManagerCycles',
                                    'TblExamManagerCyclesID as id, intYear, intActive, intResultsActive, intFormatMonth, intFormatYear',
                                    'intYear < ? AND intFormatMonth = 6 ORDER BY intYear DESC',
                                    [$year]
                                  );
      foreach ($sessions as $session) {
          $data = $this->cache->read($session['id'], true);
          $year = $session['intYear'];
          if (!$data) {
              $this->console->error('Cached data not found for ' . $year);
              continue;
          }
          if(!isset($data['statistics']['hundredStats'])) continue;
          $this->console->publish('Cached data found for ' . $year);

          $statistics = $data['statistics']['hundredStats'];


          $this->history[] = $statistics['averages'] ?? null;
          $this->historyKeys['y_' . $year] = $statistics['averages'] ?? null;
          //subject data
          $subjects = $statistics['subjectResults'];
          foreach ($subjects as $key => $subject) {
            $code = $subject['subjectCode'];
            $summaryData = $subject['summaryData'];
            $summaryData['year'] = (int)$year;
            if (isset($this->subjectResults[$key])) {
              unset($summaryData['history']);
              unset($summaryData['historyKeys']);
              $this->subjectResults[$key]->summaryData['history'][] = $summaryData;
              $this->subjectResults[$key]->summaryData['historyKeys']['y_' . $year] = $summaryData;
            } else {
              $keyOld = $key;
              $key = str_replace(".","",$key);
              if (isset($this->subjectResults[$key])) { //must have changes grade here so look for Letter Grade version.
                unset($summaryData['history']);
                unset($summaryData['historyKeys']);
                $this->subjectResults[$key]->summaryData['history'][] = $summaryData;
                $this->subjectResults[$key]->summaryData['historyKeys']['y_' . $year] = $summaryData;
              }
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
              unset($summaryData['historyKeys']);
              $this->houseResults[$code]->summaryData['history'][] = $summaryData;
              $this->houseResults[$code]->summaryData['historyKeys']['y_' . $year] = $summaryData;
            }
          }
      }
    }

    private function checkForDoubleAwards(array $results)
    {
      $this->console->publish('Processing double awards');
      $newResults = array();
      foreach($results as $result){
        //Double Science Award
        if($this->contains("double", $result['txtOptionTitle']) && $result['subjectCode'] = "SC"){
          //create two copies and extract grades
          $end = '';
          // is_numeric($result['grade']) ? '' : '.';
          $sci1 = $result;
          $sci2 = $result;
          $sci2['id'] = $sci2['id'] . '.2';
          $sci1['subjectCode'] = "S1$end";
          $sci2['subjectCode'] = "S2$end";
          $sci1['subjectName'] = "Science 1$end";
          $sci2['subjectName'] = "Science 2$end";
          // $sci1['grade'] = $result['grade'][0];
          $sci1['grade'] = substr($result['grade'],0,1);
          // if(!$result['grade'][1]) echo '---'.$result['grade'].'---';
          substr($result['grade'],0,1);
          $sci2['grade'] = substr($result['grade'],1,1) ? substr($result['grade'],1,1) : $result['grade'][0];
          // $sci2['grade'] = isset($result['grade'][1]) ? $result['grade'][1] : $result['grade'][0];
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
      $student = new \Exams\Tools\GCSE\Student($result);
      $key = 's_' . $result['txtSchoolID'];
      $this->allStudents[$key] = &$student;

      $this->houseResults[$result['txtHouseCode']]->setStudent($student);
      $this->subjectResults[$result['subjectCode']]->setStudent($student);

      return $student;
    }

    private function newHouse($result)
    {
      $house = new \Exams\Tools\GCSE\House($result);
      $key = $result['txtHouseCode'];
      $this->houseResults[$key] = $house;
      return $house;
    }

    private function newSubject($result, $objResult)
    {
      $subject = new \Exams\Tools\GCSE\Subject($result);
      $subject->subjectName = $objResult->txtSubjectName;
      $key = $objResult->subjectCode;
      $this->subjectResults[$key] = $subject;
      return $subject;
    }

    function contains($needle, $haystack)
    {
        return stripos($haystack, $needle) !== false;
    }

    // https://stackoverflow.com/questions/6086267/how-to-merge-two-arrays-by-summing-the-merged-values
    private function combineGradeCounts($gradeCounts, $donor)
    {
      $a1 = $gradeCounts;
      $a2 = $donor->gradeCounts ?? $donor;
      $sums = array();
      foreach (array_keys($a1 + $a2) as $key) {
          $sums[$key] = @($a1[$key] + $a2[$key]);
      }
      return $sums;
    }



}
