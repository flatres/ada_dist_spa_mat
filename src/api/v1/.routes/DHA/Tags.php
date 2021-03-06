<?php
use Slim\Http\UploadedFile;
/**
 * Description

 * Usage:

 */
namespace DHA;

class Tags
{
    protected $container;
    private $console;
    private $channel = 'dha.tags.upload';
    private $year;
    private $hasTags = false;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->adaData = $container->adaData;
       $this->isams = $container->isams;
    }

    public function toggleFlagPut($request, $response, $args) {
      $flag = $args['flag'];
      $id = $args['id'];
      $this->adaData->update('ucas_offers', 'flagged=?', 'studentId=?', [$flag, $id]);
    }

    public function toggleCheckedPut($request, $response, $args) {
      $flag = $args['flag'];
      $id = $args['id'];
      $this->adaData->update('ucas_offers', 'checked=?', 'studentId=?', [$flag, $id]);
    }

    public function overviewGet($request, $response, $args)
    {
      $year = $args['year'];
      $this->year = $year;
      $table = $year < 12 ? 'tag_results_gcse' : 'tag_results_alevel';

      $students = [];
      $results = $this->adaData->select($table, 'count(*) as count, studentId', 'id>0 GROUP BY studentId');
      foreach($results as $r) {
        $s = new \Entities\People\Student($this->ada, $r['studentId']);
        if ($s->NCYear == $year) {
          $iSamsS = new \Entities\People\iSamsStudent($this->isams, $s->misId);
          $s->getHmNote();
          $s->getAccessArrangements();
          $s->flagged = 0;
          $s->checked = 0;
          $s->ucasHigh = $this->getOffer($s->id);
          $s->ucasLow = $this->getOffer($s->id, false);
          $s->ethnicity = $iSamsS->ethnicGroup;
          if (isset($s->ucasHigh['offer'])) $s->flagged = $s->ucasHigh['flagged'];
          if (isset($s->ucasHigh['offer'])) $s->checked = $s->ucasHigh['checked'];

          // if (isset($ucas[0])) $s = (object)\array_merge((array)$s, $ucas[0]);
          $s = (object)\array_merge((array)$s, $this->getPupilResults($s->id));

          $s->baseline = isset($s->exams[0]) ? $s->exams[0]->baseline->baseline : '';

          $students[] = $s;
        }
      }
      $students = sortObjects($students, 'displayName', 'ASC');

      return emit($response, $students);
    }

    private function getOffer($sId, $high = true) {
      $code = $high ? 'CF' : 'CI';
      $o = [];
      $o = $this->adaData->select('ucas_offers', '*', 'studentId=? AND decision=?', [$sId, $code]);
      if (!isset($o[0]) && $high == true) {
        $o = $this->adaData->select('ucas_offers', '*', 'studentId=? AND decision=?', [$sId, 'C']);
      }
      if (!isset($o[0])) return [];
      $o = $o[0];

      $o['offer'] = "[${o['grade1']}]";
      if ($o['grade2']) $o['offer'] .= " [${o['grade2']}]";
      if ($o['grade3']) $o['offer'] .= " [${o['grade3']}]";
      if ($o['grade4']) $o['offer'] .= " [${o['grade4']}]";
      return $o;
    }

    private function makePoints($offer) {
      if (is_numeric($offer)) return $offer;
      //sanitise
      $offer = \str_replace('Maths', '', $offer);
      if (substr_count($offer, '/') > 0) $offer = explode('/', $offer)[0];

      $grades = [
        'A*' => 0,
        'A' => 0,
        'B' => 0,
        'C' => 0,
        'D' => 0,
        'E' => 0,
        'D1' => 0,
        'D2' => 0,
        'D3' => 0,
        'M1' => 0,
        'M2' => 0,
        'M3' => 0,
        'P1' => 0,
        'P2' => 0,
        'P3' => 0
      ];
      foreach($grades as $grade => &$count) $count = substr_count($offer, $grade);
      unset($count);
      $grades['A'] = $grades['A'] - $grades['A*'];
      $grades['D'] = $grades['D'] - $grades['D1'] - $grades['D2'] - $grades['D3'];
      // return $grades;
      $points = 0;
      $result = new \Exams\Tools\ALevel\Result();
      foreach($grades as $grade => $c) {
        if (!$grade) continue;
        $result->processGrade($grade);
        $points += $c * $result->ucasPoints;
      }
      return $points;
    }

    private function getPupilResults($id) {
      $data = [];
      $exams = [];
      $tagPointsTotal = 0;
      $tagUcasPointsTotal = 0;
      $megPointsTotal = 0;
      $megUcasPointsTotal = 0;
      $sort = 'meg';

      $table = $this->year < 12 ? 'tag_results_gcse' : 'tag_results_alevel';
      $results = $this->adaData->select($table, 'tag, meg, examId, specialCircumstances, evidenceRemarks, rationale', 'studentId=?', [$id]);
      foreach($results as $r) {
        $e = new \Entities\Academic\SubjectExam($this->ada, $r['examId']);
        $e = (object)\array_merge((array)$e, $r);
        if ($r['tag']) {$sort = 'tag'; $this->hasTags = true;}
        if ($this->year < 12) {
          $result = new \Exams\Tools\GCSE\Result();
          $e->baseline = new \Entities\Metrics\Midyis($id, $e->id, $this->adaData);
        } else {
          $result = new \Exams\Tools\ALevel\Result();
          $e->baseline = new \Entities\Metrics\Alis($id, $e->id, $this->adaData);
          if ($e->examCode == 'EPQ') $result->level = 'EPQ';
        }
        $e->baseline->exam = null;
        // process tag
        if ($r['tag']) $tagPointsTotal += $result->processGrade($r['tag']);
        if ($this->year > 11) {
          $tagUcasPointsTotal += $result->ucasPoints;
          $tagPointsTotal = $tagUcasPointsTotal;
        }
        // process meg
        if ($r['meg']) $megPointsTotal += $result->processGrade($r['meg']);
        if ($this->year > 11) {
          $megUcasPointsTotal += $result->ucasPoints;
          $megPointsTotal = $megUcasPointsTotal;
        }
        $exams[] = $e;
      }
      $exams = sortObjects($exams, $sort, 'ASC');
      return [
        'tagPoints' => $tagPointsTotal,
        'megPoints' => $megPointsTotal,
        'tagUCAS' => $tagUcasPointsTotal,
        'megUCAS' => $megUcasPointsTotal,
        'exams' => $exams
      ];
    }

    public function finalUploadPost($request, $response, $args)
    {
      $examId = $args['id'];
      $examCode = $args['code'];
      $examYear = $args['year'];


      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, $this->channel);

      $uploadedFile = $request->getUploadedFiles();

      $level = $examYear > 11 ? 'alevel/' : 'gcse/';
      $directory = FILESTORE_PATH . "dha/tags/" . $level;
      $fileName = $examCode . '_' . $examId;
      $filename = moveUploadedFile($directory, $uploadedFile['file'], $fileName);
      $data = [];
      $this->parseSheet($response, $directory . $filename, $data);

      // $subject = $data['subject'];
      $exam = $data['exam'];
      $table = $exam->year < 12 ? 'tag_results_gcse' : 'tag_results_alevel';

      $i = 0;
      $megCount = 0;
      $tagCount = 0;
      foreach($data['students'] as $s) {
        $i++;
          $this->adaData->delete($table, 'examId=? AND studentId=?', [$exam->id, $s->id]);
          $this->adaData->insert(
            $table,
            'studentId, examId, tag, meg, specialCircumstances, evidenceRemarks, rationale',
            [
              $s->id,
              $exam->id,
              $s->tag,
              $s->meg,
              $s->sc,
              $s->evidence,
              $s->rationale
            ]
        );
        if (strlen($s->tag) > 0) $tagCount++;
        if (strlen($s->meg) > 0) $megCount++;
      }

      $this->adaData->delete('tag_exams', 'examId=? AND year=?', [$exam->id, $exam->year]);
      $this->adaData->insert('tag_exams', 'examId, year, hasUploaded, tagCount, megCount', [$examId, $exam->year, 1, $tagCount, $megCount]);
      $data['count'] = $i;

      return emit($response, $data);

    }
    private function parseSheet($response, $path, &$data) {
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      $reader->setReadDataOnly(true);
      $spreadsheet = $reader->load($path);
      $worksheet = $spreadsheet->getActiveSheet();
      $worksheetTitle     = $worksheet->getTitle();
      $highestRow         = $worksheet->getHighestRow(); // e.g. 10
      $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
      $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

      $data = ['info' => [], 'error' => false, 'sheetInfo' => []];
      $data['sheetInfo'] = [
        'sheet' => $worksheetTitle,
        'rows'  => $highestRow,
        'columns' => $highestColumn
      ];
      $code = $worksheet->getCellByColumnAndRow(1, 2)->getValue();
      $data['sheetInfo']['code'] = $code;
      $code = explode('/', $code);

      if (count($code) !== 3) return emit($response, ['error' => true, 'msg' => 'Exam Code Not Identified in Cell A2']);
      $examCode = $code[0];
      $subjectId = $code[1];
      $examId = $code[2];

      $exam = new \Entities\Academic\SubjectExam($this->ada, $examId);
      // if (!$examId) return emit($response, ['error' => true, 'msg' => 'Exam Name Not Identified']);
      $data['exam'] = $exam;

      //identify Columns
      $columns = [
        'TAG' => null,
        "MEG" => null,
        "SC" => null,
        "Evidence" => null,
        "Rationale" => null
      ];

      for ($col = 5; $col <= $highestColumnIndex; $col++) {
        $cell = $worksheet->getCellByColumnAndRow($col, 3);
        $name = $cell->getValue();
        switch (strtoupper($name)) {
          case "FINAL GRADE":
            $columns['TAG'] = $col; break;
          case "MODERATED EVIDENCE GRADE":
            $columns['MEG'] = $col; break;
          case "EXPLANATION":
            $columns['SC'] = $col; break;
          case "EVIDENCE REMARKS":
            $columns['Evidence'] = $col; break;
          case "RATIONALE":
            $columns['Rationale'] = $col; break;
        }
      }
      // exit();
      $colError = false;
      $colErrorMsg = '';
      foreach($columns as $key => &$col) {
        if (!$col) {
          $colError = true;
          $colErrorMsg .= "$key column not found. <br/>";
        }
      }
      if ($colError) return emit($response, ['error' => true, 'msg' => $colErrorMsg]);
      unset($col);
      $data['columns'] = $columns;

      $students = [];
      $studentError = false;
      $studentErrorMsg = '';
      for ($row = 5; $row <= $highestRow; ++$row) {
        $cell = $worksheet->getCellByColumnAndRow(1, $row);
        $name = $cell->getValue();
        $lastName = explode(',', $name)[0];
        $schoolNumber = $worksheet->getCellByColumnAndRow(3, $row);
        $student = new \Entities\People\Student($this->ada);
        $student->bySchoolNumber($schoolNumber);
        if ($student->id) {
          $students[] = $student;
        } else {
          $studentErrorMsg .= 'School #' . $schoolNumber . ' not matched<br/>';
          $studentError = true;
          continue;
        }
        if ($student->lastName !== $lastName) {
          $studentErrorMsg .= 'Row ' . $row . ' surname mismatch<br/>';
          $studentError = true;
          continue;
        }
        $student->tag = $worksheet->getCellByColumnAndRow($columns['TAG'], $row)->getValue();
        // if (!$student->tag) {$studentError = true; $studentErrorMsg .= "Row $row has no Final Grade<br/>";}

        $student->meg = $worksheet->getCellByColumnAndRow($columns['MEG'], $row)->getValue();
        // if (!$student->meg) {$studentError = true; $studentErrorMsg .= "Row $row has no Mod. Evd. Grade<br/>";}
        $student->sc = $columns['SC'] ? $worksheet->getCellByColumnAndRow($columns['SC'], $row)->getValue() : '';
        $student->evidence = $columns['Evidence'] ? $worksheet->getCellByColumnAndRow($columns['Evidence'], $row)->getValue() : '';
        $student->rationale = $columns['Rationale'] ? $worksheet->getCellByColumnAndRow($columns['Rationale'], $row)->getValue() : '';
        // if (!$student->rationale) {$studentError = true; $studentErrorMsg .= "Row $row has no Rationale<br/>";}

      }

      if($studentError) {
        return emit($response, ['error' => true, 'msg' => $studentErrorMsg]);
      }

      $data['students'] = $students;
      $data['exam']->year = $students[0]->NCYear > 11 ? 13 : 11;

      return emit($response, $data);

    }

    public function initialUploadPost($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, $this->channel);

      $uploadedFile = $request->getUploadedFiles();

      // var_dump($uploadedFile['file']); return;
      $directory = FILESTORE_PATH . "dha/tags/temp/";
      $filename = moveUploadedFile($directory, $uploadedFile['file']);
      $data = [];
      return $this->parseSheet($response, $directory . $filename, $data);

      // return emit($response, $data);

    }


}
