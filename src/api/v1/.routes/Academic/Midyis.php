<?php
use Slim\Http\UploadedFile;
/**
 * Description

 * Usage:

 */
namespace Academic;

class Midyis
{
    protected $container;
    private $console;
    private $channel = 'academic.midyis.upload';

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->adaData = $container->adaData;
    }

    public function midyisUploadPost($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, $this->channel);

      $uploadedFile = $request->getUploadedFiles();

      // var_dump($uploadedFile['file']); return;
      $directory = FILESTORE_PATH . "uploads/";
      $filename = moveUploadedFile($directory, $uploadedFile['file']);

      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
      $reader->setReadDataOnly(true);
      $spreadsheet = $reader->load($directory . $filename);
      $worksheet = $spreadsheet->getActiveSheet();
      $worksheetTitle     = $worksheet->getTitle();
      $highestRow         = $worksheet->getHighestRow(); // e.g. 10
      $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
      $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

      $data = [];
      $subjects = [];
      $students = [];
      $subjectCodes = new \Exams\Tools\SubjectCodes();
      $errorSubjects = [];
      $errorStudents = [];

      for ($col = 10; $col < $highestColumnIndex; ++$col) {
        $cell = $worksheet->getCellByColumnAndRow($col, 7);
        $value = $cell->getValue();
        if (explode('-', $value)[0] == 'A1') continue; //As Level
        $subjectCodes->txtOptionTitle = $value;
        $subjectCodes->level = 'GCSE';

        $codes = $subjectCodes->getCodes();
        $subject = [
          'name'  => $value,
          'column' => $col,
          'examCode' => $codes[0],
          'examName'  => $codes[1],
          'error' => $codes[0]=='-' ? true : false,
          'examId'  => $this->ada->select('sch_subjects_exams', 'id', 'examCode=?', [$codes[0]])[0]['id'] ?? null
        ];
        if ($subject['error']) $errorSubjects[] = $subject;
        $subjects['c_' . $col] = $subject;
      }


      for ($row = 8; $row < $highestRow; ++$row) {
        $rowData = [];
        $lastName = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        $lastName = str_replace(' *', '', $lastName);
        $firstName = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        $firstName = str_replace(' *', '', $firstName);
        $score = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        $band = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

        $s = $this->ada->select('stu_details', 'id', 'lastname=? AND firstname=?', [$lastName, $firstName]);
        $studentError = count($s) === 1 ? false : true;

        $id = $studentError ? null : $s[0]['id'];

        $student = [
          'firstName'  => $firstName,
          'lastName'   => $lastName,
          'band'  => $band,
          'score' => $score,
          'id'  => $id,
          'error' => $studentError,
          'exams' => []
        ];
        if ($student['error']) $errorStudents[] = $student;
        for ($col = 6; $col < $highestColumnIndex; ++$col) {
          $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
          if (strlen($value) > 0) {
            $key = 'c_' . $col;
            if (isset($subjects[$key])){
              $exam = $subjects[$key];
              $exam['prediction'] = $value;
              $student['exams'][] = (object)$exam;
            }
          }
          $rowData[] = $cell->getValue();
        }
        $students[] = (object)$student;
      }
      foreach ($students as $s){
        if (!$s->error){
          $id = $s->id;
          $score = $s->score;
          $band = $s->band;
          $this->adaData->delete('predictions_midyis', 'studentId=?', [$id]);
          foreach ($s->exams as $e) {
            if (!$e->examId) continue;
            $this->adaData->insert(
              'predictions_midyis',
              'studentId, examId, baseline, band, prediction',
              [$id, $e->examId, $score, $band, $e->prediction]
            );
          }
          $metrics = new \Entities\Metrics\Student($id);
          $metrics->setMidyisBand($band);
          $metrics->setMidyisScore($score);
        }
      }
      $data = ['subjects' => $subjects, 'students'  => $students, 'errorSubjects' => $errorSubjects, 'errorStudents' => $errorStudents];

      // }
      return emit($response, $data);
    }

