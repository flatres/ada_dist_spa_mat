<?php

/**
 * Description

 * Usage:

 */
namespace Users;

class User
{
    protected $container;

    public function __construct(\Slim\Container $container = null)
    {
       $this->sql= $container->ada ?? new \Dependency\Databases\Ada();
       $this->isams = $container->isams;

    }

    public function displayName($id)
    {
      $s = $this->sql->select(
        'usr_details',
        'id, firstname, lastname',
        'id=?',
        array($id));

      return $s[0]['lastname'] . ', ' . $s[0]['firstname'] ?? '';
    }

    public function details_GET($request, $response, $args)
    {
      $user = new \Entities\People\User($this->sql);
      $user->byId($args['id']);
      return emit($response, $user);
    }
    
    
    
}
