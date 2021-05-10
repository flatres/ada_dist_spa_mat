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

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->adaData = $container->adaData;
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

      $this->parseSheet($response, $directory . $filename, $data);

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
        if (!$student->tag) {$studentError = true; $studentErrorMsg .= "Row $row has no Final Grade<br/>";}

        $student->meg = $worksheet->getCellByColumnAndRow($columns['MEG'], $row)->getValue();
        if (!$student->meg) {$studentError = true; $studentErrorMsg .= "Row $row has no Mod. Evd. Grade<br/>";}

        $student->evidence = $worksheet->getCellByColumnAndRow($columns['Evidence'], $row)->getValue();
        $student->rationale = $worksheet->getCellByColumnAndRow($columns['Rationale'], $row)->getValue();
        if (!$student->rationale) {$studentError = true; $studentErrorMsg .= "Row $row has no Rationale<br/>";}

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
      $fileName =
      $filename = moveUploadedFile($directory, $uploadedFile['file']);
      $data = [];

      return $this->parseSheet($response, $directory . $filename, $data);

    }


}
