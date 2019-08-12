<?php
namespace Exams\Tools\ALevel;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet as Worksheet;

class SpreadsheetRenderer
{
  public $path = '';
  private $statistics;
  private $spreadsheet;
  private $session;
  private $writer;

  public function __construct(array $session, \Sockets\Console $console, \Exams\Tools\Alevel\StatisticsGateway $statistics)
  {
    // return;
     // $this->sql= $sql;
    $this->console = $console;
    $this->console->publish("Generating basic spreadsheet");
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

    //generate the data sheets

    $this->makeStudentsSheet('Other Years', $this->statistics->data, false);
    $this->makeStudentsSheet('U6 Results', $this->statistics->data);
    $this->makePreUSubjects();
    $this->makeALevelSubjects();
    // $this->makeHouseSheets();
    //
    // $this->makeOverview();


    //generate file path and save sheet

    $fileName = 'Alevel_Results_' . $session['month'] . $session['year'] . '_' . date('d-m-y-H-i-s',time()) . '.xlsx';
    $filePath = FILESTORE_PATH . "exams/alevel/$fileName";
    $url = FILESTORE_URL . "exams/alevel/$fileName";

    $this->writer = new Xlsx($this->spreadsheet);
    $this->writer->save($filePath);

    $this->path = $url;
    $this->filename = $fileName;

    //get rid

    return $this;
  }

  private function makeOverview()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Overview');
    $worksheet->getTabColor()->setRGB('008B00');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Overview');

    $sheet->setCellValue('A1', 'GCSE Results Overview 20' . $this->session['year']);
    $sheet->mergeCells('A1:F1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['', 'Boys', '%', 'Girls', '%', 'Total', '%'];

    $data = array();
    $data[] = $fields;

    foreach($this->statistics->hundredStats->summaryData as $e){
      $d = [  $e['desc'],
              $e['M_val'],
              isset($e['M_val%']) ? $e['M_val%'] : null,
              $e['F_val'],
              isset($e['F_val%']) ? $e['F_val%'] : null,
              $e['total_val'],
              isset($e['total_val%']) ? $e['total_val%'] : null
      ];
      $data[] = $d;
    }
    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );

    $styleArray = [
      'font' => [
          'bold' => true,
          'size' => 18
      ]
    ];
    $sheet->getStyle('A1')->applyFromArray($styleArray);

    foreach (range('A','G') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
  }

  private function makeStudentsSheet(string $title, $yearStats, $isYear13 = true)
  {
    if(!$yearStats) return;

    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $title);
    $worksheet->getTabColor()->setRGB('0000FF');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName($title);
    $subjects = $yearStats->subjectNames;

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['Name', 'Gender', 'House', 'Yr', "#", "Grade. Avg"];
    $columnIndex = 3;

    foreach($subjects as $key => $subject){
        $fields[] = $subject;
    }
    $data = array();
    $data[] = $fields;

    //load students
    $students = $yearStats->allStudents;
    usort($students, array($this, "compareNames"));

