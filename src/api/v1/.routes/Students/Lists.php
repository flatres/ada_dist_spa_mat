<?php

/**
 * Description

 * Usage:

 */
namespace Students;

class Lists
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->sql= $container->mysql;

    }

    public function names_GET($request, $response, $args)
    {
      $students = $this->sql->select(
        'stu_details',
        'id, firstname, lastname',
        'disabled=0 ORDER BY lastname ASC',
        array());

      foreach($students as &$student) {
        $student['displayName'] = $student['lastname'] . ', ' . $student['firstname'];
      }

      return emit($response, $students);
    }

    public function tags_GET($request, $response, $args)
    {
      $students = $this->sql->select(
        'stu_details',
        'id',
        'disabled=0 ORDER BY lastname ASC',
        array());
      $data = array();
      foreach($students as &$student)
      {
        $tagReader = new \Entities\Tags\ToolsTagReader($this->sql);
        $student['tags'] = $tagReader->studentTags($student['id']);
      }
      return emit($response, $students);
    }
}
