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
      foreach($exgardeLog as $l) {
        if (!isset($l['type']) || !isset($l['ada_id'])) continue;
        if ($l['type'] === 'Student'){
          $records[] = $l;
          $exId = $l['id'];
          //check is already in ADA
          $adaRecord = $this->adaModules->select('watch_exgarde_gym', 'exgardeId', 'exgardeId=?', [$exId]);
          if (!isset($adaRecord[0]) && isset($l['ada_id'])) {
            $this->adaModules->insert('watch_exgarde_gym', 'exgardeId, studentId, timestamp', [$exId, $l['ada_id'], $l['LOCAL_TIME']]);
          }
        }
      }

      $data = [
        'timestamp' => date('Y-m-d H:i:s', time()),
        'records'   => $records
      ];
      return emit($response, $data);
    }

}
