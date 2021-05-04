<?php

/**
 * Description

 * Usage:

 */
namespace DHA;

class Years
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------
    public function yearsGet($request, $response, $args)
    {
      $years = [];

      $years[] =   [
          'id'  => 13,
          'name'  => 'U6'
        ];

      $years[] =   [
          'id'  => 12,
          'name'  => 'L6'
        ];

      $years[] =   [
          'id'  => 11,
          'name'  => 'Hundred'
        ];

      $years[] =   [
          'id'  => 10,
          'name'  => 'Remove'
        ];
      $years[] =   [
          'id'  => 9,
          'name'  => 'Shell'
        ];

      return emit($response, $years);
    }



}
