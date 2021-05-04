<?php

namespace Entities\Metrics;

class WYAP
{
  public $subjectId, $examId, $year, $name, $marks, $created_at, $typeId, $gradeSetId;
  public $results, $boundaries;
  public $hasGrades = false;
  public $missingStudents = [];
  public $type, $typeShort;
  private $ada, $adaData;

  public function __construct(int $wyapId = null)
  {
     $this->ada = new \Dependency\Databases\Ada();
     $this->adaData = new \Dependency\Databases\AdaData();

     if ($wyapId) $this->byId($wyapId);

     return $this;
  }

  public function byId($id) {
    $wyap = $this->adaData->select('wyaps', 'id, subjectId, examId, gradeSetId, year, name, marks, created_at, typeId', 'id=?', [$id]);
    if (!isset($wyap[0])) return $this;
    $wyap = $wyap[0];
    $this->id = $id;
    $this->subjectId = $wyap['subjectId'];
    $this->examId = $wyap['examId'];
    $this->gradeSetId = $wyap['gradeSetId'];
    $this->year = $wyap['year'];
    $this->name = $wyap['name'];
    $this->marks = $wyap['marks'];
    $this->typeId = $wyap['typeId'];
    $this->created_at = $wyap['created_at'];

    //type name
    $t = $this->adaData->select('wyap_types', 'name, short', 'id=?', [$this->typeId]);
    if (isset($t[0])) {
        $this->type = $t[0]['name'];
        $this->typeShort = $t['0']['short'];
    }

    if ($this->gradeSetId) $this->hasGrades = count($this->adaData->select('wyap_grade_boundaries', 'id', 'wyapId=?', [$id])) > 0;

    return $this;
  }

  public function create($subjectId, $examId, $year, $name, $marks, $typeId) {
    global $userId;
    $this->id = $this->adaData->insert('wyaps', 'subjectId, examId, year, name, marks, typeId', [$subjectId, $examId, $year, $name, $marks, $typeId]);
    $students = (new \Entities\Academic\Subject($this->ada, $subjectId))->getStudentsByExam($year, $examId);
    foreach($students as $s) {
      $this->adaData->insert('wyap_results', 'wyap_id, student_id, exam_id, updated_by_id', [$this->id, $s->id, $examId, $userId]);
    }
    $this->byId($this->id);
    return $this;
  }

  public function delete() {
    if (!$this->id) return;
    $this->adaData->delete('wyaps', 'id=?', [$this->id]);
    $this->adaData->delete('wyap_grade_boundaries', 'wyapId=?', [$this->id]);
    $this->adaData->delete('wyap_results', 'wyap_id=?', [$this->id]);
    return true;
  }

  // studentsToMerge used when compiling all metrics for a pupil
  public function results (array &$studentsToMerge = null) {
    global $userId;
    if (!$this->id) return;

    $this->makeTotals();

    $studentMap = [];
    $wyapKey = 'wyap_' . $this->id . '_';
    if ($studentsToMerge) {
      foreach ($studentsToMerge as &$s) {
        $key = 's_' . $s->id;
        $s->{$wyapKey . 'mark'} = null;
        $s->{$wyapKey . 'grade'} = null;
        $s->{$wyapKey . 'pct'} = null;
        $s->{$wyapKey . 'hasUsedExtraTime'} = null;
        $s->{$wyapKey . 'hasUnderperformed'} = false;
        $s->{$wyapKey . 'rank'} = null;
        $s->{$wyapKey . 'comment'} = '';
        $studentMap[$key] = &$s;
      }
    }
    unset($s);

    $data = (new \Entities\Academic\Subject($this->ada, $this->subjectId))->getStudentsByExam($this->year, $this->examId);
    foreach ($data as $s) $students['s_' . $s->id] = $s;

    $results = $this->adaData->select(
      'wyap_results',
      'id, student_id, mark, grade, percentage, rank, standard_deviation_delta, hasUsedExtraTime, hasUnderperformed, comment, last_updated',
      'wyap_id=?',
      [$this->id]
    );

    //if someone has been added to the subject since, identify them and add them to wyap
    $wyapStudents = [];
    $missing = [];
    foreach($results as $r) $wyapStudents['s_' . $r['student_id']] = true;
    foreach($students as $s) {
      if (!isset($wyapStudents['s_' . $s->id])) $missing[] = $s;
    }

    $this->missingStudents = $missing;
    if (count($missing) > 0) {
      foreach ($missing as $m) $this->adaData->insert('wyap_results', 'wyap_id, student_id, exam_id, updated_by_id', [$this->id, $m->id, $this->examId, $userId]);
      $results = $this->adaData->select(
        'wyap_results',
        'id, student_id, mark, grade, percentage, rank, standard_deviation_delta, hasUsedExtraTime, hasUnderperformed, comment, last_updated',
        'wyap_id=?',
        [$this->id]
      );
    }

    unset($r);
    $allResults = [];
    foreach ($results as &$r) {
      //try to find in map and update if found
      $key = 's_' . $r['student_id'];
      if (isset($studentMap[$key])) {
        $s = &$studentMap[$key];
        $s->{$wyapKey . 'mark'} = $r['mark'];
        $s->{$wyapKey . 'grade'} = $r['grade'];
        $s->{$wyapKey . 'pct'} = $r['percentage'];
        $s->{$wyapKey . 'hasUsedExtraTime'} = (bool)$r['hasUsedExtraTime'];
        $s->{$wyapKey . 'hasUnderperformed'} = (bool)$r['hasUnderperformed'];
        $s->{$wyapKey . 'rank'} = $r['rank'];
        $s->{$wyapKey . 'comment'} = $r['comment'];

        // for when this feature is pushed and previuously entered wyaps dont' have totals data

        if (!isset($s->wyap_totals_pct)) {
          $isLowerSchool = $this->year < 12;
          $totals = $this->adaData->select(
            'wyap_totals',
            'percentage, rank',
            'student_id=? AND exam_id=? AND is_lower_school=?',
            [$s->id, $this->examId, $isLowerSchool]
          );
          if (isset($totals[0])) {
            $totals = $totals[0];
            $s->wyap_totals_rank = $totals['rank'];
            $s->wyap_totals_pct = $totals['percentage'];
          }
        }
      }

      $student = (new \Entities\People\Student($this->ada, $r['student_id']))->basic();
      if ($student['isDisabled']) continue;
      $r = array_merge($r, $student);
      if (isset($students[$key])) $r['classCode'] = str_replace(' (FM)', '', $students[$key]->classCode);
      $allResults[] = $r;
    }

    // if ($studentsToMerge) { var_dump($studentMap); exit(); }

    $statistics = $this->statistics($allResults);

    $this->results = $allResults;

    if ($studentsToMerge) $studentsToMerge = array_values($studentMap);

    return [
        'results' => $allResults,
        'statistics' => $statistics,
        'missing' => $missing
    ];
  }

