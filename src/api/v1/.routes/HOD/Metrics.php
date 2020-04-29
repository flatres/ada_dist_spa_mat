<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class METRICS
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
      $subject = new \Entities\Academic\iSamsSubject($this->isams);
      $subject->byAdaId($args['subject'])->studentsByYear($args['year']);
      return emit($response, $subject);
    }

    public function yearMLOGet($request, $response, $args)
    {
      $subject = new \Entities\Academic\iSamsSubject($this->isams);
      $subject->byAdaId($args['subject'])->studentsByYear($args['year']);
      // $subject->getSets($args['year']);
      return emit($response, $subject);
    }



}
