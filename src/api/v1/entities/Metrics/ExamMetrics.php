<?php

namespace Entities\Metrics;

class ExamMetrics
{
  public $students, $examCode;
  public $examId, $exam, $year;
  public $gcseAvg;
  public $metrics = [
    'RemoveEOY' => [],
    'GCSEMock'  => [],
    'GCSE' => [],
    'AsLevel' => [],
    'L6EOY' => [],
    'ALevelMock'  => [],
    'IGDR'  => [],
    'Midyis'  => [],
    'Alis'  => []
  ];
  public $weightings = [];
  public $metricsList = [];
  private $GCSEMock, $GCSE, $RemoveEOY, $L6EOY;

  public function __construct($examId, &$students, $year)
  {
     $this->ada = new \Dependency\Databases\Ada();
     $this->adaData= new \Dependency\Databases\AdaData();
     $this->students = &$students;
     $this->exam  = new \Entities\Academic\SubjectExam($this->ada, $examId);
     $this->examId = $examId;
     $this->year = $year;
     $this->GCSEMock = new \Entities\Exams\Internal\GCSEMock($this->adaData);
     $this->RemoveEOY = new \Entities\Exams\Internal\RemoveEOY($this->adaData);
     $this->L6EOY = new \Entities\Exams\Internal\L6EOY($this->adaData);
     $this->GCSE = new \Entities\Exams\External\GCSE($this->adaData, $this->ada);
     $this->AsLevel = new \Entities\Exams\External\AsLevel($this->adaData, $this->ada);
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
      $this->getRemoveEOY($s);
      $this->getL6EOY($s);
      $this->getGCSE($s);
      $this->getAsLevel($s);
      $this->getALevelMock($s);
      $this->getAlis($s);
      $this->getMidyis($s);

    }
    $this->makeCohortRanking();
    $this->makeInbandGCSEDeltaRank();
    $this->getWeightings();
    $this->moveDataToStudents();
    $this->makeGCSEAvg();
    return $this->metrics;
  }

  // calculates the average gcse gpa for this cohort
  // will return nothing if a lower school set
  private function makeGCSEAvg() {
      $avg = 0;
      $count = 0;
      foreach($this->students as $s) {
        $d = $this->adaData->select('exams_gcse_avg', 'gcseAvg', 'misId=?', [$s->misId]);
        if (isset($d[0])) {
          $avg += $d[0]['gcseAvg'];
          $count++;
        }
      }
      if ($count > 0) {$avg = round($avg / $count, 2);}
      $this->gcseAvg = $avg;
  }

  private function getAlis(&$s)
  {
    $alis = new \Entities\Metrics\Alis($s->id, $this->examId);
    $s->alisTestBaseline = (new \Entities\Metrics\Student($s->id))->alisBaselineTest();
    $s->alisTestPrediction = $alis->testPrediction;

    $s->alisGcseBaseline = (new \Entities\Metrics\Student($s->id))->alisBaselineGcse();
    $s->alisGcsePrediction = $alis->gcsePrediction;


    if ($alis->gcseBaseline) {
      $this->metrics['Alis'][] = [
        'studentId' => $s->id,
        'testBaseline' => $s->alisTestBaseline,
        'testPrediction' => $alis->testPrediction,
        'gcseBaseline' => $alis->gcseBaseline,
        'gcsePrediction' => $alis->gcsePrediction
      ];
    }
  }

  private function getMidyis(&$s)
  {
    $midyis = new \Entities\Metrics\Midyis($s->id, $this->examId);
    $s->midyisBaseline = (new \Entities\Metrics\Student($s->id))->midyisScore();
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
    // $s->gcseExamId = $gcse->examId;
    // $s->typeId = $this->gcse->levelId;
    if($gcse){
      $s->gcseGrade = $gcse->result;
      $this->metrics['GCSE'][] = [
        'studentId' => $s->id,
        'grade' => $gcse->result
      ];
    }
  }

  private function getAsLevel(&$s)
  {
    // echo $s->id . "-" . $this->examId . PHP_EOL;
    $asLevel = $this->AsLevel->fetchStudentByExam($s->id, $this->examId, $this->examCode);
    $s->asGrade = null;
    // $s->gcseExamId = $gcse->examId;
    // $s->typeId = $this->gcse->levelId;
    if($asLevel){
      $s->asGrade = $asLevel->result;
      $this->metrics['AsLevel'][] = [
        'studentId' => $s->id,
        'grade' => $asLevel->result
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

  private function getRemoveEOY(&$s)
  {
    $examId = $this->year > 11 ? $this->exam->getGcseExamId($this->examId) : $this->examId;
    // if ($this->year > 11) return;
    $exam = $this->RemoveEOY->fetchStudentByExam($s->id, $examId);
    $s->removeEOYGrade = null;
    $s->removeEOYPercentage = null;
    if($exam) {
      $s->removeEOYGrade = $exam->grade;
      $s->removeEOYPercentage = $exam->percentage;
      $this->metrics['RemoveEOY'][] = [
        'studentId' => $s->id,
        'mark'  => $exam->mark,
        'grade' => $exam->grade,
        'percentage'  => $exam->percentage,
        'yearRank'  =>  $exam->rank
      ];
    }
  }

  private function getL6EOY(&$s)
  {

    if ($this->year < 12) return;
    $examId = $this->examId;
    // if ($this->year > 11) return;
    $exam = $this->L6EOY->fetchStudentByExam($s->id, $examId);
    $s->L6EOYGrade = null;
    $s->L6EOYPercentage = null;
    if($exam) {
      $s->L6EOYGrade = $exam->grade;
      $s->L6EOYPercentage = $exam->percentage;
      $this->metrics['L6EOY'][] = [
        'studentId' => $s->id,
        'mark'  => $exam->mark,
        'grade' => $exam->grade,
        'percentage'  => $exam->percentage,
        'yearRank'  =>  $exam->rank
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
      $this->metrics['GCSE'] = rankArray($this->metrics['GCSE'], 'grade', 'cohortRank');
      $this->metrics['GCSEMock'] = rankArray($this->metrics['GCSEMock'], 'mark', 'cohortRank');
      $this->metrics['ALevelMock'] = rankArray($this->metrics['ALevelMock'], 'mark', 'cohortRank');
      $this->metrics['Midyis'] = rankArray($this->metrics['Midyis'], 'baseline', 'cohortRank');
      $this->metrics['Alis'] = rankArray($this->metrics['Alis'], 'testBaseline', 'cohortRank');
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
      if ($gcseMockPoints == 0 && $gcsePoints == 0) continue;

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
    $gcse = [];
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
    foreach($this->metrics['GCSE'] as $g) $gcse['_' . $g['studentId']] = $g['cohortRank'];
    foreach($this->metrics['ALevelMock'] as $g) $alevelMock['_' . $g['studentId']] = $g['cohortRank'];
    foreach($this->metrics['IGDR'] as $g) $igdr['_' . $g['studentId']] = $g['IGDR'];

    foreach($this->metrics['Alis'] as $g) $alis['_' . $g['studentId']] = $g['cohortRank'];
    foreach($this->metrics['Midyis'] as $g) $midyis['_' . $g['studentId']] = $g['cohortRank'];

    foreach($this->students as &$s){
      $key = '_' . $s->id;
      $s->gcseCohortRank = $gcse[$key] ?? null;
      $s->gcseMockMark = $gcseMockMark[$key] ?? null;
      $s->gcseMockCohortRank = $gcseMock[$key] ?? null;
      $s->aLevelMockCohortRank = $alevelMock[$key] ?? null;
      $s->igdr = $igdr[$key] ?? null;
      $s->alisCohortRank = $alis[$key] ?? null;
      $s->midyisCohortRank = $midyis[$key] ?? null;
    }
  }

}
