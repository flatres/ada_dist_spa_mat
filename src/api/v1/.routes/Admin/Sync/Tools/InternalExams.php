<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Sync\Tools;

class InternalExams
{
    protected $container;
    private $results;
    private $adaSessionId;

    public function __construct(\Slim\Container $container)
    {
       $this->isams= $container->isams;
       $this->ada = $container->ada;
       $this->adaData = $container->adaData;
       $this->container = $container;
       $this->mcCustom = $container->mcCustom;
    }

    public function internalSessionSync_POST($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.U6Mocks');
      $year = $args['year'];
      $adaData = $this->adaData;
      $adaData->delete('internal_exams_sessions', 'misId=? AND isIsamsInternal = ?', [$year, 0]);

      $session = ['id'  => $year];
      $this->processSession($session);

      if (!$session['typeId']) return emit($response, ['error']);
      $sessionId = $this->adaData->insert(
        'internal_exams_sessions',
        'misId, sessionTypeId, year, NCYear, isIsamsInternal',
        [$year, $session['typeId'], $year, $session['NCYear'], 0]
      );
      // fetch papers
      $i = 1;
      $papers = $this->getExams($year);
      $count = count($papers);
      $ids = [];
      foreach($papers as &$p) {
        if ($p->error) continue;
        $this->progress->publish($i / $count);
        $i++;
        if (!$p->examId){
          $ids[] = $p->subjectCode;
          continue;
        }
        $adaData->insert(
          'internal_exams_papers',
          'misId, sessionId, examCode, examId, name, totalMark',
          [$p->id, $sessionId, $p->subjectCode, $p->examId, $p->name, $p->totalMark]
        );
        $adaPaperId = $this->adaData->select('internal_exams_papers', 'id', 'misId=?', [$p->id])[0]['id'] ?? null;
        $p->results = $this->getResults($p->id, $p->totalMark);
        foreach($p->results as $r) {
          $r = (object)$r;
          $houseId = (new \Entities\Houses\House($this->ada))->byCode($r->boarding)->id;
          $adaData->insert(
            'internal_exams_results',
            'studentId, misId, houseId, examId, typeId, gender, paperId, mark, grade, percentage, rank',
            [$r->adaId, $r->studentId, $houseId, $p->examId, $session['typeId'], $r->gender, $adaPaperId, $r->mark, $r->grade, $r->percentage, $r->rank]
          );
        }
      };

      $data = [$papers, $ids];
      return emit($response, $data);
    }

    public function examResults_GET($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.U6Mock', 'Fetching results');
      $examId = $args['id'];

      $exam = $this->getExam($examId);

      $results = $this->getResults($examId, $exam->totalMark);
      $data = $results;

      return emit($response, $data);

    }

    public function exams_GET($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.U6Mocks', 'Fetching exams.. wish me luck');
      $exams = $this->mcCustom->select(
        'TblExams',
        'TblExamsID as id, txtResultSlipName as name',
        'intNCYear = ? AND txtTerm = ? ORDER BY intYear DESC',
        [13, 'Lent']);

      $unmatched = [];
      $count = count($exams);

      sortArrays($exams, 'name');

      $data = ['unmatched' => [], 'exams' =>  []];
      $i = 0;
      foreach($exams as $e){
        if (!$e['name']) continue;
        $i++;
        $exam = $this->getExam($e['id']);
        if ($exam->error) $data['unmatched'][] = $exam;
        $data['exams'][] = $exam;
        if ($i % 50 == 0) $this->progress->publish($i / $count, "$i / $count");
      }

      return emit($response, $data);
    }


    public function sessions_GET($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.U6Mock', 'Loading sessions');
      $sessions = $this->getSessions();

      return emit($response, $sessions);
    }

    private function getExam($examId)
    {
      $e = (object)$this->mcCustom->select(
        'TblExams',
        'TblExamsID as id, txtResultSlipName as name, txtName as description, intsub1 as m1, intsub2 as m2, intsub3 as m3, intsub4 as m4, intsub5 as m5, intsub6 as m6',
        'TblExamsID = ?',
        [$examId])[0] ?? null;
      if (!$e) return null;

      if (!$e->name){
        $e->error = true;
        return $e;
      }

      $codes = new \Exams\Tools\SubjectCodes('', $e->name, $this->isams);
      $e->subjectName = $codes->subjectName;
      $e->subjectCode = $codes->subjectCode;
      $e->examId = $this->ada->select('sch_subjects_exams', 'id', 'examCode=?', [$codes->subjectCode])[0]['id'] ?? null;
      $e->error = $codes->error;
      $e->totalMark = (int)$e->m1 + (int)$e->m2 + (int)$e->m3 + (int)$e->m4 + (int)$e->m5 + (int)$e->m6;
      return $e;
    }

    private function getExams($year)
    {
      $data = $this->mcCustom->select(
        'TblExams',
        'TblExamsID as id, txtResultSlipName as name',
        'intNCYear = ? AND txtTerm = ? AND intYear = ?',
        [13, 'Lent', $year]);

      $exams = [];
      foreach ($data as $e) $exams[] = $this->getExam($e['id']);
      return $exams;
    }

    private function getSessions()
    {
      $sessions = $this->mcCustom->query(
        'SELECT count(*) as count, intYear as id FROM TblExams WHERE intNCYear = ? AND txtTerm = ? GROUP BY intYear ORDER BY intYear DESC',
        [13, 'Lent']);

      $count = count($sessions);
      $i = 0;
      foreach ($sessions as &$s) {
        $i++;
        if ($i % 10 == 0) $this->progress->publish($i / $count);
        $this->processSession($s);
        $ada = $this->adaData->select('internal_exams_sessions', 'id', 'misId = ? AND isIsamsInternal=?', [$s['id'], 0]);
        $s['isInAda'] = $ada ? true : false;
      }
      return $sessions;
    }


    private function getResults($paperId, $totalMark)
    {
      $results = $this->mcCustom->select(
        'TblExamsPupils',
        'TblExamsPupilsID as id, intsub1 as m1, intsub2 as m2, intsub3 as m3, intsub4 as m4, intsub5 as m5, intsub6 as m6, txtSchoolID as studentId, txtGrade as grade',
        'intExamID = ?',
        [$paperId]
      );
      $count = count($results);
      foreach($results as &$r) {
        $student = new \Entities\People\iSamsStudent($this->isams, $r['studentId']);
        $r['adaId'] = $student->adaId;
        $r['name'] = $student->displayName;
        $r['gender']  = $student->gender;
        $r['boarding'] = $student->houseCode;
        $mark = (int)$r['m1'] + (int)$r['m2'] + (int)$r['m3'] + (int)$r['m4'] + (int)$r['m5'] + (int)$r['m6'];
        $r['mark'] = $mark;
        $pct = $totalMark > 0 ? round(100 * $mark / $totalMark, 2) : null;
        $r['percentage'] = $pct < 100 ? $pct : null;
      }
      unset($r);
      $results = rankArray($results, 'mark', 'rank');
      return $results;
    }

    private function processSession(&$s)
    {
      $s['NCYear'] = 13;
      $s['type']  = 'U6 Mocks';

      //attempt to get ada session type id
      $types = $this->adaData->select('internal_exams_types', 'id', 'name=?', [$s['type']]);
      $s['typeId'] = $types[0]['id'] ?? null;
      return $s;
    }



}
