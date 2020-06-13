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
       $this->isams = $container->isams;
       $this->adaModules = $container->adaModules;
    }

// ROUTE ----------------------------------------------------------------------------

    public function meetingsAllGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'academic.meetings', '');
      $this->progress->publish(0);

      $year = $args['year'];

      $data = [];
      $students = $this->ada->select('stu_details', 'id', 'NCYear=?', [$year]);
      $i = 1;
      $maxApt = 0;
      foreach($students as $s) {
        $this->progress->publish($i / count($students));
        $i++;
        // if ($i == 10) break;
        $id = $s['id'];
        $student = new \Entities\People\Student($this->ada, $id);
        $student->contacts = (new \Entities\People\iSamsStudent($this->isams, $student->misId))->getContacts();

        //get meetings
        $meetings = $this->adaModules->select('hod_parent_meeting_appointments', 'subjectId, userId', 'studentId=?', [$student->id]);
        if (count($meetings) > $maxApt) $maxApt = count($meetings);

        for ($sc = 1; $sc < 6; $sc++) {
          $student->{"subject$sc"} = null;
          $student->{"beak$sc"} = null;
        }

        $meetingCount = 1;
        foreach($meetings as $m) {
          $u = new \Entities\People\User($this->ada, $m['userId']);
          $m['beakName'] = "{$u->title} {$u->login[1]} {$u->lastName}";
          $sub = new \Entities\Academic\Subject($this->ada, $m['subjectId']);
          $m['subject'] = $sub->name;
          $student->{"subject$meetingCount"} = $sub->name;
          $student->{"beak$meetingCount"} = $m['beakName'];
          $meetingCount++;
        }
        $student->meetings = $meetings;
        $data[] = $student;
      }

      $r = [
        'maxAppointments' => $maxApt,
        'appointments'  => $data
      ];

      return emit($response, $r);
    }

    public function meetingsSchoolsCloudGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'academic.meetings', '');
      $this->progress->publish(0);
      $data = [];
      $appointments = $this->adaModules->select('hod_parent_meeting_appointments', 'userId, classId, studentId', 'id>?', [0]);
      $i = 0;
      foreach($appointments as $a) {
        $this->progress->publish($i / count($appointments));
        $i++;
        // if ($i==10) break;
        $student = new \Entities\People\Student($this->ada, $a['studentId']);
        $class = new \Entities\Academic\AdaClass($this->ada, $a['classId']);
        $subject = new \Entities\Academic\Subject($this->ada, $class->subjectId);
        $user = new \Entities\People\User($this->ada, $a['userId']);
        $data[] = [
          'firstName' => $user->preName,
          'lastName'  => $user->lastName,
          'subject'   => $subject->name,
          'code'      => $class->code,
          'misId'     => $class->misId,
          'studentFirstName'  => $student->preName,
          'studentLastName'   => $student->lastName,
          'registration'      => '',
          'dob'               => $student->dob
        ];
      }

      return emit($response, $data);
    }

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

    public function meetingsCountsPost($request, $response, $args)
    {
      $subjects = $request->getParsedBody();

      foreach($subjects as &$s){
        $d = $this->adaModules->select('hod_parent_meeting_appointments', 'id', 'subjectId=?', [$s['id']]);
        $s['count'] = count($d);

      }

      return emit($response, $subjects);
    }



}
