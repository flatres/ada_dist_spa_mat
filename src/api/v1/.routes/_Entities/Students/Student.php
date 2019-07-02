<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Students;

class Student
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->ada;

    }

    public function displayName($id)
    {
      $s = $this->sql->select(
        'stu_details',
        'id, firstname, lastname',
        'id=?',
        array($id));

      return $s[0]['lastname'] . ', ' . $s[0]['firstname'] ?? '';      
    }

    public function details_GET($request, $response, $args)
    {
      $student = $this->sql->select(
        'stu_details',
        'id, firstname, lastname, email, boardingHouse, gender',
        'id=?',
        array($args['id']));

      return emit($response, $student[0]);
    }
}
