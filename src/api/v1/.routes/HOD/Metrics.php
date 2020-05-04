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
       $this->isams = $container->isams;
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
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.mlo');
      $this->progress->publish(0.25);

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $subject = new \Entities\Academic\Subject($this->ada);
      $this->progress->publish(0.5);
      $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);
      $this->progress->publish(0.75);
      $subject->makeMLOProfile();
      $this->progress->publish(1);
      // $subject->getExamData();
      // $subject->getSets($args['year']);
      return emit($response, $subject);
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
      $this->progress->publish(1);
      return emit($response, $subject);
    }



}
