<?php

/**
 * Description

 * Usage:

 */
namespace Admin\School;

class Calendar
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------

    public function periodGet($request, $response, $args)
    {
      $date = $args['date'];
      $time = $args['time'];
      $string = $date . " " . $time;
      $timestamp = strtotime($string);
      $year = date("Y", $timestamp);

      $terms = $this->fetchTerms($year);

      $inTerm = false;
      foreach($terms as $term) {
        $start = strtotime($term['start']);
        $end = strtotime($term['end']);
        if ($timestamp > $start && $timestamp < $end) {
          $htStart = strtotime($term['htStart']);
          $htEnd = strtotime($term['htEnd']);
          if ($timestamp > $htStart && $timestamp < $htEnd) break; //is during half term
          $inTerm = $term;
          break;
        }
      }

      if (!$inTerm) return emit($response, 'holiday');

      //check exeats
      if ($timestamp > strtotime($term['ex1Start']) && $timestamp < strtotime($term['ex1End'])) return emit($response, 'exeat');
      if ($timestamp > strtotime($term['ex2Start']) && $timestamp < strtotime($term['ex2End'])) return emit($response, 'exeat');

      $period = $this->periodFromTime($timestamp, $term);

      return emit($response, $period);
    }

    private function weekTypeFromTime($timestamp, $term) {
        $day = date("N", $timestamp);
        $inWeekType = false;
        foreach ($term['weeks'] as $week){
          $start = strtotime($week['wb']);
          $end = strtotime($week['we']);
          if ($timestamp > $start && $timestamp < $end) {
            $inWeekType = $week['type'];
            break;
          }
        }
        if (!$inWeekType) return 'error - week not found';
        return $inWeekType;

    }

    private function periodFromTime($timestamp, $term) {
        $day = date("N", $timestamp);
        if ($day == 7) return 'none';

        $dt = new \DateTime();
        $dt->setTimestamp($timestamp);
        $midnight = strtotime($dt->format('d-m-Y'));
        $t = $timestamp - $midnight; //seconds since midnight

        if ($t < $this->seconds('08:45')) return 'none';
        if ($t < $this->seconds('09:40')) return 'p1';
        if ($t < $this->seconds('10:40')) return 'p2';
        if ($t < $this->seconds('11:05')) return 'break';
        if ($t < $this->seconds('12:00')) return 'p3';
        if ($day == 6) return 'none'; //sat finished at p3
        if ($t < $this->seconds('13:00')) return 'p4';

        if ($day == 2 || $day == 4) return 'none';

        if ($term['number'] < 3) { //lent or mich
              if ($t < $this->seconds('16:45')) return 'none';
              if ($t < $this->seconds('17:40')) return 'p5';
              if ($t < $this->seconds('18:40')) return 'p6';
        } else { //summer timetable
          if ($t < $this->seconds('14:15')) return 'none';
          if ($t < $this->seconds('15:10')) return 'p5';
          if ($t < $this->seconds('16:10')) return 'p6';
        }
        return 'unknown';
    }

    //returns seconds since midnight
    private function seconds($timeString) {
      $time = explode(':', $timeString);
      $hours = $time[0];
      $minutes = $time[1];
      return $hours * 3600 + $minutes * 60;
    }

    public function termsGet($request, $response, $args)
    {
      $year = $args['year'];
      $terms = $this->fetchTerms($year);

      return emit($response, $terms);
    }

    public function fetchTerms($year)
    {
      //determine if terms exist for this year
      $t = $this->ada->select('sch_cal_terms', 'id', 'year=?', [$year]);
      if (count($t) === 0) $this->generateTerms($year);

      $terms = $this->ada->select('sch_cal_terms', "*", 'year=? ORDER BY id ASC', [$year]);
      convertArrayToAdaDatetime($terms);

      foreach($terms as &$term) {
        $term['weeks'] = $this->makeWeeks($term);
      }
      return $terms;
    }

    public function termsPut($request, $response)
    {
      $terms = $request->getParsedBody();
      foreach($terms as $term) {
        unset($term['weeks']);
        convertArrayToMysqlDatetime($term);
        $this->ada->updateObject('sch_cal_terms', $term);
      }
      foreach($terms as &$term) {
        $term['weeks'] = $this->makeWeeks($term);
      }

      return emit($response, $terms);
    }

    private function makeWeeks(&$term)
    {
       $weeks = [];
       $start = strtotime($term['start']);
       $end = strtotime($term['end']);
       $htStart = strtotime($term['htStart']);
       $htEnd = strtotime($term['htEnd']);
       $htEndDay = date('N', $htEnd);
       if ($htEndDay < 6) $htEnd = strtotime('last Sunday', $htEnd); // if we come back on a day less than
       // echo $htEndDay;
       $weekday = date('N', $start); // 1(mon)-7;

       if ($weekday == 6 || $weekday == 7) {
         // first week A will be the following Monday
         $wkBegin = strtotime('next monday', $start);
       } elseif ($weekday == 1) {
         //it's today!
         $wkBegin = $start;
       } else {
         // first week A will be the previous Monday
         $wkBegin = strtotime('last monday', $start);
       }

       $wb = new \DateTime();
       // $wb->setTimestamp($wkBegin < $start ? $start : $wkBegin);
       $wb->setTimestamp($start);
        // $wb->setTimestamp($wkBegin);

       $wkEnd = strtotime('next Sunday', $wkBegin);
       $we = new \DateTime();
       $we->setTimestamp($wkEnd);

       $wkType = 'A';

       $weeks[] = [
         'wb' => $wb->format('d-m-Y'),
         'we' => $we->format('d-m-Y'),
         'type' => $wkType
       ];

       while ($wkBegin < $end) {
         $wkBegin = strtotime('next monday', $wkBegin);
         $wkEnd = strtotime('next Sunday', $wkEnd);
         if ($wkBegin >= $htStart && $wkBegin <= $htEnd) continue;
         $wkType = $wkType == 'A' ? 'B' : 'A';
         $wb->setTimestamp($wkBegin);
         // $we->setTimestamp($wkEnd > $end ? $end : $wkEnd);
         $we->setTimestamp($wkEnd);
         $weeks[] = [
           'wb' => $wb->format('d-m-Y'),
           'we' => $we->format('d-m-Y'),
           'type' => $wkType
         ];
       }
       return $weeks;

    }

    private function generateTerms($year)
    {
      $sql = $this->ada;
      //LENT
      $sql->insert('sch_cal_terms', 'year, number, name, start, end', [
        $year,
        2,
        "Lent $year",
        "$year-01-02 21:00",
        "$year-03-29 12:00"
      ]);
      //SUMMER
      $sql->insert('sch_cal_terms', 'year, number, name, start, end', [
        $year,
        3,
        "Summer $year",
        "$year-04-15 21:00",
        "$year-06-29 12:00"
      ]);
      //MICH
      $sql->insert('sch_cal_terms', 'year, number, name, start, end', [
        $year,
        1,
        "Michaelmas $year",
        "$year-09-05 21:00",
        "$year-12-12 12:00"
      ]);
    }



}
