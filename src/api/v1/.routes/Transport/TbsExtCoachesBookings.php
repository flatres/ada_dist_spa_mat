<?php

/**
 * Description

 * Usage:

 */
namespace Transport;

class TbsExtCoachesBookings
{
    protected $container;
    private $user, $email;
    private $oldCoachId = false;
    private $debug = true;

    public function __construct(\Slim\Container $container)
    {
       $this->container = $container;
       $this->isams = $container->isams;
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->student = new \Entities\People\Student($this->ada);

       $this->status = $this->getAllStatus();

       global $userId;
       $this->user = new \Entities\People\User($this->ada, $userId);
       $this->email = $this->user->email;

    }

    private function getAllStatus() {
      $d = $this->adaModules->select('tbs_coaches_status', 'id, name', '1=1', array());
      $status = array();
      foreach ($d as $s) {
        $status['s_' . $s['id']] = $s['name'];
      }
      return $status;
    }

    private function getSession($id)
    {
      $session = $this->adaModules->select(
        'tbs_sessions',
        '*',
        'id=?',
        [$id]);

      convertArrayToAdaDatetime($session);
      return isset($session[0]) ? $session[0] : [];
    }

    public function familyBookings($familyId)
    {
      $data = $this->adaModules->select('tbs_coaches_bookings', '*', 'mis_family_id=? ORDER BY sessionId DESC', [$familyId]);
      foreach ($data as &$booking) {
        $booking['type'] = 'coaches';
        $booking = $this->makeDisplayValues($booking);
      }
      return $data;
    }

    public function userBookings($contactIsamsUserId)
    {
      $data = $this->adaModules->select('tbs_coaches_bookings', '*', 'contactIsamsUserId=? ORDER BY sessionId DESC', [$contactIsamsUserId]);
      foreach ($data as &$booking) {
        $booking['type'] = 'coaches';
        $booking = $this->makeDisplayValues($booking);
        $booking['session'] = $this->getSession($booking['sessionId']);
      }
      return $data;
    }

    public function allBookingsGet($request, $response, $args)
    {
      $sessID = $args['session'];

      $data = $this->adaModules->select('tbs_coaches_bookings', '*', 'sessionId = ? ORDER BY id DESC', [$sessID]);

      $status = $this->getAllStatus();

      foreach ($data as &$booking) {
        $booking = $this->makeDisplayValues($booking);
      }
      return emit($response, $data);
    }

    public function pupilBookingsGet($request, $response, $args)
    {
      $sessId = $args['sessionId'];
      $studentId = $args['studentId'];

      $ret = $this->adaModules->select(
        'tbs_coaches_bookings', 
        '*', 
        'sessionId = ? AND studentId = ? AND isReturn = 1 AND statusId < 4', 
        [$sessId, $studentId]);

      $out = $this->adaModules->select(
        'tbs_coaches_bookings', 
        '*', 
        'sessionId = ? AND studentId = ? AND isReturn = 0 AND statusId < 4', 
        [$sessId, $studentId]);  

      $data = [
        'hasOut' => isset($out[0]),
        'hasRet' => isset($ret[0]),
      ];
      
      return emit($response, $data);
    }

    public function bookingsGet($request, $response, $args)
    {
      $sessID = $args['session'];
      $type = $args['type'];
      $isReturn = $type === 'out' ? 0 : 1;
      $isCancelled = $type == 'can' ? true : false;

      if (!$isCancelled) {
        $data = $this->adaModules->select('tbs_coaches_bookings', '*', 'sessionId = ? AND isReturn = ? AND statusId > 0 ORDER BY statusId ASC, studentId DESC', [$sessID, $isReturn]);
      } else {
        $data = $this->adaModules->select('tbs_coaches_bookings', '*', 'sessionId = ? AND statusId = 4', [$sessID]);
      }
      $status = $this->getAllStatus();

      foreach ($data as &$booking) {
        $booking = $this->makeDisplayValues($booking);
      }
      return emit($response, $data);
    }

