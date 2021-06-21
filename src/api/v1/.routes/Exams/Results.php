<?php

/**
 * Description

 * Usage:

 */
namespace Exams;

class Results
{
    protected $container;

    private $houseCodes = array();
    private $error = false;
    public $isGCSE;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->isams;
       $this->studentData = array(); //for caching student data
       $this->subjectData = array();
       $this->results = array();
       $this->resultKeys = array(); //key r_{id}
       $this->lowestFileID = 9999999999;
       $this->cache = new \Exams\Tools\Cache($container);
       $this->moduleResults = [];
    }

    /**
     * Generate a password salt
     *
     * @param int $length
     *    The number of characters that the salt should be
     *
     * @return string
     *    Returns a salt that can be used to salt a password hash
     *
     * @access private
     */
    public function getSessions($request, $response, $args)
    {
      $data = $this->fetchExamSessions();
      return emit($response, $data);
    }

    public function fetchExamSessions() {
      $data = array();
      $data = $this->sql->select(  'TblExamManagerCycles',
                                'TblExamManagerCyclesID, TblExamManagerCyclesID as id, intYear, intActive, intResultsActive, intFormatMonth, intFormatYear',
                                'TblExamManagerCyclesID > 0 AND intFormatMonth = 6 ORDER BY TblExamManagerCyclesID DESC',
                                array()
                          );
      // convert numberical month to name
      $year = (int)(new \DateTime)->format("Y");
      $month = (int)(new \DateTime)->format("n"); //without leading zeros

      foreach($data as &$cycle){
        $monthNum = $cycle['intFormatMonth'];
        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
        $cycle['month'] = $monthName;
        $cycle['m']= $month;
        $cycle['y']= $year - 1;
        if ($year === (int)$cycle['intFormatYear'] && $month > 7) {
          $cycle['intActive'] = '1';
        } elseif ($year - 1 === (int)$cycle['intFormatYear'] && $month < 7) {
          $cycle['intActive'] = '1';
        } else {
          $cycle['intActive'] = '0';
        }
      }
      return $data;
    }

    public function getCachedGCSEResults($request, $response, $args)
    {
      $isGCSE = true;
      $sessionId = $args['sessionId'];

      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);

      $data = $this->cache->read($sessionId, true);
      // return $this->getGCSEResults($request, $response, $args);
      // return;
      if (!$data) {
        return $this->getGCSEResults($request, $response, $args);
      } else {
          return emit($response, $data);
      }
    }

    public function getCachedALevelResults($request, $response, $args)
    {
      $isGCSE = false;
      $sessionId = $args['sessionId'];

      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);

      $data = $this->cache->read($sessionId, $isGCSE);
      if (!$data) {
        return $this->getALevelResults($request, $response, $args);
      } else {
        return emit($response, $data);
      }
    }

    public function getGCSEResults($request, $response, $args)
    {
      $args['isGCSE'] = true;
      $this->isGCSE = true;
      return $this->getResults($request, $response, $args);
    }

    public function getALevelResults($request, $response, $args)
    {
      $args['isGCSE'] = false;
      $this->isGCSE = false;

      return $this->getResults($request, $response, $args);
    }

    private function getResults($request, $response, $args){

      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);
      $this->console->publish('Getting Results');
      $console = $this->console;

      $sessionId = $args['sessionId'];
      $isGCSE = $args['isGCSE'];
      $data = $this->getSessionResults($sessionId, $isGCSE, $this->console);
      return emit($response, $data);
      // return $this->error ? emitError($response,500, "Failure"): emit($response, $data);

    }

    public function getSessionResults($sessionId, $isGCSE, $console, $writeCache = true, $makeStatistics = true, $makeSpreadsheets = true)
    {
      $data= array();
      $results = array();
      $data['sessionId'] = (int)$sessionId;
      $this->console = $console; //may be given console from external source such as Ada syncing
      $this->isGCSE = $isGCSE;

      //get session data
      $d = $this->sql->select(  'TblExamManagerCycles',
                                'TblExamManagerCyclesID, TblExamManagerCyclesID as id, intYear, intActive, intResultsActive, intFormatMonth',
                                'TblExamManagerCyclesID = ?',
                                array($sessionId)
                          );

      $this->session = array( 'id'    =>  $sessionId,
                              'month' =>  \DateTime::createFromFormat('!m', $d[0]['intFormatMonth'])->format('F'),
                              'year'  =>  $d[0]['intYear']);

      //get date information for calculating pupip academic year
      $d = $this->sql->select(  'TblExamManagerCycles',
                                'TblExamManagerCyclesID, TblExamManagerCyclesID as id, intYear, intFormatYear, intActive, intResultsActive, intFormatMonth',
                                'TblExamManagerCyclesID = ?',
                                array($sessionId)
                          );

      $this->sessionYear = $d[0]['intFormatYear'];
      $this->sessionMonth = $d[0]['intFormatMonth'];
      $this->sessionAcacdemicYear = $this->sessionMonth < 9 ? $this->sessionYear - 1 : $this->sessionYear;

      // $this->getAllStudents(); MAY BE FREEZING ISAMS SERVER

      //find all uploaded results files for this session
      $resultFiles = $this->sql->select(  'TblExamManagerResults',
                                          'TblExamManagerResultsID as id, intCycle, txtImportDateTime',
                                          'intCycle = ?', array($sessionId));

      $data['resultFiles'] = $resultFiles;
      $console->publish(count($resultFiles) . " files found");
      $fileIndex = 1;

      foreach($resultFiles as $resultFile){

        $console->publish("Loading results file - id ".$resultFile['id']." [".$fileIndex."/".count($resultFiles)."]");
        $fileIndex++;

        $s = $isGCSE ? "(txtQualification = 'FSMQ' OR txtQualification = 'GCSE')" : " txtQualification <> 'FSMQ' AND txtQualification <> 'GCSE'";

        //gather all results from this file and append student data to each result
        //certification type 'C' ensures that individual subject units are not included and we only get final grade
        $resultsFileResults = $this->sql->select( 'TblExamManagerResultsStore',
                                                  'TblExamManagerResultsStoreID as id, txtSchoolID, txtLevel, txtQualification, txtOptionTitle, txtModuleCode, txtFirstGrade as grade, txtUniformMarkScale as mark, txtMaxMark as total',
                                                  "intResultsID = ? AND $s AND txtCertificationType='C'",
                                                  array($resultFile['id']));

        if (!$isGCSE) {
          $moduleResults = $this->sql->select( 'TblExamManagerResultsStore',
                                                    'TblExamManagerResultsStoreID as id, txtSchoolID, txtLevel, txtQualification, txtOptionTitle, txtModuleCode, txtUniformMarkScale as mark, txtMaxMark as total, txtUniformGrade as grade',
                                                    "intResultsID = ? AND $s AND txtCertificationType <> 'C' ORDER BY txtLevel ASC",
                                                    array($resultFile['id']));
          $this->moduleResults = array_merge($this->moduleResults, $moduleResults);
        }

        if($this->lowestFileID > $resultFile['id']) $this->lowestFileID = $resultFile['id'];

        $this->processResults($resultsFileResults);
      }
      $this->findEarlyTakerResults($isGCSE);

      $data['results'] = $this->results;

      if ($fileIndex > 1) {
        $statistics = $isGCSE ? new \Exams\Tools\GCSE\StatisticsGateway($this->sql, $console) : new \Exams\Tools\ALevel\StatisticsGateway($this->sql, $console, $this->moduleResults) ;
        if ($makeStatistics) $data['statistics'] = $statistics->makeStatistics($this->session, $this->results, $this->cache, $makeSpreadsheets);
      }
      $this->error ? $console->error("Finished WITH ERRORS") : $console->publish("Finished");

      if ($writeCache) {
        $console->publish("Saving...", 1);
        $this->cache->write($sessionId, $isGCSE, $data);
      }

      foreach($data['results'] as &$r) {
        $r['sql'] = null;
      }

      $data['timestamp'] = date('Y-m-d H:i:s', time());
      return $data;
    }

    private function findEarlyTakerResults($isGCSE)
    {
      $this->console->publish('Searching for results from early takers');
      $studentsCount = count($this->studentData);
      $startResultsCount = count($this->results);

      $this->console->publish("Lowest File ID = " . $this->lowestFileID, 1);
      $this->console->publish("ISGCSE = " . $isGCSE, 1);

      $i = 0;
      $this->console->publish('Students: 0 / ' . $studentsCount,1);

      $questionMarks = '?';
      $ids = [];
      foreach($this->studentData as $student){

        if($i % 10 == 0) $this->console->replace("Students: $i / $studentsCount");
        if ($isGCSE && $student['NCYear'] > 11) continue; //for sixth form that have taken a gcse it won't go back and find their old results
        $ids[] = $student['txtSchoolID'];
        if ($i > 0) $questionMarks .= ', ?';
        $i++;

      }

      $data = [$this->lowestFileID];
      $data = array_merge($data, $ids);
      $s = $isGCSE ? "(txtQualification = 'FSMQ' OR txtQualification = 'GCSE')" : " txtQualification <> 'FSMQ' AND txtQualification <> 'GCSE'";
      $resultsData = $this->sql->select(  'TblExamManagerResultsStore',
                                          'TblExamManagerResultsStoreID as id, txtSchoolID, txtQualification, txtLevel, txtOptionTitle, txtModuleCode, txtFirstGrade as grade, txtUniformMarkScale as mark, txtMaxMark as total',
                                          "$s AND txtCertificationType='C' AND intResultsID < ? AND txtSchoolID IN ($questionMarks)",
                                          $data);

      if (!$isGCSE) {
        $moduleResults = $this->sql->select( 'TblExamManagerResultsStore',
                                                  'TblExamManagerResultsStoreID as id, txtSchoolID, txtLevel, txtQualification, txtOptionTitle, txtModuleCode, txtUniformMarkScale as mark, txtMaxMark as total, txtUniformGrade as grade',
                                                  "intResultsID < ? AND $s AND txtCertificationType <> 'C' AND txtSchoolID IN ($questionMarks) ORDER BY txtLevel ASC",
                                                  $data);
        $this->moduleResults = array_merge($this->moduleResults, $moduleResults);
      }

      $this->processResults($resultsData, true);
      $this->console->publish(count($resultsData) . " early results found");
      // $this->console->replace("$studentsCount / $studentsCount");

      $numberFound = count($this->results) - $startResultsCount;
      $this->console->publish("$numberFound found.");
    }

    private function getAllStudents()
    {
      $this->console->publish('--Getting Students', 1);

      $studentData = $this->sql->select(  'TblPupilManagementPupils',
                                          'txtSchoolID, txtCandidateNumber, txtCandidateCode, txtForename, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear, txtEthnicGroup as ethnicGroup',
                                          'intEnrolmentSchoolYear > ?', [2011]);

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

    public function processResults($resultsFileResults, $isEarlyTakers = null)
    {
      $resultCount = count($resultsFileResults);
      $i=0;
      if(!$isEarlyTakers){
        $this->console->publish('--Processing Results (' . $resultCount . ' records)...', 1);
        $this->console->publish('--1', 2);
      }
      foreach($resultsFileResults as &$resultsFileResult){
        $i++;
        if($i % 10 == 0 && !$isEarlyTakers) $this->console->replace("--$i");

        $resultsFileResult['early'] = $isEarlyTakers ? true : false;

        $this->resultKeys['r_' . $resultsFileResult['id']] = true;
        //look for this module else make a new one
        if(!isset($this->subjectData['s_' . $resultsFileResult['txtModuleCode']])){
          $level = $this->isGCSE ? 'GCSE' : $resultsFileResult['txtLevel'];
          $objSubject = new \Exams\Tools\SubjectCodes($resultsFileResult['txtModuleCode'], $resultsFileResult['txtOptionTitle'], $this->sql, $level, false, $this->console);
          if($resultsFileResult['txtLevel'] === 'ASB') {
              $resultsFileResult['txtOptionTitle'] .= ' (AS)';
              $objSubject->txtOptionTitle = $resultsFileResult['txtOptionTitle'];
              $objSubject->subjectName .= ' (AS)';
          }

          if($this->isGCSE) $objSubject->GCSEType();

          $resultsFileResult = array_merge($resultsFileResult, (array)$objSubject);
          $this->subjectData['s_' . $resultsFileResult['txtModuleCode']] = $objSubject;

          if($objSubject->subjectCode == '-') { $this->console->error('!!WARNING!! Couldnt match subject code to ' . $objSubject->txtOptionTitle);
                                                $this->error = true; }
        } else {
          $resultsFileResult = array_merge($resultsFileResult, (array)$this->subjectData['s_' . $resultsFileResult['txtModuleCode']]);
        }
        //look for cached student data else fetch
        if(isset($this->studentData["s_" . $resultsFileResult['txtSchoolID']])){
          $resultsFileResult = array_merge($resultsFileResult, $this->studentData["s_" . $resultsFileResult['txtSchoolID']]);
        }else{
          $studentData = $this->sql->select(  'TblPupilManagementPupils',
                                              'txtSchoolID, txtCandidateNumber, txtCandidateCode, txtForename, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear, txtEthnicGroup as ethnicGroup',
                                              'txtSchoolID=?', array($resultsFileResult['txtSchoolID']));
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

            $resultsFileResult = array_merge($resultsFileResult, $stuData);
            $this->studentData["s_" . $resultsFileResult['txtSchoolID']] = $stuData;
          }
        }
      }
      if(!$isEarlyTakers) $this->console->replace("--$i");
      $this->results = array_merge($this->results, $resultsFileResults);

      return $i;

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
}

