<?php

/**
 * Description

 * Usage:

 */
namespace Exams;

class Admin
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
       $this->cache = new \Exams\Tools\Cache($container);
    }

    public function filesGet($request, $response, $args)
    {
      $resultFiles = $this->isams->select(  'TblExamManagerResults',
                                          'TblExamManagerResultsID as id, intCycle, txtImportDateTime, txtFilePath',
                                          'intCycle > 0 ORDER BY id DESC', []);
      return emit($response, $resultFiles);
    }

    public function sessionsGet($request, $response, $args)
    {
      $data = array();
      $data = $this->isams->select( 'TblExamManagerCycles',
                                    'TblExamManagerCyclesID as id, intYear, intActive, intResultsActive, intFormatMonth, intFormatYear',
                                    'TblExamManagerCyclesID > 0 AND intFormatMonth = 6 ORDER BY intYear DESC',
                                    []
                          );
      // convert numberical month to name
      foreach($data as &$cycle){
        $this->processCycle($cycle);

      }
      return emit($response, $data);
    }

    public function cachesDelete($request, $response, $args)
    {
      $cache = $this->cache->deleteAll();
      return emit($response, []);
    }

    public function cacheDelete($request, $response, $args)
    {
      $id = $args['id'];
      $cache = $this->cache->delete($id);
      return emit($response, []);
    }

    public function sessionGet($request, $response, $args)
    {
      $data = array();
      $id = $args['id'];
      $data = $this->isams->select( 'TblExamManagerCycles',
                                    'TblExamManagerCyclesID as id, intYear, intActive, intResultsActive, intFormatMonth, intFormatYear',
                                    'TblExamManagerCyclesID = ? AND intFormatMonth = 6 ORDER BY intYear DESC',
                                    [$id]
                          );
      // convert numberical month to name
      foreach($data as &$cycle){
        $this->processCycle($cycle);
      }
      return emit($response, $data);
    }

    private function processCycle(&$cycle){
      $monthNum = $cycle['intFormatMonth'];
      $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
      $cycle['month'] = $monthName;

      $cycleId = $cycle['id'];
      //look for cache
      $gcseCache = $this->cache->exists($cycleId, true);
      $cycle['gcseCache'] = $gcseCache ?  ['cached'] : ['none'];
      $cycle['gcseCacheTime'] = $gcseCache ?  $gcseCache : '';

      $alevelCache = $this->cache->exists($cycleId, false);
      $cycle['alevelCache'] =  $alevelCache ?  ['cached'] : ['none'];
      $cycle['alevelCacheTime'] =  $alevelCache ?  $alevelCache : '';

      //file
      $resultFiles = $this->isams->select(  'TblExamManagerResults',
                                            'TblExamManagerResultsID as id, intCycle, txtImportDateTime',
                                            'intCycle = ?', array($cycleId));
      $cycle['files'] = count($resultFiles);

      $cycle['gcseCount'] = 0;
      $cycle['alevelCount'] = 0;

      foreach($resultFiles as $resultFile){

        $s = "(txtQualification = 'FSMQ' OR txtQualification = 'GCSE')";
        $resultsFileResults = $this->isams->select( 'TblExamManagerResultsStore',
                                                  'TblExamManagerResultsStoreID as id, txtSchoolID, txtQualification, txtOptionTitle, txtModuleCode, txtFirstGrade as grade',
                                                  "intResultsID = ? AND $s AND txtCertificationType='C'",
                                                  array($resultFile['id']));

        $cycle['gcseCount'] += count($resultsFileResults);

        $s = " txtQualification <> 'FSMQ' AND txtQualification <> 'GCSE'";
        $resultsFileResults = $this->isams->select( 'TblExamManagerResultsStore',
                                                  'TblExamManagerResultsStoreID as id, txtSchoolID, txtQualification, txtOptionTitle, txtModuleCode, txtFirstGrade as grade',
                                                  "intResultsID = ? AND $s AND txtCertificationType='C'",
                                                  array($resultFile['id']));

        $cycle['alevelCount'] += count($resultsFileResults);
      }
    }

    public function subjectsGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $progress = new \Sockets\Progress($auth, 'Exams.Admin.Subjects');
      $resultsFileResults = $this->isams->select( 'TblExamManagerResultsStore',
                                                'TblExamManagerResultsStoreID as id, txtSchoolID, txtLevel, txtQualification, txtOptionTitle, txtModuleCode, txtFirstGrade as grade',
                                                "txtCertificationType='C'",
                                                []);
      $count = count($resultsFileResults);
      $i = 1;
      $modules = [];
      foreach ($resultsFileResults as $result) {
          $objSubjectCode = new \Exams\Tools\SubjectCodes($result['txtModuleCode'], $result['txtOptionTitle'], $this->isams);
          $result = array_merge($result, (array)$objSubjectCode);

          $objResult = $result['txtQualification'] == 'FSMQ' || $result['txtQualification'] == 'GCSE' ? new \Exams\Tools\GCSE\Result($result) : new \Exams\Tools\ALevel\Result($result);

          if (isset($modules[$result['txtModuleCode']])) {
            $modules[$result['txtModuleCode']]->setResult($objResult);
            continue;
          }
          $objSubject = $result['txtQualification'] == 'FSMQ' || $result['txtQualification'] == 'GCSE' ? new \Exams\Tools\GCSE\Subject($result) : new \Exams\Tools\ALevel\Subject($result);
          $objSubject->setResult($objResult);
          $modules[$result['txtModuleCode']] = $objSubject;

          $progress->publish($i/$count * 100);
          $i++;
      }
      $progress->publish(100);
      return emit($response, array_values($modules));
    }

}
