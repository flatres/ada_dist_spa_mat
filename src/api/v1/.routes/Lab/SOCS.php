<?php

/**
 * Description

 * Usage:

 */
namespace Lab;

// define('ZMQ_SERVER', getenv("ZMQ_SERVER"));

class SOCS
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->socs = $container->socs;
    }

    public function teamsGet($request, $response, $args)
    {
      $teams = $this->socs->getTeams();
      $fix = $this->socs->getDetailedFixtures();
      $data = [
        'teams' => $teams,
        'fixtures'  => $fix
      ];
      return emit($response, $data);
    }

    public function fixturesDetailedGet($request, $response, $args)
    {
      $teams = $this->socs->getDetailedFixtures();
      return emit($response, $teams);
    }

}
