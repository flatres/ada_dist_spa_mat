<?php

/**
 * Description

 * Usage:

 */
namespace NAME;

class CLASS
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------
    public function ROUTEGet($request, $response, $args)
    {
      return emit($response, $this->adaModules->select('TABLE', '*'));
    }

    public function ROUTEPost($request, $response)
    {
      $data = $request->getParsedBody();
      $data['id'] = $this->adaModules->insertObject('TABLE', $data);
      return emit($response, $data);
    }

    public function ROUTELocationsPut($request, $response)
    {
      $data = $request->getParsedBody();
      return emit($response, $this->adaModules->updateObject('TABLE', $data, 'id'));
    }

    public function ROUTEDelete($request, $response, $args)
    {
      return emit($response, $this->adaModules->delete('TABLE', 'id=?', array($args['id'])));
    }

}
