<?php

/**
 * Description

 * Usage:

 */
namespace Home;

class Absences
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
       $this->mcCustom = $container->mcCustom;
    }

// ROUTE -----------------------------------------------------------------------------
    public function allAbsencesGet($request, $response, $args)
    {
      $data = $this->fetchAbsences();
      foreach($data['absences'] as &$a) {
        unset($a['sets']);
      }
      return emit($response, $data);
    }

    public function subjectAbsencesGet($request, $response, $args)
    {
      $data = $this->fetchAbsences();
      $absences = [];
      foreach($data['absences'] as &$a) {
        foreach($a['sets'] as $s) {
          $set = [
            'setCode'     => $s->setCode,
            'subjectName' => $s->subjectName
          ];
          $newAbsence = array_merge($a, $set);
          unset($newAbsence['sets']);
          $absences[] = $newAbsence;
        }
        unset($a['sets']);
      }
      $data['absences'] = $absences;
      return emit($response, $data);
    }

    private function fetchAbsences()
    {
      $sql = $this->isams;
      $date = new \DateTime();
      $now = $date->format('Y-m-d H:i:s');

      $subjects = [];
      $sets = [];

      $absencesRaw = (new \Entities\Misc\Absences())->getMisc()->misc;
      $count = count($absencesRaw);
      $i = 1;
      $absences = [];
      foreach($absencesRaw as $a){
        if (!$a['studentId']) continue;
        $start = new \DateTime($a['start']);
        $a['start'] = $start->format('d-m-Y');
        $finish = new \DateTime($a['finish']);
        $a['finish'] = $finish  ->format('d-m-Y');
        // $submittedAt = new \DateTime($a['submittedAt']);
        // $a['submittedAt'] = $submittedAt->format('d-m-Y');
        $student = new \Entities\People\iSamsStudent($this->isams, $a['studentId']);
        $student->getSets();
        // $a = array_merge($a, (array)$student);
        $a['firstName'] = $student->firstName;
        $a['lastName'] = $student->lastName;
        $a['fullName'] = $student->fullName;
        $a['displayName'] = $student->displayName;
        $a['boardingHouse'] = $student->boardingHouse;
        $a['year'] = $student->year;
        $a['sets'] = (array)$student->sets;
        $a['timestamp'] = $start->getTimestamp();

        foreach($a['sets'] as $s) {
          $subject = $s->subjectName;
          $set = $s->setCode;
          $subjects[$subject] = $subject;
          $sets[$set] = $set;
        }
        $absences[] = $a;

      }

      ksort($subjects);
      ksort($sets);
      $data = [
        'absences'  => $absences,
        'sets'      => $sets,
        'subjects'  => $subjects
      ];
      return $data;
    }

}
