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
    private $debug = true;

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

        $bookings = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND companyId = ?', [$sessionId, $c['id']]);

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
      $companyId = $args['companyId'];
      $c = $this->adaModules->select('tbs_taxi_companies', 'id, name, phoneNumber, email', 'id=?', [$companyId])[0];

      // $c['out'] = ['cancelled' => [], 'new' => [], 'ammended' => [], 'confirmed' => []];
      // $c['ret'] = ['cancelled' => [], 'new' => [], 'ammended' => [], 'confirmed' => []];
      $c['out'] = [];
      $c['ret'] = [];
      $c['outCount'] = 0;
      $c['retCount'] = 0;

      $bookings = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND companyId = ? ORDER BY statusId ASC', [$sessionId, $companyId]);
      $bookings = sortArrays($bookings, 'pickupUnix', 'ASC');
      $c['bookings'] = $bookings;
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
      return isset($session[0]) ? $session[0] : [];
    }

    private function makeSummaryHTML(array &$company, array $session)
    {
      $html = '';
      $email = new \Utilities\Email\Email($this->email, 'Marlborough College Bookings');

      if ($company['outCount'] > 0) {
        $html .= "<h1 width:200px; style='background:grey; margin-left:0px; padding-left:10px;  margin-top:20px;color:white;font-size:14px;text-align:left;font-weight: bold'>Outbound</h1>";
        $bookings = $company['out'];
        foreach($bookings as &$outBooking){
          $html .= $this->makeBookingHTML($outBooking);
        }
      }

      if ($company['retCount'] > 0) {
        $html .= "<h1 style='background:grey; margin-left:0px; padding-left:10px;  color: white; margin-top:20px;font-size:14px;text-align:left;font-weight: bold'>Return</h1>";
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
        $html = "<table class='body-action' align='center' widthx='100%' cellpadding='4' cellspacing='0' style='border-bottom:1px solid black; font-size:12px; color:#000000; widthx:100%;margin-top:20px;margin-bottom:5px;margin-right:auto;margin-left:0;padding-top:0;padding-bottom:15px;padding-right:0;padding-left:0;text-align:left;' >
                <tr style='text-align:left; padding:3px; background:{$b['statusColor']}; color: white; '><td style='padding-left:5px; padding-right:5px; text-align:center'>{$b['status']}</td></tr>
                <tr><td style='xmin-width:100px; text-align:right;'>Pupil #: </td><td> {$b['schoolNumber']}</td>
                <td style='xmin-width:100px; text-align:right;'>Pupil: </td><td>{$b['displayName']}</td></tr>
                <tr><td style='xmin-width:100px; text-align:right; '>When: </td><td> {$b['pickupDatePretty']}</td>
                <td style='xmin-width:100px; text-align:right;'>Mob: </td><td>{$b['mob']}</td></tr>
                <tr><td style='xmin-width:100px; text-align:right;'>From:</td><td> {$b['displayFrom']}</td>
                <td style='xmin-width:100px; text-align:right; '>To:</td><td> {$b['displayTo']}</td></tr>
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

    public function userBookings($contactIsamsUserId)
    {
      $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'contactIsamsUserId=? ORDER BY sessionId DESC', [$contactIsamsUserId]);
      foreach ($data as &$booking) {
        $booking['type'] = 'taxi';
        $booking = $this->makeDisplayValues($booking);
        $booking['session'] = $this->getSession($booking['sessionId']);
      }
      return $data;
    }

    public function allBookingsGet($request, $response, $args)
    {
      $sessId = $args['session'];

      $taxisRaw = $this->adaModules->select('tbs_taxi_taxis', '*', 'sessionId=?', [$sessId]);
      $taxis = [];

      foreach($taxisRaw as $t) {
        $t['bookings'] = [];
        $t['count'] = 0;
        $taxis['i_' . $t['id']] = $t;
      }

      $bookings = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? ORDER BY id DESC', [$sessId]);
      $status = $this->getAllStatus();

      foreach ($bookings as &$booking) {
        $booking = $this->makeDisplayValues($booking);
        $count = 1 + count($booking['passengers']);
        if ($booking['taxiId']) {
          $taxis['i_' . $booking['taxiId']]['bookings'][] = $booking;
          $taxis['i_' . $booking['taxiId']]['count'] += $count;
        }
      }
      unset($booking);
      $bookings = $this->checkDuplicates($bookings);
      $data = [
        'bookings'  => $bookings,
        'taxis'     => array_values($taxis)
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
        $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND isReturn = ? AND statusId > 0 ORDER BY statusId ASC, studentId DESC', [$sessID, $isReturn]);
      } else {
        $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND statusId = 4', [$sessID]);
      }
      $status = $this->getAllStatus();

      foreach ($data as &$booking) {
        $booking = $this->makeDisplayValues($booking);
      }
      unset($booking);
      $data = $this->checkDuplicates($data);
      return emit($response, $data);
    }

    private function checkDuplicates($data) {
      $data2 = $data;
      foreach ($data as &$booking) {
        $duplicates = [];
        foreach($data2 as $duplicate) {
          if ($duplicate['isReturn'] !== $booking['isReturn']) continue;
          if ($duplicate['statusId'] === 4 || $booking['statusId'] === 4) continue; //cancelled
          if ($booking['studentId'] == $duplicate['studentId']) {
            $duplicates[] = $duplicate['id'];
            continue;
          }
          foreach ($duplicate['passengers'] as $p) {
            if ($p == $booking['studentId']) {
              $duplicates[] = $duplicate['id'];
              continue;
            }
          }
        }
        $booking['duplicates'] = $duplicates;
        $booking['duplicateCount'] = count($duplicates) - 1;
      }
      return $data;
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
          $booking[$field] = $this->getAirport($booking['airportId']) . ' [ Flight # '. $booking['flightNumber'] .' @ ' . $booking['flightTime'] . ' ]';
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
      $booking['pickupUnix'] = strtotime($booking['pickupDate'] . ' ' . $booking['pickupTime']);
      $booking['pickupDateOnlyPretty'] = convertToAdaPrettyDate($booking['pickupDate']);
      $booking['pickupDatePretty'] = convertToAdaPrettyDate($booking['pickupDate']) . " - "  . $booking['pickupTime'];
      $booking['pickupDate'] = convertToAdaDate($booking['pickupDate']);

      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $booking['schoolNumber'] = $student->schoolNumber;
      $booking['house'] = $student->boardingHouse;
      $isamsStudent = new \Entities\People\iSamsStudent($this->isams, $student->misId);
      $booking['mob'] = $isamsStudent->mobile;
      // contacts
      $booking['contact'] = (object)$isamsStudent->getContactByUserId($booking['contactIsamsUserId']);
      $booking['actionedByTimestamp'] = convertToAdaDatetime($booking['actionedByTimestamp']);

      // taxi company
      $companyId = $booking['companyId'];
      $d = $this->adaModules->select('tbs_taxi_companies', 'name, email, phoneNumber', 'id=?', [$companyId]);
      if (isset($d[0])) {
        $booking['companyName'] = $d[0]['name'];
        $booking['companyPhoneNumber'] = $d[0]['phoneNumber'];
        $booking['companyEmail'] = $d[0]['email'];
      }

      // dates
      $d = $this->adaModules->select('tbs_sessions', 'dateOut, dateRtn', 'id=?', [$booking['sessionId']]);
      if (isset($d[0])){
        $date = $booking['isReturn'] ? $d[0]['dateRtn'] : $d[0]['dateOut'];
        $booking['date'] = convertToAdaDate($date);
      }

      //make display name of user that has actioned changed
      $user = new \Entities\People\User($this->ada, $booking['actionedByUserId']);
      $booking['actionedBy'] = $user->login;

      $booking['revisions'] = [];
      //get revisions
      $revisions = $this->adaModules->select('tbs_taxi_bookings_archive', '*', 'bookingId=? ORDER BY id DESC', [$booking['id']]);
      $flagRecent = true;
      foreach($revisions as $r){
        $booking['revisions'][] = [
          'createdAt' => $r['createdAt'],
          'pickupDate'  => convertToAdaDate($r['pickupDate']),
          'pickupTime'  => $r['pickupTime'],
          'displayFrom' => $r['displayFrom'],
          'displayTo'   => $r['displayTo'],
          'companyId' => $r['companyId'],
          'actionedBy' => (new \Entities\People\User($this->ada, $r['actionedByUserId']))->login,
          'recent'      => $flagRecent,
        ];
        $flagRecent = false;
      }

      return $booking;
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
      $this->adaModules->update('tbs_taxi_bookings', 'statusId=?, taxiId=?', 'id = ?', [4, null, $args['id']]);
      $this->publish($args['id']);
      $this->sendCancelledEmail($args['id']);
      return emit($response, []);
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

      if ($out['pickup']) $outId = $this->newBooking($sessionId, $pupilId, $parentUserId, $out, $data['note']);
      if ($ret['journeyType']) $retId = $this->newBooking($sessionId, $pupilId, $parentUserId, $ret, $data['note'], true);

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

    public function sharePut($request, $response)
    {
      $data = $request->getParsedBody();

      $fromId = $data['fromId'];
      $toId = $data['toId'];

      $oldTaxiId = $this->adaModules->select('tbs_taxi_bookings', 'taxiId', 'id=?', [$fromId])[0]['taxiId'];

      //get destination taxi
      $taxiId = $this->adaModules->select('tbs_taxi_bookings', 'taxiId', 'id=?', [$toId])[0]['taxiId'];

      $this->adaModules->update('tbs_taxi_bookings', 'taxiId=?', 'id=?', [$taxiId, $fromId]);

      //if no other people in this taxi, delete it
      $bookings = $this->adaModules->select('tbs_taxi_bookings', 'id', 'taxiId=?', [$oldTaxiId]);
      if (count($bookings) == 0) $this->adaModules->delete('tbs_taxi_taxis', 'id=?', [$oldTaxiId]);

      return emit($response, $data);
    }

    public function unsharePut($request, $response)
    {
      global $userId;
      $booking = $request->getParsedBody();
      $bookingId = $booking['id'];
      $sessionId = $booking['sessionId'];
      $companyId = $booking['companyId'];
      $isReturn = $booking['isReturn'];
      $taxiId = $this->adaModules->insert('tbs_taxi_taxis', 'sessionId, companyId, isReturn', [$sessionId, $companyId, $isReturn] );

      $this->adaModules->update('tbs_taxi_bookings', 'taxiId=?', 'id=?', [$taxiId, $bookingId]);

      return emit($response, $booking);
    }

    public function taxiAssigmentPut($request, $response)
    {
      global $userId;
      $data = $request->getParsedBody();

      $companyId = $data['companyId'];
      $cost = $data['cost'];
      $bookingId = $data['id'];
      $sessionId = $data['sessionId'];
      $taxiId = $data['taxiId'];
      $isReturn = $data['isReturn'];
      $sentToCompany = $data['sentToCompany'];

      //get current taxi companyId to look for changes
      $oldCompanyId = $this->adaModules->select('tbs_taxi_bookings', 'companyId', 'id=?', [$bookingId])[0]['companyId'];

      if ($companyId !== $oldCompanyId) {
          $this->archiveBooking($bookingId);
          //company has changed so create a new taxi for this company and reset email flags
          //first see if this booking's taxi is being shared with anyone else. If not then delete it.
          $bookings = $this->adaModules->select('tbs_taxi_bookings', 'id', 'taxiId=?', [$taxiId]);
          if (count($bookings) === 1) $this->adaModules->delete('tbs_taxi_taxis', 'id=?', [$taxiId]);

          //make a new taxi for this booking
          $taxiId = $this->adaModules->insert('tbs_taxi_taxis', 'sessionId, companyId, isReturn', [$sessionId, $companyId, $isReturn] );
          $sentToCompany = 0;

      }

      $this->setStatus($bookingId, 2);
      $this->adaModules->update(
        'tbs_taxi_bookings',
        'companyId=?, cost=?, actionedByUserId=?, taxiId=?, sentToCompany=?',
        'id=?',
        [$companyId, $cost, $userId, $taxiId, $sentToCompany, $bookingId]);
      
      $this->sendEnquiryEmail($bookingId); 
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

    private function newBooking(int $sessionId, int $studentId, int $parentUserId, array $booking, $note, $isReturn = false)
    {
      $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];
      $booking['schoolLocation'] = $schoolLocation;

      $student = new \Entities\People\Student($this->ada, $studentId);
      $familyId = $student->misFamilyId;
      $id = $this->adaModules->insert(
        'tbs_taxi_bookings',
        'studentId, mis_family_id, contactIsamsUserId, sessionId, pickupDate, pickupTime, isReturn, note, journeyType, schoolLocation, address, airportId, flightNumber, flightTime, stationId, trainTime',
        array(
          $studentId,
          $familyId,
          $parentUserId,
          $sessionId,
          convertToMysqlDate($booking['pickupDate']),
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

      $fields = [
        'name'      => $booking['contact']->letterSalutationSingle,
        'id'        => $bookingId,
        'pupil'     => $booking['displayName'],
        'date'    => $booking['pickupDateOnlyPretty'],
        'time'      => $booking['pickupTime'],
        'from'      => $booking['displayFrom'],
        'to'        => $booking['displayTo'],
        'passengers'=> $passengerString,
        'note'      => strlen($booking['note']) == 0 ? '-' : $booking['note']
      ];

      $this->sendEmail($bookingId, $booking['studentId'], $booking['contact']->email, 'Marlborough College Taxi Booking - Received','TBS.ReceivedTaxi', $fields);
    }

    private function sendCancelledEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);
      $passengers = $this->getPassengers($bookingId);
      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $cc = [$booking['companyEmail'], $student->email];
      $count = count($passengers);
      if ( $count > 0 ) {
        $c = 0;
        $passengerString = '';
        $comma = ';';
        foreach($passengers as $p){
          $c++;
          if ($c == $count) $comma = '';
          $passengerString .= "<span style='margin-right:5px'>{$p->displayName}$comma</span>";
          $cc[] = $p->email;
          $contacts = $this->getContacts($p->id);
          foreach($contacts as $c) if ($c['portalUserInfo'] && $p->misFamilyId !== $booking['mis_family_id']) $cc[] = $c['email'];
          $comma = ';';
        }
      } else {
        $passengerString = '-';
      }

      $fields = [
        'name'    => $booking['contact']->letterSalutationSingle,
        'id'      => $bookingId,
        'pupil' => $booking['displayName'],
        'date'    => $booking['pickupDateOnlyPretty'],
        'mob'   => $booking['mob'],
        'time'    => $booking['pickupTime'],
        'from'    => $booking['displayFrom'],
        'to'      => $booking['displayTo'],
        'passengers'  => $passengerString,
        'note'    => strlen($booking['note']) == 0 ? '-' : $booking['note']
      ];

      $this->sendEmail($bookingId, $booking['studentId'], $booking['contact']->email, 'Marlborough College Taxi Booking - Cancelled', 'TBS.CancelledTaxi', $fields, $cc);

    }

    private function sendEnquiryEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);
      $passengers = $this->getPassengers($bookingId);
      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $cc = ['coaches@marlboroughcollege.org'];
      $count = count($passengers);
      if ( $count > 0 ) {
        $c = 0;
        $passengerString = '';
        $comma = ';';
        foreach($passengers as $p){
          $c++;
          if ($c == $count) $comma = '';
          $passengerString .= "<span style='margin-right:5px'>{$p->displayName}$comma</span>";
          $cc[] = $p->email;
          $contacts = $this->getContacts($p->id);
          foreach($contacts as $c) if ($c['portalUserInfo'] && $p->misFamilyId !== $booking['mis_family_id']) $cc[] = $c['email'];
          $comma = ';';
        }
      } else {
        $passengerString = '-';
      }



      $fields = [
        'name'    => $booking['companyName'],
        'id'      => $bookingId,
        'pupil' => $booking['displayName'],
        'schoolNumber' => $student->schoolNumber,
        'mob'   => $booking['mob'],
        'date'    => $booking['pickupDateOnlyPretty'],
        'time'    => $booking['pickupTime'],
        'from'    => $booking['displayFrom'],
        'to'      => $booking['displayTo'],
        'cost'    => $booking['cost'],
        'passengers'  => $passengerString,
        'note'    => strlen($booking['note']) == 0 ? '-' : $booking['note']
      ];
      $this->sendEmail($bookingId, $booking['studentId'], $booking['companyEmail'], 'Marlborough College Booking - Request', 'TBS.EnquiryTaxi', $fields, $cc);
    }

    private function sendConfirmedEmail(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ? ORDER BY id DESC', [$bookingId])[0];
      // $schoolLocation = $isReturn ? $booking['destination'] : $booking['pickup'];

      $booking = $this->makeDisplayValues($booking);
      $passengers = $this->getPassengers($bookingId);
      $student = new \Entities\People\Student($this->ada, $booking['studentId']);
      $cc = [$booking['companyEmail'], $student->email];
      $count = count($passengers);
      if ( $count > 0 ) {
        $c = 0;
        $passengerString = '';
        $comma = ';';
        foreach($passengers as $p){
          $c++;
          if ($c == $count) $comma = '';
          $passengerString .= "<span style='margin-right:5px'>{$p->displayName}$comma</span>";
          $cc[] = $p->email;
          $contacts = $this->getContacts($p->id);
          foreach($contacts as $c) if ($c['portalUserInfo'] && $p->misFamilyId !== $booking['mis_family_id']) $cc[] = $c['email'];
          $comma = ';';
        }
      } else {
        $passengerString = '-';
      }



      $fields = [
        'name'    => $booking['contact']->letterSalutationSingle,
        'id'      => $bookingId,
        'pupil' => $booking['displayName'],
        'schoolNumber' => $student->schoolNumber,
        'mob'   => $booking['mob'],
        'date'    => $booking['pickupDateOnlyPretty'],
        'time'    => $booking['pickupTime'],
        'from'    => $booking['displayFrom'],
        'to'      => $booking['displayTo'],
        'cost'    => $booking['cost'],
        'company'    => $booking['companyName'] . ' (Tel: ' . $booking['companyPhoneNumber'] . ')',
        'passengers'  => $passengerString,
        'note'    => strlen($booking['note']) == 0 ? '-' : $booking['note']
      ];
      $this->sendEmail($bookingId, $booking['studentId'], $booking['contact']->email, 'Marlborough College Taxi Booking - Confirmed', 'TBS.ConfirmedTaxi', $fields, $cc);
    }

    public function summaryEmailPost($request, $response)
    {
      $summary = $request->getParsedBody();

      $to = $this->debug === true ? $this->email : $summary['email'];
      $email = new \Utilities\Email\Email($to, 'Marlborough College Bookings', 'coaches@marlboroughcollege.org', [], ['coaches@marlboroughcollege.org'], $this->debug);
      $content = $summary['html'];
      $email->send($content);

      // $this->adaModules->select('tbs_taxi_bookings', 'sessionId, companyId', 'id=?', [$bookingId])[0]; 
      // $sessionId = $b['sessionId']; 
      // $companyId = $b['companyId'];
      // //log email
      // $this->adaModules->insert(
      //   'tbs_emails',
      //   'isTaxi, bookingId, studentId, sessionId, email, cc, subject, content, companyId',
      //   [
      //     1,
      //     $bookingId,
      //     $studentId,
      //     $sessionId,
      //     $to,
      //     $ccString,
      //     $subject,
      //     $content,
      //     $companyId
      //   ]
      // );

      foreach($summary['bookings'] as $booking) {
          $this->adaModules->update('tbs_taxi_bookings', 'sentToCompany=?', 'id=?', [1, $booking['id']]);
      }
      // use the id of the first booking to trigger a session update
      $anId = $summary['bookings'][0]['id'];
      $summary['anId'] = $anId;
      $this->publish($anId);
      return emit($response, $summary);
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

    private function getPassengers(int $bookingId)
    {
      $d = $this->adaModules->select('tbs_taxi_passenger', 'studentId', 'bookingId=?', array($bookingId));
      $passengers = [];
      foreach($d as $p){
        $id = $p['studentId'];
        $student = new \Entities\People\Student($this->ada, $id);
        $passengers[] = $student;
      }
      return $passengers;
    }
    // ajbell
    private function savePassengers(int $bookingId, array $passengers)
    {
        //get current Passengers
        $current = $this->adaModules->select('tbs_taxi_passenger', 'id, studentId', 'bookingId=?', array($bookingId));
        $currentKeys = [];
        $ids = [];
        foreach($current as $passenger) {
          $currentKeys['p_' . $passenger['studentId']] = true;
          $ids['p_' . $passenger['studentId']] = $passenger['studentId'];
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

      $bookingFields = [
        'pickupDate'    => convertToMysqlDate($booking['pickupDate']),
        'pickupTime'    => $booking['pickupTime'],
        'note'          => $note,
        'journeyType'   => $booking['journeyType'],
        'schoolLocation'=> $schoolLocation,
        'address'       => $booking['address'],
        'airportId'       => $booking['airport'] ?? null,
        'flightNumber'  => $booking['flightNumber'],
        'flightTime'    => $booking['flightTime'],
        'stationId'       => $booking['station'] ?? null,
        'trainTime'     => $booking['trainTime'],
      ];

      $revision = $this->archiveBooking($bookingId);

      $bookingFields['isReturn'] = $isReturn ? 1 : 0;
      $bookingFields['revision'] = $revision;
      $bookingFields['statusId'] = 1;
      $bookingFields['bookingId'] = $bookingId;
      // var_dump($bookingFields); exit();
      $this->adaModules->update(
        'tbs_taxi_bookings',
        'pickupDate=?, pickupTime=?, note=?, journeyType=?, schoolLocation=?, address=?, airportId=?, flightNumber=?, flightTime=?, stationId=?, trainTime=?, isReturn=?, revision = ?, statusId = ?',
        'id=?',
        array_values($bookingFields)
      );

    }

    private function archiveBooking(int $bookingId)
    {
      $booking = $this->adaModules->select('tbs_taxi_bookings', '*', 'id=?', [$bookingId]);
      if (!isset($booking[0])) return;
      $b = $booking[0];
      $b = $this->makeDisplayValues($b);

      $this->adaModules->insert(
        'tbs_taxi_bookings_archive',
        'bookingId, studentId, pickupDate, pickupTime, displayFrom, displayTo, companyId, actionedByUserId',
        array(
          $b['id'],
          $b['studentId'],
          convertToMysqlDate($b['pickupDate']),
          $b['pickupTime'],
          $b['displayFrom'],
          $b['displayTo'],
          $b['companyId'],
          $b['actionedByUserId']
        )
      );

      $revision = $b['revision'];
      return $revision + 1;

    }

    private function sendEmail($bookingId, $studentId, $to, $subject, $template, $fields, $cc = [], $bcc = [])
    {
      $email = new \Utilities\Email\Email($to, $subject, 'coaches@marlboroughcollege.org', $cc, $bcc, $this->debug);
      $content = $email->template($template, $fields);
      $res = $email->send($content);
      $ccString = '';
      foreach($cc as $c) $ccString .= $c . ' ';

      $b = $this->adaModules->select('tbs_taxi_bookings', 'sessionId, companyId', 'id=?', [$bookingId])[0]; 
      $sessionId = $b['sessionId']; 
      $companyId = $b['companyId'];
      //log email
      $this->adaModules->insert(
        'tbs_emails',
        'isTaxi, bookingId, studentId, sessionId, email, cc, subject, content, companyId',
        [
          1,
          $bookingId,
          $studentId,
          $sessionId,
          $to,
          $ccString,
          $subject,
          $content,
          $companyId
        ]
      );

    }

    //argument: sessionId
    public function emailsGet($request, $response, $args)
    {
      $sessionId = $args['session'];
      $emails = $this->adaModules->select(
        'tbs_emails',
        'id, isTaxi, bookingId, studentId, sessionId, email, cc, subject, content, timestamp',
        'isTaxi = ? AND sessionId = ? ORDER BY timestamp DESC',
        [
          1,
          $sessionId
        ]
      );
      foreach($emails as &$e) {
        $e['name'] = (new \Entities\People\Student($this->ada, $e['studentId']))->displayName;
        $e['time'] = convertToAdaDatetime($e['timestamp']);
        // $e['time'] = $e['timestamp'];
      }
      return emit($response, $emails);
    }

    private function publish(int $bookingId) {
      $booking = $this->retrieveBooking($bookingId);
      $family = new \Sockets\CRUD("coaches.user.{$booking['contactIsamsUserId']}");
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