    public function bookingsNewCountGet($request, $response, $args)
    {
      $sessID = $args['session'];

      $data = $this->adaModules->select('tbs_coaches_bookings', 'id', 'sessionId = ? AND statusId = 1', [$sessID]);

      return emit($response, count($data));
    }

    private function makeDisplayValues($booking)
    {
      $this->status = $this->getAllStatus();

      $status = $this->status;
      $booking['status'] = $status['s_' . $booking['statusId']];
      $booking['displayName'] = $this->student->displayName($booking['studentId']);

      if ($booking['routeId']) {
        $r = $this->adaModules->select('tbs_coaches_stops', 'name, time, cost', 'routeId=? AND isSchoolLocation=?', [$booking['routeId'], 1]);
        if (isset($r[0])) {
          $booking['schoolLocation'] = $r[0]['name'];
          $booking['schoolTime'] = tidyTime($r[0]['time']); 
        }
      }

      if ($booking['stopId']){
        $stop = $this->getStop($booking['stopId']);
        $booking['stop'] = $stop['name'];
        $booking['stopTime'] = tidyTime($stop['time']);
        $booking['cost'] = $stop['cost'];
      }

      if ($booking['coachId']) {
        $c = $this->adaModules->select('tbs_coaches_coaches', 'code', 'id=?', [$booking['coachId']]);
        $booking['coachCode'] = $c[0]['code'] ?? '';
        $s = $this->adaModules->select('tbs_coaches_coach_stops', 'id', 'coachId=? AND stopID=?', [$booking['coachId'], $booking['stopId']]);
        $booking['coachHasStop'] = isset($s[0]);
        //check to make sure that the coach still goes to these stops
      }

      // contact

      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $booking['schoolNumber'] = $student->schoolNumber;
      $booking['house'] = $student->boardingHouse;
      $isamsStudent = new \Entities\People\iSamsStudent($this->isams, $student->misId);
      $booking['mob'] = $isamsStudent->mobile;

      $booking['contact'] = (object)$isamsStudent->getContactByUserId($booking['contactIsamsUserId']);

      // dates
      $d = $this->adaModules->select('tbs_sessions', 'dateOut, dateRtn', 'id=?', [$booking['sessionId']]);
      if (isset($d[0])){
        $date = $booking['isReturn'] ? $d[0]['dateRtn'] : $d[0]['dateOut'];
        $booking['date'] = convertToAdaDate($date);
      }

      return $booking;
    }

    private function getStop($stopId)
    {
      $d = $this->adaModules->select('tbs_coaches_stops', 'id, name, cost, time', 'id=?', [$stopId]);
      return $d[0] ?? [
        'id'    => '',
        'name'  => '',
        'cost'  => '',
        'time'  => ''
      ];
    }

    public function coachAssigmentPut($request, $response)
    {
      $data = $request->getParsedBody();

      $coachId = $data['coachId'];
      $bookingId = $data['id'] === 0 ? null : $data['id'];

      //get current taxi to look for changes. Don't want to set status to 2 w
      $oldCoachId = $this->adaModules->select('tbs_coaches_bookings', 'coachId', 'id=?', array($bookingId))[0]['coachId'];
      $this->oldCoachId = $oldCoachId;
      if ($coachId === $oldCoachId) {
        $this->adaModules->update('tbs_coaches_bookings', 'coachId=?', 'id=?', array($coachId, $bookingId));
      } elseif (!$coachId) {
        $this->adaModules->update('tbs_coaches_bookings', 'coachId=?', 'id=?', array(0, $bookingId));
        $this->setStatus($bookingId, 1);
      } else {
        $this->setStatus($bookingId, 2);
        $this->adaModules->update('tbs_coaches_bookings', 'coachId=?', 'id=?', array($coachId, $bookingId));
      }
      $this->publish($bookingId);

      return emit($response, $data);
    }

