<?php

/**
 * Description

 * Usage:

 */
namespace Portal\Transport;

class Bookings
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->container = $container;
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------
// returns a list of students for the given familly ID
    public function activeSessionGet($request, $response, $args)
    {
      $d = $this->adaModules->select('tbs_sessions', '*', 'isActive=?', [1]);
      if (!isset($d[0])) return emit($response, []);
      convertArrayToAdaDatetime($d);
      return emit($response, $d[0]);
    }
    
    public function familyBookingsGet($request, $response, $args)
    {
      $familyId = $args['familyId'];
      $taxiBookings = new \Transport\TbsExtTaxisBookings($this->container);
      $bookings = $taxiBookings->familyBookings($familyId);
      
      return emit($response, $bookings);
    }

    // public function ROUTEPost($request, $response)
    // {
    //   $data = $request->getParsedBody();
    //   $data['id'] = $this->adaModules->insertObject('TABLE', $data);
    //   return emit($response, $data);
    // }
    //
    // public function ROUTELocationsPut($request, $response)
    // {
    //   $data = $request->getParsedBody();
    //   return emit($response, $this->adaModules->updateObject('TABLE', $data, 'id'));
    // }
    //
    // public function ROUTEDelete($request, $response, $args)
    // {
    //   return emit($response, $this->adaModules->delete('TABLE', 'id=?', array($args['id'])));
    // }

}
