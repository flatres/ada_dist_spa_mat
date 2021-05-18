<?php

/**
 * Description

 * Usage:

 */
namespace DHA;

class Ucas
{
    protected $container;
    private $totalGrades = [
      'A*' => 0,
      'A' => 0,
      'B' => 0,
      'C' => 0,
      'D' => 0,
      'E' => 0,
      'D1' => 0,
      'D2' => 0,
      'D3' => 0,
      'M1' => 0,
      'M2' => 0,
      'M3' => 0,
      'P1' => 0,
      'P2' => 0,
      'P3' => 0
    ];
    private $offerCount = 0;
    private $pointsCount = 0;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaData = $container->adaData;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------
    public function pupilsGet($request, $response, $args)
    {
      $year = new \Entities\Academic\Year(13);
      foreach($year->students as &$s) {
        $ucas = $this->adaData->select('ucas_offers', '*', 'studentId=?', [$s->id]);
        $s->ucas = isset($ucas[0]) ? $ucas[0] : [];
        $s->flagged = 0;
        if (isset($s->ucas['offer'])) {
          $short = explode(' ', $s->ucas['offer'])[0];
          $short = preg_replace('#\(.*\)#', '', $short);
          $s->ucas['offerShort'] = $short;
          $s->ucas['points'] = $this->makePoints($short, $s);
          $s->flagged = $s->ucas['flagged'];
        }
        $s = (object)\array_merge((array)$s, $s->ucas);
        if (isset($s->ucas['counts'])) $s = (object)\array_merge((array)$s, $s->ucas['counts']); 
      }
      $totals = [];
      foreach($this->totalGrades as $key => $g) {
        $totals[] = [
          'grade' => $key,
          'total' => $g
        ];
      }
      $pointsAvg = $this->offerCount > 0 ? round($this->pointsCount / $this->offerCount, 2) : 0;
      $data = [
        'totals' => $totals,
        'students' => $year->students,
        'pointsAvg' => $pointsAvg
      ];
      return emit($response, $data);
    }

    private function makePoints($offer, &$s) {
      if (is_numeric($offer)) return $offer;
      //sanitise
      $offer = \str_replace('Maths', '', $offer);
      if (substr_count($offer, '/') > 0) $offer = explode('/', $offer)[0];

      $grades = [
        'A*' => 0,
        'A' => 0,
        'B' => 0,
        'C' => 0,
        'D' => 0,
        'E' => 0,
        'D1' => 0,
        'D2' => 0,
        'D3' => 0,
        'M1' => 0,
        'M2' => 0,
        'M3' => 0,
        'P1' => 0,
        'P2' => 0,
        'P3' => 0
      ];
      foreach($grades as $grade => &$count) $count = substr_count($offer, $grade);
      unset($count);
      $grades['A'] = $grades['A'] - $grades['A*'];
      $grades['D'] = $grades['D'] - $grades['D1'] - $grades['D2'] - $grades['D3'];
      // return $grades;
      $points = 0;
      $result = new \Exams\Tools\ALevel\Result();
      foreach($grades as $grade => $c) {
        if (!$grade) continue;
        $result->processGrade($grade);
        $points += $c * $result->ucasPoints;
        if (isset($this->totalGrades[$grade])) $this->totalGrades[$grade] += $c;
      }
      $s->ucas['counts'] = $grades;
      $this->pointsCount += $points;
      $this->offerCount++;
      return $points;
    }



}
