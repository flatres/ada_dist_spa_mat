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
       $this->adaData = $container->adaData;
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

    public function MLOGet($request, $response, $args)
    {
      $examId = $args['examId'];
      $classId = $args['classId'];
      global $userId;
      $class = new \Entities\Academic\AdaClass($this->ada, $classId);
      // $class = new \Entities\Academic\iSamsSet($this->isams, $id);
      $class->getStudents();
      $class->examId = $examId;

      $MLO = new \Entities\Exams\MLO($this->adaData);
      $class->session = $MLO->getActiveSession($class->year);
      foreach($class->students as &$student) {
        $mlo = $MLO->getSingleMLO($student->id, $examId, $userId);
        $student->mlo = $mlo;
        $student->mloCurrent = $mlo['mlo_current'];
        $student->mloPotential = $mlo['mlo_potential'];
      }
      return emit($response, $class);
    }

    public function MLOPost($request, $response)
    {
      global $userId;
      $class = $request->getParsedBody();
      $students = $class['students'];
      $session = $class['session'];
      foreach($students as $student) {
        $MLO = new \Entities\Exams\MLO($this->adaData);
        $MLO->newMLO($session['id'], $userId, $student['id'], $class['examId'], $student['mloCurrent'], $student['mloPotential']);
      }

      return emit($response, $class);
    }


}
