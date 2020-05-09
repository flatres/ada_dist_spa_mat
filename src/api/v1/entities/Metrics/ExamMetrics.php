<?php

namespace Entities\Metrics;

class ExamMetrics
{
  public $students, $examCode;
  public $examId, $exam, $year;
  public $metrics = [
    'GCSEMock'  => [],
    'GCSE' => [],
    'ALevelMock'  => [],
    'IGDR'  => [],
    'Midyis'  => [],
    'Alis'  => []
  ];
  public $weightings = [];
  public $metricsList = [];
  private $GCSEMock;

  public function __construct($examId, &$students, $year)
  {
     $this->ada = new \Dependency\Databases\Ada();
     $this->adaData= new \Dependency\Databases\AdaData();
     $this->students = &$students;
     $this->exam  = new \Entities\Academic\SubjectExam($this->ada, $examId);
     $this->examId = $examId;
     $this->year = $year;
     $this->GCSEMock = new \Entities\Exams\Internal\GCSEMock($this->adaData);
     $this->GCSE = new \Entities\Exams\External\GCSE($this->adaData, $this->ada);
     $this->ALevelMock = new \Entities\Exams\Internal\ALevelMock($this->adaData);

     //get exam code
     $this->examCode = $this->ada->select('sch_subjects_exams', 'examCode', 'id=?', [$examId])[0]['examCode'] ?? null;

     if ($this->students) $this->make();
     return $this;
  }

  public function make()
  {
    foreach($this->students as &$s){
      $this->getGCSEMock($s);
      $this->getGCSE($s);
      $this->getALevelMock($s);
      $this->getAlis($s);
      $this->getMidyis($s);

    }
    $this->makeCohortRanking();
    $this->makeInbandGCSEDeltaRank();
    $this->getWeightings();
    $this->moveDataToStudents();
    return $this->metrics;
  }

  private function getAlis(&$s)
  {
    $alis = new \Entities\Metrics\Alis($s->id, $this->examId);
    $s->alisTestBaseline = $alis->testBaseline;
    $s->alisTestPrediction = $alis->testPrediction;

    $s->alisGcseBaseline = $alis->gcseBaseline;
    $s->alisGcsePrediction = $alis->gcsePrediction;


    if ($alis->gcseBaseline) {
      $this->metrics['Alis'][] = [
        'studentId' => $s->id,
        'testBaseline' => $alis->testBaseline,
        'testPrediction' => $alis->testPrediction,
        'gcseBaseline' => $alis->gcseBaseline,
        'gcsePrediction' => $alis->gcsePrediction
      ];
    }
  }

  private function getMidyis(&$s)
  {
    $midyis = new \Entities\Metrics\Midyis($s->id, $this->examId);
    $s->midyisBaseline = $midyis->baseline;
    $s->midyisBand = $midyis->band;
    $s->midyisPrediction = $midyis->prediction;
    if ($midyis->baseline) {
      $this->metrics['Midyis'][] = [
        'studentId' => $s->id,
        'band'  => $midyis->band,
        'baseline' => $midyis->baseline,
        'prediction'  => $midyis->prediction
      ];
    }
  }

  private function getGCSE(&$s)
  {
    // echo $s->id . "-" . $this->examId . PHP_EOL;
    $examId = $this->year > 11 ? $this->exam->getGcseExamId($this->examId) : $this->examId;
    $gcse = $this->GCSE->fetchStudentByExam($s->id, $examId, $this->examCode);
    $s->gcseGrade = null;
    if($gcse){
      $s->gcseGrade = $gcse->result;
      $this->metrics['GCSE'][] = [
        'studentId' => $s->id,
        'grade' => $gcse->result
      ];
    }
  }

  private function getGCSEMock(&$s)
  {
    // echo $s->id . "-" . $this->examId . PHP_EOL;
    $examId = $this->year > 11 ? $this->exam->getGcseExamId($this->examId) : $this->examId;
    $gcseMock = $this->GCSEMock->fetchStudentByExam($s->id, $examId);
    $s->gcseMockGrade = null;
    if($gcseMock) {
      $s->gcseMockGrade = $gcseMock->grade;
      $s->gcseMockPercentage = $gcseMock->percentage;
      $this->metrics['GCSEMock'][] = [
        'studentId' => $s->id,
        'mark'  => $gcseMock->mark,
        'grade' => $gcseMock->grade,
        'percentage'  => $gcseMock->percentage,
        'yearRank'  =>  $gcseMock->rank
      ];
    }
  }

