<?php

/**
 * Description

 * Usage:

 */
namespace Transport;

class TbsExtCoaches
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->mcCustom= $container->mcCustomTest;
       $this->isams = $container->isamsTest;
    }

    public function busSummariesGet($request, $response, $args)
    {
      $sessID = $args['id'];
      $coaches = array();
      $coachBlank = array('countOut' => 0,
                          'countRet' => 0,
                          'pupilsOut' => array(),
                          'pupilsIn' => array(),
                        );
      $bookings = $this->mcCustom->select('TblCoachesBookings',
                                          'TblCoachesBookingsID, txtSchoolID, txtBusOut, txtBusRet, intBusOut, intBusRet, boolCancelled',
                                          'intExeatID=? AND (intBusOut > 0 OR intBusRet > 0) AND boolCancelled = 0',
                                          array($sessID));
      foreach($bookings as &$booking)
      {
          $txtSchoolID = $booking['txtSchoolID'];
          $pupil = new \Entities\People\iSamsPupil($this->isams, $txtSchoolID);

          $txtBusOut = $booking['txtBusOut'];
          $txtBusRet = $booking['txtBusRet'];

          if(!is_null($txtBusOut) && (int)$booking['intBusOut'] > 0)
          {
            if(!isset($coaches[$txtBusOut]))
            {
                $coaches[$txtBusOut] = $coachBlank;
                $coaches[$txtBusOut]['code'] = $txtBusOut;
                $coaches[$txtBusOut]['id'] = (int)$booking['intBusOut'];
            }

            $coaches[$txtBusOut]['countOut']++;
            $coaches[$txtBusOut]['pupilsOut'][] = $pupil;
          }
          if(!is_null($txtBusRet) && (int)$booking['intBusRet'] > 0)
          {
            if(!isset($coaches[$txtBusRet])){
              $coaches[$txtBusRet] = $coachBlank;
              $coaches[$txtBusRet]['code'] = $txtBusRet;
              $coaches[$txtBusRet]['id'] = (int)$booking['intBusRet'];
            }
            $coaches[$txtBusRet]['countRet']++;
            $coaches[$txtBusRet]['pupilsRet'][] = $pupil;
          }
      }
      foreach($coaches as &$coach){
        $coach = array_merge($coach, $this->makeCoachDetails($coach['id'], $coach['code']));
      }
      ksort($coaches);
      return emit($response, array_values($coaches));
    }

    private function makeCoachDetails($id, $code)
    {
      $coach = $this->mcCustom->select( 'TblCoachesCoaches',
                                         'TblCoachesCoachesID as id, txtBusCode, txtDestination, txtACDestination, mCharge, txtDepartureDetails',
                                         'txtBusCode = ?',
                                          array($code));

      if(!isset($coach[0])) return array('error' => "Coach $id not found");

      $coach = $coach[0];

      $coach['destinations'] = $this->mcCustom->select(  'TblCoachesDestinations',
                                                        'TblCoachesDestinationsID as id, txtToFrom, txtDirection',
                                                        'intBusNo = ?',
                                                        array($id)
                                                      );
      $coach['journeys'] = $this->mcCustom->select( 'TblCoachesJourneys',
                                                    'TblCoachesJourneysID as id, txtStop, intStopOrder',
                                                    'intCoachNo = ? ORDER BY intStopOrder ASC',
                                                    array($id)
                                                    );
      return $coach;
    }

    public function sessionGet($request, $response, $args)
    {
      $sessID = $args['id'];
      $data = array();
      $busCounts = array();
      $data['bookings'] = $this->mcCustom->select('TblCoachesBookings',
                                                  'TblCoachesBookingsID, txtSchoolID, txtBusOut, intBusOut, intBusRet, txtBusRet, boolCancelled',
                                                  'intExeatID=? AND (intBusOut > 0 OR intBusRet > 0)',
                                                  array($sessID));
      foreach($data['bookings'] as &$booking)
      {
          $booking['id'] = (int)$booking['TblCoachesBookingsID'];
          $pupil = new \Entities\People\iSamsPupil($this->isams, $booking['txtSchoolID']);
          $booking['txtBusRet'] = is_null($booking['txtBusRet']) ? '' : $booking['txtBusRet'];
          $booking['txtBusOut'] = is_null($booking['txtBusOut']) ? '' : $booking['txtBusOut'];
          $booking = array_merge($booking, (array)$pupil);
      }

      usort($data['bookings'], array($this, "nameSort"));

      $data['coaches'] = $this->getCoaches($response);
      //make a label value array for UI select boxes
      $selectArray = array(array('label' => 'NONE', 'value' => ''));
      foreach($data['coaches'] as $coach)
      {
        $array['value'] = $coach['txtBusCode'];
        $array['label'] = $coach['txtDestination'];
        $selectArray[] = $array;
      }
      $data['coachesSelect'] = $selectArray;
      return emit($response, $data);
    }

    public function sessionsGet($request, $response, $args)
    {
      $userId = $request->getAttribute('userId');
      $sessions = $this->mcCustom->select('TblCoachesExeats',
                                          'TblCoachesExeatsID, txtDescription, dteOutward, dteReturn, boolActive',
                                          'TblCoachesExeatsID > 0 ORDER BY boolActive DESC, dteOutward DESC', array());
      $data = array_slice($sessions, 0, 20);

      //add a label / value field for display in quasar select box
      foreach($data as &$d)
      {
        $d['value'] = $d['TblCoachesExeatsID'];
        $d['label'] = $d['txtDescription'];
        $d['sublabel'] = $d['boolActive'] == 1 ? "Active" : '';
      }
      return emit($response, $data);
    }

    public function bookingsCancelPost($request, $response)
    {
      try{
        $data = $request->getParsedBody();
        foreach($data as &$booking){
          $this->mcCustom->update('TblCoachesBookings', 'boolCancelled = 1', 'TblCoachesBookingsID=?', array($booking['id']));
          $booking['boolCancelled'] = 1;
        }
        return emit($response, $data);
      }catch(Exception $e){
        return emitError($response, 400, 'Not Cancelled');
      }
    }

    public function changeBusOutPut($request, $response)
    {
      try{
        $data = $request->getParsedBody();
        $this->mcCustom->update('TblCoachesBookings', 'txtBusOut=?', 'TblCoachesBookingsID=?', array($data['txtBusOut'], $data['id']));
        return emit($response, $data);
      }catch(Exception $e){
        return emitError($response, 400, 'Error Changing Bus');
      }
    }

    public function changeBusReturnPut($request, $response)
    {
      try{
        $data = $request->getParsedBody();
        $this->mcCustom->update('TblCoachesBookings', 'txtBusRet=?', 'TblCoachesBookingsID=?', array($data['txtBusRet'], $data['id']));
        return emit($response, $data);
      }catch(Exception $e){
        return emitError($response, 400, 'Error Changing Bus');
      }
    }

    private function getCoaches($response)
    {
      try{
        $coaches = $this->mcCustom->select( 'TblCoachesCoaches',
                                            'TblCoachesCoachesID as id, txtBusCode, txtDestination, txtACDestination, mCharge, boolActive, boolDeleted, txtDepartureDetails',
                                            'boolDeleted = 0
                                            ORDER BY txtDestination ASC',
                                            array());
        return $coaches;
      }catch(Exception $e){
        return emitError($response, 400, 'Error Loading Coaches');
      }
    }

    private function nameSort($a, $b){
      return strcmp($a['lastName'], $b['lastName']);
    }
}
