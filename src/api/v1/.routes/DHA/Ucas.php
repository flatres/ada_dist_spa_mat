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
        $s->ucasHigh = $this->getOffer($s->id);
        $s->ucasLow = $this->getOffer($s->id, false);
        $s = (object)\array_merge((array)$s, $s->ucasHigh);
        $s = (object)\array_merge((array)$s, $s->ucasLow);
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

    private function getOffer($sId, $high = true) {
      $code = $high ? 'CF' : 'CI';
      $o = [];
      $o = $this->adaData->select('ucas_offers', '*', 'studentId=? AND decision=?', [$sId, $code]);
      if (!isset($o[0]) && $high == true) {
        $o = $this->adaData->select('ucas_offers', '*', 'studentId=? AND decision=?', [$sId, 'C']);
      }
      if (!isset($o[0])) return [];
      $o = $o[0];

      $o['offer'] = "[${o['grade1']}]";
      if ($o['grade2']) $o['offer'] .= " [${o['grade2']}]";
      if ($o['grade3']) $o['offer'] .= " [${o['grade3']}]";
      if ($o['grade4']) $o['offer'] .= " [${o['grade4']}]";
      return $o;
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
