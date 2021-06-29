<?php

/**
 * Description

 * Usage:

 */
namespace Admin\School;

class People
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------

    public function isamsStudent_GET($request, $response, $args)
    {
      $id = $args['id'];
      $adaStudent = new \Entities\People\Student($this->ada, $id);
      $student = (new \Entities\People\iSamsStudent($this->isams))->byAdaId($id)->getAll();
      return emit($response, $student);
    }

    public function student_GET($request, $response, $args)
    {
      $id = $args['id'];
      $student = (new \Entities\People\Student($this->ada, $id))->getAll();
      return emit($response, $student);
    }


}
