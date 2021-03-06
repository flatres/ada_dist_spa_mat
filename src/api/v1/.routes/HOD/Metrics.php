<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Metrics
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------
    public function yearGet($request, $response, $args)
    {
      $subject = new \Entities\Academic\Subject($this->ada);
      $subject->byId($args['subject'])->getStudentsByYear($args['year']);
      return emit($response, $subject);
    }

    public function classGet($request, $response, $args)
    {
      $adaClass = new \Entities\Academic\AdaClass($this->ada, $args['class']);
      $adaClass->getStudentsMLO();
      return emit($response, $adaClass);
    }

    public function yearMLOGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.mlo', '');
      $this->progress->publish(0.25);

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $subject = new \Entities\Academic\Subject($this->ada);
      $this->progress->publish(0.5);
      $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);
      $this->progress->publish(0.75);
      $subject->makeMLOProfile();
      $subject->classes = null;
      $this->progress->publish(1);
      // $subject->getExamData();
      // $subject->getSets($args['year']);
      return emit($response, $subject);
    }

    public function yearMetricsGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Thinking... ');
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];

      $subject = $this->getYearMetrics($subjectId, $year, $examId);

      $subject->classes = null;

      return emit($response, $subject);
    }

    public function yearMetricsSpreadsheetGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Generating spreadsheet...');
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];

      $this->progress->publish(0.25, 'Gathering data...');
      $subject = $this->getYearMetrics($subjectId, $year, $examId);
      // $subject->makeMLOProfile();
      $subject->makeHistoryProfile($examId, $year);

      // get wyaps, including results
      $wyaps = (new \Entities\Academic\Subject($this->ada, $subjectId))->getWYAPsByExam($year, $examId);

      foreach($wyaps as &$w) $w->results();
      unset($w);

      $this->progress->publish(0.5, 'Generating spreadsheet...');
      foreach($subject->students as &$s) $s->getHMNote();

      $subject->examId = $examId;
      $subject->year = $year;

      $sheet = new \HOD\ExamMetricsSpreadsheet($subject, $wyaps);

      $subject->classes = null;

      $package = $sheet->package;
      $package['subject'] = $subject;
      $package['wyaps'] = $wyaps;
      // return emit($response, $subject);
      return emit($response, $package);
    }

    public function pdfByClassGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Gathering data...');

      $package = $this->makePDFData($args, true);
      return emit($response, $package);
    }

    public function pdfByNameGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Gathering data...');

      $package = $this->makePDFData($args, false);
      return emit($response, $package);
    }

    public function pdfScoresGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Gathering data...');

      $package = $this->makePDFData($args, false, false, true);
      return emit($response, $package);
    }

    public function pdfBlankGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.metrics', 'Gathering data...');

      $package = $this->makePDFData($args, false, true);
      return emit($response, $package);
    }

    private function makePDFData ($args, $byClass = false, $blank = false, $includeScores = false) {
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];

      $this->progress->publish(0.25, 'Gathering data...');
      $subject = $this->getYearMetrics($subjectId, $year, $examId);
      // $subject->makeMLOProfile();
      $subject->makeHistoryProfile($examId, $year);

      // get wyaps, including results
      $wyaps = (new \Entities\Academic\Subject($this->ada, $subjectId))->getWYAPsByExam($year, $examId);

      foreach($wyaps as &$w) $w->results();
      unset($w);

      $this->progress->publish(0.5, 'Generating pdf...');
      foreach($subject->students as &$s) $s->getHMNote();

      sortObjects($subject->students, 'lastName', 'ASC');
      if ($byClass == true && $blank == false) {
        foreach($subject->students as &$s) $s->cc = \strtoupper($s->classCode . $s->lastName);
        sortObjects($subject->students, 'cc', 'ASC');
      }
      if ($blank == true) {
        $student = [
          'displayName' => 'Name:                                                          ',
          'classCode' => 'Class: ...............................',
          'schoolNumber' => 'Sch. #:                   '
        ];
        $subject->students = [];
        $subject->students[] = (object)$student;
      }

      $subject->examId = $examId;
      $subject->year = $year;

      $pdf = new \HOD\Tools\WyapPDF($subject, $wyaps, $includeScores);

      $package = [];
      $package = $pdf->package;
      $package['subject'] = $subject;
      $package['wyaps'] = $wyaps;
      return $package;
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

    public function yearHistoryGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.metrics.history');
      $this->progress->publish(0.25);

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $subject = new \Entities\Academic\Subject($this->ada);
      $subject->byId($subjectId)->getStudentsMLOByExam($year, $examId);
      $this->progress->publish(0.5);
      $subject->makeMLOProfile();
      $this->progress->publish(0.75);
      $subject->makeHistoryProfile($examId, $year);
      // $subject->getExamData();
      // $subject->getSets($args['year']);
      $subject->classes = null;
      $subject->students = null;
      $subject->examId = (int)$examId;
      $this->progress->publish(1);
      return emit($response, $subject);
    }



}
