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
        $this->container = $container;
       $this->sql= $container->mysql;

    }

    public function studentsGet($request, $response, $args) {
      $students = $this->sql->select(
        'stu_details',
        'id, firstname, lastname',
        'disabled=0 ORDER BY lastname ASC',
        []);

      $data = [];
      foreach($students as $s) $data[] = new \Entities\People\Student($this->sql, $s['id']);
      return emit($response, $data);
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

    public function portalNames_GET($request, $response, $args)
    {
      $this->isams = $this->container->isams;
      $students = $this->sql->select(
        'stu_details',
        'id, firstname, lastname',
        'disabled=0 ORDER BY lastname ASC',
        array());

      $portalStudents = [];
      foreach($students as &$student) {
        $student['displayName'] = $student['lastname'] . ', ' . $student['firstname'];
        $s = new \Entities\People\iSamsStudent($this->isams);
        $s->byAdaId($student['id']);
        $s->getContacts();
        if (count($s->portalUserCodes) > 0 ) {
          $student['portalCode'] = $s->portalUserCodes[0];
          $portalStudents[] = $student;
        }
      }

      return emit($response, $portalStudents);
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
        $tagReader = new \Entities\TagsTagReader($this->sql);
        $student['tags'] = $tagReader->studentTags($student['id']);
      }
      return emit($response, $students);
    }
}
