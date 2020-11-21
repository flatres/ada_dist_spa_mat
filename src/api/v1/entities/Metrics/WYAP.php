<?php

namespace Entities\Metrics;

class WYAP
{
  public $subjectId, $examId, $year, $name, $marks, $created_at;
  public $results;
  private $ada, $adaData;

  public function __construct(int $wyapId = null)
  {
     $this->ada = new \Dependency\Databases\Ada();
     $this->adaData = new \Dependency\Databases\AdaData();

     if ($wyapId) $this->byId($wyapId);

     return $this;
  }

  public function byId($id) {
    $wyap = $this->adaData->select('wyaps', 'id, subjectId, examId, year, name, marks, created_at', 'id=?', [$id]);
    if (!isset($wyap[0])) return $this;
    $wyap = $wyap[0];
    $this->id = $id;
    $this->subjectId = $wyap['subjectId'];
    $this->examId = $wyap['examId'];
    $this->year = $wyap['year'];
    $this->name = $wyap['name'];
    $this->marks = $wyap['marks'];
    $this->created_at = $wyap['created_at'];
    return $this;
  }

  public function create($subjectId, $examId, $year, $name, $marks) {
    global $userId;
    $this->id = $this->adaData->insert('wyaps', 'subjectId, examId, year, name, marks', [$subjectId, $examId, $year, $name, $marks]);
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
    $this->adaData->delete('wyap_results', 'wyap_id=?', [$this->id]);
    return true;
  }

  public function results () {
    if (!$this->id) return;

    $data = (new \Entities\Academic\Subject($this->ada, $this->subjectId))->getStudentsByExam($this->year, $this->examId);
    foreach ($data as $s) $students['s_' . $s->id] = $s;

    $results = $this->adaData->select(
      'wyap_results',
      'id, student_id, mark, percentage, rank, standard_deviation_delta, hasUnderperformed, comment, last_updated',
      'wyap_id=?',
      [$this->id]
    );

    $allResults = [];
    foreach ($results as &$r) {
      $student = (new \Entities\People\Student($this->ada, $r['student_id']))->basic();
      if ($student['isDisabled']) continue;
      $r = array_merge($r, $student);
      if (isset($students['s_' . $student['student_id']])) $r['classCode'] = str_replace(' (FM)', '', $students['s_' . $student['student_id']]->classCode);
      $allResults[] = $r;
    }

    $statistics = $this->statistics($allResults);

    return [
        'results' => $allResults,
        'statistics' => $statistics
    ];
  }

  public function edit($name, $marks, $results) {
    if (!$this->id) return;
    $this->id = $this->adaData->update('wyaps', 'name=?, marks=?', 'id=?', [$name, $marks, $this->id]);
    $results = rankArray($results, 'mark', 'rank');
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
        'mark=?, percentage=?, rank=?, hasUnderperformed=?, comment=?',
        'id=?',
        [$r['mark'], $r['percentage'], $r['rank'], $r['hasUnderperformed'], $r['comment'], $r['id']]);
    }
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




}