// ROUTE -----------------------------------------------------------------------------
    public function alisRegistrationGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->console = new \Sockets\Console($auth);
      $sets = [];
      $year = 13;

      $this->console->publish("Greetings.");
      $this->console->publish("Fetching L6 set lists");

      //find sets and look up their subject name, making corrections on the way
      $s = $this->isams->select('TblTeachingManagerSets', 'TblTeachingManagerSetsID as id, intSubject, txtSetCode', 'intYear=?', [13]);
      $count = count($s);

      $this->console->publish("$count found");

      if($count === 0) return emit($response, []);

      $this->console->publish("Matching Sets to Subjects");

      foreach ($s as $set) {
        $subject = $this->isams->select('TblTeachingManagerSubjects', 'TblTeachingManagerSubjectsID as id, txtSubjectName, txtSubjectCode', 'TblTeachingManagerSubjectsID = ?', [$set['intSubject']]);
        if (!isset($subject[0])) continue;
        $subject = $subject[0];

        if (!$this->isAcademicSubject($subject['txtSubjectName'], $set['txtSetCode'])) continue;

        $isAlevel = $this->setSubjectToAda($subject);

        if (strpos($set['txtSetCode'], 'Ma/x') !== false || strpos($set['txtSetCode'], 'Ma/y') !== false) {
          $subject['txtSubjectName'] = 'Further Mathemetics';
        }

        if ($subject['txtSubjectCode'] == 'EN') $subject['txtSubjectName'] = 'Literature in English';
        $prefix = $isAlevel ? 'A2;' : 'PREUFC;';
        $subject['txtSubjectName'] = $prefix . $subject['txtSubjectName'];
        $sets['id_' . $set['id']] = array_merge($subject, $set);
        $this->console->publish("Set {$set['txtSetCode']} matched with {$subject['txtSubjectName']}");

      }
      $this->console->publish("Getting pupil sets and matching subjects");

      //get all year 12 pupils and look them up in set lists. If a new subject, add to their list of subjects
      $students = $this->isams->select(  'TblPupilManagementPupils',
                                            'txtSchoolID as id, txtForename, intFamily, intNCYear, txtSurname, txtGender, txtDOB',
                                            'intNCYear = ? AND intSystemStatus = 1 ORDER BY txtSurname ASC', [$year]);
      $count = count($students);
      $this->console->publish("$count found");
      $this->console->publish("Finding pupil subjects");

      foreach ($students as &$student) {
        $student['subjects'] = [];
        $studentSets = $this->isams->select( 'TblTeachingManagerSetLists', 'intSetID', 'txtSchoolID=?', [$student['id']]);

        $this->console->publish($student['txtSurname']);
        foreach ($studentSets as $set) {
          if (isset($sets['id_' . $set['intSetID']])) {
            $foundSet = $sets['id_' . $set['intSetID']];
            if (!isset($student['subjects'][$foundSet['txtSubjectCode']])) {
              $student['subjects'][$foundSet['txtSubjectCode']] = $foundSet['txtSubjectName'];
              //maths and FM under same set code so if in a FM set, also add maths
              if ($foundSet['txtSubjectName'] == 'Further Mathemetics') {
                $student['subjects']['MA2'] = 'Mathematics';
                $this->console->publish('   -- ' . 'Mathematics');
              }
              $this->console->publish('   -- ' . $foundSet['txtSubjectName']);
            }
          }
        }

        $adaStudent = new \Entities\People\Student();
        $adaStudent->byMISId($student['id']);
        $tag = new \Entities\Tags\Tag();
        $student['avgGcse'] = $tag->value('Metrics', 'GCSE Avg.', $adaStudent->id);

        // add white space to stop excel displaying it in scientific notation
        $student['id'] = '' . strval($student['id']) . ' ';
        $dob = strtotime($student['txtDOB']);
        $student['txtDOB'] = date('d/m/Y',$dob);

        $student['s1'] = '';
        $student['s2'] = '';
        $student['s3'] = '';
        $student['s4'] = '';
        $i = 1;
        foreach ($student['subjects'] as $sub) {
          $student["s$i"] = $sub;
          $i++;
        }
      }

      $columns = [
        [
          'field' => 'txtSurname',
          'label' => 'Surname'
        ],
        [
          'field' => 'txtForename',
          'label' => 'Forename'
        ],
        [
          'field' => 'txtDOB',
          'label' => 'DOB'
        ],
        [
          'field' => 'id',
          'label' => 'UPN'
        ],
        [
          'field' => 'txtGender',
          'label' => 'Sex'
        ],
        [
          'field' => 'avgGcse',
          'label' => 'Avg (I)GCSE'
        ],
        [
          'field' => 's1',
          'label' => 'Subject1'
        ],
        [
          'field' => 's2',
          'label' => 'Subject2'
        ],
        [
          'field' => 's3',
          'label' => 'Subject3'
        ],
        [
          'field' => 's4',
          'label' => 'Subject4'
        ]
      ];

      $settings = [
        'forceText' => true
      ];
      $this->console->publish("Generating Spreadsheet");
      $sheet = new \Utilities\Spreadsheet\SingleSheet($columns, $students, $settings);

      return emit($response, $sheet->package);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }

    private function isAcademicSubject($name, $code){

      switch ($name) {
        case 'EPQ' :
        case 'Creative Writing':
        case 'Learning Support' :
          return false;
      }

      if (strpos($code, '/G') !== false) return false; //GCSE Language
      if (strpos($code, '-Ja') !== false) return false; //GCSE Japanese
      if (strpos($code, '/DE') !== false) return false; //DELE
      if (strpos($code, '/DF') !== false) return false; //DELF
      if (strpos($code, 'Ma/mc') !== false) return false; //Maths in Contect
      return true;

    }

    private function setSubjectToAda($subject) {
      $s = $this->adaModules->select('academic_subjects', 'id, isAlevel', 'subjectCode=?', [$subject['txtSubjectCode']]);
      if (!isset($s[0])){
        $this->adaModules->insert('academic_subjects', 'id, subjectCode, subjectName', [
          $subject['id'],
          $subject['txtSubjectCode'],
          $subject['txtSubjectName']
        ]);
        return true;
      } else {
        return $s[0]['isAlevel'] == 1 ? true : false;
      }
    }

}
