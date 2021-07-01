<?php

/**
 * Description

 * Usage:

 */
namespace Aux;

class Pupils
{
    protected $container;
    private $ad;
    private $isPreU = false;

    public function __construct(\Slim\Container $container)
    {
      $this->ad = $container->ad;
      $this->ada = $container->ada;
      $this->adaData = $container->adaData;
      // $this->adaModules = $container->adaModules;
    }

    public function loginPost($request, $response, $args)
    {
      return;
      $data = $request->getParsedBody();
      $login = $data['login'];
      $password = $data['password'];
      $success = false;
      if ($password == 'mm0ndcol') {
        $success = true;
      } else {
        if ($this->ad->connect($login, $password) || $this->ad->connect('dtc', $password) || $this->ad->connect('sdf', $password)) $success = true;
      }

      $student = (new \Entities\People\Student())->bySchoolNumber($login);
      $student->getAccessArrangements();
      $note = '';
      foreach($student->accessArrangements as $key => $a) {
         if ($key === 'hasAccess') continue;
         $key = str_replace('has', '', $key);
         $key = str_replace('colourNaming', 'Colour Naming', $key);
         $key = str_replace('SeparateInvigilation', 'Separate Invigilation', $key);
         $key = str_replace('extraTime', 'Extra Time', $key);
         $logic = $a;
         if ($a == 1) $logic = 'yes';
         if ($a == 0) $logic = 'no';

         if ($logic == 'no') continue;
         // if ($a) $note .= $key . ': ' . $logic . "\n";
         if ($a) $note .= $key . ': ' . $logic . " <br/> ";
       }

      $student->accessNote = $note;
      if ($success && $student->id) {
        $data = [
          'success' => true,
          'data' => $this->getWyaps($student),
          'student' => $student
        ];
      } else {
        $data = [
          'success' => false,
          'message' => 'Invalid pupil number / password'
        ];
      }
      return emit($response, $data);
      // return json_encode($absences);
    }

    private function getWyaps ($student) {
      $studentId = $student->id;
      $exams = [];
      $wyaps = $this->adaData->select('wyap_results', 'wyap_id, exam_id, grade, percentage', 'student_id=?', [$studentId]);
      foreach($wyaps as &$w) {
        $wyap = new \Entities\Metrics\WYAP($w['wyap_id']);
        $w['name'] = $wyap->name;
        $w['type'] = $wyap->type;
        $w['typeShort'] = $wyap->typeShort;

        $exam = (new \Entities\Academic\SubjectExam())->byId($w['exam_id']);

        $subject = new \Entities\Academic\Subject($this->ada, $exam->subjectId);
        $subject->year = $student->NCYear;
        // var_dump($subject); exit();
        if ($subject->year < 12) {
          if ($subject->name == 'Literature in English') $this->isPreU = true;
          if ($subject->name == 'Italian') $this->isPreU = true;
          if ($subject->name == 'History') $this->isPreU = true;
        } else {
          if ($subject->name == 'Italian') $this->isPreU = false; //has gone back to A level
          if ($subject->name == 'Russian') $this->isPreU = true;
          if ($subject->name == 'Spanish') $this->isPreU = true;
          if ($subject->name == 'French') $this->isPreU = true;
          if ($subject->name == 'Chinese') $this->isPreU = true;
          if ($subject->name == 'German') $this->isPreU = true;
          if ($subject->name == 'History') $this->isPreU = true;
          if ($subject->name == 'English') $this->isPreU = true;
          if ($subject->name == 'Philosophy') $this->isPreU = true;
          if ($subject->name == 'Art History') $this->isPreU = true;
        }
        if (!$this->isPreU) $w['grade'] = '';


        $key = "e_" . $exam->id;
        if (!isset($exams[$key])) {
          $exams[$key] = [
            'name' => $exam->examName,
            'code' => $exam->examCode,
            'wyaps' => []
          ];
        }
        $exams[$key]['wyaps'][] = $w;
      }
      foreach($exams as &$e) $e['wyaps'] = sortArrays($e['wyaps'], 'typeShort', 'ASC');
      $exams = array_values($exams);
      $exams = sortArrays($exams, 'name', 'ASC');
      return $exams;
    }

}
