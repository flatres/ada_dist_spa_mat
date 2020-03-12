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
    public function TermsGet($request, $response, $args)
    {
      $year = $args['year'];

      //determine if terms exist for this year
      $t = $this->ada->select('sch_cal_terms', 'id', 'year=?', [$year]);
      if (count($t) === 0) $this->generateTerms($year);

      $terms = $this->ada->select('sch_cal_terms', "*", 'year=? ORDER BY id ASC', [$year]);
      convertArrayToAdaDatetime($terms);
      return emit($response, $terms);
    }

    public function TermsPut($request, $response) {
      $terms = $request->getParsedBody();
      convertArrayToMysqlDatetime($terms);
      foreach($terms as $term) {
        $this->ada->updateObject('sch_cal_terms', $term);
      }

      return emit($response, $terms);
    }

    private function generateTerms($year){
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
