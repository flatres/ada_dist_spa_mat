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
       $this->mcCustom= $container->mcCustomTest;
       $this->isams = $container->isamsTest;
       // $userId = $request->getAttribute('userId');
    }

    public function sessionsGet($request, $response, $args)
    {
        return emit($response,
                    $this->mcCustom->select('TblCoachesExeats', '*', '1=1 ORDER BY boolActive DESC, dteOutward DESC'));
    }

    public function sessionGet($request, $response, $args)
    {
        return emit($response,
                    $this->mcCustom->select(
                      'TblCoachesExeats',
                      '*',
                      'TblCoachesExeatsID=? ORDER BY boolActive DESC, dteOutward DESC',
                      array($args['id']))
                  );
    }

    public function sessionActivate($request, $response, $args)
    {
      $id = $args['id'];
      $this->mcCustom->update('TblCoachesExeats', 'boolActive=?', '1=1', array(false));
      $this->mcCustom->update('TblCoachesExeats', 'boolActive=?', 'TblCoachesExeatsID=?', array(true, $id));

      return emit($response, $id);
    }

    public function sessionPut($request, $response)
    {
     $data = $request->getParsedBody();
     return emit($response, $this->mcCustom->updateObject('TblCoachesExeats', $data, 'TblCoachesExeatsID'));
    }

    public function sessionPost($request, $response)
    {
     $data = $request->getParsedBody();
     $data['id'] = $this->mcCustom->insertObject('TblCoachesExeats', $data, 'TblCoachesExeatsID');
     return emit($response, $data);
    }

    public function sessionDelete($request, $response, $args)
    {
      return emit($response, $this->mcCustom->delete('TblCoachesExeats', 'TblCoachesExeatsID=?', array($args['id'])));
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
