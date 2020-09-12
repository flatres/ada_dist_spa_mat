<?php
namespace Exams\Tools\ALevel;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet as Worksheet;

class CemSpreadsheetRenderer
{
  public $path = '';
  private $statistics;
  private $spreadsheet;
  private $session;
  private $writer;

  public function __construct(array $session, \Sockets\Console $console, \Exams\Tools\ALevel\StatisticsGateway $statistics)
  {
    // return;
     // $this->sql= $sql;
    $this->console = $console;
    $this->console->publish("Generating CEM spreadsheet");
    $this->statistics = $statistics;
    $this->session = $session;


    $this->spreadsheet = new Spreadsheet();

    //delete the default sheet
    $sheetIndex = $this->spreadsheet->getIndex($this->spreadsheet->getSheetByName('Worksheet'));
    $this->spreadsheet->removeSheetByIndex($sheetIndex);

    //set metadata
    $this->spreadsheet->getProperties()
                      ->setCreator("SD Flatres")
                      ->setLastModifiedBy("SD Flatres")
                      ->setTitle("A Level Results 20" . $session['year'])
                      ->setSubject("A Level Results 20" . $session['year'])
                      ->setDescription("A Level Results 20" . $session['year'])
                      ->setKeywords("A Level Results 20" . $session['year'])
                      ->setCategory("A Level Results 20" . $session['year']);


    $this->makeStudentsSheet('U6_', $this->statistics->data);

    $this->year = (int)$session['year'];

    $fileName = 'CEM_ALevel_Results_' . $session['month'] . $session['year'] . '.xlsx';
    $filePath = FILESTORE_PATH . "exams/alevel/$fileName";
    $url = FILESTORE_URL . "exams/alevel/$fileName";

    $this->writer = new Xlsx($this->spreadsheet);
    $this->writer->save($filePath);

    $this->path = $url;
    $this->filename = $fileName;

    //get rid

    return $this;
  }


  private function makeStudentsSheet(string $title, $yearStats)
  {
    if(!$yearStats) return;

    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $title);
    $worksheet->getTabColor()->setRGB('0000FF');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName($title);
    $subjects = $yearStats->subjectResults;

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['Forename', 'Surname', 'Date Of Birth', 'Gender', 'UPN', 'Level', 'Subject', 'Board', 'Option Code', 'Result'];
    $columnIndex = 3;

    $data = array();
    $data[] = $fields;

    //load students
    $students = $yearStats->allStudents;
    usort($students, array($this, "compareNames"));

    foreach($students as $student){
      $d = [  $student->txtForename,
              $student->txtSurname,
              date("d/m/Y",strtotime($student->txtDOB)),
              $student->txtGender,
              '="' . $student->txtSchoolID . '"'
            ];
      // $data[] = $d;

      if ($student->NCYear !== 13 ) continue;

      foreach($student->results as $result) {
        $r = [
          $result->level,
          $result->txtSubjectName,
          $result->boardName,
          $result->moduleCode,
          $result->grade
        ];
        $data[] = array_merge($d, $r);
      }

    }
    $sheet->getStyle('A1:AZ1000')
          ->getNumberFormat()
          ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    // return;
    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    );

    $sheet->getRowDimension('1')->setRowHeight(100);
    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];


  }

  private function compareNames($a, $b){
     return strcmp($a->txtInitialedName, $b->txtInitialedName);
  }


  private function getPointsFromGrade($grade)
  {
    $result = new \Exams\Tools\GCSE\Result();
    return $result->processGrade($grade);
  }

}

// $styleArray = [
//     'font' => [
//         'bold' => true,
//     ],
//     'alignment' => [
//         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
//     ],
//     'borders' => [
//         'top' => [
//             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//         ],
//     ],
//     'fill' => [
//         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
//         'rotation' => 90,
//         'startColor' => [
//             'argb' => 'FFA0A0A0',
//         ],
//         'endColor' => [
//             'argb' => 'FFFFFFFF',
//         ],
//     ],
// ];
//
// $spreadsheet->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);

// { name: 'subjectName', required: true, label: 'Subject', align: 'left', field: 'subjectName', sortable: true },
// { name: 'boardName', required: true, label: 'Board', align: 'left', field: 'boardName', sortable: true },
// { name: 'position', label: 'Position', field: 'position', sortable: true },
// { name: 'candidates', label: 'Candidates', field: row => row.summaryData['candidateCount'], sortable: true },
// { name: 'gradeAverage', label: 'Grade Avg.', field: row => row.summaryData['gradeAverage'], sortable: true },
// { name: 'ABs', label: '% AB', field: row => row.summaryData['%ABs'], sortable: true },
// { name: 'passes', label: 'Passes', field: 'passes', sortable: true, align: 'right' },
// { name: '%pass', label: '% Pass ', field: row => row.summaryData['%passRate'], sortable: true, align: 'left' },
// { name: 'fails', label: 'Fails', field: 'fails', sortable: true },
// { name: 'astar', label: 'A*', field: row => row.gradeCounts['A*'], sortable: true },
// { name: 'a', label: 'A', field: row => row.gradeCounts['A'], sortable: true },
// { name: 'b', label: 'B', field: row => row.gradeCounts['B'], sortable: true },
// { name: 'c', label: 'C', field: row => row.gradeCounts['C'], sortable: true },
// { name: 'd', label: 'D', field: row => row.gradeCounts['D'], sortable: true },
// { name: 'e', label: 'E', field: row => row.gradeCounts['E'], sortable: true },
// { name: 'u', label: 'U', field: row => row.gradeCounts['U'], sortable: true }
