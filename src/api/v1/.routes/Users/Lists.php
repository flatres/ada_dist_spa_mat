<?php

/**
 * Description

 * Usage:

 */
namespace Users;

class Lists
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->mysql;

    }

    public function names_GET($request, $response, $args)
    {
      $users = $this->sql->select(
        'usr_details',
        'id, firstname, lastname',
        'disabled=0 ORDER BY lastname ASC',
        array());

      foreach($users as &$user) {
        $user['displayName'] = $user['lastname'] . ', ' . $user['firstname'];
      }

      return emit($response, $users);
    }

    // public function tags_GET($request, $response, $args)
    // {
    //   $students = $this->sql->select(
    //     'stu_details',
    //     'id',
    //     'disabled=0 ORDER BY lastname ASC',
    //     array());
    //   $data = array();
    //   foreach($students as &$student)
    //   {
    //     $tagReader = new \Entities\TagsTagReader($this->sql);
    //     $student['tags'] = $tagReader->studentTags($student['id']);
    //   }
    //   return emit($response, $students);
    // }
}
