<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Science
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaData = $container->adaData;
       $this->adaModules = $container->adaModules;
    }

    public function tagsSpreadsheetGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Standby Gary...');
      $pg = $this->progress;

      $subjectId =1; //double science special case
      $year = 11;
      $examId = $this->ada->select('sch_subjects_exams', 'id', 'subjectId=?', [$subjectId])[0]['id'];

      $this->progress->publish(0.25, 'Gathering data...');
      $subject = $this->getYearMetrics($subjectId, $year, $examId);
      // $subject->makeMLOProfile();
      $subject->makeHistoryProfile($examId, $year);

      //get phys / chem / bio wyaps
      $exams = $this->ada->select('sch_subjects_exams', 'id, subjectId, aliasCode', 'examName=? AND subjectId <> ?', ['Double Science', $subjectId]);
      $wyaps = [];

      foreach($exams as $e) {
        $subId = $e['subjectId'];
        $eId = $e['id'];
        $newWyaps = (new \Entities\Academic\Subject($this->ada, $subId))->getWYAPsByExam($year, $eId);
        foreach($newWyaps as &$w) $w->name = $e['aliasCode'] . ': ' . $w->name;
        $wyaps = array_merge($wyaps, $newWyaps);
        $tempSubject = $this->getYearMetrics($subId, $year, $eId);
        $this->progress->publish(0.25, $e['aliasCode'] . " done");

      }
      // var_dump($tempSubject); exit();
      $subject->students = $tempSubject->students;
      $subject->wyaps = $wyaps;
      foreach($subject->wyaps as &$w) {
        // $w->results();
        (new \Entities\Metrics\WYAP($w->id))->results($subject->students);
      }
      unset($w);

      $this->progress->publish(0.5, 'Generating spreadsheet...');
      foreach($subject->students as &$s) $s->getHMNote();

      $subject->examId = $examId;
      $subject->year = $year;

      $sheet = new \HOD\ExamMetricsSpreadsheet($subject, $wyaps);
      //
      $subject->classes = null;
      //
      $package = $sheet->package;
      $package['subject'] = $subject;
      $package['wyaps'] = $wyaps;
      // return emit($response, $subject);
      return emit($response, $package);
    }

    private function getYearMetrics($subjectId, $year, $examId)
    {
      $this->progress->publish(0.1, 'Fetching pupils...');
      $subject = new \Entities\Academic\Subject($this->ada, $subjectId);
      $subject->getStudentsByExam($year, $examId);

      // $this->progress->publish(0.25);
      // $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);

      $this->progress->publish(0.5, 'Fetching baseline data...');
      // $subject->makeMLOProfile();

      $metrics = new \Entities\Metrics\ExamMetrics($examId, $subject->students, $year);
      $subject->metrics = $metrics->metrics;
      $subject->gcseGPA = $metrics->gcseAvg;
      $subject->metricWeightings = $metrics->weightings;

      $this->progress->publish(0.8, 'Fetching assessment points...');
      $wyaps = $subject->getWYAPsByExam($year, $examId);

      foreach($wyaps as &$w) {
        (new \Entities\Metrics\WYAP($w->id))->results($subject->students);
      }


      $subject->wyaps = $wyaps;
      $this->progress->publish(1);
      return $subject;
    }



}
