<?php

/**
 * Description

 * Usage:

 */
namespace Portal;

class User
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------
    public function byCodeGet($request, $response, $args)
    {
      $userCode = $args['userCode'];
      $user = new \Entities\People\iSamsUser($this->isams);
      $user->byUserCode($userCode);
      $contact = (new \Entities\People\iSamsStudentContact($this->isams))->byEmail($user->email);
      $contact->id = $user->id;
      
      return emit($response, $contact);
    }

}
