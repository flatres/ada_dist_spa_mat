<?php

/**
 * Description

 * Usage:

 */
namespace SMT;

class Privs
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->isams = $container->isams;
       $this->mcCustom = $container->mcCustom;
    }

// ROUTE -----------------------------------------------------------------------------
    public function privsGet($request, $response, $args)
    {
      $unix = strtotime($args['date']);
      $privs = new \SMT\Tools\Watch\Privs($this->ada, $this->isams, $this->mcCustom);
      $privs->byDate($unix);
      return emit($response, $privs);
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
