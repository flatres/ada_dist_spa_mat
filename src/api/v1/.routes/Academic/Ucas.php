<?php
use Slim\Http\UploadedFile;
// parses GUY's fuckwit color coordinated sheet

/**
 * Description



 * Usage:

 */
namespace Academic;

class Ucas
{
    protected $container;
    private $console;
    private $channel = 'academic.alis.upload';

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->adaData = $container->adaData;
       // $this->isams = $container->isams;
       $this->mcCustom= $container->mcCustom;

    }

    // a very particular format used during the TAG process - CAREFFUL Prob don't use.
    public function ucasOffersUploadPost($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, $this->channel);

      $uploadedFile = $request->getUploadedFiles();

      // var_dump($uploadedFile['file']); return;
      $directory = FILESTORE_PATH . "uploads/";
      $filename = moveUploadedFile($directory, $uploadedFile['file']);

      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
      $reader->setReadDataOnly(false);
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
      $data['highestRow'] = $highestRow;
      $colors = [];

      for ($row = 2; $row < $highestRow; ++$row) {
        $rowData = [];
        $lastName = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        // $color = $worksheet->getStyle('B'. $row)->getFill()->getStartColor()->getRGB();
        $color = $worksheet->getCellByColumnAndRow(3, $row)->getStyle()->getFill()->getStartColor()->getARGB();
        // FFC000 - orange 92D050 - green
        $decision = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
       // if ($color == 'FFFFC000' || $color == 'FF92D050' || $decision == 'CF') {
       if ($decision == 'CF' || $decision == 'CI' || $color == 'FF92D050') {
         // is a highest offer
         $s= $this->getRow($worksheet, $row);
         $s->color = $color;
         if ($s->id) {
          $students[] = $s;
          // delete old ones. I know! Terrible way to do it. Blaming Dan
          $this->adaData->delete('ucas_offers', 'studentId=?', [$s->id]);
        } else {
          $errorStudents[] = $s;
        }
       }

       // $colors[] = $color;
        if (!isset($colors[$color])) $colors[$color] = $color;
      }
      // }
      $data['colors'] = $colors;
      $data['students'] = $students;

      foreach($students as $s) {
        // $this->adaData->delete('ucas_offers', 'studentId=?', [$s->id]);
        $this->adaData->insert(
          'ucas_offers',
          'studentId, uni, decision, status, grade1, grade2, grade3, grade4, details, predictions, entryYear',
          [
            $s->id,
            $s->uni,
            $s->decision,
            $s->status,
            $s->grade1,
            $s->grade2,
            $s->grade3,
            $s->grade4,
            $s->details,
            $s->predictions,
            $s->entryYear
          ]);
      }

      $data['unmatched'] = $errorStudents;
      return emit($response, $data);
    }

    private function getRow($worksheet, $row) {
      $s = (object)[];
      $s->id = null;
      $s->row = $row;
      $s->lastName = str_replace(' - ', '-', $worksheet->getCellByColumnAndRow(1, $row)->getValue());
      $s->firstName = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
      $s->house = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
      $s->uni = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
      $s->decision = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
      $s->status = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
      $s->grade1 = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
      $s->grade2 = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
      $s->grade3 = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
      $s->grade4 = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
      $s->details = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
      $s->predictions = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
      $s->entryYear = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
      // $s->points = $this->makePoints($s->offer, $s);
      $firstname = explode(' ', $s->firstName)[0];
      $bind = [
        $firstname,
        $firstname,
        $s->lastName
      ];
      //TODO:  should add DOB check!!
      $d = $this->ada->select('stu_details', "id", "(firstname LIKE ? OR prename LIKE ?) AND lastname LIKE ?", $bind);
      if (count($d) == 1) $s->id = $d[0]['id'];
      return $s;

    }

    private function makePoints($offer, &$s) {
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
        if (isset($this->totalGrades[$grade])) $this->totalGrades[$grade] += $c;
      }
      $s->ucas['counts'] = $grades;
      return $points;
    }

// ROUTE -----------------------------------------------------------------------------
    public function ucasGradesGet($request, $response, $args)
    {


      return emit($response, []);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }

}
