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

    public function __construct(\Slim\Container $container)
    {
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
      return $session[0];
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
        $booking['stopTime'] = $stop['time'];
        $booking['cost'] = $stop['cost'];
      }
      
      if ($booking['coachId']) {
        $c = $this->adaModules->select('tbs_coaches_coaches', 'code', 'id=?', [$booking['coachId']]);
        $booking['coachCode'] = $c[0]['code'] ?? '';
        $s = $this->adaModules->select('tbs_coaches_coach_stops', 'id', 'coachId=? AND stopID=?', [$booking['coachId'], $booking['stopId']]);
        $booking['coachHasStop'] = isset($s[0]);
        //check to make sure that the coach still goes to these stops
      }
      
      // contacts
      $booking['contacts'] = $this->getContacts($booking['studentId']);

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
    
    public function bookingDecline($request, $response, $args)
    {
      $this->adaModules->update('tbs_coaches_bookings', 'statusId=?', 'id = ?', [5, $args['id']]);
      $this->publish($args['id']);
      
      $this->sendDeclinedEmail($args['id']);
      return emit($response, []);
    }

    public function bookingPost($request, $response)
    {
      $data = $request->getParsedBody();

      $sessionId = $data['sessionId'];
      $pupilId = $data['pupilId'];
      // var_dump($data); return;
      $out = $data['out'];
      $ret = $data['ret'];

      $retId = $outId = null;

      if ($out['stopId']) $outId = $this->newBooking($sessionId, $pupilId, $out);
      if ($ret['stopId']) $retId = $this->newBooking($sessionId, $pupilId, $ret, true);

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

    private function newBooking(int $sessionId, int $studentId, array $booking, $isReturn = false)
    {
      $student = new \Entities\People\Student($this->ada, $studentId);
      $familyId = $student->misFamilyId;
      $booking['routeId'] = $this->routeIdFromStopId($booking['stopId']);

      $id = $this->adaModules->insert(
        'tbs_coaches_bookings',
        'studentId, mis_family_id, sessionId, routeId, stopId, isReturn',
        array(
          $studentId,
          $familyId,
          $sessionId,
          $booking['routeId'],
          $booking['stopId'],
          $isReturn ? 1 : 0
        )
      );
      $this->publish($id);

      return $id;
    }

    private function sendPendingEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);

      $email = new \Utilities\Email\Email($this->email, 'MC Coach Booking Received');
      
      if ($booking['isReturn'] == 0) {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'to'    => $booking['stop'],
          'from'  => $booking['schoolLocation'],
          'time'  => $booking['schoolTime']
        ];
      } else {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['stopTime'],
          'from'    => $booking['stop'],
          'to'  => $booking['schoolLocation'],
        ];
      }

      $content = $email->template('TBS.ReceivedCoach', $fields);

      $res = $email->send($content);
    }

    private function sendCancelledEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);

      $email = new \Utilities\Email\Email($this->email, 'MC Coach Booking Cancelled');
      if ($booking['isReturn'] === 1) {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['stopTime'],
          'from'    => $booking['stop'],
          'to'  => $booking['schoolLocation']
        ];
      } else {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['schoolTime'],
          'from'    => $booking['schoolLocation'],
          'to'      => $booking['stop']
        ];
      }

      $content = $email->template('TBS.CancelledCoach', $fields);

      $res = $email->send($content);
    }
    
    private function sendDeclinedEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);

      $email = new \Utilities\Email\Email($this->email, 'MC Coach Booking Declined');
      if ($booking['isReturn'] === 1) {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['stopTime'],
          'from'    => $booking['stop'],
          'to'  => $booking['schoolLocation']
        ];
      } else {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['schoolTime'],
          'from'    => $booking['schoolLocation'],
          'to'      => $booking['stop']
        ];
      }

      $content = $email->template('TBS.DeclinedCoach', $fields);

      $res = $email->send($content);
    }

    private function sendConfirmedEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_coaches_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);

      $email = new \Utilities\Email\Email($this->email, 'MC Coach Booking Confirmed');
      if ($booking['isReturn'] === 1) {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['stopTime'],
          'from'    => $booking['stop'],
          'to'  => $booking['schoolLocation'],
          'cost'    => $booking['cost'],
          'code'    => $booking['coachCode']
        ];
      } else {
        $fields = [
          'name'    => 'Simon',
          'id'      => $bookingId,
          'pupil'   => $booking['displayName'],
          'date'    => $booking['date'],
          'time'=> $booking['schoolTime'],
          'from'    => $booking['schoolLocation'],
          'to'      => $booking['stop'],
          'cost'    => $booking['cost'],
          'code'    => $booking['coachCode']
        ];
      }

      $content = $email->template('TBS.ConfirmedCoach', $fields);

      $res = $email->send($content);
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

    private function publish(int $bookingId) {
      $booking = $this->retrieveBooking($bookingId);
      $family = new \Sockets\CRUD("coaches.family.{$booking['mis_family_id']}");
      $session = new \Sockets\CRUD("coaches{$booking['sessionId']}");
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
