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
    'IGDR'  => []
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
     $this->GCSE = new \Entities\Exams\External\GCSE($this->adaData);
     $this->ALevelMock = new \Entities\Exams\Internal\ALevelMock($this->adaData);

     if ($this->students) $this->make();
     return $this;
  }

  public function make()
  {
    foreach($this->students as &$s){
      $this->getGCSEMock($s);
      $this->getGCSE($s);
      $this->getALevelMock($s);
    }
    $this->makeCohortRanking();
    $this->makeInbandGCSEDeltaRank();
    $this->getWeightings();
    $this->moveDataToStudents();
    return $this->metrics;
  }

  private function getGCSE(&$s)
  {
    // echo $s->id . "-" . $this->examId . PHP_EOL;
    $examId = $this->year > 11 ? $this->exam->getGcseExamId($this->examId) : $this->examId;
    $gcse = $this->GCSE->fetchStudentByExam($s->id, $examId);
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
  }

  //computes the difference between gcse actual and gcse mock and ranks these within each ALevel Mock Result Band
  private function makeInbandGCSEDeltaRank() {
    $gcse = new \Exams\Tools\GCSE\Result();
    $mockBands = [];
    foreach($this->students as &$s) {

      $gcseMockPoints = $gcse->processGrade($s->gcseMockGrade);
      $gcsePoints = $gcse->processGrade($s->gcseGrade);
      if (!$gcseMockPoints || !$gcsePoints) continue;

      $gcseDelta = $gcsePoints - $gcseMockPoints;
      $s->gcseDelta = $gcseDelta;

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

  private function moveDataToStudents(){
    //put back into students
    $gcseMock = [];
    $alevelMock = [];
    $igdr = [];

    foreach($this->metrics['GCSEMock'] as $g) $gcseMock['_' . $g['studentId']] = $g['cohortRank'];
    foreach($this->metrics['ALevelMock'] as $g) $alevelMock['_' . $g['studentId']] = $g['cohortRank'];
    foreach($this->metrics['IGDR'] as $g) $igdr['_' . $g['studentId']] = $g['IGDR'];

    foreach($this->students as &$s){
      $key = '_' . $s->id;
      $s->gcseMockCohortRank = $gcseMock[$key] ?? null;
      $s->aLevelMockCohortRank = $alevelMock[$key] ?? null;
      $s->igdr = $igdr[$key] ?? null;
    }
  }

}
