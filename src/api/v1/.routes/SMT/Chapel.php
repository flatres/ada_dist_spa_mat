<?php

/**
 * Description

 * Usage:

 */
namespace SMT;

class Chapel
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
       $this->exgarde = $container->exgarde;
       $this->mcCustom = $container->mcCustom;
    }

// ROUTE -----------------------------------------------------------------------------
    public function AttendanceGet($request, $response, $args)
    {
      $unix = strtotime($args['date']);
      $chapel = new \SMT\Tools\Watch\Chapel($this->ada, $this->adaModules, $this->isams, $this->mcCustom, $this->exgarde);
      $data = $chapel->byDate($unix);
      return emit($response, $data);
    }
    
    public function EmailsPost($request, $response, $args)
    {
      $unix = strtotime($args['date']);
      $chapel = new \SMT\Tools\Watch\Chapel($this->ada, $this->adaModules, $this->isams, $this->mcCustom, $this->exgarde);
      $data = $chapel->sendAllHouseEmails($unix);
      return emit($response, $data);
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
