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
       $this->student = new \Entities\Students\Student($container);

       $this->status = $this->getAllStatus();
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
            case 0:
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
//
    public function bookingsGet($request, $response, $args)
    {
      $sessID = $args['session'];
      $type = $args['type'];
      $isReturn = $type === 'out' ? 0 : 1;
      $isCancelled = $type == 'can' ? true : false;

      if (!$isCancelled) {
        $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND isReturn = ? AND statusId > 0 ORDER BY statusId ASC, studentId DESC', [$sessID, $isReturn]);
      } else {
        $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'sessionId = ? AND statusId = 0', [$sessID]);
      }
      $status = $this->getAllStatus();

      foreach ($data as &$booking) {
        $booking = $this->makeDisplayValues($booking);
      }
      return emit($response, $data);
    }

    private function makeDisplayValues($booking)
    {
      $status = $this->status;

      $booking['displayFrom'] = $this->getPickup($booking['schoolLocation']);
      switch($booking['journeyType']) {
        case 'flight':
          $booking['displayTo'] = $this->getAirport($booking['airportId']) . ' ['. $booking['flightNumber'] .']';
          break;
        case 'train':
          $booking['displayTo'] = $this->getStation($booking['stationId']) . ' ['. $booking['trainTime'] .']';
          break;
        case 'address':
          $booking['displayTo'] = $booking['address'];
          break;
      }
      $booking['passengers'] = array();
      $booking['displayFrom'] = $this->getPickup($booking['schoolLocation']);
      $booking['passengerIds'] = $this->getPassengers($booking['id']);
      $booking['passengerCount'] = count($booking['passengerIds']);
      $booking['status'] = $status['s_' . $booking['statusId']];
      $booking['displayName'] = $this->student->displayName($booking['studentId']);

      return $booking;
    }

    public function bookingGet($request, $response, $args)
    {
      $data = $this->adaModules->select('tbs_taxi_bookings', '*', 'id = ?', [$args['id']]);
      $data['passengerIds'] = $this->getPassengers($args['id']);
      $data['passengerCount'] = count($data['passengerIds']);
      return emit($response, $data);
    }

    public function bookingDelete($request, $response, $args)
    {
      $data = array();
      $this->adaModules->update('tbs_taxi_bookings', 'statusId=?', 'id = ?', [0, $args['id']]);
      return emit($response, $data);
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

      if ($outId) $this->savePassengers($outId, $out['passengers']);
      if ($retId) $this->savePassengers($retId, $ret['passengers']);

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
        $this->adaModules->update('tbs_taxi_bookings', 'taxiId=?, cost=?', 'id=?', array($taxiId, 0, $bookingId));
      }

      return emit($response, $data);
    }

    private function newBooking(int $sessionId, int $studentId, array $booking, $note, $isReturn = false)
    {
      $schoolLocation = $isReturn ? $booking['destination']['value'] : $booking['pickup']['value'];
      $id = $this->adaModules->insert(
        'tbs_taxi_bookings',
        'studentId, sessionId, pickupTime, isReturn, note, journeyType, schoolLocation, address, airportId, flightNumber, flightTime, stationId, trainTime',
        array(
          $studentId,
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
      return $id;
    }

    private function getPassengers(int $bookingId)
    {
      $d = $this->adaModules->select('tbs_taxi_passenger', 'studentId', 'bookingId=?', array($bookingId));
      $ids = array();
      foreach($d as $passenger){
        $ids[] = $passenger['studentId'];
      }
      return $ids;
    }

    private function savePassengers(int $bookingId, array $passengers)
    {
        //get current Passengers
        $current = $this->adaModules->select('tbs_taxi_passenger', 'id, studentId', 'bookingId=?', array($bookingId));
        $currentKeys = array();
        foreach($current as $passenger) {
          $currentKeys['p_' . $passenger['id']] = true;
        }
        foreach($passengers as $passenger) {
          $id = $passenger['id'];
          if (!isset($currentKeys['p_' . $id])) {
            //must be new
            $this->adaModules->insert('tbs_taxi_passenger', 'studentId, bookingId', array($id, $bookingId));
          } else {
            //already exists
            $currentKeys['p_' . $id] = false;
          }
        }
        //delete any remaining passengers as they must have been taken off the booking
        foreach($currentKeys as $passenger) {
          if ($passenger === true) {
            $this->adaModules->delete('tbs_taxi_passenger', 'studentId = ? and bookingId = ?', array($id, $bookingId));
          }
        }
    }

    private function updateBooking(int $bookingId, array $booking, $note, $isReturn = false)
    {
      $schoolLocation = $isReturn ? $booking['destination']['value'] : $booking['pickup']['value'];
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
