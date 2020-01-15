<?php

/**
 * Description

 * Usage:

 */
namespace Transport;

class TbsExtSessions
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       // $this->mcCustom= $container->mcCustomTest;
       // $this->isams = $container->isamsTest;
       // $userId = $request->getAttribute('userId');
    }

    public function sessionsGet($request, $response, $args)
    {
        $sessions = $this->adaModules->select('tbs_sessions', '*', '1=1 ORDER BY isActive DESC, dateOut DESC');
        convertArrayToAdaDatetime($sessions);
        return emit($response, $sessions);
    }

    public function sessionGet($request, $response, $args)
    {
        $session = $this->adaModules->select(
          'tbs_sessions',
          '*',
          'id=? ORDER BY isActive DESC, dateOut DESC',
          array($args['id']));

        convertArrayToAdaDatetime($session);

        return emit($response,
                    $session[0]
                  );
    }

    //for making a new session, the deadline is midday on the monday (taxi) and wednesday (coaches) before outward travel
    public function deadlinesGet($request, $response, $args)
    {
      $dateOut = $args['dateOut'];
      $data = [];
      $data['taxiDeadline'] = date('d-m-Y', strtotime('previous monday', strtotime($dateOut))) . ' 12:00';
      $data['coachDeadline'] = date('d-m-Y', strtotime('previous wednesday', strtotime($dateOut))) . ' 12:00';

      return emit($response, $data);
    }

    public function sessionActivate($request, $response, $args)
    {
      $id = $args['id'];
      $this->adaModules->update('tbs_sessions', 'isActive=?', '1=1', array(0));
      $this->adaModules->update('tbs_sessions', 'isActive=?', 'id=?', array(1, $id));

      return emit($response, $id);
    }

    public function sessionActivateFromCheckist($request, $response, $args)
    {
      $id = $args['id'];
      $isActive = $args['isActive'];
      $this->adaModules->update('tbs_sessions', 'isActive=?', '1=1', array(0));
      $this->adaModules->update('tbs_sessions', 'isActive=?', 'id=?', array($isActive, $id));

      return emit($response, $id);
    }

    public function sessionSelfServicePost($request, $response, $args)
    {
      $id = $args['id'];
      $isOn = $args['isOn'];
      $this->adaModules->update('tbs_sessions', 'selfServiceOn=?', 'id=?', [$isOn, $id]);
      $this->publish($id);

      return emit($response, $id);
    }

    public function sessionPut($request, $response)
    {
     $data = $request->getParsedBody();
     convertArrayToMysqlDatetime($data);
     return emit($response, $this->adaModules->updateObject('tbs_sessions', $data, 'id'));
    }

    public function sessionPost($request, $response)
    {
     $data = $request->getParsedBody();
     convertArrayToMysqlDatetime($data);

     $data['id'] = $this->adaModules->insertObject('tbs_sessions', $data, 'id');
     return emit($response, $data);
    }

    public function sessionDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('tbs_sessions', 'id=?', array($args['id'])));
    }

    private function publish(int $sessionId) {
      $session = new \Sockets\CRUD("coaches$sessionId");
      $self = new \Sockets\CRUD("coaches.self.$sessionId");
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
