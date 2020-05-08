<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Sync;

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


    }

    public function internalSessionSync_POST($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.internalexams');
      $isamsSessionId = $args['sessionId'];
      //delete old session (will cascade down to all papers / results)
      $adaData = $this->adaData;
      $adaData->delete('internal_exams_sessions', 'misId=? AND isIsamsInternal = ?', [$isamsSessionId, 1]);

      // fetch session and write to db
      $session = $this->isams->select(
        "TblInternalExamsSessions",
        'TblInternalExamsSessionsID as id, txtDescription as description',
        'TblInternalExamsSessionsID > 0 ORDER BY txtStartDate DESC',
        [])[0];

      $this->processSession($session);
      //
      if (!$session['typeId']) return emit($response, ['error']);
      //
      $sessionId = $this->adaData->insert(
        'internal_exams_sessions',
        'misId, sessionTypeId, year, NCYear',
        [$session['id'], $session['typeId'], $session['year'], $session['NCYear']]
      );
      // fetch papers
      $i = 1;
      $papers = $this->getPapers($isamsSessionId);
      $count = count($papers);
      foreach($papers as $p) {
        if ($i % 5 == 0) $this->progress->publish($i / $count);
        $i++;
        $adaData->insert(
          'internal_exams_papers',
          'misId, sessionId, examCode, examId, name, totalMark',
          [$p->id, $sessionId, $p->subjectCode, $p->examId, $p->name, $p->totalMark]
        );
        $adaPaperId = $this->adaData->select('internal_exams_papers', 'id', 'misId=?', [$p->id])[0]['id'] ?? null;
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

      $data = [$papers];
      return emit($response, $data);
    }

    public function examResults_GET($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.internalexams', 'Fetching results');
      $paperId = $args['id'];

      $paper = $this->getPaper($paperId);

      if ($paper) {
        $sessions = $this->isams->select(
          "TblInternalExamsSessions",
          'TblInternalExamsSessionsID as id, txtDescription as description',
          'TblInternalExamsSessionsID =?',
          [$paper['sessionId']]);

        $session = $sessions ? $this->processSession($sessions[0]) : [];

        $paperCode = $paper['paperCode'];
        $results = $this->getResults($paperCode);
      }
      $data = ['session'  => $session, 'paper' => $paper, 'results' => $results];

      return emit($response, $data);

    }

    public function exams_GET($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.internalexams', 'Fetching exams');
      $exams = $this->isams->select(
        'TblInternalExamsPapers',
        'TblInternalExamsPapersID as id, txtPaperCode as paperCode, txtPaperName as name, intSessionID as sessionId, intNCYear as NCYear, intTotalMark as totalMark',
        'intSessionID > ?',
        [0]
      );
      $unmatched = [];
      $count = count($exams);
      sortArrays($exams, 'name');
      $i = 0;
      foreach($exams as &$e){
        $i++;
        $codes = new \Exams\Tools\SubjectCodes('', $e['name'], $this->isams, 'GCSE');
        $e['subjectName'] = $codes->subjectName;
        $e['subjectCode'] = $codes->subjectCode;
        if ($codes->error) $unmatched[] = $e;
        if ($i % 10 == 0) $this->progress->publish($i / $count, "$i / $count");
      }
      $data = [
        'unmatched' => $unmatched,
        'exams' =>  $exams
      ];
      return emit($response, $data);

    }

    public function sessions_GET($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'sync.internalexams', 'Loading sessions');

      $sessions = $this->getSessions();

      return emit($response, $sessions);
    }

    private function getSessions()
    {
      $sessions = $this->isams->select(
        "TblInternalExamsSessions",
        'TblInternalExamsSessionsID as id, txtDescription as description',
        'TblInternalExamsSessionsID > 0 ORDER BY txtStartDate DESC',
        []);

      $count = count($sessions);
      $i = 0;
      foreach ($sessions as &$s) {
        $i++;
        if ($i % 10 == 0) $this->progress->publish($i / $count);
        $this->processSession($s);
        $results = $this->isams->select(
          "TblInternalExamsResults",
          'TblInternalExamsResultsID as id',
          'intSessionID = ?',
          [$s['id']]);
        $s['results'] = count($results);

        $papers = $this->isams->select(
          'TblInternalExamsPapers',
          'TblInternalExamsPapersID as id',
          'intSessionID = ?',
          [$s['id']]
        );

        $ada = $this->adaData->select('internal_exams_sessions', 'id', 'misId = ? AND isIsamsInternal=?', [$s['id'], 1]);
        $s['isInAda'] = $ada ? true : false;

        $s['papers'] = count($papers);
      }
      return $sessions;
    }

    private function getResults($paperCode, $totalMarks){
      $results = $this->isams->select(
        "TblInternalExamsResults",
        'TblInternalExamsResultsID as id, txtGrade as grade, intMark as mark, txtSchoolID as studentId',
        'txtPaperCode = ? ORDER BY intMark DESC',
        [$paperCode]);
      $count = count($results);
      foreach($results as &$r) {
        $student = new \Entities\People\iSamsStudent($this->isams, $r['studentId']);
        $r['adaId'] = $student->adaId;
        $r['name'] = $student->displayName;
        $r['gender']  = $student->gender;
        $r['boarding'] = $student->houseCode;
        $pct = $totalMarks > 0 ? round(100 * $r['mark'] / $totalMarks, 2) : null;
        $r['percentage'] = $pct < 100 ? $pct : null;
      }
      unset($r);
      $results = rankArray($results, 'mark', 'rank');
      return $results;
    }

    private function getPapers($sessionId)
    {
      $papers = $this->isams->select(
        'TblInternalExamsPapers',
        'TblInternalExamsPapersID as id',
        'intSessionID = ?',
        [$sessionId]
      );
      $processedPapers = [];
      $count = count($papers);
      $i = 1;
      foreach($papers as $p) {
        $this->progress->publish($i / $count);
        $i++;
        $processedPapers[] = $this->getPaper($p['id']);
      }
      return $processedPapers;
    }

    private function getPaper($paperId){
      $paper = $this->isams->select(
        'TblInternalExamsPapers',
        'TblInternalExamsPapersID as id, txtPaperCode as paperCode, txtPaperName as name, intSessionID as sessionId, intNCYear as NCYear, intTotalMark as totalMark',
        'TblInternalExamsPapersID = ?',
        [$paperId]
      );

      $paper = $paper[0] ?? null;
      if ($paper) {
        // //get session
        $codes = new \Exams\Tools\SubjectCodes('', $paper['name'], $this->isams, 'GCSE');
        $paper['subjectName'] = $codes->subjectName;
        $paper['subjectCode'] = $codes->subjectCode;
        $paper['examId'] = $this->ada->select('sch_subjects_exams', 'id', 'examCode=?', [$codes->subjectCode])[0]['id'] ?? null;
        $paper['error'] = $codes->error;
        $paper['results'] = $this->getResults($paper['paperCode'], $paper['totalMark']);
        return (object)$paper;
      }
      return null;
    }

    private function processSession(&$s)
    {
      $explode = explode(" ", $s['description']);
      $s['isError'] = true;
      if (count($explode) !== 2){
        $s['year'] = 'error';
        $s['type'] = 'error';
        $s['NCYear'] = 0;
        return false;
      }
      $s['isError'] = false;
      $type = $explode[0];
      $s['year'] = $explode[1];
      switch(strtoupper($type)) {
        case 'CE' :
          $type = 'Common Entrance';
          $NCYear = 8;
          break;
        case 'SHELL' :
          $type = 'Shell - End of Year';
          $NCYear = 9;
          break;
        case 'REMOVE' :
          $type = 'Remove - End of Year';
          $NCYear = 10;
          break;
        case 'HUNDRED' :
          $type = 'GCSE Mocks';
          $NCYear = 11;
          break;
        default:
          $type = null;
          $NCYear = 0;
          $s['isError'] = true;
      }
      $s['NCYear'] = $NCYear;
      $s['type']  = $type;

      //attempt to get ada session type id
      $types = $this->adaData->select('internal_exams_types', 'id', 'name=?', [$s['type']]);
      $s['typeId'] = $types[0]['id'] ?? null;
      return $s;
    }



}
