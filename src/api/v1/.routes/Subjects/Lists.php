<?php

/**
 * Description

 * Usage:

 */
namespace Subjects;

class Lists
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->ada;

    }

    public function names_GET($request, $response, $args)
    {
      $subjects = $this->sql->select(
        'sch_subjects',
        'id, name, code',
        '1=1 ORDER BY name ASC',
        []);

      return emit($response, $subjects);
    }

}
