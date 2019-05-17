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
       $this->sql= $container->mysql;

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
