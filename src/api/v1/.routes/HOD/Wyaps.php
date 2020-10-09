<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Wyaps
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

    public function wyapsGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.wyaps', 'Thinking... ');
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];

      $subject = $this->getYearWyaps($subjectId, $year, $examId);
      $subject = [];
      // $subject->classes = null;
      // $subject->sql = null;
      // $subject->adaData = null;

      return emit($response, $subject);
    }

    private function getYearWyaps($subjectId, $year, $examId)
    {
      // $this->progress->publish(0.1);
      // $subject = new \Entities\Academic\Subject($this->ada);
      //
      // $this->progress->publish(0.25);
      // $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);
      //
      // $this->progress->publish(0.5);
      // $subject->makeMLOProfile();
      //
      // $this->progress->publish(0.75);
      // $metrics = new \Entities\Metrics\ExamMetrics($examId, $subject->students, $year);
      // $subject->metrics = $metrics->metrics;
      // $subject->metricWeightings = $metrics->weightings;
      //
      // $this->progress->publish(1);
      // return $subject;
    }


}