    private function getContacts($studentId){
      $student = new \Entities\People\iSamsStudent($this->isams);
      $student->byAdaId($studentId);
      return $student->getContacts();
    }

    public function bookingGet($request, $response, $args)
    {
      $data = $this->retrieveBooking($args['id']);
      return emit($response, $data);
    }

    private function retrieveBooking(int $id)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ?', [$id]);
      $booking = $booking[0] ?? false;
      return $booking;
    }

    public function getCoachBookings($coachId) {
      $bookings = $this->adaModules->select('tbs_coaches_bookings', '*', 'coachId = ? AND statusId <> 4', [$coachId]);
      foreach($bookings as &$booking) {
        $booking = $this->makeDisplayValues($booking);
      }
      return $bookings;
    }

    public function getUnassignedBookings($routeId) {
      $bookings = $this->adaModules->select('tbs_coaches_bookings', '*', 'routeId = ? AND (coachID IS NULL || coachID = 0)', [$routeId]);
      foreach($bookings as &$booking) {
        $booking = $this->makeDisplayValues($booking);
      }
      return $bookings;
    }

    public function bookingDelete($request, $response, $args)
    {
      $this->adaModules->update('tbs_coaches_bookings', 'statusId=?', 'id = ?', [4, $args['id']]);
      $this->sendCancelledEmail($args['id']);
      $this->publish($args['id']);
      return emit($response, []);
    }

    //self service booking are done by parents via the portal when the deadline has passed. Only return journeys.
    public function bookingSelfServicePost($request, $response, $args)
    {
      $data = $request->getParsedBody();
      $sessionId = $data['sessionId'];
      $pupilId = $data['pupilId'];
      $parentUserId = $data['parentUserId'];

      $ret = $data['ret'];
      $stopId = $ret['stopId'];
      //get coaches for this stop
      $tbsExtRoutes = new \Transport\TbsExtRoutes($this->container);
      $coaches = $tbsExtRoutes->getStopCoaches($stopId);

      $coachId = null;
      foreach($coaches as $coach){
        if ($coach['spacesLeft'] > 0) {
          $coachId = $coach['id'];
          break;
        }
      }
      if ($coachId && $stopId) {
        $bookingId = $this->newBooking($sessionId, $pupilId, $parentUserId, $ret, true);
        $this->adaModules->update('tbs_coaches_bookings', 'coachId=?', 'id=?', array($coachId, $bookingId));
        $this->setStatus($bookingId, 6);
        $this->publish($bookingId);
        $this->sendConfirmedEmail($bookingId);

        return emit($response, ['success' => true]);

      } else {
        return emit($response, ['success' => false]);
      }

    }

    public function bookingPost($request, $response)
    {
      $data = $request->getParsedBody();

      $sessionId = $data['sessionId'];
      $pupilId = $data['pupilId'];
      $parentUserId = $data['parentUserId'];
      $out = $data['out'];
      $ret = $data['ret'];

      $retId = $outId = null;

      if ($out['stopId']) $outId = $this->newBooking($sessionId, $pupilId, $parentUserId, $out);
      if ($ret['stopId']) $retId = $this->newBooking($sessionId, $pupilId, $parentUserId, $ret, true);

      if ($outId) {
        $this->publish($outId);
        $this->sendPendingEmail($outId);
      }
      if ($retId) {
        $this->publish($retId);
        $this->sendPendingEmail($retId);
      }

      return emit($response, $data);
    }

    public function summaryPost($request, $response)
    {
      $summary = $request->getParsedBody();

      $email = new \Utilities\Email\Email($this->email, 'Marlborough College Bookings');
      $content = $summary['html'];
      $email->send($content);

      foreach($summary['bookings'] as $booking) {
          $this->adaModules->update('tbs_coaches_bookings', 'sentToCompany=?', 'id=?', [1, $booking['id']]);
      }
      // use the id of the first booking to trigger a session update
      $anId = $summary['bookings'][0]['id'];
      $summary['anId'] = $anId;
      $this->publish($anId);
      return emit($response, $summary);
    }

    public function bookingPut($request, $response)
    {
      $data = $request->getParsedBody();

      $sessionId = $data['sessionId'];
      $bookingId = $data['id'];
      // var_dump($data); return;
      $out = $data['out'];
      $ret = $data['ret'];

      $retId = $outId = null;

      if ($out['stopId']) {
        $this->updateBooking($bookingId, $out);
        $this->setStatus($bookingId, 2);
      }
      if ($ret['stopId']) {
        $this->updateBooking($bookingId, $ret, true);
        $this->setStatus($bookingId, 2);
      }

      $this->publish($bookingId);

      return emit($response, $data);
    }

    private function setStatus($bookingId, $statusId)
    {
      $this->adaModules->update('tbs_coaches_bookings', 'statusId=?', 'id=?', array($statusId, $bookingId));
    }


    public function coachConfirmPut($request, $response)
    {
      $data = $request->getParsedBody();
      $bookingId = $data['id'];
      $this->publish($bookingId);

      $this->setStatus($bookingId, 3);

      $this->sendConfirmedEmail($bookingId);

      return emit($response, $data);
    }

    private function routeIdFromStopId($stopId){
      $d = $this->adaModules->select('tbs_coaches_stops', 'routeId', 'id=?', [$stopId]);
      return $d[0]['routeId'] ?? null;
    }

    private function newBooking(int $sessionId, int $studentId, int $parentUserId, array $booking, $isReturn = false)
    {
      $student = new \Entities\People\Student($this->ada, $studentId);
      $familyId = $student->misFamilyId;
      $booking['routeId'] = $this->routeIdFromStopId($booking['stopId']);

      $id = $this->adaModules->insert(
        'tbs_coaches_bookings',
        'studentId, mis_family_id, contactIsamsUserId, sessionId, routeId, stopId, isReturn',
        array(
          $studentId,
          $familyId,
          $parentUserId,
          $sessionId,
          $booking['routeId'],
          $booking['stopId'],
          $isReturn ? 1 : 0
        )
      );
      $this->publish($id);

      return $id;
    }

    private function updateBooking(int $bookingId, array $booking, $isReturn = false)
    {

      $this->adaModules->update(
        'tbs_coaches_bookings',
        'stopId=?, isReturn=?',
        'id=?',
        array(
          $booking['stopId'],
          $isReturn ? 1 : 0,
          $bookingId
        )
      );

    }

    public function checklistGet($request, $response, $args)
    {
      $id = $args['session'];
      $checklist = [
        'routes'          => false,
        'coaches'         => true,
        'deadlines'       => false,
        'deadlineDateTimes' => [],
        'stopsOut'       => [],
        'stopsRet'       => [],
        'routesOutTime'   => true,
        'stops'           => false,
        'supervisors'     => true,
        'unsupervisedCoaches' => [],
        'notEmailedCoaches' => [],
        'activeSession'   => false,
        'allocations'        => false,
        'confirmations'   => false,
        'selfService'     => false,
        'registers'       => true

      ];
      $sql = $this->adaModules;

      $session = $sql->select('tbs_sessions', 'isActive, taxiDeadline, coachDeadline, selfServiceOn', 'id=?', [$id])[0];
      if (strlen($session['coachDeadline']) > 0 && strlen($session['taxiDeadline']) > 0) $checklist['deadlines'] = true;

      $checklist['selfService'] = $session['selfServiceOn'] === 1 ? true : false;
      $checklist['deadlineDateTimes'] = [
        'taxi'  => convertToAdaDatetime($session['taxiDeadline']),
        'coach' => convertToAdaDatetime($session['coachDeadline'])
      ];

      $checklist['activeSession'] = $session['isActive'] == 1 ? true : false;

      //get routes and make sure everything has been set up
      $routesOut = $sql->select('tbs_coaches_routes', 'id, isReturn', 'sessionId=? AND isReturn = 0', [$id]);
      foreach ($routesOut as $route) {
        $start = $sql->select('tbs_coaches_stops', 'id, time', 'routeId=? AND isSchoolLocation = 1 ORDER BY id ASC', [$route['id']]);
        $time = $start[0]['time'];
        $dateTime = new \DateTime($time);
        $time = $dateTime->format('H:i');
        if ($time == '00:00:00') $checklist['routesOutTime'] = false;
        $stops = $sql->select('tbs_coaches_stops', 'id, time, cost, name', 'routeId=? AND isSchoolLocation = 0 ORDER BY name ASC', [$route['id']]);
        foreach ($stops as $s) {
          $stop = [
            'id'      => $s['id'],
            'time'    => $time,
            'name'    => $s['name'],
            'cost'    => $s['cost'],
            'isError' => ($s['cost'] == 0 || $time == '00:00')
          ];
          $checklist['stopsOut'][] = $stop;
        }
      }

      $routesRet = $sql->select('tbs_coaches_routes', 'id, isReturn', 'sessionId=? AND isReturn = 1', [$id]);
      foreach ($routesRet as $route) {
        $stops = $sql->select('tbs_coaches_stops', 'id, time, cost, name', 'routeId=? AND isSchoolLocation = 0 ORDER BY name ASC', [$route['id']]);
        foreach ($stops as $s) {
          $time = $s['time'];
          $dateTime = new \DateTime($time);
          $time = $dateTime->format('H:i');
          $stop = [
            'id'      => $s['id'],
            'name'    => $s['name'],
            'cost'    => $s['cost'],
            'time'    => $time,
            'isError' => ($s['cost'] == 0 || $time == '00:00')
          ];
          $checklist['stopsRet'][] = $stop;
        }
      }

      $atLeastOneCoach = false;
      $routes = $sql->select('tbs_coaches_routes', 'id, isReturn', 'sessionId=?', [$id]);
      foreach ($routes as $route) {
        $coaches = $sql->select('tbs_coaches_coaches', 'id, supervisorId, code, registerSent', 'routeId=?', [$route['id']]);
        foreach($coaches as $coach) {
          $atLeastOneCoach = true;
          if (!$coach['supervisorId']) {
              $checklist['supervisors'] = false;
              $checklist['registers'] = false;
              $checklist['unsupervisedCoaches'][] = $coach['code'];
          }

          if(!$coach['registerSent'] && $coach['supervisorId']) {
            $checklist['registers'] = false;
            $checklist['notEmailedCoaches'][] = $coach['code'];
          }
        }
      }

      if ($atLeastOneCoach == false) {
        $checklist['supervisors'] = false;
        $checklist['registers'] = false;
      }

      $bookingsCount = count($sql->select('tbs_coaches_bookings', 'id', 'sessionId=? AND statusId < 4', [$id]));
      $bookings = $sql->select('tbs_coaches_bookings', 'id', 'sessionId=? AND statusId < 3', [$id]);
      $checklist['confirmations'] = count($bookings) == 0 && $bookingsCount > 0;

      $bookings = $sql->select('tbs_coaches_bookings', 'id', 'sessionId=? AND statusId = 1', [$id]);
      $checklist['allocations'] = count($bookings) == 0 && $bookingsCount > 0;

      sort($checklist['notEmailedCoaches']);
      sort($checklist['unsupervisedCoaches']);

      return emit($response, $checklist);
    }

    private function publish(int $bookingId) {
      $booking = $this->retrieveBooking($bookingId);
      $family = new \Sockets\CRUD("coaches.user.{$booking['contactIsamsUserId']}");
      $session = new \Sockets\CRUD("coaches{$booking['sessionId']}");
      $self = new \Sockets\CRUD("coaches.self.{$booking['sessionId']}");
      $checklist = new \Sockets\CRUD("coaches.checklist.{$booking['sessionId']}");

      //see if booking is assigned a coach and if so, send to the unique ID to update the register
      if ($booking['coachId']){
        $c = $this->adaModules->select('tbs_coaches_coaches', 'uniqueId', 'id=?', [$booking['coachId']]);
        if (isset($c[0])) {
          $uniqueId = $c[0]['uniqueId'];
          $register = new \Sockets\CRUD("aux.coaches.register.$uniqueId");
        }
        if ($this->oldCoachId) {
          $c = $this->adaModules->select('tbs_coaches_coaches', 'uniqueId', 'id=?', [$this->oldCoachId]);
          if (isset($c[0])) {
            $uniqueId = $c[0]['uniqueId'];
            $register = new \Sockets\CRUD("aux.coaches.register.$uniqueId");
          }

        }
      }
    }

    private function sendPendingEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      $booking = $this->makeDisplayValues($booking);

      if ($booking['isReturn'] == 0) {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'to'      => $booking['stop'],
          'from'    => $booking['schoolLocation'],
          'time'   => tidyTime($booking['schoolTime'])
        ];
      } else {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'    => tidyTime($booking['stopTime']),
          'from'    => $booking['stop'],
          'to'      => $booking['schoolLocation'],
        ];
      }

      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $cc = [$student->email];

      $this->sendEmail($bookingId, $booking['contact']->email, 'MC Coach Booking Received', 'TBS.ReceivedCoach', $fields, $cc);
    }

    private function sanitiseTime($time) {
      $ex = explode($time);
      if (count($ex) == 2) return $time;
      return $ex[0] . ':' . $ex[1];
    }

    private function sendCancelledEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      $booking = $this->makeDisplayValues($booking);

      if ($booking['isReturn'] === 1) {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> tidyTime($booking['stopTime']),
          'from'    => $booking['stop'],
          'to'  => $booking['schoolLocation']
        ];
      } else {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> tidyTime($booking['schoolTime']),
          'from'    => $booking['schoolLocation'],
          'to'      => $booking['stop']
        ];
      }

      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $cc = [$student->email];

      $this->sendEmail($bookingId, $booking['contact']->email, 'MC Coach Booking Cancelled', 'TBS.CancelledCoach', $fields, $cc);
    }

    public function bookingDeclineEmail($request, $response, $args)
    {
      $bookingId = $args['id'];
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      $booking = $this->makeDisplayValues($booking);

      if ($booking['isReturn'] === 1) {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> tidyTime($booking['stopTime']),
          'from'    => $booking['stop'],
          'to'  => $booking['schoolLocation']
        ];
      } else {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['schoolTime'],
          'from'    => $booking['schoolLocation'],
          'to'      => $booking['stop']
        ];
      }
      $email = new \Utilities\Email\Email();
      $content = $email->template('TBS.DeclinedCoach', $fields);
      $data = [
        'content' => $content
      ];

      return emit($response, $data);
    }

    public function bookingDecline($request, $response, $args)
    {
      $id = $args['id'];  
      $data = $request->getParsedBody();
      $content = $data['content'];

      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$id])[0];
      $booking = $this->makeDisplayValues($booking);

      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $cc = [$student->email];

      $this->sendEmail($id, $booking['contact']->email, 'MC Coach Booking Declined', null, null, $cc, [], $content);
      $this->adaModules->update('tbs_coaches_bookings', 'statusId=?', 'id = ?', [5, $id]);
      $this->publish($id);
      return emit($response, []);
    }

    private function sendConfirmedEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      $booking = $this->makeDisplayValues($booking);

      $data = $booking['coachId'] . '-' . $booking['studentId'];
      $qrPath = (new \Utilities\QRCode\Generator($data))->render();

      if ($booking['isReturn'] === 1) {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'    => tidyTime($booking['stopTime']),
          'from'    => $booking['stop'],
          'to'      => $booking['schoolLocation'],
          'cost'    => $booking['cost'],
          'code'    => $booking['coachCode']
        ];
      } else {
        $fields = [
          'name'    => $booking['contact']->letterSalutationSingle,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'    => tidyTime($booking['schoolTime']),
          'from'    => $booking['schoolLocation'],
          'to'      => $booking['stop'],
          'cost'    => $booking['cost'],
          'code'    => $booking['coachCode']
        ];
      }
      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $cc = [$student->email];
      $images = [
        [
        'path' => $qrPath,
        'cid' => 'qrcode'
        ]
      ];
      $this->sendEmail($bookingId, $booking['contact']->email, 'MC Coach Booking Confirmed', 'TBS.ConfirmedCoach', $fields, $cc, [], null, $images);
    }

    public function sendAdHocConfirmedEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      $booking = $this->makeDisplayValues($booking);

      $data = $booking['coachId'] . '-' . $booking['studentId'];
      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $isamsStudent = (new \Entities\People\iSamsStudent($this->isams))->byAdaId($student->id);
      $isamsStudent->getContacts();
      if ($booking['isReturn'] === 1) {
        $fields = [
          'name'    => $student->firstName,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'    => tidyTime($booking['stopTime']),
          'from'    => $booking['stop'],
          'to'      => $booking['schoolLocation'],
          'cost'    => $booking['cost'],
          'code'    => $booking['coachCode']
        ];
      } else {
        $fields = [
          'name'    => $student->firstName,
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'    => tidyTime($booking['schoolTime']),
          'from'    => $booking['schoolLocation'],
          'to'      => $booking['stop'],
          'cost'    => $booking['cost'],
          'code'    => $booking['coachCode']
        ];
      }
      
      $cc = [];
      foreach ($isamsStudent->contacts as $c) {
        if ($c['portalUserInfo']) $cc[] = $c['email'];
      }
      $this->sendEmail($bookingId, $student->email, 'MC Coach Booking Confirmed', 'TBS.ConfirmedAdHocCoach', $fields, $cc, []);
    }


 
    private function sendEmail($bookingId, $to, $subject, $template, $fields, $cc = [], $bcc = [], $contentOverride = null, $images = [])
    {

      $email = new \Utilities\Email\Email($to, $subject, 'coaches@marlboroughcollege.org', [], [], $this->debug);
      
      $content = $contentOverride ? $contentOverride : $email->template($template, $fields);
      $res = $email->send($content, null, $images);
      $ccString = '';
      foreach($cc as $c) $ccString .= $c . ' ';

      // var_dump($email); exit();
      $b = $this->adaModules->select('tbs_coaches_bookings', 'studentId, sessionId', 'id=?', [$bookingId])[0]; 
      $sessionId = $b['sessionId']; 
      $studentId = $b['studentId']; 

      //log email
      $this->adaModules->insert(
        'tbs_emails',
        'isTaxi, bookingId, studentId, sessionId, email, cc, subject, content',
        [
          0,
          $bookingId,
          $studentId,
          $sessionId,
          $to,
          $ccString,
          $subject,
          $content
        ]
      );

    }

    public function emailsGet($request, $response, $args)
    {
      $sessionId = $args['session'];
      $emails = $this->adaModules->select(
        'tbs_emails',
        'id, isTaxi, bookingId, studentId, sessionId, email, cc, subject, content, timestamp',
        'isTaxi = ? AND sessionId = ? ORDER BY timestamp DESC',
        [
          0,
          $sessionId
        ]
      );
      foreach($emails as &$e) {
        $e['name'] = $e['studentId'] ? (new \Entities\People\Student($this->ada, $e['studentId']))->displayName : '';
        $e['time'] = convertToAdaDatetime($e['timestamp']);
        // $e['time'] = $e['timestamp'];
      }
      return emit($response, $emails);
    }

}