  public function edit($name, $marks, $results) {
    if (!$this->id) return;
    $this->adaData->update('wyaps', 'name=?, marks=?', 'id=?', [$name, $marks, $this->id]);
    // return;
    foreach ($results as &$r) {
      $mark = is_null($r['mark']) ? "" : $r['mark'];

      if (strlen($r['mark']) == 0) {
        $r['mark'] = null;
        $r['percentage'] = null;
      } else {
        $r['percentage'] = $marks > 0 ? round(100 * $r['mark'] / $marks, 1) : null;
      }
      $this->adaData->update(
        'wyap_results',
        'mark=?, percentage=?,hasUsedExtraTime=?, hasUnderperformed=?, comment=?',
        'id=?',
        [$r['mark'], $r['percentage'], $r['hasUsedExtraTime'], $r['hasUnderperformed'], $r['comment'], $r['id']]);
    }
    $this->rankResults();
    $this->makeTotals();
    $this->setGrades();
  }

  private function rankResults() {
    if (!$this->id) return;

    $results = $this->results()['results'];
    $results = rankArray($results, 'mark', 'rank');
    foreach ($results as &$r) {
      $this->adaData->update(
        'wyap_results',
        'rank=?',
        'id=?',
        [$r['rank'], $r['id']]);
    }
    return true;
  }

  // https://www.geeksforgeeks.org/php-program-find-standard-deviation-array/
  private function statistics(&$results)
  {
    $nonBlank = 0;
    $markSum = 0;
    $mean = 0;
    $sd = 0;

    //count and find sum of non blank marks
    foreach($results as $r) if (!is_null($r['mark']) && strlen($r['mark']) > 0) {
      $nonBlank++; $markSum += $r['mark'];
    }
    unset($r);

    if ($nonBlank > 0) $mean = $markSum / $nonBlank;

    //calculate variance
    $variance = 0;
    foreach($results as $r) {
      $i = $r['mark'];
      if (!is_null($i) && strlen($i) > 0) $variance += pow(($i - $mean), 2);
    }
    unset($r);
    $stdDev = $nonBlank > 0 ? sqrt($variance/$nonBlank) : 0;

    //calculate the distance away from the standard deviation for each result
    foreach($results as &$r) if (!is_null($r['mark']) && strlen($r['mark']) > 0) { $r['sdDelta'] = $r['mark'] - $stdDev; }

    return [
      'count' => $nonBlank,
      'mean'  => round($mean,1),
      'sd'    => round($stdDev,1)
    ];
  }

  public function getAllStudentResults(string $examId, array &$students)
  {

  }

  public function getBoundaries() {
    if (!$this->gradeSetId || !$this->id) return [];
    $gradeSet = new \Entities\Academic\GradeSet($this->gradeSetId);

     $previousThreshold = $this->marks + 1;
     $gradeCounter = 1;
     $split = $this->marks / count($gradeSet->grades);
     $boundaries = $gradeSet->grades;
     foreach($boundaries as &$b) {
       $t = $this->adaData->select(
         'wyap_grade_boundaries',
         'id, gradeSetGradeId, markThreshold',
         'wyapId=? AND gradeSetGradeId = ?',
         [$this->id, $b['id']]
        );
       if (isset($t[0])) {
         $b['lowerThreshold'] = $t[0]['markThreshold'];
       } else {
         $b['lowerThreshold'] = round($this->marks - $gradeCounter * $split);
       }
       $gradeCounter++;
       $b['upperThreshold'] = $previousThreshold;
       // for top grade so that a mark equal to the max possible get the top grade
       $b['upperThreshold'] == $this->marks ? $this->marks + 1 : $b['upperThreshold'];

       $b['pupils'] = count($this->adaData->select(
         'wyap_results',
         'id',
         'wyap_id=? AND mark >= ? AND mark < ?',
         [$this->id, $b['lowerThreshold'], $b['upperThreshold']]
       ));
       $previousThreshold = $b['lowerThreshold'];
     }
     $this->boundaries = $boundaries;
     return $boundaries;
  }

