<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Science
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaData = $container->adaData;
       $this->adaModules = $container->adaModules;
    }

    public function tagsSpreadsheetGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Standby Gary...');
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = 11;
      $examId = $args['exam'];

      $this->progress->publish(0.25, 'Gathering data...');
      $subject = $this->getYearMetrics($subjectId, $year, $examId);
      // $subject->makeMLOProfile();
      $subject->makeHistoryProfile($examId, $year);

      // get wyaps, including results
      $wyaps = (new \Entities\Academic\Subject($this->ada, $subjectId))->getWYAPsByExam($year, $examId);

      foreach($wyaps as &$w) $w->results();
      unset($w);

      $this->progress->publish(0.5, 'Generating spreadsheet...');
      foreach($subject->students as &$s) $s->getHMNote();
      $sheet = new \HOD\ExamMetricsSpreadsheet($subject, $wyaps);

      $subject->classes = null;

      $package = $sheet->package;
      $package['subject'] = $subject;
      $package['wyaps'] = $wyaps;
      // return emit($response, $subject);
      return emit($response, $package);
    }



}