    foreach($students as $student){
      if ($isYear13) {
        if ($student->NCYear !== 13) continue;
      } else {
        if ($student->NCYear === 13) continue;
      }

      $d = [  $student->txtInitialedName,
              $student->txtGender,
              $student->txtHouseCode,
              $student->NCYear,
              $student->resultCount,
              $student->gradeAverage
            ];
      foreach($subjects as $key => $subject){
          if(isset($student->{$key})){
            $d[] = $student->{$key};
          } else {
            $d[] = null;
          }
      }
      $data[] = $d;
    }

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
    $sheet->getStyle('A1:E1')->applyFromArray($styleArray);

    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'textRotation' => 90,
        'shrinkToFit' => true
      ]
    ];
    $sheet->getStyle('B1:ZZ1')->applyFromArray($styleArray);
    $count = count($data) + 1;
    $sheet->freezePane('F' . $count);

    //set filters
    $spreadsheet->getActiveSheet()->setAutoFilter('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow());

  }

  private function compareNames($a, $b){
     return strcmp($a->txtInitialedName, $b->txtInitialedName);
  }

  //create the sheet which lists the hundred stats for each subject
  private function makeHouseSheets()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Houses');
    $worksheet->getTabColor()->setRGB('FFCC00');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Houses');
    $sheet->getDefaultColumnDimension()->setWidth(15);

    $data = array();
    //generate array to be placed in spreadsheet
    $fields = ['House', 'Position', 'Grade Avg.', 'Passes', 'Fails' , '> 6 As (9-7)',  'A*', 'A', 'B', 'C', 'D', 'E', 'U', '9', '8', '7', '6', '5', '4', '3', '2', '1' ];
    $data[] = $fields;
    $data[] = []; //blank row

    foreach($this->statistics->hundredStats->houseResults as $s){
      $g = $s->gradeCounts;
      $sum = $s->summaryData;

      $values = [ $s->txtHouseCode,
                  $s->position,
                  $sum['gradeAverage'],
                  $s->passes,
                  $s->fails,
                  $sum['sixOrMoreAs'],
                  $g['A*'],
                  $g['A'],
                  $g['B'],
                  $g['C'],
                  $g['D'],
                  $g['E'],
                  $g['U'],
                  $g['#9'],
                  $g['#8'],
                  $g['#7'],
                  $g['#6'],
                  $g['#5'],
                  $g['#4'],
                  $g['#3'],
                  $g['#2'],
                  $g['#1']
                ];
      $data[] = $values;
    }
     $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    );

    //styling
    $sheet->getColumnDimension('A')->setAutoSize(true);
    //the title
    $styleArray = [
      'font' => [
          'bold' => true,
          'size' => 18
      ]
    ];
    $sheet->getStyle('A1')->applyFromArray($styleArray);
    //headers
    $styleArray = [
      'font' => [
          'bold' => true,
        ]
    ];
    $sheet->getStyle('A1:A1000')->applyFromArray($styleArray);
    $sheet->getStyle('A3:AA3')->applyFromArray($styleArray);

    foreach (range('A','E') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    foreach (range('F','Z') as $col) {
      $sheet->getColumnDimension($col)->setWidth(4);
    }
    $sheet->getColumnDimension('AA')->setWidth(4);

    //put key at the bottom
    $bottomRow = count($this->statistics->hundredStats->subjectResults) + 3;
    $key = array();


  }

  //create the sheet which lists the hundred stats for each subject
  private function makeALevelSubjects()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'U6 Headlines (A)');
    $worksheet->getTabColor()->setRGB('FF0000');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('U6 Headlines (A)');
    $sheet->getDefaultColumnDimension()->setWidth(15);
    $sheet->setCellValue('A1', 'A Level Results 20' . $this->session['year']);
    $sheet->mergeCells('A1:D1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    $data = array();
    //generate array to be placed in spreadsheet
    $fields = ['Subject', 'Board', 'Entries', 'A*', 'A', 'B', 'C', 'D', 'E', 'U', '%A*', '%A*A','%A*AB', '%Pass', 'Grd Avg', 'UCAS Avg'];
    $data[] = $fields;
    $data[] = []; //blank row

    foreach($this->statistics->data->subjectResults['A'] as $s){
      $sum = $s->summaryData;
      $g = $s->gradeCounts;
      $values = [ $s->subjectName,
                  $s->boardName,
                  $sum['candidateCount'],
                  $g['A*'],
                  $g['A'],
                  $g['B'],
                  $g['C'],
                  $g['D'],
                  $g['E'],
                  $g['U'],
                  $sum['%Astar'],
                  $sum['%As'],
                  $sum['%ABs'],
                  $sum['%passRate'],
                  $sum['gradeAverage'],
                  $sum['ucasAverage']
                ];
      $data[] = $values;
    }
    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );

    //styling
    $sheet->getColumnDimension('A')->setAutoSize(true);
    //the title
    $styleArray = [
      'font' => [
          'bold' => true,
          'size' => 18
      ]
    ];
    $sheet->getStyle('A1')->applyFromArray($styleArray);
    //headers
    $styleArray = [
      'font' => [
          'bold' => true,
        ]
    ];
    $sheet->getStyle('A1:A1000')->applyFromArray($styleArray);
    $sheet->getStyle('A3:AA3')->applyFromArray($styleArray);

    foreach (range('A','L') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    foreach (range('L','Z') as $col) {
      $sheet->getColumnDimension($col)->setWidth(4);
    }
    $sheet->getColumnDimension('AA')->setWidth(4);

    // //put key at the bottom
    // $bottomRow = count($this->statistics->hundredStats->subjectResults) + 6;
    // $keyString = '';
    //
    // $keyString .= 'A*' . ' = ' . $this->getPointsFromGrade('A*') . ', ';
    // foreach (range('A','E') as $grade) {
    //   $keyString .= $grade . ' = ' . $this->getPointsFromGrade($grade) . ', ';
    // }
    // foreach (range(9,1) as $grade) {
    //   $keyString .= $grade . ' = ' . $this->getPointsFromGrade($grade) . ', ';
    // }
    //
    // $sheet->mergeCells('A' . $bottomRow . ':XX' . $bottomRow);
    // $sheet->setCellValue('A' . $bottomRow, $keyString);
    // $bottomRow++;
    // // $sheet->setCellValue('A' . $bottomRow, "X");
    // $sheet->setCellValue('A' . $bottomRow, 'Average Subject Points = ' . $this->statistics->hundredStats->weightedAvgSubjectPoints);

  }

  //create the sheet which lists the hundred stats for each subject
  private function makePreUSubjects()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'U6 Headlines (Pre U)');
    $worksheet->getTabColor()->setRGB('FF0000');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('U6 Headlines (Pre U)');
    $sheet->getDefaultColumnDimension()->setWidth(15);
    $sheet->setCellValue('A1', 'A Level Results 20' . $this->session['year']);
    $sheet->mergeCells('A1:D1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    $data = array();
    //generate array to be placed in spreadsheet
    $fields = ['Subject', 'Board', 'Entries', 'D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3', 'U', 'Grd Avg', 'UCAS Avg'];
    $data[] = $fields;
    $data[] = []; //blank row

    foreach($this->statistics->data->subjectResults['PreU'] as $s){
      $sum = $s->summaryData;
      $g = $s->gradeCounts;
      $values = [ $s->subjectName,
                  $s->boardName,
                  $sum['candidateCount'],
                  $g['D1'],
                  $g['D2'],
                  $g['D3'],
                  $g['M1'],
                  $g['M2'],
                  $g['M3'],
                  $g['P1'],
                  $g['P2'],
                  $g['P3'],
                  $g['U'],
                  $sum['gradeAverage'],
                  $sum['ucasAverage']
                ];
      $data[] = $values;
    }
    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );

    //styling
    $sheet->getColumnDimension('A')->setAutoSize(true);
    //the title
    $styleArray = [
      'font' => [
          'bold' => true,
          'size' => 18
      ]
    ];
    $sheet->getStyle('A1')->applyFromArray($styleArray);
    //headers
    $styleArray = [
      'font' => [
          'bold' => true,
        ]
    ];
    $sheet->getStyle('A1:A1000')->applyFromArray($styleArray);
    $sheet->getStyle('A3:AA3')->applyFromArray($styleArray);

    foreach (range('A','L') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    foreach (range('L','Z') as $col) {
      $sheet->getColumnDimension($col)->setWidth(4);
    }
    $sheet->getColumnDimension('AA')->setWidth(4);

    // //put key at the bottom
    // $bottomRow = count($this->statistics->hundredStats->subjectResults) + 6;
    // $keyString = '';
    //
    // $keyString .= 'A*' . ' = ' . $this->getPointsFromGrade('A*') . ', ';
    // foreach (range('A','E') as $grade) {
    //   $keyString .= $grade . ' = ' . $this->getPointsFromGrade($grade) . ', ';
    // }
    // foreach (range(9,1) as $grade) {
    //   $keyString .= $grade . ' = ' . $this->getPointsFromGrade($grade) . ', ';
    // }
    //
    // $sheet->mergeCells('A' . $bottomRow . ':XX' . $bottomRow);
    // $sheet->setCellValue('A' . $bottomRow, $keyString);
    // $bottomRow++;
    // // $sheet->setCellValue('A' . $bottomRow, "X");
    // $sheet->setCellValue('A' . $bottomRow, 'Average Subject Points = ' . $this->statistics->hundredStats->weightedAvgSubjectPoints);

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
