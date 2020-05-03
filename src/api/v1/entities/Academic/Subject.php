<?php

namespace Entities\Academic;

class Subject
{
  public $id;
  public $code;
  public $year;
  public $isForm;
  public $misFormId;
  public $academicLevel;
  public $teachers=[];
  public $subjectId;
  public $classes=[];
  public $exams=[];
  public $mloMaxGradeProfile=[];
  public $mloMinGradeProfile=[];
  public $history=[];
  public $stackedHistory=[];
  public $grades=[];

  public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
  {
     // $this->sql= $ada ?? new \Dependency\Databases\Ada();
     $this->sql= $ada ?? new \Dependency\Databases\Ada();
     $this->adaData= new \Dependency\Databases\AdaData();
     if ($id) $this->byId($id);
     return $this;
  }

  public function byId($id) {

    $this->id = (int)$id;
    $s = $this->sql->select('sch_subjects', 'misId, name, code, isForm', 'id=?', [$id]);
    if ($s[0]) {
      $s = $s[0];
      $this->misId = $s['misId'];
      $this->name = $s['name'];
      $this->code = $s['code'];
      $this->isForm = $s['isForm'] == 0 ? false : true;
    }
    return $this;
  }

  public function getClassesByYear($year) {
    $classes = $this->sql->select('sch_classes', 'id', 'subjectId = ? AND year=?', [$this->id, $year]);
    foreach($classes as $c){
      $this->classes[] = new \Entities\Academic\AdaClass($this->sql, $c['id']);
    }
    return $this->classes;
  }

  public function getClassesByExam($year, $examId) {
    $classIds = $this->sql->select('sch_class_exams', 'classId', 'examId=?', [$examId]);
    $examClasses = [];
    foreach($classIds as $c) {
      $d = $this->sql->select('sch_classes', 'year', 'id=?', [$c['classId']]);
      $classYear = $d[0]['year'] ?? -1;
      if ($year == $classYear) $examClasses[] = new \Entities\Academic\AdaClass($this->sql, $c['classId']);
    }

    $this->classes = sortObjects($examClasses, 'code', 'ASC');
    return $this->classes;
  }

  public function getExamsByYear($year) {
    $classes = count($this->classes) == 0 ? $this->getClassesByYear($year) : $this->classes;
    $exams = [];

    foreach ($classes as $c) {
      foreach ($c->exams as $e) {
        $key = 'id' . $e->id;
        if (!isset($exams[$key])) $exams[$key] = $e;
      }
    }
    $this->exams = array_values($exams);
    return $this->exams;
  }

  public function getStudentsByYear($year) {
    $classes = count($this->classes) == 0 ? $this->getClassesByYear($year) : $this->classes;
    $students = [];
    foreach($classes as $c) {
      $classStudents = $c->getStudents();
      foreach($classStudents as $s) {
        $key = 'id' . $s->id;
        if (!isset($students[$key])) $students[$key] = $s;
      }
    }
    $students = array_values($students);
    $this->students = sortObjects($students, 'lastName', 'ASC');
    return $this->students;
  }

  public function getStudentsMLOByExam($year, $examId) {
    $students = $this->getStudentsByExam($year, $examId);
    $this->year = (int)$year;
    $maxMLOCount = 0;
    $exam = new \Entities\Academic\SubjectExam($this->sql, $examId);
    foreach($students as $s) {
      $s->examData['mlo'] = [];
      $mloCount = 0;
      $s->getClassesByExam($examId);
      foreach($s->classes as $c){
        foreach($c->teachers as $t){
          $mlo = (new \Entities\Exams\MLO($this->sql))->getSingleMLO($s->id, $exam->examCode, $t->id);
          $s->examData['mlo'][] = [
            'teacher' => $t,
            'examId'  => $examId,
            'class'   => $c,
            'mlo'     => $mlo
          ];
          $s->{'mlo' . $mloCount} = $mlo;
          $mloCount++;
        }
      }
      if ($mloCount > $maxMLOCount) $maxMLOCount = $mloCount;
    }
    $this->students = $students;
    $this->maxMLOCount = $mloCount;
    return [
      'students'  => $students,
      'maxMLOCount' => $maxMLOCount
      ];
  }

  public function makeMLOProfile($examId = null) {
    $students = &$this->students;
    $mlo = new \Entities\Exams\MLO($this->sql);
    foreach ($students as &$s) {
      $exam = $mlo->makeProfile($s, $examId);
      $this->countGrade($this->mloMaxGradeProfile, $exam->mloMax, $s);
      $this->countGrade($this->mloMinGradeProfile, $exam->mloMin, $s);
    }

    //get gcse Avg
    $count = 0;
    $gcseAvg = 0;
    foreach ($students as $s) {
      $g = $this->adaData->select('exams_gcse_avg', 'gcseAvg', 'misId=?', [$s->misId]);
      if ($g) {
        $gcseAvg = $gcseAvg + $g[0]['gcseAvg'];
        $count++;
      }
    }
    if ($count > 0) $gcseAvg = round($gcseAvg / $count, 2);

    $this->mloMaxGradeProfile = array_values(sortArrays($this->mloMaxGradeProfile, 'grade', 'ASC'));
    $this->mloMinGradeProfile = array_values(sortArrays($this->mloMinGradeProfile, 'grade', 'ASC'));

    $this->mloMaxGradeProfile[] = $results[] = [
      'grade' => 'GCSE Avg',
      'count' => $gcseAvg
    ];

    $this->mloMinGradeProfile[] = $results[] = [
      'grade' => 'GCSE Avg',
      'count' => $gcseAvg
    ];

    return $this;
  }

