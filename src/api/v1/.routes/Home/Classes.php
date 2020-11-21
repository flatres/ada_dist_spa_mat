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
       // $this->isams = $container->isams;
       // $this->mcCustom = $container->mcCustom;
       // global $userId;
       // $this->teacher = (new \Entities\People\iSamsTeacher($this->isams))->byAdaId($userId);
    }

// ROUTE -----------------------------------------------------------------------------
    public function classesGet($request, $response, $args)
    {
      global $userId;
      $teacher = new \Entities\People\User($this->ada, $userId);
      $classes = $teacher->getClasses();
      return emit($response, $teacher->classes);
      // $this->teacher->getSets();
      // return emit($response, $this->teacher->sets);
    }

    public function wyapsGet($request, $response, $args)
    {
        $classId = $args['classId'];
        $class = new \Entities\Academic\AdaClass($this->ada, $classId);
        $wyaps = [];
        $subject = new \Entities\Academic\Subject($this->ada, $class->subjectId);
        foreach($class->exams as $e) {
          $wyaps = array_merge($wyaps, $subject->getWYAPsByExam($class->year, $e->id));
        }
        // $wyaps = $class;
        return emit($response, $wyaps);
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
      global $userId;
      $isEnLit = false;
      if (strpos($id, '(LIT)') !== false) $isEnLit = true;
      $id = str_replace('(LIT)','', $id);
      $class = new \Entities\Academic\iSamsForm($this->isams, $id);
      if ($isEnLit) $class->subjectCode = 'ENLIT';

      $class->getStudents();
      $MLO = new \Entities\Exams\MLO($this->ada);
      foreach($class->students as &$student) {
        $student->mlo = $MLO->getSingleMLO($student->id, $class->subjectCode, $userId);
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
