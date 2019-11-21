<?php

/**
 * Description

 * Usage:

 */
namespace Transport;

class TbsExtTaxisBookings
{
    protected $container;
    private $user, $email;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->student = new \Entities\People\Student($this->ada);

       $this->status = $this->getAllStatus();
       
       global $userId;
       $this->user = new \Entities\People\Staff($this->ada, $userId);
       $this->email = $this->user->email;
       
    }

    private function getStation($id)
    {
        $d = $this->adaModules->select('tbs_taxi_stations', 'name', 'id=?', array($id));
        return $d[0]['name'] ?? '';
    }

    private function getAirport($id) {
      $d = $this->adaModules->select('tbs_taxi_airports', 'name', 'id=?', array($id));
      return $d[0]['name'] ?? '';
    }

    private function getPickup($id) {
      $d = $this->adaModules->select('tbs_taxi_pickups', 'name', 'id=?', array($id));
      return $d[0]['name'] ?? '';
    }

    private function getAllStatus() {
      $d = $this->adaModules->select('tbs_taxi_status', 'id, name', '1=1', array());
      $status = array();
      foreach ($d as $s) {
        $status['s_' . $s['id']] = $s['name'];
      }
      return $status;
    }

    //argument: sessionId
    public function bookingsByCompanyGet($request, $response, $args)
    {
      $sessionId = $args['session'];
      $companies = $this->adaModules->select('tbs_taxi_companies', 'id, name, phoneNumber, email', '1=1', []);

      foreach ($companies as &$c) {
        $c['out'] = ['cancelled' => [], 'new' => [], 'pending' => [], 'confirmed' => []];
        $c['ret'] = ['cancelled' => [], 'new' => [], 'pending' => [], 'confirmed' => []];

        $bookings = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND taxiId = ?', [$sessionId, $c['id']]);

        foreach ($bookings as $booking){
          $journey = $booking['isReturn'] ? 'ret' : 'out';
          $booking = $this->makeDisplayValues($booking);
          switch ($booking['statusId']){
            case 4:
              $c[$journey]['cancelled'][] = $booking; break;
            case 1:
              $c[$journey]['new'][] = $booking; break;
            case 2:
              $c[$journey]['pending'][] = $booking; break;
            case 3:
              $c[$journey]['confirmed'][] = $booking; break;
          }
        }
      }
      return emit($response, $companies);
    }
    
    //generated the email html summary to be sent for review to the front
    public function summaryGet($request, $response, $args)
    {
      $sessionId = $args['sessionId'];
      $taxiId = $args['taxiId'];
      $c = $this->adaModules->select('tbs_taxi_companies', 'id, name, phoneNumber, email', 'id=?', [$taxiId])[0];
  
      // $c['out'] = ['cancelled' => [], 'new' => [], 'ammended' => [], 'confirmed' => []];
      // $c['ret'] = ['cancelled' => [], 'new' => [], 'ammended' => [], 'confirmed' => []];
      $c['out'] = [];
      $c['ret'] = [];
      $c['outCount'] = 0;
      $c['retCount'] = 0;

      $bookings = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND taxiId = ? ORDER BY id DESC', [$sessionId, $taxiId]);

      foreach ($bookings as &$booking){
        $journey = $booking['isReturn'] ? 'ret' : 'out';
        $booking = $this->makeDisplayValues($booking);
        $c[$journey . 'Count']++;
        switch ($booking['statusId']){
          case 4:
            // $c[$journey]['cancelled'][] = $booking;
            $booking['status'] = 'Cancelled';
            $booking['statusColor'] = 'red';
            $c[$journey][] = $booking;
            break;
          case 2:
          case 3:
            if ($booking['sentToCompany'] === 1) {
              $booking['status'] = 'Confirmed';
              $booking['statusColor'] = 'blue';
              $c[$journey][] = $booking;
              // $c[$journey]['confirmed'][] = $booking;
            } else {
              $booking['status'] = 'New';
              $booking['statusColor'] = 'Green';
              $c[$journey][] = $booking;
              // $c[$journey]['new'][] = $booking;
            }
            break;
        }
      }
      
      $session = $this->getSession($sessionId);
      
      $c['html'] = $this->makeSummaryHTML($c, $session);
      
      return emit($response, $c);
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
    
    private function makeSummaryHTML(array &$company, array $session)
    {
      $html = '';
      $email = new \Utilities\Email\Email($this->email, 'Marlborough College Bookings');
      
      if ($company['outCount'] > 0) {
        $html .= "<h1 width:200px; style='background:grey; margin-left:0px; padding-left:10px;  margin-top:20px;color:white;font-size:14px;text-align:left;font-weight: bold'>Outbound : {$session['dateOut']}</h1>";
        $bookings = $company['out'];
        foreach($bookings as &$outBooking){
          $html .= $this->makeBookingHTML($outBooking);
        }
      }
      
      if ($company['retCount'] > 0) {
        $html .= "<h1 style='background:grey; margin-left:0px; padding-left:10px;  color: white; margin-top:20px;font-size:14px;text-align:left;font-weight: bold'>Return : {$session['dateRtn']}</h1>";
        $bookings = $company['ret'];
        foreach($bookings as &$retBooking){
          $html .= $this->makeBookingHTML($retBooking);
        }
      }
      
      $fields = [
        'name' => $company['name'],
        'bookings' => $html
      ];
      
      $content = $email->template('TBS.TaxiSummary', $fields);
      return $content;
    }
    
    
    // $b is type Booking
    private function makeBookingHTML(array &$b)
    {
      
      // 'name'    => 'Simon',
      // 'id'      => $bookingId,
      // 'pupil' => $booking['displayName'],
      // 'date'    => '31/1/92',
      // 'time'    => $booking['pickupTime'],
      // 'from'    => $booking['displayFrom'],
      // 'to'      => $booking['displayTo'],
      // 'passengers'  => $passengerString,
      // 'note'    => strlen($booking['note']) == 0 ? '-' : $booking['note']
        $html = "<table class='body-action' align='center' widthx='100%' cellpadding='0' cellspacing='0' style='border-bottom:1px solid black; font-size:12px; color:#000000; widthx:100%;margin-top:20px;margin-bottom:5px;margin-right:auto;margin-left:0;padding-top:0;padding-bottom:15px;padding-right:0;padding-left:0;text-align:left;' >
                <tr style='background:{$b['statusColor']}; color: white; '><td style='padding-left:5px; padding-right:5px text-align:center'>{$b['status']}</td></tr>
                <tr><td style='xmin-width:100px; text-align:right; padding-right:10px'>Ref #:</td><td>{$b['id']}</td>
                <td style='xmin-width:100px; text-align:right; padding-right:10px'>Pupil:</td><td>{$b['displayName']}</td></tr>
                <tr><td style='xmin-width:100px; text-align:right; padding-right:10px'>Time:</td><td>{$b['pickupTime']}</td>
                <td style='xmin-width:100px; text-align:right; padding-right:10px'>Price:</td><td>£{$b['cost']}</td></tr>
                <tr><td style='xmin-width:100px; text-align:right; padding-right:10px'>From:</td><td>{$b['displayFrom']}</td>
                <td style='xmin-width:100px; text-align:right; padding-right:10px'>To:</td><td>{$b['displayTo']}</td></tr>
                </table>
              ";
        if ($b['passengerCount'] > 0) {
          $passengerString = $this->makePassengerString($b['passengers']);
          $b['passengerString'] = $passengerString;
          $html .= "<table class='body-action' align='center' widthx='100%' cellpadding='0' cellspacing='0' style='border-bottom:0px solid black; font-size:12px; color:#000000; widthx:100%;margin-top:5px;margin-bottom:0px;margin-right:auto;margin-left:0;padding-top:0;padding-bottom:15px;padding-right:0;padding-left:0;text-align:left;' >
                    <tr><td colspan='2' style='xmin-width:100px; text-align:right; padding-right:10px'>Passengers:</td><td>$passengerString</td></tr>
                    </table>";
        }
        if (strlen($b['note'] > 0)) {
          $html .= "<tr style='background:yellow'><td style='xmin-width:100px; text-align:right; padding-right:10px'>Note:</td><td>{$b['note']}</td></tr>";
        }
        $html .= '</table>';
        return $html;
      
    }
//
    public function familyBookings($familyId)
    {
      $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'mis_family_id=? ORDER BY sessionId DESC', [$familyId]);
      foreach ($data as &$booking) {
        $booking['type'] = 'taxi';
        $booking = $this->makeDisplayValues($booking);
      }
      return $data;
    }

    public function allBookingsGet($request, $response, $args)
    {
      $sessID = $args['session'];

      $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? ORDER BY id DESC', [$sessID]);

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
        $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND isReturn = ? AND statusId > 0 ORDER BY statusId ASC, studentId DESC', [$sessID, $isReturn]);
      } else {
        $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND statusId = 4', [$sessID]);
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
      
      $data = $this->adaModules->select('tbs_taxi_bookings', 'id', 'sessionId = ? AND statusId = 1', [$sessID]);
      
      return emit($response, count($data));
    }

    private function makeDisplayValues($booking)
    {
      $this->status = $this->getAllStatus();
      $status = $this->status;

      $field = $booking['isReturn'] === 1 ? 'displayFrom' : 'displayTo';
      switch($booking['journeyType']) {
        case 'flight':
          $booking[$field] = $this->getAirport($booking['airportId']) . ' ['. $booking['flightNumber'] .']';
          break;
        case 'train':
          $booking[$field] = $this->getStation($booking['stationId']) . ' ['. $booking['trainTime'] .']';
          break;
        case 'address':
          $booking[$field] = $booking['address'];
          break;
      }
      $booking['passengers'] = $this->getPassengerNames($booking['id']);
      $field = $booking['isReturn'] === 1 ? 'displayTo' : 'displayFrom';
      $booking[$field] = $this->getPickup($booking['schoolLocation']);
      $booking['passengerIds'] = $this->getPassengerIds($booking['id']);
      $booking['passengerCount'] = count($booking['passengerIds']);
      $booking['status'] = $status['s_' . $booking['statusId']];
      $booking['displayName'] = $this->student->displayName($booking['studentId']);

      $taxiId = $booking['taxiId'];
      $d = $this->adaModules->select('tbs_taxi_companies', 'name', 'id=?', [$taxiId]);
      if (isset($d[0])) $booking['companyName'] = $d[0]['name'];

      return $booking;
    }

    public function bookingGet($request, $response, $args)
    {
      $data = $this->retrieveBooking($args['id']);
      return emit($response, $data);
    }

    private function retrieveBooking(int $id)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ?', [$id]);
      if (isset($booking[0])) {
        $booking = $booking[0];
      } else {
        return false;
      }
      $booking['passengerIds'] = $this->getPassengerIds($id);
      $booking['passengerCount'] = count($booking['passengerIds']);
      return $booking;
    }

    public function bookingDelete($request, $response, $args)
    {
      $this->adaModules->update('tbs_taxi_bookings', 'statusId=?', 'id = ?', [4, $args['id']]);
      $this->publish($args['id']);
      $this->sendCancelledEmail($args['id']);
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

      if ($out['pickup']) $outId = $this->newBooking($sessionId, $pupilId, $out, $data['note']);
      if ($ret['journeyType']) $retId = $this->newBooking($sessionId, $pupilId, $ret, $data['note'], true);

      if ($outId) {
        $this->savePassengers($outId, $out['passengers']);
        $this->publish($outId);
        $this->sendPendingEmail($outId);
      }
      if ($retId) {
        $this->savePassengers($retId, $ret['passengers']);
        $this->publish($retId);
        $this->sendPendingEmail($retId);
      }

      // $data['id'] = $this->adaModules->insertObject('tbs_taxi_bookings', $data);
      return emit($response, $data);
    }

    public function summaryPost($request, $response)
    {
      $bookings = $request->getParsedBody();

      foreach($bookings as $booking) {
          $this->adaModules->update('tbs_taxi_bookings', 'sentToCompany=?', 'id=?', [1, $booking['id']]);
      }

      // $data['id'] = $this->adaModules->insertObject('tbs_taxi_bookings', $data);
      return emit($response, $bookings);
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

      if ($out['pickup']) $this->updateBooking($bookingId, $out, $data['note']);
      if ($ret['journeyType']) $this->updateBooking($bookingId, $ret, $data['note'], true);

      $this->savePassengers($bookingId, $out['passengers']);
      $this->publish($bookingId);

      return emit($response, $data);
    }

    private function setStatus($bookingId, $statusId)
    {
      $this->adaModules->update('tbs_taxi_bookings', 'statusId=?', 'id=?', array($statusId, $bookingId));
    }

    public function taxiAssigmentPut($request, $response)
    {
      $data = $request->getParsedBody();

      $taxiId = $data['taxiId'];
      $cost = $data['cost'];
      $bookingId = $data['id'];

      //get current taxi to look for changes
      $oldTaxiId = $this->adaModules->select('tbs_taxi_bookings', 'taxiId', 'id=?', array($bookingId))[0]['taxiId'];

      if ($taxiId === $oldTaxiId) {
        $this->adaModules->update('tbs_taxi_bookings', 'taxiId=?, cost=?', 'id=?', array($taxiId, $cost, $bookingId));
      } else {
        $this->setStatus($bookingId, 2);
        $this->adaModules->update('tbs_taxi_bookings', 'taxiId=?, cost=?', 'id=?', array($taxiId, $cost, $bookingId));
      }
      $this->publish($bookingId);

      return emit($response, $data);
    }

    public function taxiConfirmPut($request, $response)
    {
      $data = $request->getParsedBody();
      $bookingId = $data['id'];
      $this->publish($bookingId);

      $this->setStatus($bookingId, 3);

      $this->sendConfirmedEmail($bookingId);

      return emit($response, $data);
    }

    private function newBooking(int $sessionId, int $studentId, array $booking, $note, $isReturn = false)
    {
      $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];
      $booking['schoolLocation'] = $schoolLocation;

      $student = new \Entities\People\Student($this->ada, $studentId);
      $familyId = $student->misFamilyId;
      $id = $this->adaModules->insert(
        'tbs_taxi_bookings',
        'studentId, mis_family_id, sessionId, pickupTime, isReturn, note, journeyType, schoolLocation, address, airportId, flightNumber, flightTime, stationId, trainTime',
        array(
          $studentId,
          $familyId,
          $sessionId,
          $booking['pickupTime'],
          $isReturn ? 1 : 0,
          $note,
          $booking['journeyType'],
          $schoolLocation,
          $booking['address'],
          $booking['airport'],
          $booking['flightNumber'],
          $booking['flightTime'],
          $booking['station'],
          $booking['trainTime']
        )
      );
      $this->publish($id);

      return $id;
    }
    
    //makes the of passenger names HTML for sending within emails. Taken an array of passenger names
    private function makePassengerString($passengers)
    {
      $count = count($passengers);
      if ( $count > 0 ) {
        $c = 0;
        $passengerString = '';
        $comma = ' / ';
        foreach($passengers as $p){
          $c++;
          if ($c == $count) $comma = '';
          $passengerString .= "<span style='margin-right:5px'>{$p}$comma</span>";
          $comma = ';';
        }
      } else {
        $passengerString = '-';
      }
      return $passengerString;
    }

    private function sendPendingEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);
      $passengers = $this->getPassengerNames($bookingId);

      $passengerString = $this->makePassengerString($passengers);

      $email = new \Utilities\Email\Email($this->email, 'MC Taxi Booking Received');
      $fields = [
        'name'    => 'Simon',
        'id'      => $bookingId,
        'pupil' => $booking['displayName'],
        'date'    => '31/1/92',
        'time'    => $booking['pickupTime'],
        'from'    => $booking['displayFrom'],
        'to'      => $booking['displayTo'],
        'passengers'  => $passengerString,
        'note'    => strlen($booking['note']) == 0 ? '-' : $booking['note']
      ];
    
      $content = $email->template('TBS.ReceivedTaxi', $fields);

      $res = $email->send($content);
    }

    private function sendCancelledEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);
      $passengers = $this->getPassengerNames($bookingId);

      $count = count($passengers);
      if ( $count > 0 ) {
        $c = 0;
        $passengerString = '';
        $comma = ';';
        foreach($passengers as $p){
          $c++;
          if ($c == $count) $comma = '';
          $passengerString .= "<span style='margin-right:5px'>{$p}$comma</span>";
          $comma = ';';
        }
      } else {
        $passengerString = '-';
      }

      $email = new \Utilities\Email\Email($this->email, 'MC Taxi Booking Cancelled');
      $fields = [
        'name'    => 'Simon',
        'id'      => $bookingId,
        'pupil' => $booking['displayName'],
        'date'    => '31/1/92',
        'time'    => $booking['pickupTime'],
        'from'    => $booking['displayFrom'],
        'to'      => $booking['displayTo'],
        'passengers'  => $passengerString,
        'note'    => strlen($booking['note']) == 0 ? '-' : $booking['note']
      ];

      $content = $email->template('TBS.CancelledTaxi', $fields);

      $res = $email->send($content);
    }

    private function sendConfirmedEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);
      $passengers = $this->getPassengerNames($bookingId);

      $count = count($passengers);
      if ( $count > 0 ) {
        $c = 0;
        $passengerString = '';
        $comma = ';';
        foreach($passengers as $p){
          $c++;
          if ($c == $count) $comma = '';
          $passengerString .= "<span style='margin-right:5px'>{$p}$comma</span>";
          $comma = ';';
        }
      } else {
        $passengerString = '-';
      }

      $email = new \Utilities\Email\Email($this->email, 'MC Taxi Booking Confirmed');
      $fields = [
        'name'    => 'Simon',
        'id'      => $bookingId,
        'pupil' => $booking['displayName'],
        'date'    => '31/1/92',
        'time'    => $booking['pickupTime'],
        'from'    => $booking['displayFrom'],
        'to'      => $booking['displayTo'],
        'cost'    => $booking['cost'],
        'company'    => $booking['companyName'],
        'passengers'  => $passengerString,
        'note'    => strlen($booking['note']) == 0 ? '-' : $booking['note']
      ];

      $content = $email->template('TBS.ConfirmedTaxi', $fields);

      $res = $email->send($content);
    }

    private function getPassengerIds(int $bookingId)
    {
      $d = $this->adaModules->select('tbs_taxi_passenger', 'studentId', 'bookingId=?', array($bookingId));
      $ids = array();
      foreach($d as $passenger){
        $ids[] = $passenger['studentId'];
      }
      return $ids;
    }

    private function getPassengerNames(int $bookingId)
    {
      $d = $this->adaModules->select('tbs_taxi_passenger', 'studentId', 'bookingId=?', array($bookingId));
      $names = array();
      foreach($d as $passenger){
        $student = new \Entities\People\Student($this->ada);
        $id = $passenger['studentId'];
        $name = $student->displayName($id);
        $names[] = $name;
      }
      return $names;
    }

    private function savePassengers(int $bookingId, array $passengers)
    {
        //get current Passengers
        $current = $this->adaModules->select('tbs_taxi_passenger', 'id, studentId', 'bookingId=?', array($bookingId));
        $currentKeys = array();
        $ids = array();
        foreach($current as $passenger) {
          $currentKeys['p_' . $passenger['id']] = true;
          $ids['p_' . $passenger['id']] = $passenger['id'];
        }
        foreach($passengers as $passenger) {
          $id = $passenger['id'];
          $student = new \Entities\People\Student($this->ada, $id);
          $familyId = $student->misFamilyId;
          if (!isset($currentKeys['p_' . $id])) {
            //must be new
            $this->adaModules->insert('tbs_taxi_passenger', 'studentId, bookingId, mis_family_id', array($id, $bookingId, $familyId));
          } else {
            //already exists
            $currentKeys['p_' . $id] = false;
          }
        }
        //delete any remaining passengers as they must have been taken off the booking
        foreach($currentKeys as $key => $passenger) {
          if ($passenger === true) {
            $this->adaModules->delete('tbs_taxi_passenger', 'studentId = ? and bookingId = ?', array($ids[$key], $bookingId));
          }
        }
    }

    private function updateBooking(int $bookingId, array $booking, $note, $isReturn = false)
    {
      $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];
      $this->adaModules->update(
        'tbs_taxi_bookings',
        'pickupTime=?, isReturn=?, note=?, journeyType=?, schoolLocation=?, address=?, airportId=?, flightNumber=?, flightTime=?, stationId=?, trainTime=?',
        'id=?',
        array(
          $booking['pickupTime'],
          $isReturn ? 1 : 0,
          $note,
          $booking['journeyType'],
          $schoolLocation,
          $booking['address'],
          $booking['airport'] ?? null,
          $booking['flightNumber'],
          $booking['flightTime'],
          $booking['station'] ?? null,
          $booking['trainTime'],
          $bookingId
        )
      );

    }

    private function publish(int $bookingId) {
      $booking = $this->retrieveBooking($bookingId);
      $family = new \Sockets\CRUD("taxi.family.{$booking['mis_family_id']}");
      $session = new \Sockets\CRUD("taxi{$booking['sessionId']}");
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