// SELECT DISTINCT
// rs.txtModuleCode as SubjectNo,
// '' as SubjectCode,
// rs.txtOptionTitle as Description,
// CASE e.intUABCode WHEN 1 THEN '01' WHEN 2 THEN '02' WHEN 3 THEN '03' ELSE e.intUABCode END as Board,
// rs.txtLevel,
// rs.txtQualification,
// CASE rs.Qualification WHEN 'GCE' THEN 'A' WHEN 'GCEAS' THEN 'Z'
// WHEN 'PREU' THEN 'U' WHEN 'GCSE' THEN 'G'
//   WHEN 'EXPJ' THEN 'C' WHEN 'FSMQ' THEN 'D' WHEN 'AEA' THEN 'L' ELSE '' END as SubjectType
//
// FROM TblPupilManagementPupils p,
// TblExamManagerResults r,
// TblExamManagerCycles c,
// (
// SELECT
// CASE txtLevel WHEN 'ASB' THEN 'GCEAS' ELSE txtQualification END as Qualification,
// *
// FROM TblExamManagerResultsStore
// WHERE txtCertificationType='C'
// ) rs,
// TblExamManagerEntries e
// WHERE rs.txtSchoolID=p.txtSchoolID
// AND rs.intResultsID=r.TblExamManagerResultsID
// AND rs.txtCertificationType='C'
// AND r.intCycle=c.TblExamManagerCyclesID
// AND c.intFormatMonth=6
// AND c.intFormatYear=2017
// -----------------------------------------
// -- Change one of the following two lines
// -- to get either pupils who have left
// -- or current pupils
//
// --AND p.intLeavingSchoolYear=2011
// --AND p.intNCYear=13
// -----------------------------------------
// -- A   = A level
// -- ASB = AS Level
// -- FC  = PreU
// -- B   = EPQ
// --AND rs.txtLevel IN ('A','FC')
// -----------------------------------------
// AND e.txtSchoolID=rs.txtSchoolID
// AND e.txtModuleCode=rs.txtModuleCode
// ORDER BY txtOptionTitle
