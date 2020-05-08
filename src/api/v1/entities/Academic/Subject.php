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
  public $bandedHistory = [];
  public $stackedHistory=[];
  public $grades=[];
  public $bands=[];

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
    if ($s) {
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
      if ($year == $classYear) {
        $class = new \Entities\Academic\AdaClass($this->sql, $c['classId']);
        $examClasses[$class->code] = $class;
      }
    }

    $examClasses = array_values($examClasses);

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
    foreach($students as &$s) {
      $s->examData['mlo'] = [];
      $mloCount = 0;
      $s->getClassesByExam($examId);
      foreach($s->classes as $c){
        foreach($c->teachers as $t){
          $mlo = (new \Entities\Exams\MLO($this->sql))->getSingleMLO($s->id, $exam->aliasCode ? $exam->aliasCode : $exam->examCode, $t->id);
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
    $this->maxMLOCount = $mloCount ?? 0;
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

    unset($s);

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

    //calculate %
    $countGrades = 0;
    foreach($this->mloMaxGradeProfile as $g){
      $countGrades += $g['count'];
    }
    unset($g);
    foreach($this->mloMaxGradeProfile as &$g) $g['pct'] = $count > 0 ? round(100*$g['count'] / $countGrades): '';
    unset($g);
    foreach($this->mloMinGradeProfile as &$g) $g['pct'] = $count > 0 ? round(100*$g['count'] / $countGrades): '';
    unset($g);

    $this->mloMaxGradeProfile = array_values(sortArrays($this->mloMaxGradeProfile, 'grade', 'ASC'));
    $this->mloMinGradeProfile = array_values(sortArrays($this->mloMinGradeProfile, 'grade', 'ASC'));

    if ($gcseAvg > 0) {
      $this->mloMaxGradeProfile[] = $results[] = [
        'grade' => 'GCSE Avg',
        'count' => $gcseAvg,
        'pct' => $gcseAvg
      ];

      $this->mloMinGradeProfile[] = $results[] = [
        'grade' => 'GCSE Avg',
        'count' => $gcseAvg,
        'pct'   => $gcseAvg

      ];
    }

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

  // TODO: Split this up and perhaps even move all of this stats business into its own class
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
        $stacked['year'] = 'Max MLO';
        foreach($mlo['results'] as $r){
          $stacked[$r['grade']] = $r['count'];
        }
        $this->stackedHistory[] = $stacked;
        $history[] = $mlo;


        $mlo = [];
        $mlo['results'] = $this->mloMinGradeProfile;
        $mlo['year'] = "Min MLO";
        $stacked = [];
        $stacked['year'] = 'Min MLO';
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
      $sessionHistory = [];
      $stackedHistory = [];
      $multiYearHistory = [];
      $sessions = $sql->select('exams_sessions', 'id, year', 'id > 0 ORDER BY year DESC', []);
      $yearCount = 0;
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
          $results = sortArrays($results, 'grade', 'ASC', true);

          //create data for stacked chart
          $stacked = [];
          $stacked['year'] = (string)$s['year'];

          $countLetterGrades = 0;
          $countNumberGrades = 0;

          foreach($results as $r){
            //get totals. Oh god this code hurts
            $g = $r['grade'];
            $stacked[$g] = $r['count'];
            if (is_numeric($g)) $countNumberGrades += $r['count'];
            if (!is_numeric($g)) $countLetterGrades += $r['count'];
          }
          $stackedHistory[] = $stacked;

          //make percentages
          unset($r);
          foreach($results as &$r){
            $g = $r['grade'];
            $countGrades = is_numeric($g) ? $countNumberGrades : $countLetterGrades;
            if ($countGrades > 0) {
              $r['pct'] = round(100 * $r['count'] / $countGrades);
            }
          }
          unset($r);

          if ($year > 11) {
            $results[] = [
              'grade' => 'GCSE Avg',
              'count' => $gcseAvg,
              'pct'   => $gcseAvg
            ];
          }

          if ($yearCount < 3) {
            $multiYearHistory[] = $results;
            $yearCount++;
          }

          $sessionHistory[] = [
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
        GROUP BY grade',
        [$examId, $year]);
      $this->grades = sortArrays($grades, 'grade', 'ASC', true); //need this in multiyear

      //create averages of last three years
      $multiYearHistory = $this->multiYearAverages($multiYearHistory, $year > 11);

      //Keeps MLO and Multi year at the start for display purposes
      $this->stackedHistory = array_merge($this->stackedHistory, $stackedHistory);
      $this->history = array_merge($history, $multiYearHistory, $sessionHistory);

      if ($year > 11) {
        $this->grades[] = [
          'grade' => 'GCSE Avg',
          'count' => 0
        ];
      }
      $this->bandedHistory = $this->gradeBands($this->history);
  }

  // this has got to be some of the worst code I have written. Don't code in a rush!!!
  private function multiYearAverages(Array $years, bool $hasGCSEAvg) {
      $count = 0;
      $avgCount;
      $gcseAvg=0;
      $gradeCounts = [];
      $numericYears = 0;
      $letterYears = 0; //takes into account depts that have switch grding systems. Yet another edge case
      $countLetterGrades = 0;
      $countNumberGrades = 0;
      foreach($years as $y){
        $yearCount = 0;
        $haveCountedYearNumeric = false;
        $haveCountedYearLetter = false;
        foreach($y as $r){
          $g = $r['grade'];
          $key = '_' . $r['grade'];
          if ($g !== 'GCSE Avg') {
            if (is_numeric($g) && !$haveCountedYearNumeric) {
              $numericYears++;
              $haveCountedYearNumeric = true;
            }
            if (!is_numeric($g) && !$haveCountedYearLetter) {
              $letterYears++;
              $haveCountedYearLetter = true;
            }

            if (!isset($gradeCounts[$key])) $gradeCounts[$key] = [
              'grade' => $g,
              'count' => 0
            ];
            $gradeCounts[$key]['count'] += $r['count'];
            $count += $r['count'];
            $yearCount += $r['count'];
            if (is_numeric($g)) $countNumberGrades += $r['count'];
            if (!is_numeric($g)) $countLetterGrades += $r['count'];
          }

          if ($hasGCSEAvg && $g === 'GCSE Avg'){  //assume that GCSE avg is last so that counrt works. BOLD!
            $gcseAvg += $yearCount * $r['count']; //rcount is the GCSE avg here
          }
        }
      }
      //average
      foreach($this->grades as $g) {
        $g = $g['grade'];
        $key = '_' . $g;
        $countYears = is_numeric($g) ? $numericYears : $letterYears;
        $countGrades = is_numeric($g) ? $countNumberGrades : $countLetterGrades;
        if (isset($gradeCounts[$key]) && $countYears > 0) {
          if ($countGrades > 0) $gradeCounts[$key]['pct'] = round(100 * $gradeCounts[$key]['count'] / $countGrades);
          $gradeCounts[$key]['count'] = round($gradeCounts[$key]['count'] / $countYears);
        }
      }
      $gradeCounts = array_values($gradeCounts);
      if ($hasGCSEAvg) {
        if ($count > 0) $gcseAvg = round($gcseAvg / $count, 2);
        $gradeCounts[] = [
          'grade'  => 'GCSE Avg',
          'count'  => $gcseAvg
        ];
      }
      $result = [
        'year'  => 'Avg Last 3 Yrs',
        'results' => $gradeCounts
      ];
      return [$result];

  }

  private function gradeBands($history)
  {
      //determine what grade structure we have
      $isPreU = false;
      $isNumbers = false;
      $isLetters = false;
      $gradeBands = [];
      foreach($this->grades as $g){
        $g = $g['grade'];
        if ($g == 'A' || $g == 'B' || $g == 'C' || $g == 'D') $isLetters = true;
        if ($g == 'D1' || $g == 'D2' || $g == 'D3' || $g == 'M1' || $g == 'M2' || $g == 'M3') $isPreU = true;
        if ((int)$g == 9 || (int)$g == 8 || (int)$g == 7 || (int)$g == 6 || (int)$g == 5)  $isNumbers = true;
      }
      // $this->h = $history;
      foreach($history as $h) {
        $results = $h['results'];
        $gC = []; //grade counts
        $totalGrades = 0;
        $countNumberGrades = 0;
        $countLetterGrades = 0;
        $avgGCSE = 0;
        foreach($results as $r) {
          $grade = $r['grade'];
          if ($grade == 'GCSE Avg') {
            $avgGCSE = $r['count'];
            continue;
          }
          $key = is_numeric($grade) ? '_' . $grade : $grade;

          $count = (int)$r['count'];
          if (is_numeric($grade)) $countNumberGrades += $count;
          if (!is_numeric($grade)) $countLetterGrades += $count;

          $gC[$key] = $count;
          $totalGrades += $count;
        }
        // $this->gC = $gC;
        $b1 = []; $b2 = []; $b3 = [];
        if ($isNumbers) {
          $b1 = [
            ' 9' => $gC["_9"] ?? 0,
            '9-8' => ($gC["_9"] ?? 0) + ($gC["_8"] ?? 0),
            '9-7' => ($gC["_9"] ?? 0) + ($gC["_8"] ?? 0) + ($gC["_7"] ?? 0),
            '9-6' => ($gC["_9"] ?? 0) + ($gC["_8"] ?? 0) + ($gC["_7"] ?? 0) + ($gC["_6"] ?? 0),
            '9-4' => ($gC["_9"] ?? 0) + ($gC["_8"] ?? 0) + ($gC["_7"] ?? 0) + ($gC["_6"] ?? 0) + ($gC["_5"] ?? 0)  + ($gC["_4"] ?? 0)
          ];
        }
        if ($isLetters) {
          $b2 = [
            'A*' => $gC["A*"] ?? 0,
            'A*-A' => ($gC["A*"] ?? 0) + ($gC["A"] ?? 0),
            'A*-B' => ($gC["A*"] ?? 0) + ($gC["A"] ?? 0) + ($gC["B"] ?? 0),
            'A*-C' => ($gC["A*"] ?? 0) + ($gC["A"] ?? 0) + ($gC["B"] ?? 0) + ($gC["C"] ?? 0),
            'A*-D' => ($gC["A*"] ?? 0) + ($gC["A"] ?? 0) + ($gC["B"] ?? 0) + ($gC["C"] ?? 0) + ($gC["D"] ?? 0),
            'A*-E' => ($gC["A*"] ?? 0) + ($gC["A"] ?? 0) + ($gC["B"] ?? 0) + ($gC["C"] ?? 0) + ($gC["D"] ?? 0) + ($gC["E"] ?? 0),
          ];
        }
        if ($isPreU) {
          $b3 = [
            'D1-D2' => ($gC["D1"] ?? 0) + ($gC["D2"] ?? 0),
            'D1-D3' => ($gC["D1"] ?? 0) + ($gC["D2"] ?? 0) + ($gC["D3"] ?? 0),
            'D1-M1' => ($gC["D1"] ?? 0) + ($gC["D2"] ?? 0) + ($gC["D3"] ?? 0) + ($gC["M1"] ?? 0),
            'D1-M2' => ($gC["D1"] ?? 0) + ($gC["D2"] ?? 0) + ($gC["D3"] ?? 0) + ($gC["M1"] ?? 0) + ($gC["M2"] ?? 0),
            'D1-M3' => ($gC["D1"] ?? 0) + ($gC["D2"] ?? 0) + ($gC["D3"] ?? 0) + ($gC["M1"] ?? 0) + ($gC["M2"] ?? 0) + ($gC["M3"] ?? 0),
            'D1-P2' => ($gC["D1"] ?? 0) + ($gC["D2"] ?? 0) + ($gC["D3"] ?? 0) + ($gC["M1"] ?? 0) + ($gC["M2"] ?? 0) + ($gC["M3"] ?? 0) + ($gC["P1"] ?? 0) + ($gC["P2"] ?? 0)
          ];
        }
        $b = array_merge($b1, $b2, $b3);
        $bands = [];
        foreach($b as $key => $value) {
          $this->bands[$key] = $key;
          $countGrades = strpos(strtoupper($key), '9') !== false ? $countNumberGrades : $countLetterGrades;
          $bands[] = [
            'band'  => $key,
            'abs'   => $value,
            'pct'   => $countGrades == 0 ? 0 : round(100 * $value / $countGrades)
          ];
        }
        if ($this->year > 11) {
          $bands[] = [
            'band'  => 'GCSE Avg',
            'abs'   => $avgGCSE,
            'pct'   => $avgGCSE
          ];
          if (!isset($this->bands['Avg GCSE'])) $this->bands['Avg GCSE'] = 'GCSE Avg';
        }

        $gradeBands[] = [
          'year'  => $h['year'],
          'results' => $bands
        ];

      }
      $this->bands = array_values($this->bands);
      return $gradeBands;
  }


  private function createBandPercentages($bands, $total) {

  }

}
