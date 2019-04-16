<?php

/**
 * Description

 * Usage:

 */
namespace Transport;

class TbsExtTaxisBookings
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

//
    public function bookingsGet($request, $response, $args)
    {
      $sessID = $args['session'];
      return emit($response, $this->adaModules->select('tbs_taxi_bookings', '*', 'session_id = ?', array($sessID)));
    }

    public function bookingGet($request, $response, $args)
    {
      $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ?', array($args['id']));
      return emit($response, $data);
    }

    public function bookingPost($request, $response)
    {
      $data = $request->getParsedBody();
      $data['session_id']=$args['session'];

      $data['id'] = $this->adaModules->insertObject('tbs_taxi_bookings', $data);
      return emit($response, $data);
    }

    public function bookingPut($request, $response)
    {
      $data = $request->getParsedBody();
      return emit($response, $this->adaModules->updateObject('tbs_taxi_bookings', $data, 'id'));
    }

    public function bookingDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('tbs_taxi_bookins', 'id=?', array($args['id'])));
    }

//
//
// // COMPANIES -----------------------------------------------------------------------------
//     public function companiesGet($request, $response, $args)
//     {
//       return emit($response, $this->adaModules->select('tbs_taxi_companies', '*'));
//     }
//
//     public function companiesPost($request, $response)
//     {
//       $data = $request->getParsedBody();
//       $data['id'] = $this->adaModules->insertObject('tbs_taxi_companies', $data);
//       return emit($response, $data);
//     }
//
//     public function companiesPut($request, $response)
//     {
//       $data = $request->getParsedBody();
//       return emit($response, $this->adaModules->updateObject('tbs_taxi_companies', $data, 'id'));
//     }
//
//     public function companiesDelete($request, $response, $args)
//     {
//       return emit($response, $this->adaModules->delete('tbs_taxi_companies', 'id=?', array($args['id'])));
//     }

}
