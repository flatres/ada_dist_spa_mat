<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Sync;

class ExternalExams
{
    protected $container;
    private $results;
    private $adaSessionId;

    public function __construct(\Slim\Container $container)
    {
       $this->isams= $container->isams;
       $this->ada = $container->ada;
       $this->adaData = $container->adaData;
       $this->container = $container;


    }

    public function sessions_GET($request, $response, $args)
    {
      $data = $this->adaData->select('exams_sessions', 'id, misId, year, month, isActive, isPublished', 'id>? ORDER BY misId DESC', [0]);
      foreach($data as &$d) {
        $results = $this->adaData->select('exams_results', 'id', 'sessionId=?', [$d['id']]);
        $d['resultsCount'] = count($results);
      }
      return emit($response, $data);

    }

    //ensure that ADA Subjects are up to date with iSams
    public function externalExamSessionsSync_POST($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);
      $this->progress = new \Sockets\Progress($auth, 'Admin/Sync/ExternalExamSessions');
      $this->console->publish('Pulling iSAMS Subjects...');
      // $this->progress->publish($i/$count);
      $this->results = new \Exams\Results($this->container);
      $sessions = $this->syncSessions();
      return emit($response, $sessions);

    }

    public function externalExamsSync_POST($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');

      //clear previous
      $this->adaData->delete('exams_results', 'sessionId=?', [$args['sessionId']]);
      //get MIS session ID
      $session = $this->adaData->select('exams_sessions', 'misId', 'id=?', [$args['sessionId']]);
      $sessionId = $session[0]['misId'];
      $this->adaSessionId = $args['sessionId'];

      $session = [];

      $this->console = new \Sockets\Console($auth);
      $this->progress = new \Sockets\Progress($auth, 'Admin/Sync/ExternalExams');

      //GCSE Results
      $isGCSE = true;
      $this->results = new \Exams\Results($this->container);
      $this->console->publish("<--------- FETCHING GCSE --------->");
      $data = $this->results->getSessionResults($sessionId, $isGCSE, $this->console, false, true, false);

      if (isset($data['statistics']->hundredStats)) {
        $session['gcse_hundred'] = $data['statistics']->hundredStats->results;
        $this->saveResults($session['gcse_hundred'], $isGCSE);
      }

      if (isset($data['statistics']->removeStats)) {
        $session['gcse_remove'] = $data['statistics']->removeStats->results;
        $this->saveResults($session['gcse_remove'], $isGCSE);
      }

      if (isset($data['statistics']->shellStats)) {
        $session['gcse_shell'] = $data['statistics']->shellStats->results;
        $this->saveResults($session['gcse_shell'], $isGCSE);
      }

      $session['gcse_other'] = $data['statistics']->otherStats->results;
      $this->saveResults($session['gcse_other'], $isGCSE);

      $this->progress->publish(0.5);

      //GCSE Results
      $isGCSE = false;
      $this->results = new \Exams\Results($this->container);
      $this->console->publish("<--------- FETCHING ALevel --------->");
      $data = $this->results->getSessionResults($sessionId, $isGCSE, $this->console, false, true, false);
      $results = $data['statistics']->data->results;
      $session['alevel'] = $results;
      $this->saveResults($results, $isGCSE);

      $this->progress->publish(1);
      return emit($response, $session);

    }

    private function saveResults(Array $results, $isGCSE) {
      $sessionId = $this->adaSessionId;
      $externalResult = new \Entities\Exams\ExternalResult($this->ada, $this->adaData);
      foreach($results as $r){
        $externalResult->save($r, $isGCSE, $sessionId);
      }
    }

    private function syncSessions() {
      $sessions = $this->results->fetchExamSessions();
      $sql = $this->adaData;
      // $sql->delete('exams_sessions', 'id > ?', [0]);
      foreach($sessions as $s) {
        $exists = $sql->exists('exams_sessions', 'misId=?', [$s['id']]);
        if ($exists) {
          $sql->update('exams_sessions', 'year=?, month=?, isActive=?', 'misId=?', [$s['intFormatYear'], $s['intFormatMonth'], $s['intActive'] =='1' ? 1 : 0, $s['id']]);
        } else {
          $sql->insert('exams_sessions', 'misId, year, month, isActive', [$s['id'], $s['intFormatYear'], $s['intFormatMonth'], $s['intActive'] =='1' ? 1 : 0]);
        }

      }
      return $sessions;
    }



}
