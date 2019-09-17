<?php

/**
 * Description

 * Usage:

 */
namespace Watch;

class Exgarde
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       // $this->adaModules = $container->adaModules;
       // $this->msSql = $container->msSql;
       $this->exgarde = $container->exgarde;
    }

// ROUTE -----------------------------------------------------------------------------

    public function PeopleGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getAllPeople());
    }

    public function PersonGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getPerson($args['id']));
    }

    public function PersonByDateGet($request, $response, $args)
    {
      $date = strtotime($args['date']);
      $id = $args['id'];
      return emit($response, $this->exgarde->getPersonByDate($date, $id));
    }

    public function AreasGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getAreas());
    }

    public function AreaGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getArea($args['id']));
    }

    public function LocationsGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getLocations());
    }

    public function LocationGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getLocation($args['id']));
    }

    public function LocationByDateGet($request, $response, $args)
    {
      $date = strtotime($args['date']);
      $id = $args['id'];
      return emit($response, $this->exgarde->getLocationByDate($date, $id));
    }

    public function TestGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');

      $this->progress = new \Sockets\Progress($auth, 'Watch/Exgarde/Test');
      $students = new \Entities\People\AllStudents($this->ada);
      $i = 0;
      $this->exgarde->initialiseTest($students);
      $count = count($students->list);
      foreach($students->list as $student){
          $i++;
          $this->exgarde->match($student);
          $this->progress->publish($i/$count);
      }
      rsort($this->exgarde->adaNames);
      $data = [
        'all' => array_values($students->list),
        'matched' => array_values($this->exgarde->adaMatched),
        'unmatched' => array_values($this->exgarde->adaUnmatched),
        'namesForSearch' => array_values($this->exgarde->namesForSearch),
        'adaNames' => array_values($this->exgarde->adaNames),
        'count_unmatched' => count($this->exgarde->adaUnmatched),
        'count_matched' => count($this->exgarde->adaMatched)
      ];
      return emit($response, $data);
    }

}