  private function countGrade(&$gradeStore, $grade, $student) {
    if (!$grade) return $gradeStore;
    $key = 'g' . $grade;
    if (!isset($gradeStore[$key])) $gradeStore[$key] = [
      'grade' => $grade,
      'count' => 0,
      'countM' => 0,
      'countF'  => 0
    ];
    $gradeStore[$key]['count']++;
    $gradeStore[$key]['count' . strtoupper($student->gender)]++;
    return $gradeStore;
  }

  public function getStudentsByExam($year, $examId) {
    $classes = count($this->classes) == 0 ? $this->getClassesByYear($year) : $this->classes;
    $students = [];
    foreach($classes as $c) {
      foreach($c->exams as $e) {
        if ($e->id == $examId) {
          foreach($c->students as $s) {
            $key = 'id' . $s->id;
            if (!isset($students[$key])) $students[$key] = $s;
          }
        }
      }
    }
    $students = array_values($students);
    $students = sortObjects($students, 'lastName', 'ASC');
    return $students;
  }

  public function makeHistoryProfile($examId, $year)
  {
      $sql = $this->adaData;
      $history = [];
      if($this->maxMLOCount > 1) {
        $mlo = [];
        $mlo['results'] = $this->mloMaxGradeProfile;
        $mlo['year'] = "Max MLO";
        //create data for stacked chart
        $stacked = [];
        $stacked['year'] = 'Max';
        foreach($mlo['results'] as $r){
          $stacked[$r['grade']] = $r['count'];
        }
        $this->stackedHistory[] = $stacked;
        $history[] = $mlo;


        $mlo = [];
        $mlo['results'] = $this->mloMinGradeProfile;
        $mlo['year'] = "Min MLO";
        $stacked = [];
        $stacked['year'] = 'Min';
        foreach($mlo['results'] as $r){
          $stacked[$r['grade']] = $r['count'];
        }
        $this->stackedHistory[] = $stacked;
        $history[] = $mlo;

      } else {
        $mlo = [];
        $mlo['results'] = $this->mloMaxGradeProfile;
        $mlo['year'] = "MLO";
        //create data for stacked chart
        $stacked = [];
        $stacked['year'] = 'MLO';
        foreach($mlo['results'] as $r){
          $stacked[$r['grade']] = $r['count'];
        }
        $this->stackedHistory[] = $stacked;
        $history[] = $mlo;
      }
      $sessions = $sql->select('exams_sessions', 'id, year', 'id > 0 ORDER BY year DESC', []);
      foreach($sessions as $s){
        $gcseAvg = 0;
        $results = $sql->select('exams_results', 'misId', 'sessionId = ? AND examId=? AND NCYear=?', [$s['id'], $examId, $year]);
        $count = count($results);
        if ($count > 0 ) {
          //reset count
          $count = 0;
          $gcseAvg = 0;
          foreach ($results as $r) {
            $g = $this->adaData->select('exams_gcse_avg', 'gcseAvg', 'misId=?', [$r['misId']]);
            if ($g) {
              $gcseAvg = $gcseAvg + $g[0]['gcseAvg'];
              $count++;
            }
          }
          if ($count > 0) $gcseAvg = round($gcseAvg / $count, 2);
        } else {
          $gcseAvg = 0;
        }

        // counts
        $results = $sql->query(
          'select result as grade, count(*) as count
          FROM exams_results
          WHERE sessionId = ? AND examId=? AND NCYear=?
          GROUP BY result',
          [$s['id'], $examId, $year]);
        if (count($results) > 0) {
          $results = sortArrays($results, 'grade', 'ASC');

          //create data for stacked chart
          $stacked = [];
          $stacked['year'] = (string)$s['year'];
          foreach($results as $r){
            $stacked[$r['grade']] = $r['count'];
          }
          $this->stackedHistory[] = $stacked;

          if ($year > 11) {
            $results[] = [
              'grade' => 'GCSE Avg',
              'count' => $gcseAvg
            ];
          }
          $history[] = [
            'year'  =>  $s['year'],
            'results' => $results
          ];

        }
      }

      //collect all grades
      $grades = $sql->query(
        'select result as grade, count(*) as count
        FROM exams_results
        WHERE examId=? AND NCYear=?
        GROUP BY result',
        [$examId, $year]);

      $this->history = $history;
      $this->grades = sortArrays($grades, 'grade', 'ASC');
      if ($year > 11) {
        $this->grades[] = [
          'grade' => 'GCSE Avg',
          'count' => 0
        ];
      }
  }


}
