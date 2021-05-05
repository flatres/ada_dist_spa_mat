<?php

/**
 * Description

 * Usage:

 */
namespace DHA;

class Wyaps
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------
    public function yearWyapsGet($request, $response, $args)
    {
      $year = $args['year'];
      $year = new \Entities\Academic\Year($year);
      $year->getWyaps(true);
      $exams = [];
      foreach($year->subjects as $s) {
        foreach ($s->exams as $e) $exams[] = $e;
      }
      return emit($response, $exams);
    }



}
