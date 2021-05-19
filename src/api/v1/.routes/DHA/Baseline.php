<?php

/**
 * Description

 * Usage:

 */
namespace DHA;

class Baseline
{
    protected $container;
    private $offerCount = 0;
    private $pointsCount = 0;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaData = $container->adaData;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------
    public function alisGet($request, $response, $args)
    {
      $year = new \Entities\Academic\Year($args['year']);
      $examCodes = [];
      $exams = $year->getExams(true);
      $students = [];
      foreach($exams as $e) {
        foreach($e->students as &$s) {
          $sKey = 's_' . $s->id;
          $eKey = 'e_' . $e->examCode;
          if (!\array_key_exists($sKey, $students)) $students[$sKey] = $s;
          if (!\array_key_exists($eKey, $examCodes)) $examCodes[$eKey] = $e->examCode;
          $metric = (new \Entities\Metrics\Alis($s->id, $e->id, $this->adaData));

          $students[$sKey]->baseline = $metric->baseline;
          $students[$sKey]->{$e->examCode} = $metric->prediction;
        }
      }
      ksort($examCodes);
      $examCodes = array_values($examCodes);
      $data = [
        'exams' => $examCodes,
        'students' => array_values($students)
      ];
      return emit($response, $data);
    }

}
