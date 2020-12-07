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

  public function results (array &$studentsToMerge = null) {
    if (!$this->id) return;

    $studentMap = [];
    $wyapKey = 'wyap_' . $this->id . '_';
    if ($studentsToMerge) {
      foreach ($studentsToMerge as &$s) {
        $key = 's_' . $s->id;
        $s->{$wyapKey . 'mark'} = null;
        $s->{$wyapKey . 'pct'} = null;
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
      'id, student_id, mark, percentage, rank, standard_deviation_delta, hasUnderperformed, comment, last_updated',
      'wyap_id=?',
      [$this->id]
    );

    $allResults = [];
    foreach ($results as &$r) {
      //try to find in map and update if found
      $key = 's_' . $r['student_id'];
      if (isset($studentMap[$key])) {
        $s = &$studentMap[$key];
        $s->{$wyapKey . 'mark'} = $r['mark'];
        $s->{$wyapKey . 'pct'} = $r['percentage'];
        $s->{$wyapKey . 'hasUnderperformed'} = (bool)$r['hasUnderperformed'];
        $s->{$wyapKey . 'rank'} = $r['rank'];
        $s->{$wyapKey . 'comment'} = $r['comment'];
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
        'statistics' => $statistics
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
        'mark=?, percentage=?, hasUnderperformed=?, comment=?',
        'id=?',
        [$r['mark'], $r['percentage'], $r['hasUnderperformed'], $r['comment'], $r['id']]);
    }
    $this->rankResults();
  }

  private function rankResults() {
    if (!$this->id) return;

    $results = $this->results()['results'];
    var_dump($results);
    $results = rankArray($results, 'mark', 'rank');
    var_dump($results);
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




}