  private function getAlevelMock(&$s)
  {
    // echo $s->id . "-" . $this->examId . PHP_EOL;
    $aLevelMock = $this->ALevelMock->fetchStudentByExam($s->id, $this->examId);
    $s->aLevelMockGrade = null;
    if($aLevelMock) {
      $s->aLevelMockGrade = $aLevelMock->grade;
      $s->aLevelMockPercentage = $aLevelMock->percentage;
      $this->metrics['ALevelMock'][] = [
        'studentId' => $s->id,
        'mark'  => $aLevelMock->mark,
        'grade' => $aLevelMock->grade,
        'percentage'  => $aLevelMock->percentage,
        'yearRank'  =>  $aLevelMock->rank
      ];
    }
  }

  private function makeCohortRanking(){
      $this->metrics['GCSEMock'] = rankArray($this->metrics['GCSEMock'], 'mark', 'cohortRank');
      $this->metrics['ALevelMock'] = rankArray($this->metrics['ALevelMock'], 'mark', 'cohortRank');
      $this->metrics['Midyis'] = rankArray($this->metrics['Midyis'], 'baseline', 'cohortRank');
      $this->metrics['Alis'] = rankArray($this->metrics['Alis'], 'testPrediction', 'cohortRank');
  }

  //computes the difference between gcse actual and gcse mock and ranks these within each ALevel Mock Result Band
  private function makeInbandGCSEDeltaRank() {
    $gcse = new \Exams\Tools\GCSE\Result();
    $mockBands = [];
    foreach($this->students as &$s) {

      $metrics = new \Entities\Metrics\Student($s->id);

      $gcseMockPoints = $metrics->gcseMockGpa();
      $s->gcseMockGpa = $gcseMockPoints;
      // $gcseMockPoints = $gcse->processGrade($s->gcseMockGrade);

      // $gcsePoints = $gcse->processGrade($s->gcseGrade);
      $gcsePoints = $metrics->gcseGpa();
      $s->gcseGpa = $gcsePoints;
      if (!$gcseMockPoints || !$gcsePoints) continue;

      $gcseDelta = $gcsePoints - $gcseMockPoints;
      $s->gcseDelta = round($gcseDelta,1);

      $aLevelMockGrade = $s->aLevelMockGrade;
      if (!$aLevelMockGrade) continue;

      $key = "_" . $aLevelMockGrade;
      if (!isset($mockBands[$key])) $mockBanks[$key] = [];
      $mockBands[$key][] = $s;

    }
    //do  the ranking
    foreach($mockBands as $mb){
      $students = [];
      foreach($mb as $student) {
        $students[] = [
          'studentId' => $student->id,
          'gcseDelta' => $student->gcseDelta
        ];
      }
      $students = rankArray($students, 'gcseDelta', 'IGDR');
      $this->metrics['IGDR'] = array_merge($this->metrics['IGDR'], $students);
    }
  }

  private function getWeightings() {
    foreach($this->metrics as $key => $m){
      $this->weightings[$key] = 1;
    }
  }

  //for easuer front end use
  private function moveDataToStudents(){
    //put back into students
    $gcseMock = [];
    $gcseMockMark = [];
    $alevelMock = [];
    $igdr = [];
    $alis = [];
    $midyis = [];

    foreach($this->metrics['GCSEMock'] as $g) {
      $gcseMock['_' . $g['studentId']] = $g['cohortRank'];
      $gcseMockMark['_' . $g['studentId']] = $g['mark'];
    }
    foreach($this->metrics['ALevelMock'] as $g) $alevelMock['_' . $g['studentId']] = $g['cohortRank'];
    foreach($this->metrics['IGDR'] as $g) $igdr['_' . $g['studentId']] = $g['IGDR'];

    foreach($this->metrics['Alis'] as $g) $alis['_' . $g['studentId']] = $g['cohortRank'];
    foreach($this->metrics['Midyis'] as $g) $midyis['_' . $g['studentId']] = $g['cohortRank'];

    foreach($this->students as &$s){
      $key = '_' . $s->id;
      $s->gcseMockMark = $gcseMockMark[$key] ?? null;
      $s->gcseMockCohortRank = $gcseMock[$key] ?? null;
      $s->aLevelMockCohortRank = $alevelMock[$key] ?? null;
      $s->igdr = $igdr[$key] ?? null;
      $s->alisCohortRank = $alis[$key] ?? null;
      $s->midyisCohortRank = $midyis[$key] ?? null;
    }
  }

}
