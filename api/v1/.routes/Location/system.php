<?php

/**
 * Description

 * Usage:

 */
namespace Location;

class System
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       // $this->ada = $container->ada;
       // $this->adaModules = $container->adaModules;
       // $this->msSql = $container->msSql;
       $this->exgarde = $container->exgarde;
    }

// ROUTE -----------------------------------------------------------------------------
    public function AreasGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getAreas());
    }

    public function AreaGet($request, $response, $args)
    {
      return emit($response, $this->exgarde->getArea($args['id']));
    }
    //
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
