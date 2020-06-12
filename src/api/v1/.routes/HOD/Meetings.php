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

    public function meetingClassesDownloadGet($request, $response, $args)
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
          $booking = [
            'firstName' => $student->firstName,
            'lastName'  => $student->lastName,
            'code'      => $c->code,
            'beak'      => (new \Entities\People\User($this->ada, $student->meetingBeakId))->login ?? ''
          ];
          $data[] = $booking;
        }
      }

      $settings = [
        'title' => 'L6 Parents Meeting',
        'sheetTitle' => 'Appointments',
        'filename'  => 'L6 Parents Meeting',
        'timestamp' => true
      ];
      $columns = [
        ['field' => 'firstName', 'label' => 'First Name'],
        ['field' => 'lastName', 'label' => 'Last Name'],
        ['field' => 'code', 'label' => 'Class'],
        ['field' => 'beak', 'label' => 'Beak'],
      ];
      $package = (new \Utilities\Spreadsheet\SingleSheet($columns, $data, $settings))->package;

      return emit($response, $package);
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
