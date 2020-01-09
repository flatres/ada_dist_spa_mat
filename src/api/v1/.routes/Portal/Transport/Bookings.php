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

      $s = $d[0];
      $stampOutTaxiDeadline = strtotime($s['taxiOutDeadline']);
      $stampOutCoachDeadline = strtotime($s['coachOutDeadline']);
      $stampRetTaxiDeadline = strtotime($s['taxiRetDeadline']);
      $stampRetCoachDeadline = strtotime($s['coachRetDeadline']);

      $s['taxiOutClosed'] = time() - $stampOutTaxiDeadline > 0;
      $s['coachOutClosed'] = time() - $stampOutCoachDeadline > 0;
      $s['taxiRetClosed'] = time() - $stampRetTaxiDeadline > 0;
      $s['coachRetClosed'] = time() - $stampRetCoachDeadline > 0;
      $d = [$s];
      convertArrayToAdaDatetime($d);
      return emit($response, $d[0]);
    }

    public function familyBookingsGet($request, $response, $args)
    {
      $familyId = $args['familyId'];
      $taxiObj = new \Transport\TbsExtTaxisBookings($this->container);
      $taxiBookings = $taxiObj->familyBookings($familyId);

      foreach($taxiBookings as &$b) {
        $b['bookingType'] = 'taxi';
      }

      $coachObj = new \Transport\TbsExtCoachesBookings($this->container);
      $coachBookings = $coachObj->familyBookings($familyId);

      foreach($coachBookings as &$c) {
        $c['bookingType'] = 'coach';
      }

      $bookings = array_merge($taxiBookings, $coachBookings);
      // https://stackoverflow.com/questions/8121241/sort-array-based-on-the-datetime-in-php

      usort($bookings, function($a, $b) {
        $ad = new \DateTime($a['createdAt']);
        $bd = new \DateTime($b['createdAt']);

        if ($ad == $bd) {
          return 0;
        }

        return $ad < $bd ? -1 : 1;
      });

      return emit($response, $bookings);
    }

    public function userBookingsGet($request, $response, $args)
    {
      $parentUserId = $args['parentUserId'];
      $taxiObj = new \Transport\TbsExtTaxisBookings($this->container);
      $taxiBookings = $taxiObj->userBookings($parentUserId);

      foreach($taxiBookings as &$b) {
        $b['bookingType'] = 'taxi';
      }

      $coachObj = new \Transport\TbsExtCoachesBookings($this->container);
      $coachBookings = $coachObj->userBookings($parentUserId);

      foreach($coachBookings as &$c) {
        $c['bookingType'] = 'coach';
      }

      $bookings = array_merge($taxiBookings, $coachBookings);
      // https://stackoverflow.com/questions/8121241/sort-array-based-on-the-datetime-in-php

      usort($bookings, function($a, $b) {
        $ad = new \DateTime($a['createdAt']);
        $bd = new \DateTime($b['createdAt']);

        if ($ad == $bd) {
          return 0;
        }

        return $ad < $bd ? -1 : 1;
      });

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
