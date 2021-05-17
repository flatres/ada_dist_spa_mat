<?php

/**
 * Description

 * Usage:

 */
namespace DHA;

class Wyaps
{
    protected $container;
    private $ada, $adaData, $adaModules, $sql, $cache;
    private $studentData = [];
    private $console;
    private $sessionAcacdemicYear = 2020;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaData = $container->adaData;
       $this->adaModules = $container->adaModules;
       $this->sql= $container->isams;
       $this->cache = new \Exams\Tools\Cache($container);
    }

// ROUTE -----------------------------------------------------------------------------
    public function yearWyapsGet($request, $response, $args)
    {
      $year = $args['year'];
      $table = $year < 12 ? 'tag_results_gcse' : 'tag_results_alevel';
      $year = new \Entities\Academic\Year($year);

      $year->getWyaps(true);
      $exams = [];
      foreach($year->subjects as $s) {
        foreach ($s->exams as &$e) {
          $e->megCount = 0;
          $e->tagCount = 0;
          $results = $this->adaData->select($table, 'tag, meg', 'examId=?', [$e->id]);
          foreach($results as $r) {
            if ($r['tag']) $e->tagCount++;
            if ($r['meg']) $e->megCount++;
          }
          $exams[] = $e;
        }
      }
      return emit($response, $exams);
    }

    public function statisticsSimulate($request, $response, $args)
    {
      $year = $args['year'];
      $yearInt = $year;
      $table = $year < 12 ? 'tag_results_gcse' : 'tag_results_alevel';
      $year = new \Entities\Academic\Year($year);

      $year->getWyaps(true);
      $exams = [];
      foreach($year->subjects as $subject) {
        $grades = [];
        if ($yearInt < 12) {
          $grades = [9, 8, 7, 6, 5, 4, 3, 2, 1, 'U'];
        } else {
          $this->isPreU = $subject->isPreU;
          if ($subject->name == 'Italian') $this->isPreU = false; //has gone back to A level
          if ($subject->name == 'Russian') $this->isPreU = true;
          if ($subject->name == 'Spanish') $this->isPreU = true;
          if ($subject->name == 'French') $this->isPreU = true;
          if ($subject->name == 'Chinese') $this->isPreU = true;
          if ($subject->name == 'German') $this->isPreU = true;
          if ($subject->name == 'History') $this->isPreU = true;
          if ($subject->name == 'English') $this->isPreU = true;
          if ($subject->name == 'Philosophy') $this->isPreU = true;
          if ($subject->name == 'Art History') $this->isPreU = true;
          if ($this->isPreU) {
            $grades = ['D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3'];
          } else {
            $grades = ['A*', 'A', 'B', 'C', 'D', 'E'];
          }
        }
        foreach ($subject->exams as &$e) {
          $students = $subject->getStudentsByExam($yearInt, $e->id);
          foreach ($students as $s) {
            $meg = $grades[random_int(0, count($grades)-1)];
            $tag = $grades[random_int(0, count($grades)-1)];
            $this->adaData->delete($table, 'examId=? AND studentId=?', [$e->id, $s->id]);
            $this->adaData->insert(
              $table,
              'studentId, examId, tag, meg, specialCircumstances, evidenceRemarks, rationale',
              [
                $s->id,
                $e->id,
                $tag,
                $meg,
                "",
                "",
                ""
              ]);
          }
        }
      }
      return emit($response, $exams);
    }

    public function statisticsMEGGet($request, $response, $args) {
      return $this->statisticsGet($request, $response, $args, 'meg');
    }

    public function statisticsTAGGet($request, $response, $args) {
      return $this->statisticsGet($request, $response, $args, 'tag');
    }

    public function statisticsGet($request, $response, $args, $gradeField)
    {
      $auth = $request->getAttribute('auth');
      $console = new \Sockets\Console($auth);
      $this->console = $console;
      // $this->getAllStudents();

      $year = $args['year'];
      $isGCSE = $year < 12 ? true : false;
      $table = $year < 12 ? 'tag_results_gcse' : 'tag_results_alevel';
      $makeSpreadsheets = true;

      // get most recent session so that exam info can be extracted from results table
      $lastSessionId = $this->adaData->select('exams_sessions', 'id', 'year=?', [2020])[0]['id'];
      $console->publish('Hold my beer....');

      //spoof this year's sesions
      $session = [
        'intFormatYear' => 2021,
        'intFormatMonth' => 6,
        'intYear' => 21,
        'year' => 21,
        'month' => '06'
      ];
      $exams = [];
      $students = [];
      $results = [];
      $adaResults = $this->adaData->select($table, 'id, studentId, examId, tag, meg', 'id>?', [0]);
      // var_dump($adaResults); exit();s
      foreach($adaResults as $r) {
        $eId = $r['examId'];
        $key = 'e_' . $eId;
        if (isset($exams[$key])) {
          $exam = $exams[$key];
        } else {
          $oldResult = $this->adaData->select('exams_results', 'title, moduleCode', 'NCYear =? AND examId = ? AND sessionId= ?', [$year, $eId, $lastSessionId]);
          if (!isset($oldResult[0])) {
            // echo 'examID: '. $eId . " not found $year $lastSessionId" . PHP_EOL;
            continue;
          }
          $oldResult = $oldResult[0];
          $exams[$key] = [
            'txtModuleCode' => $oldResult['moduleCode'],
            'txtOptionTitle' => $oldResult['title']
          ];
          $exam = $exams[$key];
        }
        $grade = $r[$gradeField];
        if (\strlen($grade) == 0) continue;
        $title = \strtoupper($exam['txtOptionTitle']);
        $level = null;
        if (stripos($title, 'EXTENDED') !== false) {
          $level = 'B';
        } else {
          if (is_numeric($grade)) {
            $level = 'GCSE';
          } else {
            if ($grade == 'A*' || $grade == 'A' || $grade == 'B' || $grade == 'C' || $grade == 'D' || $grade == 'E') {
              $level = 'A';
            } else {
              $level = 'FC';
            }
          }
        }
        $student = new \Entities\People\Student($this->ada, $r['studentId']);
        $result = [
          'id' => $r['id'],
          'txtSchoolID' => $student->misId,
          'NCYear'  => $student->NCYear,
          'txtModuleCode'  => $exam['txtModuleCode'],
          'txtOptionTitle'  => $exam['txtOptionTitle'],
          'txtLevel'  => $level,
          'grade' => \strtoupper($grade),
          'mark' => 0,
          'total' => 1,
          'early' => false,
          'year' => 2021
        ];
        $objSubject = new \Exams\Tools\SubjectCodes($exam['txtModuleCode'], $exam['txtOptionTitle'], $this->sql, $level, false, $console);
        $result = array_merge($result, (array)$objSubject);

        $result = array_merge($result, $this->getStudent($result['txtSchoolID']));

        $results[] = $result;

      }

      $console->publish('Spoofing results using Ada data...');

      $data = [];
      $data['results'] = $results;
      if (count($results) > 0) {
        $statistics = $isGCSE ? new \Exams\Tools\GCSE\StatisticsGateway($this->sql, $console) : new \Exams\Tools\ALevel\StatisticsGateway($this->sql, $console, $results) ;
        $data['statistics'] = $statistics->makeStatistics($session, $results, $this->cache, true);
      }
      // }

      return emit($response, $data);
    }

    private function getAllStudents()
    {
      $this->console->publish('--Getting Students', 1);

      $studentData = $this->sql->select(  'TblPupilManagementPupils',
                                          'txtSchoolID, txtCandidateNumber, txtCandidateCode, txtForename, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear',
                                          'intEnrolmentSchoolYear > ?', [2016]);

      foreach($studentData as &$stuData) {
        $stuData['txtInitialedName'] = $stuData['txtSurname'] . ', ' . $stuData['txtInitials'];
        //some students that have left don't seem to have an entry for txtBoardingHouse
        if(strlen($stuData['txtBoardingHouse']) == 0) $stuData['txtBoardingHouse'] = $stuData['txtLeavingBoardingHouse'];
        $stuData['txtHouseCode'] = $this->getHouseCode($stuData['txtBoardingHouse']);
        //work out what academic year there were in for this session
        $stuData['NCYear'] = $stuData['intEnrolmentNCYear'] + ($this->sessionAcacdemicYear - $stuData['intEnrolmentSchoolYear']);
        $stuData['isNewSixthForm'] = $stuData['intEnrolmentNCYear'] > 11 ? true : false;

        $this->studentData["s_" . $stuData['txtSchoolID']] = $stuData;
      }
    }

    private function getHouseCode($houseName){

      //look for cached results
      if(isset($this->houseCodes[$houseName])) return $this->houseCodes[$houseName];

      $d = $this->sql->select( 'TblSchoolManagementHouses',
                               'txtHouseCode',
                               'txtHouseName = ?',
                               array($houseName)
                             );

      if(isset($d[0])) {
        $this->houseCodes[$houseName] = $d[0]['txtHouseCode'];
        return $d[0]['txtHouseCode'];
      }

      return '-';

    }

    private function getStudent ($txtSchoolID) {
      //look for cached student data else fetch
      if(isset($this->studentData["s_" . $txtSchoolID])){
        return $this->studentData["s_" . $txtSchoolID];
      }else{
        $studentData = $this->sql->select(  'TblPupilManagementPupils',
                                            'txtSchoolID, txtCandidateNumber, txtCandidateCode, txtForename, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear',
                                            'txtSchoolID=?', array($txtSchoolID));
        //format the data and merge into the result
        if(isset($studentData[0])) {
          $stuData = $studentData[0];
          $stuData['txtInitialedName'] = $stuData['txtSurname'] . ', ' . $stuData['txtInitials'];
          //some students that have left don't seem to have an entry for txtBoardingHouse
          if(strlen($stuData['txtBoardingHouse']) == 0) $stuData['txtBoardingHouse'] = $stuData['txtLeavingBoardingHouse'];
          $stuData['txtHouseCode'] = $this->getHouseCode($stuData['txtBoardingHouse']);
          //work out what academic year there were in for this session
          $stuData['NCYear'] = $stuData['intEnrolmentNCYear'] + ($this->sessionAcacdemicYear - $stuData['intEnrolmentSchoolYear']);
          $stuData['isNewSixthForm'] = $stuData['intEnrolmentNCYear'] > 11 ? true : false;


          $this->studentData["s_" . $txtSchoolID] = $stuData;
          return $stuData;
        }
      }
    }


}
