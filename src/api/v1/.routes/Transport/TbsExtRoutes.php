<?php

/**
 * Description

 * Usage:

 */
namespace Transport;
define('ROOT', $_SERVER["ROOT"]);

class TbsExtRoutes
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->container = $container;
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;

       global $userId;
       $this->user = new \Entities\People\User($this->ada, $userId);
       $this->email = $this->user->email;
       $this->debug = true;

    }

    public function routesGet($request, $response, $args)
    {
        $sessionId = $args['sessionId'];
        $routes = $this->adaModules->select('tbs_coaches_routes', 'id, sessionId, name, isReturn', 'sessionId = ? ORDER BY name ASC', [$sessionId]);
        foreach($routes as &$r) {
          $route = $this->route($r['id']);
          $r['supervisorAlert'] = $route['supervisorAlert'];
          $r['alert'] = $route['alert'];
        }
        return emit($response, $routes);
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
     $this->adaModules->insert('tbs_coaches_stops', 'routeId, name, time, isSchoolLocation', [
       $data['id'],
       'Parade Ground',
       '00:00',
       1
     ]);
     $this->publish($data['id']);
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
    $this->publish($data['id']);
    return emit($response, $data);
   }

   public function routeDelete($request, $response, $args)
   { 
      $id = $args['id'];
      $bookings = $this->adaModules->select('tbs_coaches_bookings', 'id', 'routeId=? AND statusId < 4', [$id]);
      if (isset($bookings[0])) {
        return emitError($response, 400, 'All bookings on this route must be cancelled before it can be deleted');
      }

       $this->adaModules->delete('tbs_coaches_routes', 'id=?', [$id]);
       $this->adaModules->delete('tbs_coaches_stops', 'routeId=?', [$id]);
       $d = $this->adaModules->select('tbs_coaches_coaches', 'id', 'routeId=?', [$id]);
       foreach($d as $coach){
         $this->adaModules->delete('tbs_coaches_coach_stops', 'coachId=?', [$coach['id']]);
       }
       $this->adaModules->delete('tbs_coaches_coaches', 'routeId=?', [$id]);
       $this->publish($id);

       return emit($response, $id);
   }

   //used mainly when booking throuigh portal. Given a list of all stops along with the total capacity remaining from all bussed on that route
   public function allStopsWithCapacity($request, $response, $args)
   {
     $sessionId = $args['sessionId'];


   }

   public function stopsGet($request, $response, $args)
   {
       $sessionId = $args['sessionId'];
       $session = $this->adaModules->select('tbs_sessions', 'dateOut, dateRtn', 'id=?', [$sessionId])[0];
       $allStops = ['out' => [], 'ret' => []];
       $schoolLocation = 'Unknown';
       $schoolTime = '00:00';
       $routes = $this->adaModules->select('tbs_coaches_routes', 'id, sessionId, name, isReturn', 'sessionId = ? ORDER BY name ASC', [$sessionId]);
       foreach($routes as $r) {
         $route = $this->route($r['id']);
         $stops = $route['stops'];
         foreach($stops as $stop) {
           if ($stop['isSchoolLocation'] === 1) {
             $schoolLocation = $stop['name'];
             $schoolTime = $stop['time'];
             break;
           }
         }
        unset($stop);
        foreach($stops as &$stop) {
          if ($stop['isSchoolLocation'] === 1) continue;
          $stop['schoolLocation'] = $schoolLocation;
          $stop['schoolTime'] = $schoolTime;
          $key = $r['isReturn'] === 1 ? 'ret' : 'out';
          $stop['coaches'] = $this->getStopCoaches($stop['id']);
          $spacesLeft = 0;
          foreach($stop['coaches'] as $coach) {
            $spacesLeft += $coach['spacesLeft'];
          }
          $stop['spacesLeft'] = $spacesLeft;

          $date = $key === 'out' ? $session['dateOut'] : $session['dateRtn'];
          $stop['departTime'] = $key === 'out' ? $schoolTime : $stop['time'];
          // closes 1 hr before departure
          $stop['selfServiceCloses'] = strtotime($date . ' ' . $stop['departTime']) - 60*60;
          $stop['selfServicesClosesPretty'] = convertUnixToAdaDatetime($stop['selfServiceCloses']);
          $stop['selfServiceClosed'] = time() > $stop['selfServiceCloses'];

          $allStops[$key][$stop['name']] = $stop;
          
        }
       }

       ksort($allStops['out']);
       ksort($allStops['ret']);
       $allStops['out'] = array_values($allStops['out']);
       $allStops['ret'] = array_values($allStops['ret']);

       return emit($response, $allStops);
   }

   public function getStopCoaches($stopId) {
     $d = $this->adaModules->select('tbs_coaches_coach_stops', 'coachId', 'stopId=?', [$stopId]);
     $coaches = [];
     foreach($d as $coach) {
       $c = $this->adaModules->select('tbs_coaches_coaches', 'id, capacity, code, supervisorId', 'id=?', [$coach['coachId']]);
       if (isset($c[0])) {
         $c = $c[0];
         //get bookings to count
         $b = $this->adaModules->select('tbs_coaches_bookings', 'id', 'coachId=? AND statusId <> 4', [$coach['coachId']]);
         $bookingsCount = count($b);
         $c['bookingsCount'] = $bookingsCount;
         $c['spacesLeft'] = (int)$c['capacity'] - (int)$bookingsCount;
         $coaches[] = $c;
       }
     }
     return $coaches;
   }

   private function route($id) {
     $d = $this->adaModules->select('tbs_coaches_routes', 'id, sessionId, name, isReturn', 'id = ?', [$id]);

     if(!isset($d[0])) return emit($response, []);
     $route = $d[0];
     //STOPS
     $route['stops'] = $this->adaModules->select(
       'tbs_coaches_stops',
       'id, routeId, name, time, cost, isSchoolLocation',
        'routeId = ? ORDER BY time ASC, id ASC',
        [$id]
      );

      foreach($route['stops'] as &$stop) {
        $dateTime = new \DateTime($stop['time']);
        $stop['time'] = $dateTime->format('H:i');
      }

      //COACHES
      $route['coaches'] = $this->adaModules->select(
        'tbs_coaches_coaches',
        'id, routeId, capacity, code, supervisorId, uniqueId',
         'routeId = ? ORDER BY id ASC',
         [$id]
       );

       $supervisorAlert = false;
       $alert = false;

       $tbsExtCoachesBookings = new \Transport\TbsExtCoachesBookings($this->container);
       foreach($route['coaches'] as &$coach){
         $coach['url'] = ROOT . 'aux/bookings/coach/' . $coach['uniqueId'];
         // otherwise copies by reference and mucks everything up
         // https://stackoverflow.com/questions/1532618/is-there-a-function-to-make-a-copy-of-a-php-array-to-another
         $coach['stops'] = unserialize(serialize($route['stops']));
         $activeCount = 0;
         $stops = [];
         foreach($coach['stops'] as &$stop){
           $s = $this->adaModules->select('tbs_coaches_coach_stops', 'id', 'coachId=? AND stopId=?', [$coach['id'], $stop['id']]);
           $stop['isActive'] = isset($s[0]);
           if ($stop['isActive']) $stops['s_' . $stop['id']] = true;
           if (!$stop['isSchoolLocation'] && isset($s[0])) $activeCount++;
         }
         $coach['activeCount'] = $activeCount;
         $coach['bookings'] = $tbsExtCoachesBookings->getCoachBookings($coach['id']);
         $coach['alert'] = false;
         foreach ($coach['bookings'] as $booking) {
           if (!isset($stops['s_' . $booking['stopId']])) {
             $alert = true;
             $coach['alert'] = true;
           }
         }
         if (!$coach['supervisorId']) $supervisorAlert = true;
         $coach['supervisor'] = new \Entities\People\User($this->ada, $coach['supervisorId']);
       }

       $route['unassigned'] = $tbsExtCoachesBookings->getUnassignedBookings($id);

       //has the route got unassigned bookings or bookings assigned to coaches without that stop
       if (count($route['unassigned']) >  0) $alert = true;
       $route['alert'] = $alert;
       $route['supervisorAlert'] = $supervisorAlert;
      return $route;
   }

   public function routeGet($request, $response, $args)
   {
       $id = $args['id'];
       $route = $this->route($id);
       return emit($response, $route);
   }

   public function stopPost($request, $response)
   {
    $data = $request->getParsedBody();
    $data['id'] = $this->adaModules->insert('tbs_coaches_stops', 'routeId, name', [
      $data['routeId'],
      $data['name']
    ]);
    $coaches = $this->adaModules->select('tbs_coaches_coaches', 'id', 'routeId = ?', [$data['routeId']]);
    foreach($coaches as $coach) {
      $this->adaModules->insert('tbs_coaches_coach_stops', 'coachId, stopId', [$coach['id'], $data['id']]);
    }
    $this->publish($data['routeId']);
    return emit($response, $data);
  }

  public function stopPut($request, $response)
  {
   $data = $request->getParsedBody();
   $this->adaModules->update('tbs_coaches_stops', 'routeId=?, name=?, time=?, cost=?', 'id=?', [
     $data['routeId'],
     $data['name'],
     $data['time'],
     $data['cost'],
     $data['id']
   ]);
   $this->publish($data['routeId']);
   return emit($response, $data);
  }

  public function coachStopPut($request, $response)
  {
   $data = $request->getParsedBody();
   $stopId = $data['stopId'];
   $coachId = $data['coachId'];
   $isActive = $data['isActive'];

   if ($isActive === 1 || $isActive === true) {
     $this->adaModules->insert('tbs_coaches_coach_stops', 'coachId, stopId', [$coachId, $stopId]);
   } else {
     $this->adaModules->update('tbs_coaches_bookings', 'coachId=?, statusId=?', 'coachId=? AND stopId=?', [null, 1, $coachId, $stopId]);
     $this->adaModules->delete('tbs_coaches_coach_stops', 'coachId=? AND stopId=?', [$coachId, $stopId]);
   }
   $this->publishByCoach($coachId);
   return emit($response, $data);
  }

  public function stopDelete($request, $response, $args)
  {
      $id = $args['id'];

      $routeId = $this->adaModules->select('tbs_coaches_stops', 'routeId', 'id=?', [$id])[0]['routeId'] ?? null;

      $this->adaModules->delete('tbs_coaches_stops', 'id=?', [$id]);
      $this->adaModules->delete('tbs_coaches_coach_stops', 'stopId=?', [$id]);

      if ($routeId) $this->publish($routeId);

      return emit($response, $id);
  }

  public function coachPost($request, $response)
  {
   $data = $request->getParsedBody();
   $data['id'] = $this->adaModules->insert('tbs_coaches_coaches', 'routeId, code, capacity, uniqueId', [
     $data['routeId'],
     $data['code'],
     $data['capacity'],
     uniqid()
   ]);
   // copy all stops from the route to the new coach
   $stops = $this->adaModules->select('tbs_coaches_stops', 'id', 'isSchoolLocation = 0 AND routeId = ? ORDER BY TIME ASC', [$data['routeId']]);
   foreach($stops as $stop) {
     $this->adaModules->insert('tbs_coaches_coach_stops', 'coachId, stopId', [$data['id'], $stop['id']]);
   }

   $this->publish($data['routeId']);
   return emit($response, $data);
 }

 public function coachPut($request, $response)
 {
  $data = $request->getParsedBody();
  $bookings = $this->adaModules->select('tbs_coaches_bookings', 'id', 'coachId=?', [$data['id']]);
  if (count($bookings) > $data['capacity']) {
    return emitError($response, 503, 'Capacity can not be less than the number of assigned bookings.');
  }
  $this->adaModules->update('tbs_coaches_coaches', 'routeId=?, capacity=?, code=?', 'id=?', [
    $data['routeId'],
    $data['capacity'],
    $data['code'],
    $data['id']
  ]);

  $this->publish($data['routeId']);
  return emit($response, $data);
 }

 public function coachDelete($request, $response, $args)
 {
     $id = $args['id'];
     $this->adaModules->delete('tbs_coaches_coaches', 'id=?', [$id]);
     $this->adaModules->delete('tbs_coaches_coach_stops', 'coachId=?', [$id]);
     //un assign any bookings
     $this->adaModules->update('tbs_coaches_bookings', 'coachId=?, statusId=?', 'coachId=?', [null, 1, $id]);
     $this->publishByCoach($id);
     return emit($response, $id);
 }

 public function coachRegisterEmailPost($request, $response, $args){
    $coachId = $args['id'];
    $this->sendRegisterEmail($coachId);
    return emit($response, $coachId);
 }

 public function sendAllRegistersPost($request, $response, $args)
 {
    $sessionId = $args['sessionId'];
    $routes = $this->adaModules->select('tbs_coaches_routes', 'id', 'sessionId=?', [$sessionId]);
    foreach ($routes as $route) {
      $coaches = $this->adaModules->select('tbs_coaches_coaches', 'id, supervisorId', 'routeId=? AND registerSent = 0', [$route['id']]);
      foreach ($coaches as $coach) {
        if ($coach['supervisorId']) {
          $this->sendRegisterEmail($coach['id']);
          $this->adaModules->update('tbs_coaches_coaches', 'registerSent=?', 'routeId=?', [1, $route['id']]);
        }
      }
    }
    return emit($response, $sessionId);
  }

  public function registerGet($request, $response, $args) {
      $sessionId = $args['sessionId'];
      $bookings = $this->adaModules->select('tbs_coaches_bookings', 'studentId, stopId, coachId, isReturn, isRegistered', 'sessionId=?', [$sessionId]);
      $students = [];
      foreach($bookings as $b) {
        $b = (object)$b;
        $key = 's_' . $b->studentId;
        if (!isset($students[$key])) $students[$key] = new \Entities\People\Student($this->ada, $b->studentId);
        $students[$key]->cost = 0;

        $coachCode = $this->adaModules->select('tbs_coaches_coaches', 'code', 'id=?', [$b->coachId]);
        $coachCode = $coachCode ? $coachCode[0]['code'] : '?';
        $b->coachCode = $coachCode;
        $stop = $this->adaModules->select('tbs_coaches_stops', 'name, cost', 'id=?', [$b->stopId])[0];
        $b->stopName = $stop['name'] . " [$coachCode]";
        $b->cost = $stop['cost'];
        $students[$key]->cost += $b->cost;

        $dir = $b->isReturn === 1 ? 'ret' : 'out';
        $students[$key]->{$dir} = $b;
      }
      $students = array_values($students);
      return emit($response, sortObjects($students, 'displayName', 'ASC'));
  }

 public function copyRoutesPost($request, $response, $args)
 {
    $sql = $this->adaModules;
    //to from are sessionIds
    $from = $args['from'];
    $to = $args['to'];

    //copy routes
    $routes = $sql->select('tbs_coaches_routes', 'id, sessionId, name, isReturn', 'sessionId=?', [$from]);
    foreach($routes as $route){
      $newRouteId = $sql->insert('tbs_coaches_routes', 'sessionId, name, isReturn', [
        $to,
        $route['name'],
        $route['isReturn']
      ]);
      //get stops
      $stopMap = [];
      $stops = $sql->select('tbs_coaches_stops', 'id, name, time, cost, isSchoolLocation', 'routeId = ?', [$route['id']]);
      foreach($stops as $stop) {
        $id = $sql->insert('tbs_coaches_stops', 'routeId, name, time, cost, isSchoolLocation', [
          $newRouteId,
          $stop['name'],
          $stop['time'],
          $stop['cost'],
          $stop['isSchoolLocation']
        ]);
        $stopMap["s_" . $stop['id']] = $id;
      }
      //get coaches
      $coachMap = [];
      $coaches = $sql->select('tbs_coaches_coaches', 'id, capacity, code', 'routeId = ?', [$route['id']]);
      foreach($coaches as $coach) {
        $id = $sql->insert('tbs_coaches_coaches', 'routeId, capacity, code, uniqueId', [
          $newRouteId,
          $coach['capacity'],
          $coach['code'],
          uniqid()
        ]);
        $coachMap["c_" . $coach['id']] = $id;
      }

      unset($coach);
      //copy coach stops using maps
      foreach($coaches as $coach) {
        $cs = $this->adaModules->select('tbs_coaches_coach_stops', 'stopId', 'coachId=?', [$coach['id']]);
        foreach($cs as $coachStop) {
          $oldStopId = $coachStop['stopId'];
          $newStopId = $stopMap["s_" . $oldStopId];
          $newCoachId = $coachMap["c_" . $coach['id']];
          $this->adaModules->insert('tbs_coaches_coach_stops', 'coachId, stopId', [$newCoachId, $newStopId]);
        }
      }
    }
     $this->publish($newRouteId);
     return emit($response, $newRouteId);
 }

 public function supervisorPut($request, $response, $args)
 {
     $coachId = $args['coachId'];
     $supervisorId = $args['supervisorId'];

     //get old supervisor
     $oldId = $this->adaModules->select('tbs_coaches_coaches', 'supervisorId', 'id=?', [$coachId])[0]['supervisorId'] ?? null;
     if ($oldId !== $supervisorId) {
       $this->adaModules->update('tbs_coaches_coaches', 'supervisorId=?, registerSent = 0', 'id=?', [$supervisorId, $coachId]);
       $this->publishByCoach($coachId);
     }
     return emit($response, $supervisorId);
 }

 private function retrieveRoute(int $id)
 {
   $route = $this->adaModules->select('tbs_coaches_routes', '*', 'id = ?', [$id]);
   $route = $route[0] ?? false;
   return $route;
 }

 private function publishByCoach(int $id)
 {
   $coach = $this->adaModules->select('tbs_coaches_coaches', '*', 'id = ?', [$id]);
   $routeId = $coach[0]['routeId'] ?? false;
   if ($routeId) $this->publish($routeId);
 }

 private function publish(int $routeId) {
   $route = $this->retrieveRoute($routeId);
   $session = new \Sockets\CRUD("routes{$route['sessionId']}");
 }


 private function sendRegisterEmail(int $coachId)
 {
   
   $coach = $this->adaModules->select('tbs_coaches_coaches', 'code, supervisorId, uniqueId', 'id = ?', [$coachId])[0];
   // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

   $supervisor = new \Entities\People\User($this->ada, $coach['supervisorId']);
   $subject = 'Coach Register - ' . $coach['code'];
   $to = $supervisor->email;

   $email = new \Utilities\Email\Email($to, $subject, 'coaches@marlboroughcollege.org', [], [], $this->debug);
   $coach['url'] = 'https://ada.marlboroughcollege.org/aux/bookings/coach/' . $coach['uniqueId'];
   $coach['supervisorName'] = $supervisor->firstName;
   

   $content = $email->template('TBS.CoachRegister', $coach);
  
   $res = $email->send($content);
    
   $routeId = $this->adaModules->select('tbs_coaches_coaches', 'routeId', 'id=?', [$coachId])[0]['routeId'];
   $sessionId = $this->adaModules->select('tbs_coaches_routes', 'sessionId', 'id=?', [$routeId])[0]['sessionId'];
   
   $this->adaModules->insert(
        'tbs_emails',
        'isTaxi, sessionId, email, subject, content',
        [
          0,
          $sessionId,
          $to,
          $subject,
          $content
        ]
      );

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
