<?php

/**
 * Description

 * Usage:

 */
namespace SMT;

class Covid
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
    }

// ROUTE -----------------------------------------------------------------------------
    public function staffGet($request, $response, $args)
    {
      $staff = (new \SMT\Tools\Covid\Staff())->getAll();
      return emit($response, $staff);
    }

    public function studentsGet($request, $response, $args)
    {
      $staff = (new \SMT\Tools\Covid\Students())->getAll();
      return emit($response, $staff);
    }

    public function studentEmailsPost($request, $response, $args)
    {
      $staff = (new \SMT\Tools\Covid\Students())->sendTodayEmails();
      return emit($response, $staff);
    }

    public function staffEmailsPost($request, $response, $args)
    {
      $staff = (new \SMT\Tools\Covid\Staff())->sendTodayEmails();
      return emit($response, ['jkj']);
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
