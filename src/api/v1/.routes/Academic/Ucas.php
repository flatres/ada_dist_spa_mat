<?php
use Slim\Http\UploadedFile;
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
       if ($color == 'FFC000X' || $color == 'FF92D050' || $decision == 'CF') {
         // is a highest offer
         $s= $this->getRow($worksheet, $row);
         $s->color = $color;
         if ($s->id) {
          $students[] = $s;
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
        $this->adaData->delete('ucas_offers', 'studentId=?', [$s->id]);
        $this->adaData->insert(
          'ucas_offers',
          'studentId, uni, course, decision, status, offer, details',
          [
            $s->id,
            $s->uni,
            $s->course,
            $s->decision,
            $s->status,
            $s->offer,
            $s->details
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
      $s->course = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
      $s->decision = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
      $s->status = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
      $s->offer = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
      $s->details = $worksheet->getCellByColumnAndRow(10, $row)->getValue();

      $firstname = explode(' ', $s->firstName)[0];
      $bind = [
        $firstname,
        $firstname,
        $s->lastName
      ];
      $d = $this->ada->select('stu_details', "id", "(firstname LIKE ? OR prename LIKE ?) AND lastname LIKE ?", $bind);
      if (count($d) == 1) $s->id = $d[0]['id'];
      return $s;

    }

// ROUTE -----------------------------------------------------------------------------
    public function ucasGradesGet($request, $response, $args)
    {


      return emit($response, []);
      // return emit($response, $this->adaModules->select('TABLE', '*'));
    }

}
