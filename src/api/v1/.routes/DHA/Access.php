<?php

/**
 * Description

 * Usage:

 */
namespace DHA;

class Access
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------
    public function yearAccessGet($request, $response, $args)
    {

      $year = $args['year'];
      $year = new \Entities\Academic\Year($year);
      $hasAccess = $year->getAccessArrangements();
      return emit($response, $hasAccess);
    }



}
