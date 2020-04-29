<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Years
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------
    public function yearsGet($request, $response, $args)
    {
      $data = [
        [
          'id'  => 13,
          'name'  => 'U6'
        ],
        [
          'id'  => 11,
          'name'  => 'Hundred'
        ]
      ];
      return emit($response, $data);
    }



}
