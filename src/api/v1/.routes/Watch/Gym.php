<?php

/**
 * Description

 * Usage:

 */
namespace Watch;

class Gym
{
    protected $container;

    private $GYM_AREA = '10681800';

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       // $this->msSql = $container->msSql;
       $this->exgarde = $container->exgarde;
    }

// ROUTE -----------------------------------------------------------------------------

    public function allGet($request, $response, $args)
    {
      //get latest data from Exgarde
      $exgardeLog = $this->exgarde->getArea($this->GYM_AREA, true);
      $records = [];
      //put any new ones into ada
      foreach($exgardeLog as &$l) {
        if (!isset($l['type']) || !isset($l['ada_id'])) continue;
        if ($l['type'] === 'Student'){
          $exId = $l['id'];
          //check is already in ADA
          $adaRecord = $this->adaModules->select('watch_exgarde_gym', 'exgardeId, sessionTypeId', 'exgardeId=?', [$exId]);
          if (!isset($adaRecord[0]) && isset($l['ada_id'])) {
            $this->adaModules->insert('watch_exgarde_gym', 'exgardeId, studentId, timestamp', [$exId, $l['ada_id'], $l['LOCAL_TIME']]);
            $l['sessionTypeId'] = null;
          } else {
            $l['sessionTypeId'] = $adaRecord[0]['sessionTypeId'];
          }
          $records[] = $l;
        }
      }
      // $records = $this->adaModules->select('watch_exgarde_gym', '*', 'id > ? ORDER BY id DESC', [0]);
      $data = [
        'timestamp' => date('Y-m-d H:i:s', time()),
        'records'   => $records
      ];
      return emit($response, $data);
    }

    public function optionsGet($request, $response, $args)
    {
      $data = $this->adaModules->select('watch_exgarde_gym_session_types', 'id as value, name as label', 'id > ?', [0]);
      return emit($response, $data);
    }

    public function liveEntryPut($request, $response)
    {
      $data = $request->getParsedBody();
      $this->adaModules->update('watch_exgarde_gym', 'sessionTypeId=?', 'exgardeId = ?', [$data['sessionTypeId'], $data['id']]);
      return emit($response, $data);
    }

}