  // save boundaries for this wyap and set grades
  public function saveBoundaries($boundaries) {
    if (!$this->gradeSetId || !$this->id) return $this;
    $wyapId = $this->id;
    $this->adaData->delete('wyap_grade_boundaries', 'wyapId=?', [$wyapId]);
    foreach($boundaries as $b) {
      $this->adaData->insert(
        'wyap_grade_boundaries',
        'wyapId, gradeSetId, gradeSetGradeId, markThreshold, gradeSetGradePoints',
        [$wyapId, $this->gradeSetId, $b['id'], $b['lowerThreshold'], $b['points']]
      );
    }
    $this->setGrades();
    return $this;
  }

  public function setGrades() {
    if (!$this->gradeSetId || !$this->id) return $this;
    $wyapId = $this->id;
    $boundaries = $this->getBoundaries();
    foreach($boundaries as $b) {
      $this->adaData->update(
        'wyap_results',
        'grade=?',
        'wyap_id=? AND mark >= ? AND mark < ?',
        [$b['grade'], $this->id, $b['lowerThreshold'], $b['upperThreshold']]
      );
    }
    return $this;
  }

  public function saveGradeSet($gradeSetId) {
    if (!$this->id) return $this;
    $wyapId = $this->id;
    // get current gradeset
    $previousId = $this->adaData->select('wyaps', 'gradeSetId', 'id=?', [$wyapId])[0]['gradeSetId'];

    if ($gradeSetId !== $previousId) {
      $this->adaData->delete('wyap_grade_boundaries', 'wyapId=?', [$wyapId]);
      $this->adaData->update('wyap_results', 'grade=?', 'wyap_id=?', [null, $wyapId]);
      $this->adaData->update('wyaps', 'gradeSetId=?', 'id=?', [$gradeSetId, $wyapId]);
      $this->gradeSetId = $gradeSetId;
    }

    return $this;
   }

  // returns the grade profile for a provision set on boundaries
  public function makeProfile(&$boundaries) {
    if (!$this->id) return $boundaries;
    foreach ($boundaries as &$b) $b['pupils'] = count($this->adaData->select(
      'wyap_results',
      'id',
      'wyap_id=? AND mark >= ? AND mark < ?',
      [$this->id, $b['lowerThreshold'], $b['upperThreshold'] == $this->marks ? $this->marks + 1 : $b['upperThreshold']]
    ));
    return $boundaries;
  }

  // calculate the overall totals for each students
  // called when WYAP results are updated
  private function makeTotals() {
    $wyaps = (new \Entities\Academic\Subject($this->ada, $this->subjectId))->getWYAPsByExam($this->year, $this->examId);
    $students = [];

    // collect marks for each student
    foreach($wyaps as $w) {
      $results = $this->adaData->select(
        'wyap_results',
        'id, student_id, mark',
        'wyap_id=?',
        [$w->id]
      );
      foreach($results as $r) {
        $studentId = $r['student_id'];
        $key = "s_$studentId";
        if (!isset($students[$key])) $students[$key] = [
          'id' => $studentId,
          'marksScored' => 0,
          'marksAvailable' => 0,
          'pct' => 0,
          'rank' => 0,
          'wyapCount' => 0
        ];
        $s = &$students[$key];
        if (!$r['mark']) continue;
        if (strlen($r['mark']===0)) continue;
        $s['marksScored'] += $r['mark'];
        $s['marksAvailable'] += $w->marks;
        $s['wyapCount']++;
      }
    }
    $students = array_values($students);
    unset($s);
    foreach ($students as &$s) {
      if ($s['marksAvailable'] > 0) $s['pct'] = round(100 * $s['marksScored'] / $s['marksAvailable'], 1);
    }
    $students = rankArray($students, 'pct', 'rank');

    unset($s);
    $isLowerSchool = $this->year < 12 ? 1 : 0;
    foreach($students as $s) {
      $this->adaData->delete('wyap_totals', 'student_id=? AND exam_id=? and is_lower_school=?', [$s['id'], $this->examId, $isLowerSchool]);
      $this->adaData->insert(
        'wyap_totals',
        'student_id, exam_id, is_lower_school, total_marks, marks_available, percentage, rank, wyap_count',
        [$s['id'], $this->examId, $isLowerSchool, $s['marksScored'], $s['marksAvailable'], $s['pct'], $s['rank'], $s['wyapCount']]
      );
    }
  }


}
