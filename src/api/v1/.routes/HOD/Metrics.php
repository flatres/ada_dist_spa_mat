<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Metrics
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------
    public function yearGet($request, $response, $args)
    {
      $subject = new \Entities\Academic\Subject($this->ada);
      $subject->byId($args['subject'])->getStudentsByYear($args['year']);
      return emit($response, $subject);
    }

    public function classGet($request, $response, $args)
    {
      $adaClass = new \Entities\Academic\AdaClass($this->ada, $args['class']);
      $adaClass->getStudentsMLO();
      return emit($response, $adaClass);
    }

    public function yearMLOGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.mlo', '');
      $this->progress->publish(0.25);

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $subject = new \Entities\Academic\Subject($this->ada);
      $this->progress->publish(0.5);
      $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);
      $this->progress->publish(0.75);
      $subject->makeMLOProfile();
      $subject->classes = null;
      $subject->sql = null;
      $subject->adaData = null;
      $this->progress->publish(1);
      // $subject->getExamData();
      // $subject->getSets($args['year']);
      return emit($response, $subject);
    }

    public function yearMetricsGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Thinking... ');
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];

      $subject = $this->getYearMetrics($subjectId, $year, $examId);

      $subject->classes = null;
      $subject->sql = null;
      $subject->adaData = null;
      
      return emit($response, $subject);
    }

    public function yearMetricsSpreadsheetGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Generating spreadsheet...');
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];

      $this->progress->publish(0.25, 'Gathering data...');
      $subject = $this->getYearMetrics($subjectId, $year, $examId);
      $subject->makeMLOProfile();
      $subject->makeHistoryProfile($examId, $year);

      $this->progress->publish(0.5, 'Generating spreadsheet...');
      foreach($subject->students as &$s) $s->getHMNote();
      $sheet = new \HOD\ExamMetricsSpreadsheet($subject);

      $subject->classes = null;
      $subject->sql = null;
      $subject->adaData = null;

      return emit($response, $sheet->package);
    }

    private function getYearMetrics($subjectId, $year, $examId)
    {
      $this->progress->publish(0.1);
      $subject = new \Entities\Academic\Subject($this->ada);

      $this->progress->publish(0.25);
      $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);

      $this->progress->publish(0.5);
      $subject->makeMLOProfile();

      $this->progress->publish(0.75);
      $metrics = new \Entities\Metrics\ExamMetrics($examId, $subject->students, $year);
      $subject->metrics = $metrics->metrics;
      $subject->metricWeightings = $metrics->weightings;

      $this->progress->publish(1);
      return $subject;
    }

    public function yearHistoryGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.history');
      $this->progress->publish(0.25);

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $subject = new \Entities\Academic\Subject($this->ada);
      $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);
      $this->progress->publish(0.5);
      $subject->makeMLOProfile();
      $this->progress->publish(0.75);
      $subject->makeHistoryProfile($examId, $year);
      // $subject->getExamData();
      // $subject->getSets($args['year']);
      $subject->classes = null;
      $subject->students = null;
      $subject->sql = null;
      $subject->adaData = null;
      $subject->examId = (int)$examId;
      $this->progress->publish(1);
      return emit($response, $subject);
    }



}
