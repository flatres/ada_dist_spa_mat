<?php

/**
 * Description

 * Usage:

 */
namespace Transport;

class TbsExtTaxisAdmin
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }


// PICKUP LOCATIONS -----------------------------------------------------------------------------
    public function pickupLocationsGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('tbs_taxi_pickups', '*'));
    }

    public function pickupLocationGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('tbs_taxi_pickups', '*', 'id=?', array($args['id'])));
    }

    public function pickupLocationsPost($request, $response)
    {
      $data = $request->getParsedBody();
      $data['id'] = $this->adaModules->insertObject('tbs_taxi_pickups', $data);
      return emit($response, $data);
    }

    public function pickupLocationsPut($request, $response)
    {
      $data = $request->getParsedBody();
      return emit($response, $this->adaModules->updateObject('tbs_taxi_pickups', $data, 'id'));
    }

    public function pickupLocationsDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('tbs_taxi_pickups', 'id=?', array($args['id'])));
    }

// stations -----------------------------------------------------------------------------
    public function stationsGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('tbs_taxi_stations', '*'));
    }

    public function stationGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('tbs_taxi_stations', '*', 'id=?', array($args['id'])));
    }

    public function stationPost($request, $response)
    {
      $data = $request->getParsedBody();
      $data['id'] = $this->adaModules->insertObject('tbs_taxi_stations', $data);
      return emit($response, $data);
    }

    public function stationPut($request, $response)
    {
      $data = $request->getParsedBody();
      return emit($response, $this->adaModules->updateObject('tbs_taxi_stations', $data, 'id'));
    }

    public function stationDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('tbs_taxi_stations', 'id=?', array($args['id'])));
    }

// Airports -----------------------------------------------------------------------------
    public function airportsGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('tbs_taxi_airports', '*'));
    }

    public function airportGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('tbs_taxi_airports', '*', 'id=?', array($args['id'])));
    }

    public function airportPost($request, $response)
    {
      $data = $request->getParsedBody();
      $data['id'] = $this->adaModules->insertObject('tbs_taxi_airports', $data);
      return emit($response, $data);
    }

    public function airportPut($request, $response)
    {
      $data = $request->getParsedBody();
      return emit($response, $this->adaModules->updateObject('tbs_taxi_airports', $data, 'id'));
    }

    public function airportDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('tbs_taxi_airports', 'id=?', array($args['id'])));
    }

// COMPANIES -----------------------------------------------------------------------------
    public function companiesGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('tbs_taxi_companies', '*'));
    }

    public function companiesPost($request, $response)
    {
      $data = $request->getParsedBody();
      $data['id'] = $this->adaModules->insertObject('tbs_taxi_companies', $data);
      return emit($response, $data);
    }

    public function companiesPut($request, $response)
    {
      $data = $request->getParsedBody();
      return emit($response, $this->adaModules->updateObject('tbs_taxi_companies', $data, 'id'));
    }

    public function companiesDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('tbs_taxi_companies', 'id=?', array($args['id'])));
    }

}
