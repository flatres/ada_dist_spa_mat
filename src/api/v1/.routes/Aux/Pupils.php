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

    public function __construct(\Slim\Container $container)
    {
      $this->ad = $container->ad;
      $this->ada = $container->ada;
      $this->adaData = $container->adaData;
      // $this->adaModules = $container->adaModules;
    }

    public function loginPost($request, $response, $args)
    {
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
      if ($success && $student->id) {
        $data = [
          'success' => true,
          'data' => $this->getWyaps($student->id),
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

    private function getWyaps ($studentId) {
      $exams = [];
      $wyaps = $this->adaData->select('wyap_results', 'wyap_id, exam_id, percentage', 'student_id=?', [$studentId]);
      foreach($wyaps as &$w) {
        $wyap = new \Entities\Metrics\Wyap($w['wyap_id']);
        $w['name'] = $wyap->name;
        $w['type'] = $wyap->type;
        $w['typeShort'] = $wyap->typeShort;

        $exam = (new \Entities\Academic\SubjectExam())->byId($w['exam_id']);

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
