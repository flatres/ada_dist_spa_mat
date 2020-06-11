<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Meetings
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE ----------------------------------------------------------------------------

    public function meetingClassesGet($request, $response, $args)
    {
      $year = $args['year'];
      $subjectId = $args['subject'];
      $examId = $args['exam'];
      $subject = new \Entities\Academic\Subject($this->ada, $subjectId);
      $classes = $subject->getClassesByExam($year, $examId);
      $data = [];

      foreach($classes as $c) {
        $class = new \Entities\Academic\AdaClass($this->ada, $c->id);
        foreach($class->students as &$student) {
          $student->meetingBeakId = $this->adaModules->select(
            'hod_parent_meeting_appointments',
            'userId',
            'subjectId=? AND examId=? AND studentId=? AND classId=?',
            [$subjectId, $examId, $student->id, $c->id]
            )[0]['userId'] ?? null;
        }
        $data[] = $class;
      }

      return emit($response, $data);
    }

    public function meetingPost($request, $response, $args)
    {
      $subjectId = $args['subject'];
      $examId = $args['exam'];
      $studentId = $args['studentId'];
      $userId = $args['userId'];
      $classId = $args['classId'];

      $this->adaModules->delete('hod_parent_meeting_appointments', 'subjectId=? AND examId=? AND studentId=? AND classId=?', [$subjectId, $examId, $studentId, $classId]);
      $this->adaModules->insert('hod_parent_meeting_appointments', 'subjectId, examId, studentId, userId, classId', [$subjectId, $examId, $studentId, $userId, $classId]);

      return emit($response, true);
    }



}
