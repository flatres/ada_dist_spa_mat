<?php

/**
 * Description

 * Usage:

 */
namespace Aux;

class Bookings
{
    protected $container;
    private $coachBookings;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;

       $this->coachBookings = new \Transport\TbsExtCoachesBookings($container);
    }

    public function housesGet($request, $response, $args) {
      $id = $args['registerId'];
      $coach = $this->adaModules->select('tbs_coaches_coaches', 'id', 'uniqueId=?', [$id]);
      if (!isset($coach[0])) return;
      $d = $this->ada->select('sch_houses', 'id, name, code', 'id > ? ORDER BY code ASC', [0]);
      // $all = new \Entities\Houses\All();
      return emit($response, $d);
    }

    public function studentsGet($request, $response, $args) {
      $id = $args['registerId'];
      $coach = $this->adaModules->select('tbs_coaches_coaches', 'id', 'uniqueId=?', [$id]);
      if (!isset($coach[0])) return;

      $students = $this->ada->select(
      'stu_details',
      'id, firstname as firstName, lastname as lastName, boardingHouseId as houseId, NCYear as year',
      'disabled=0 ORDER BY lastname ASC',
      []);

      // $all = new \Entities\Houses\All();
      return emit($response, $students);
    }

    // returns the coach corresponding to the unique ID so that the supervisor can register
    public function coachGet($request, $response, $args)
    {
      $uniqueId = $args['uniqueId'];
      $coach = $this->adaModules->select(
        'tbs_coaches_coaches',
        'id, routeId, capacity, code, supervisorId',
        'uniqueId = ?',
        [$uniqueId]
      );
      if (!isset($coach[0])) emit($response, []);

      $coach = $coach[0];
      $register = $this->adaModules->select(
        'tbs_coaches_bookings',
        'id as bookingId, studentId, isRegistered, stopId',
        'coachId = ? AND (statusId = 2 OR statusId = 3 OR statusId = 6 OR statusId = 8)',
        [$coach['id']]
      );

      $coach['spacesLeft'] = $coach['capacity'] - count($register);
      foreach($register as &$student){
        $s = new \Entities\People\Student($this->ada, $student['studentId']);
        $student['details'] = $s;
        $student['stop'] = $this->getStop($student['stopId']);
      }

      usort($register, function($a, $b) {
        $ad = $a['details']->lastName;
        $bd = $b['details']->lastName;
        return strcmp($ad, $bd);
      });

      $coach['register'] = $register;
      $coach['stops'] = $this->getStops($coach['id']);
      return emit($response, $coach);
    }

    private function getStops($coachId) {
      $stops = $this->adaModules->select('tbs_coaches_coach_stops', 'stopId', 'coachId=?', [$coachId]);
      if (!isset($stops[0])) return [];
      $data = [];
      foreach ($stops as $s) {
        $stop = $this->adaModules->select('tbs_coaches_stops', 'id, name', 'id=?', [$s['stopId']]);
        if (isset($stop[0])) $data[] = $stop[0];
      }
      return sortArrays($data, 'name', 'ASC');
    }

    private function getStop($id)
    {
        return $this->adaModules->select('tbs_coaches_stops', 'id, name', 'id=?', [$id])[0]['name'] ?? '';
    }

    public function registerPut($request, $response)
    {
      $data = $request->getParsedBody();

      $this->adaModules->update(
        'tbs_coaches_bookings',
        'isRegistered=?',
        'id=?',
        [$data['isRegistered'], $data['bookingId']]
      );

      $crud = new \Sockets\CRUD("aux.bookings.coach." . $data['coachUniqueId']);
      $sessionId = $this->adaModules->select('tbs_coaches_bookings', 'sessionId', 'id=?', [$data['bookingId']])[0]['sessionId'];
      $session = new \Sockets\CRUD("coaches.register{$sessionId}");
      return emit($response, $data);
    }

    public function bookingPost($request, $response)
    {
      $data = $request->getParsedBody();
      $uniqueId = $data['uniqueId'];
      $routeId = $data['routeId'];
      $studentId = $data['studentId'];
      $stopId = $data['stopId'];
      $coachId = $this->adaModules->select('tbs_coaches_coaches', 'id', 'uniqueId=?', [$uniqueId])[0]['id'];

      $route = $this->adaModules->select('tbs_coaches_routes', 'isReturn, sessionId', 'id=?', [$routeId])[0];
      $sessionId = $route['sessionId'];
      $isReturn = $route['isReturn'];

      $student = new \Entities\People\Student($this->ada, $studentId);
      $misFamilyId = $student->misFamilyId;

      $bookingId = $this->adaModules->insert(
        'tbs_coaches_bookings', 
        'studentId, mis_family_id, sessionId, isReturn, statusId, routeId, stopId, coachId, isRegistered',
        [
          $studentId,
          $misFamilyId,
          $sessionId,
          $isReturn,
          8,
          $routeId,
          $stopId,
          $coachId,
          1
        ]
      );

      $this->coachBookings->sendAdHocConfirmedEmail($bookingId);

      $crud = new \Sockets\CRUD("aux.bookings.coach." . $uniqueId);
      $session = new \Sockets\CRUD("coaches.register{$sessionId}");
      return emit($response, $data);
    }

}
