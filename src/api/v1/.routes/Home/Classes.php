<?php

/**
 * Description

 * Usage:

 */
namespace Home;

class Classes
{
    protected $container;
    public $teacher;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
       $this->mcCustom = $container->mcCustom;
       global $userId;
       $this->teacher = (new \Entities\People\iSamsTeacher($this->isams))->byAdaId($userId);
    }

// ROUTE -----------------------------------------------------------------------------
    public function classesGet($request, $response, $args)
    {
      $this->teacher->getSets();
      return emit($response, $this->teacher->sets);
    }

    public function setMLOGet($request, $response, $args)
    {
      $id = $args['id'];
      global $userId;
      $class = new \Entities\Academic\iSamsSet($this->isams, $id);
      $class->getStudents();
      $MLO = new \Entities\Exams\MLO($this->ada);
      foreach($class->students as &$student) {
        $student->mlo = $MLO->getSingleMLO($student->id, $class->subjectCode, $userId);
      }
      return emit($response, $class);
    }

    public function formMLOGet($request, $response, $args)
    {
      $id = $args['id'];
      $class = new \Entities\Academic\iSamsForm($this->isams, $id);
      $class->getStudents();
      foreach($class->students as &$student) {
        $student->mlo = null;
      }
      return emit($response, $class);
    }

    public function MLOPost($request, $response)
    {
      global $userId;
      $class = $request->getParsedBody();
      $students = $class['students'];
      foreach($students as $student) {
        $MLO = new \Entities\Exams\MLO($this->ada);
        $MLO->newMLO($student['id'], $student['mlo'], $class['subjectCode'], $userId);
      }

      return emit($response, $class);
    }


}
