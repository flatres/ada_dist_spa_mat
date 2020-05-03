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
      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $subject = new \Entities\Academic\Subject($this->ada);
      $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);
      $subject->makeMLOProfile();
      // $subject->getExamData();
      // $subject->getSets($args['year']);
      return emit($response, $subject);
    }



}
