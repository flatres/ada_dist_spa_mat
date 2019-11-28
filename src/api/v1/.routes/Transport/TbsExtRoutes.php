<?php

/**
 * Description

 * Usage:

 */
namespace Transport;

class TbsExtRoutes
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;

    }

    public function routesGet($request, $response, $args)
    {
        $sessionId = $args['sessionId'];
        $sessions = $this->adaModules->select('tbs_coaches_routes', 'id, sessionId, name, isReturn, cost', 'sessionId = ? ORDER BY name ASC', [$sessionId]);

        return emit($response, $sessions);
    }

    public function routePost($request, $response)
    {
     $data = $request->getParsedBody();
     $data['id'] = $this->adaModules->insert('tbs_coaches_routes', 'sessionId, name, isReturn', [
       $data['sessionId'],
       $data['name'],
       $data['isReturn']
     ]);

     //put in a stop: parade ground
     $this->adaModules->insert('tbs_coaches_stops', 'routeId, name, time', [
       $data['id'],
       'Parade Ground',
       '00:00'
     ]);

     return emit($response, $data);
   }

   public function routePut($request, $response)
   {
    $data = $request->getParsedBody();
    $this->adaModules->update('tbs_coaches_routes', 'sessionId=?, name=?, isReturn=?', 'id=?', [
      $data['sessionId'],
      $data['name'],
      $data['isReturn'],
      $data['id']
    ]);
    return emit($response, $data);
   }

   public function routeDelete($request, $response, $args)
   {
       $id = $args['id'];
       $this->adaModules->delete('tbs_coaches_routes', 'id=?', [$id]);

       return emit($response, $id);
   }

   public function routeGet($request, $response, $args)
   {
       $id = $args['id'];
       $d = $this->adaModules->select('tbs_coaches_routes', 'id, sessionId, name, isReturn, cost', 'id = ?', [$id]);

       if(!isset($d[0])) return emit($response, []);
       $route = $d[0];
       //STOPS
       $route['stops'] = $this->adaModules->select(
         'tbs_coaches_stops',
         'id, routeId, name, time',
          'routeId = ? ORDER BY Time ASC, id ASC',
          [$id]
        );

        foreach($route['stops'] as &$stop) {
          $dateTime = new \DateTime($stop['time']);
          $stop['time'] = $dateTime->format('H:i');
        }
        
        //COACHES
        $route['coaches'] = $this->adaModules->select(
          'tbs_coaches_coaches',
          'id, routeId, capacity',
           'routeId = ? ORDER BY id ASC',
           [$id]
         );
       return emit($response, $route);
   }

   public function stopPost($request, $response)
   {
    $data = $request->getParsedBody();
    $data['id'] = $this->adaModules->insert('tbs_coaches_stops', 'routeId, name', [
      $data['routeId'],
      $data['name']
    ]);
    return emit($response, $data);
  }

  public function stopPut($request, $response)
  {
   $data = $request->getParsedBody();
   $this->adaModules->update('tbs_coaches_stops', 'routeId=?, name=?, time=?', 'id=?', [
     $data['routeId'],
     $data['name'],
     $data['time'],
     $data['id']
   ]);
   return emit($response, $data);
  }
  
  public function stopDelete($request, $response, $args)
  {
      $id = $args['id'];
      $this->adaModules->delete('tbs_coaches_stops', 'id=?', [$id]);

      return emit($response, $id);
  }
  
  public function coachPost($request, $response)
  {
   $data = $request->getParsedBody();
   $data['id'] = $this->adaModules->insert('tbs_coaches_coaches', 'routeId, capacity', [
     $data['routeId'],
     $data['capacity']
   ]);
   return emit($response, $data);
 }

 public function coachPut($request, $response)
 {
  $data = $request->getParsedBody();
  $this->adaModules->update('tbs_coaches_coaches', 'routeId=?, capacity=?', 'id=?', [
    $data['routeId'],
    $data['capacity'],
    $data['id']
  ]);
  return emit($response, $data);
 }
 
 public function coachDelete($request, $response, $args)
 {
     $id = $args['id'];
     $this->adaModules->delete('tbs_coaches_coaches', 'id=?', [$id]);

     return emit($response, $id);
 }
 
 public function copyRoutesPost($request, $response, $args)
 {
    $sql = $this->adaModules;
    //to from are sessionIds
    $from = $args['from'];
    $to = $args['to'];
    
    //copy routes
    $routes = $sql->select('tbs_coaches_routes', 'id, sessionId, name, isReturn, cost', 'sessionId=?', [$from]);
    foreach($routes as $route){
      $newRouteId = $sql->insert('tbs_coaches_routes', 'sessionId, name, isReturn, cost', [
        $to,
        $route['name'],
        $route['isReturn'],
        $route['cost']
      ]);
      //get stops
      $stops = $sql->select('tbs_coaches_stops', 'name, time', 'routeId = ?', [$route['id']]);
      foreach($stops as $stop) {
        $sql->insert('tbs_coaches_stops', 'routeId, name, time', [
          $newRouteId,
          $stop['name'],
          $stop['time']
        ]);
      }
      //get coaches
      $coaches = $sql->select('tbs_coaches_coaches', 'capacity', 'routeId = ?', [$route['id']]);
      foreach($coaches as $coach) {
        $sql->insert('tbs_coaches_coaches', 'routeId, capacity', [
          $newRouteId,
          $coach['capacity']
        ]);
      }
    }

     return emit($response, $newRouteId);
 }



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
