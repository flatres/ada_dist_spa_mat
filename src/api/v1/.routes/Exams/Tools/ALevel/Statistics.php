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
    public $subjectResults = [
      'A'   => [],
      'AS'  => [],
      'PreU'=> [],
      'EPQ' => [],
      'unknown' => []
    ]; //key s_{subjectCode}
    public $upperIntake = array(); //key s_{txtSchoolID}
    public $lowerIntake = array(); //key s_{txtSchoolID}
    public $maleStudents = array(); //key s_{txtSchoolID}
    public $femaleStudents = array();
    public $summaryData = array();
    public $averages = [];
    public $earlyResults = array();
    public $year;
    public $relationships;

    private $error = false;
    private $typeKey;
    private $joinKey;
    private $genderKey;
    private $sql;
    private $console;
    public $moduleResults;
    public $subjectKeys = []; // used in construcing the chord plot in relationships
    public $subjectNames = [];

    public function __construct(\Dependency\Databases\ISams $sql, \Sockets\Console $console, $moduleResults)
    {
       $this->sql= $sql;
       $this->console = $console; //for caching student data
       $this->console->publish("Building Statistics");
       $this->moduleResults = $moduleResults;

    }

    public function makeStatistics(array $session, array &$results, \Exams\Tools\Cache $cache)
    {
      $i = 0;
      $this->isGCSE = false;
      $this->cache = $cache;

      $results = $this->checkForDoubleAwards($results);
      $count = count($results);
      $this->console->publish("Sorting Results $i / $count", 1);

      foreach($results as &$result){
        // if($result['NCYear'] > 13) continue;

        $i++;
        if($i % 100 == 0) $this->console->replace("Sorting Results $i / $count");

        switch($result['txtLevel']) {
            case 'A'  : $level = 'A'; break;
            case 'ASB': $level = 'AS'; break;
            case 'FC': $level = 'PreU'; break;
            case 'B' : $level = 'EPQ'; break;
            default: $level = 'unknown';
        }

        $result['level'] = $level;

        $objResult = new \Exams\Tools\ALevel\Result($result);

        if($result['early']) $this->earlyResults[] = $objResult;

        $txtHouseCode = $result['txtHouseCode'];
        if(!isset($this->houseResults[$txtHouseCode])) $this->newHouse($result);
        $this->houseResults[$txtHouseCode]->setResult($objResult);

        $subjectCode = $result['subjectCode'];
        $subjectName = $result['subjectName'];
        if (!isset($this->subjectKeys[$subjectCode])) $this->subjectKeys[$subjectCode] = $subjectCode;
        if (!isset($this->subjectNames[$subjectCode])) $this->subjectNames[$subjectCode] = $subjectName;

        if(!isset($this->subjectResults[$objResult->level][$subjectCode])) $this->newSubject($result);
        $this->subjectResults[$objResult->level][$subjectCode]->setResult($objResult);

        //must go after subject and houses as new student will add the student to these arrays
        $txtSchoolID = $result['txtSchoolID'];
        if(!isset($this->allStudents['s_' . $txtSchoolID])) $this->newStudent($result);
        $this->allStudents['s_' . $txtSchoolID]->setResult($objResult);

      }
      $year = $session['year'];
      $this->year = $year;
      $this->processModules();
      // $this->console->error("MODULES DISABLED", 1);
      $this->makeSummaryData($results);
      $this->makeSurplusScores();
      $this->makeHouseSummaryData();
      $this->makeSubjectSummaryData($year);
      $this->makeSchoolData($year);
      $this->processGender();


      $this->makeHistoricalData($year);
      // $this->makeRelationshipData()


      $this->console->replace("Sorting Results $count / $count");
      ksort($this->houseResults);
      ksort($this->subjectKeys);
      ksort($this->subjectNames);
      foreach($this->subjectResults as $level){
          ksort($level);
      }

      unset($this->console);
      unset($this->sql);
      unset($this->results);
      unset($this->moduleResults);
      return $this;
    }

    private function makeSurplusScores()
    {
      $this->console->publish("Marking surplus scores..");

      //calculate the subject surplus for each student (the distance of each subject from that students mean points [not including the subject being studied])
      foreach($this->allStudents as &$student){
        $subjects = $student->subjects;
        // $avg = $student->ucasAverage;
        foreach($student->subjects as &$subject){
          $cunt = 0;
          $total = 0;
          foreach($subjects as $subject2){
            //when calculating for a non-epq subject, don't include the EPQ
            if ($subject2->level === 'A' || $subject2->level === 'PreU' || $subject->level === 'EPQ') {
              if ($subject->subjectCode !== $subject2->subjectCode)
              {
                $cunt++;
                $total += ($subject->ucasPoints - $subject2->ucasPoints);
              }
            }
          }
          $avg = $cunt === 0 ? 0 : $total / $cunt;
          $subject->surplus = $avg / 8; //8 points in a ucase grade
        }
      }

      foreach($this->subjectResults['A'] as &$subject){
        $code = $subject->subjectCode;
        $count = 0;
        $subject->surplus = 0;
        foreach($this->allStudents as $student){
          if (isset($student->subjects[$code])){
            if ($student->subjects[$code]->level === 'A') {
              $count++;
              $subject->surplus += $student->subjects[$code]->surplus;
            }
          }
        }
        $subject->surplus = $count == 0 ? 0 : round($subject->surplus / $count, 2);
      }

      foreach($this->subjectResults['PreU'] as &$subject){
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

    private function processModules()
    {
      $count = count($this->moduleResults);
      $i = 0;
      foreach ($this->moduleResults as &$moduleResult){


        if($i % 100 == 0) $this->console->replace("Module results: $i / $count");
        //try to determine the subject code
        $objSubject = new \Exams\Tools\SubjectCodes($moduleResult['txtModuleCode'], $moduleResult['txtOptionTitle'], $this->sql, $moduleResult['txtLevel'], true);

        switch($moduleResult['txtLevel']) {
            case 'A'  : $level = 'A'; break;
            case 'ASB': $level = 'AS'; break;
            case 'FC': $level = 'PreU'; break;
            case 'B' : $level = 'EPQ'; break;
            default: $level = 'unknown';
        }
        $level = $objSubject->subjectCode === 'EPQ' ? 'EPQ' : 'A';
        $moduleResult['level'] = $level;

        if($objSubject->subjectCode == '-') {
          // $this->console->error('!!WARNING!! Couldnt match subject code to ' . $objSubject->txtOptionTitle);
          // $this->error = true;
          continue;
        }
        if (!isset($this->subjectResults[$level][$objSubject->subjectCode])){
          // $this->console->error('!!WARNING!! Could not find subject ('.$level.')' . $objSubject->txtOptionTitle . 'with code ' . $objSubject->subjectCode);
          continue;
        }
        $i++;
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
        $this->subjectResults[$level][$objSubject->subjectCode]->setModuleResult($moduleResult);
      }
      $this->console->replace("Module results: $i / $count");
      $this->console->publish("Sorting Module Results...", 1);
      //sort results
      foreach($this->subjectResults as &$subjectLevel){
        foreach($subjectLevel as &$subject){
          foreach($subject->modules as &$module){
            $module->sortResults();
          }
        }
      }
      unset($this->moduleResults);
    }

    private function processGender()
    {
      $data = [];
      $data[] = $this->getGenderData('Boys Shell Entrants', 'LS', 'boys');
      $data[] = $this->getGenderData('Boys L6 Entrants', 'NL6', 'boys');
      $data[] = $this->getGenderData('Girls Shell Entrants', 'LS', 'girls');
      $data[] = $this->getGenderData('Girls L6 Entrants', 'NL6', 'girls');
      $data[] = $this->getGenderData('All Shell Entrants', 'LS', 'all');
      $data[] = $this->getGenderData('All L6 Entrants', 'NL6', 'all');
      $data[] = $this->getGenderData('All Boys', 'all', 'boys');
      $data[] = $this->getGenderData('All Girls', 'all', 'girls');
      $data[] = $this->getGenderData('All', 'all', 'all');
      $this->summaryData['gender'] = $data;
    }

    private function getGenderData($title, $intake, $gender)
    {
      $data = ['title' => $title];
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
      $count = 0;
      $points = 0;
      $ucas = 0;
      $passes = 0;
      $Astar = 0;
      $As = 0;
      foreach ($this->houseResults as $house)
      {
        $stats = $house->summaryData['data']['U6'][$intake][$gender];
        $gC = $stats['gradeCounts'];
        $gradeCounts = $this->combineGradeCounts($gradeCounts, $gC);
        $count += $stats['results'];
        $points += $stats['points'];
        $passes += $stats['passes'];
        $ucas += $stats['ucasPoints'];
        $Astar += $gC['A*'] + $gC['D1'] + $gC['D2'];
        $As += $gC['A*'] + $gC['A']+ $gC['D1'] + $gC['D2'] + $gC['D3'];
      }
      $pointsAvg = $count == 0 ? 0 : round($points / $count, 1);
      $ucasAvg = $count == 0 ? 0 : round($ucas / $count, 1);
      $data = array_merge($data, $gradeCounts);
      $data['%Astar'] = $count == 0 ? 0 : round(100 * $Astar / $count);
      $data['%As'] = $count == 0 ? 0 : round(100 * $As / $count);

      $data['%pass'] = $count == 0 ? 0 : round(100 * $passes / $count);
      $data['gradeAvg'] = $pointsAvg;
      $data['ucasAvg'] = $ucasAvg;


      return $data;

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

    //creates an object used to display a chord dragram - giving an overview of what subject were done together
    private function makeRelationshipData(){
        $this->console->publish("Making relationship data...", 1);
        $data = [];
        //set up the initial object
        foreach ($this->subjectKeys as $subjectFrom) {
          foreach ($this->subjectKeys as $subjectTo) {
            $key = $subjectFrom . '_' . $subjectTo;
            $data[$key] = [
              'from'  => $subjectFrom,
              'to'    => $subjectTo,
              'value' => 0
            ];
          }
        }
        //cycle through all results
        foreach ($this->allStudents as $student){
          foreach ($student->results as $resultFrom) {
            foreach ($student->results as $resultTo) {
              if ($resultFrom->level === 'AS' || $resultTo->level === 'AS') continue;
              if ($resultFrom->subjectCode === $resultTo->subjectCode) continue;
              $key = $resultFrom->subjectCode . '_' . $resultTo->subjectCode;
              if (isset($data[$key])) {
                $data[$key]['value']++;
              }
            }
          }
        }
        // get rid of duplicates
        $track = []; //keep track otherwise everything will be deleted and we only want duplicates deleted.
        foreach($data as $rel){
          $reverseKey = $rel['to'] . '_' . $rel['from'];
          if (!isset($track[$rel['from']])) {
            unset($data[$reverseKey]);
            $track[$rel['to']] = true;
          }
        }
        // get rid of zeroes
        foreach($data as $rel) {
          if ($rel['value'] === 0) {
            $key = $rel['from'] . '_' . $rel['to'];
            unset($data[$key]);
          }
        }

        $this->relationships = array_values($data);

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
      $boysResultsCount = $girlsResultsCount = $allResultsCount = $newResultsCount = 0;

      $boysGradeCounts = $gradeCounts;
      $girlsGradeCounts = $gradeCounts;
      $newGradeCounts = $gradeCounts;
      $allGradeCounts = $gradeCounts;

      foreach ($this->allStudents as $student) {
        $avg = $student->points;
        if ($student->txtGender === 'M') {
          $boysAvg += $avg;
          $boysCount++;
          $boysResultsCount += $student->resultCount;
          $boysGradeCounts = $this->combineGradeCounts($boysGradeCounts, $student);
        } else {
          $girlsAvg += $avg;
          $girlsCount++;
          $girlsResultsCount += $student->resultCount;
          $girlsGradeCounts = $this->combineGradeCounts($girlsGradeCounts, $student);
        }
        if ($student->isNewSixthForm === true) {
          $newAvg += $avg;
          $newCount++;
          $newResultsCount += $student->resultCount;
          $newGradeCounts = $this->combineGradeCounts($newGradeCounts, $student);
        }
        $allAvg += $avg;
        $allCount++;
        $allResultsCount += $student->resultCount;
        $allGradeCounts = $this->combineGradeCounts($allGradeCounts, $student);
      }
      $data['year'] = $this->year;
      $data['boysAvg'] = $boysCount > 0 ? round($boysAvg / $boysCount, 2) : 0;
      $data['girlsAvg'] = $girlsCount > 0 ? round($girlsAvg / $girlsCount, 2) : 0;
      $data['allAvg'] = $allCount > 0 ? round($allAvg / $allCount, 2) : 0;
      $data['newAvg'] = $newCount > 0 ? round($newAvg / $newCount, 2) : 0;

      $data['boysAvgPerExam'] = $boysCount > 0 ? round($boysAvg / $boysResultsCount, 2) : 0;
      $data['girlsAvgPerExam'] = $girlsCount > 0 ? round($girlsAvg / $girlsResultsCount, 2) : 0;
      $data['allAvgPerExam'] = $allCount > 0 ? round($allAvg / $allResultsCount, 2) : 0;
      $data['newAvgPerExam'] = $newCount > 0 ? round($newAvg / $newResultsCount, 2) : 0;


      $gradeCounts = [];
      $gradeCounts['boys'] = $boysGradeCounts;
      $gradeCounts['girls'] = $girlsGradeCounts;
      $gradeCounts['new'] = $newGradeCounts;
      $gradeCounts['all'] = $allGradeCounts;
      $data['gradeCounts'] = $gradeCounts;

      $this->averages = $data;
      $this->history = [$data];
      $this->historyKeys = ['y_' . $this->year];
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
          $this->historyKeys['y_' . $year] = $statistics['averages'] ?? null;
          //subject data
          $subjects = $statistics['subjectResults'];
          foreach ($subjects as $key => $level) {
            foreach ($level as $subject) {
              $code = $subject['subjectCode'];
              $summaryData = $subject['summaryData'];
              $summaryData['year'] = (int)$year;

              if (isset($this->subjectResults[$key][$code])) {
                unset($summaryData['history']);
                unset($summaryData['historyKeys']);
                $this->subjectResults[$key][$code]->summaryData['history'][] = $summaryData;
                $this->subjectResults[$key][$code]->summaryData['historyKeys']['y_' . $year] = $summaryData;
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

    private function makeSummaryData($results)
    {
      $this->console->replace("Generating Summary Data");
      $summaryData = array();
      $countAL = $countPU = 0;

      $gradeCounts = [        'A*'  => 0,
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

      foreach($this->allStudents as $student){

        $studentSummaryData = $student->makeSummaryData();

      }

      foreach($gradeCounts as $grade => &$value){
        $value = ['grade' => $grade, 'boys'=> 0, 'girls' => 0, 'all' => 0];
      }

      foreach($this->subjectResults['A'] as $subject){
        foreach($subject->results as $result){
          $grade = $result->grade;
          $gender = $result->txtGender === 'M' ? 'boys' : 'girls';
          $gradeCounts[$grade][$gender]++;
          $gradeCounts[$grade]['all']++;
          $countAL++;
        }
      }

      foreach($this->subjectResults['PreU'] as $subject){
        foreach($subject->results as $result){
          $grade = $result->grade;
          $gender = $result->txtGender === 'M' ? 'boys' : 'girls';
          $gradeCounts[$grade][$gender]++;
          $gradeCounts[$grade]['all']++;
          $countPU++;
        }
      }

      $sD = [];
      $count = $countAL;
      $Astar = $gradeCounts['A*']['all'];
      $sD['countAL'] = $count;
      $sD['%Astar'] = round(100 * $Astar / $count);

      $As = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'];
      $sD['%As'] = round(100 * $As / $count);

      $ABs = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'];
      $sD['%ABs'] = round(100 * $ABs / $count);

      $AC = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'] + $gradeCounts['C']['all'];
      $sD['%ABCs'] = round(100 * $AC / $count);

      $AD = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'] + $gradeCounts['C']['all'] + $gradeCounts['D']['all'];
      $sD['%ABCDs'] = round(100 * $AD / $count);

      $AE = $gradeCounts['A*']['all'] + $gradeCounts['A']['all'] + $gradeCounts['B']['all'] + $gradeCounts['C']['all'] + $gradeCounts['D']['all'] + $gradeCounts['E']['all'];
      $sD['%ABCDEs'] = round(100 * $AE / $count);


      $count = $countPU;
      $D1D2 = $gradeCounts['D1']['all'] + $gradeCounts['D2']['all'];
      $sD['countPU'] = $count;
      $sD['%D1D2'] = round(100 * $D1D2 / $count);

      $D1D3 = $D1D2 + $gradeCounts['D3']['all'];
      $sD['%D1D3'] = round(100 * $D1D3 / $count);

      $D1M2 = $D1D3 + $gradeCounts['M1']['all'] + $gradeCounts['M2']['all'];
      $sD['%D1M2'] = round(100 * $D1M2 / $count);

      $D1M3 = $D1M2 + $gradeCounts['M3']['all'];
      $sD['%D1M3'] = round(100 * $D1M3 / $count);

      $D1P2 = $D1M3 + $gradeCounts['P1']['all'] + $gradeCounts['P2']['all'];
      $sD['%D1P2'] = round(100 * $D1P2 / $count);

      $D1P3 = $D1P2 + $gradeCounts['P3']['all'];
      $sD['%D1P3'] = round(100 * $D1P3 / $count);

      // combined percentages
      $count = $countPU + $countAL;
      $sD['L1'] = round(100 * ($Astar + $D1D2) / $count );
      $sD['L2'] = round(100 * ($As + $D1D3) / $count );
      $sD['L3'] = round(100 * ($ABs + $D1M2) / $count );
      $sD['L4'] = round(100 * ($AC + $D1M3) / $count );
      $sD['L5'] = round(100 * ($AD + $D1P2) / $count );
      $sD['L6'] = round(100 * ($AE + $D1P3) / $count );

      $ranges = [];
      $ranges['L1'] = ['A*', $sD['%Astar'], 'D1-D2', $sD['%D1D2'], $sD['L1']];
      $ranges['L2'] = ['A*-A', $sD['%As'], 'D1-D3', $sD['%D1D3'], $sD['L2']];
      $ranges['L3'] = ['A*-B', $sD['%ABs'], 'D1-M2', $sD['%D1M2'], $sD['L3']];
      $ranges['L4'] = ['A*-D', $sD['%ABCs'], 'D1-M3', $sD['%D1M3'], $sD['L4']];
      $ranges['L5'] = ['A*-D', $sD['%ABCDs'], 'D1-P2', $sD['%D1P2'], $sD['L5']];
      $ranges['L6'] = ['A*-E', $sD['%ABCDEs'], 'D1-P3', $sD['%D1P3'], $sD['L6']];

      // ksort($gradeCounts);
      $this->summaryData['gradeCounts'] = $gradeCounts;
      $this->summaryData['ranges'] = $ranges;
      return $this->summaryData;
    }

    private function makeSubjectSummaryData()
    {
      foreach($this->subjectResults as &$level){
        foreach($level as &$subject){
          $subject->makeSummaryData($this->year);
        }
      }

      foreach($this->subjectResults as &$level){
        //ua sort keeps keys so can later sort into alphabetical order
        usort($level ,'self::pointsSort');
        //usort got rid of the keys so put back : tried uasort but kept freezing
        $newSubjectArray = array();
        for ($i=0; $i < count($level); $i++) {
          $level[$i]->position = $i + 1;
          $newSubjectArray[$level[$i]->subjectCode] = $level[$i];
        }
        $level = $newSubjectArray;
      }
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
      $this->subjectResults[$result['level']][$result['subjectCode']]->setStudent($student);

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
      $this->subjectResults[$result['level']][$key] = $subject;
      return $subject;
    }

    function contains($needle, $haystack)
    {
        return stripos($haystack, $needle) !== false;
    }


}
