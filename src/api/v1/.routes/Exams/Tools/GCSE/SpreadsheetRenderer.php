<?php
namespace Exams\Tools\GCSE;

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
  private $year;
  private $keyL = 'Grade Points: A* = 8.5, A = 7, B = 5.5, C = 4, D = 3, E = 2';
  private $keyN = 'Grade Points: 9 = 9, 8 = 8, 7 = 7, 6 = 6, 5 = 5, 4 = 4, 3 = 3, 2 = 2, 1 = 1';

  public function __construct($filename, $title, $type, array $session, \Sockets\Console $console, \Exams\Tools\GCSE\StatisticsGateway $statistics)
  {
    // return;
     // $this->sql= $sql;
    $this->console = $console;
    $this->console->publish("Generating spreadsheet");
    $this->statistics = $statistics;
    $this->session = $session;
    $this->year = (int)$session['year'];

    $this->spreadsheet = new Spreadsheet();

    //delete the default sheet
    $sheetIndex = $this->spreadsheet->getIndex($this->spreadsheet->getSheetByName('Worksheet'));
    $this->spreadsheet->removeSheetByIndex($sheetIndex);

    //set metadata
    $this->spreadsheet->getProperties()
                      ->setCreator("SD Flatres")
                      ->setLastModifiedBy("SD Flatres")
                      ->setTitle("$title 20" . $session['year'])
                      ->setSubject("$title 20" . $session['year'])
                      ->setDescription("$title 20" . $session['year'])
                      ->setKeywords("$title 20" . $session['year'])
                      ->setCategory("$title 20" . $session['year']);
    switch($type){
      case 'detailed':
      //generate the data sheets
      $this->makeOverview();
      $this->makeStudentsSheet('Hundred Stats', $this->statistics->hundredStats, false, true);
      // $this->makeHouseSheets();
      $this->makeHouseSummary($this->statistics->hundredStats->hasLetterGrades);
      $this->makeCombinedSubjects();
      $this->makeLetterSubjects();
      $this->makeNumericSubjects();
      $this->makeStudentsSheet('SSS', $this->statistics->hundredStats, true);
      $this->makeStudentsSheet('Other', $this->statistics->otherStats, false);
      $this->makeStudentsSheet('Shell', $this->statistics->shellStats, false);
      $this->makeStudentsSheet('Remove', $this->statistics->removeStats, false);
      $this->makeStudentsSheet('Hundred', $this->statistics->hundredStats, false);
      $this->makeSummary();

        break;
      case 'sss':
        $this->makeStudentsSheet('SSS', $this->statistics->hundredStats, true);
        break;
      case 'houseresults':
        $houses = $this->statistics->hundredStats->houseResults;
        krsort($houses);
        foreach($houses as $house){
          $this->makeHouseCandidates($house);
        };
        break;
      case 'subjectresults':
        $this->subjectResults();
        break;

    }


    // $this->makeSubjectSheets();



    //generate file path and save sheet

    $fileName = $filename . '_' . $session['month'] . $session['year'] . '_' . date('d-m-y_H-i-s',time()) . '.xlsx';
    $filePath = FILESTORE_PATH . "exams/gcse/$fileName";
    $url = FILESTORE_URL . "exams/gcse/$fileName";

    $this->writer = new Xlsx($this->spreadsheet);
    $this->writer->save($filePath);

    $this->path = $url;
    $this->filename = $fileName;

    //get rid

    return $this;
  }

  private function makeSummary()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Overview');
    $worksheet->getTabColor()->setRGB('008B00');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Overview');

    $sheet->setCellValue('A1', 'GCSE Results Overview 20' . $this->session['year']);
    $sheet->mergeCells('A1:J1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    //make columns
    // $subjectColumnIndex = array();
    // $fields = ['A Level', '%', 'Pre U', '%', 'Combined %'];
    //
    // $data = array();
    // $data[] = $fields;
    // $data = array_merge($data, $this->statistics->data->summaryData['ranges'] );
    //
    // $sheet->fromArray(
    //     $data,  // The data to set
    //     NULL,        // Array values with this value will not be set
    //     'A3'         // Top left coordinate of the worksheet range where
    // );

    $fields = ['Grade', '#Boys', '#Girls', '#Total'];
    $data = [$fields];

    krsort($this->statistics->hundredStats->gradeCounts);

    foreach($this->statistics->hundredStats->gradeCounts as $key => $grade)
    {
      $data[] = [
        is_numeric($key) ? '#' . $key : $key,
        $grade['boys'],
        $grade['girls'],
        $grade['all']
      ];
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

    foreach (range('A','L') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $history = $this->statistics->hundredStats->history;
    //find smallest years
    $smallest = 9999;
    foreach($history as $h){
      if ($h['year'] < $smallest) $smallest = $h['year'];
    }
    $data = [];

    $fields = [];
    $fields[] = '';
    for($x = $smallest; $x <= $this->year; $x++) {
      $fields[] = "20$x";
    }
    $data[] = $fields;

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'H3'         // Top left coordinate of the worksheet range where
    );
    /// isc stats

    // //candidate counts;
    // $students = $this->statistics->data->allStudents;
    // $countA = 0;
    // $countAB = 0;
    // $countAG = 0;
    // $countP = 0;
    // $countPB = 0;
    // $countPG = 0;
    // $threeOrMoreAstar = 0;
    // $threeOrMoreAstarEquiv = 0;
    // foreach($students as $student){
    //   if($student->NCYear !== 13) continue;
    //   $AstarCount = 0;
    //   $AstarEquivCount = 0;
    //   $isAL = false;
    //   $isALG = false;
    //   $isALB = false;
    //   $isPU = false;
    //   $isPUG = false;
    //   $isPUB = false;
    //   foreach($student->results as $result){
    //
    //     if($result->level === 'A') {
    //       $isAL = true;
    //       if($result->txtGender==='M') $isALB = true;
    //       if($result->txtGender==='F') $isALG = true;
    //       if($result->grade === 'A*') {
    //         $AstarCount++;
    //         $AstarEquivCount++;
    //       }
    //     }
    //     if($result->level === 'PreU') {
    //       $isPU = true;
    //       if($result->txtGender==='M') $isPUB = true;
    //       if($result->txtGender==='F') $isPUG = true;
    //       if($result->grade === 'D1' || $result->grade === 'D2') {
    //         $AstarEquivCount++;
    //       }
    //     }
    //   }
    //   if ($AstarCount > 2) $threeOrMoreAstar++;
    //   if ($AstarEquivCount > 2) $threeOrMoreAstarEquiv++;
    //   if ($isALB) $countAB++;
    //   if ($isALG) $countAG++;
    //   if ($isAL) $countA++;
    //   if ($isPUB) $countPB++;
    //   if ($isPUG) $countPG++;
    //   if ($isPU) $countP++;
    //
    // }
    //
    // $countAAB = 0;
    // $test = [];
    // //calculate how many have AAB or better (or equivalent). Take three best results and the qualify if the score is 26 or more
    // foreach($students as $student){
    //   //sort results by points
    //   if($student->NCYear !== 13) continue;
    //   //get only A and PRU results
    //   $results = [];
    //   $count = 0;
    //   $avg = 0;
    //   foreach($student->results as $result){
    //     if($result->level === 'A' || $result->level === 'PreU') {
    //       $avg = $avg + $result->points;
    //       $count++;
    //       $results[] = $result;
    //     }
    //   }
    //   if($count == 0) continue;
    //   $avg1 = round($avg / $count);
    //   $avg = round($avg / $count,2);
    //
    //   usort($results, array($this, "compareResults"));
    //
    //   $first = $results[0]->points ?? 0;
    //   $second = $results[1]->points ?? 0;
    //   $third = $results[2]->points ?? 0;
    //   $total = $first + $second + $third;
    //   if ($total > 25) {
    //     $countAAB++;
    //   }
    // }
    //
    //
    // $data = [];
    // $data[] = ['# A Level Candidates Boys', $countAB];
    // $data[] = ['# A Level Candidates Girls', $countAG];
    // $data[] = ['# A Level Candidates', $countA];
    // $data[] = ['> 2 A*', $threeOrMoreAstar];
    // $data[] = ['# Pre U Level Candidates Boys', $countPB];
    // $data[] = ['# Pre U  Level Candidates Girls', $countPG];
    // $data[] = ['# Pre U Level Candidates', $countP];
    // $data[] = ['>2 A* Equiv (D1, D2)', $threeOrMoreAstarEquiv];
    // $data[] = ['>AAB or Equiv', $countAAB];
    //
    // $sheet->fromArray(
    //     $data,  // The data to set
    //     NULL,        // Array values with this value will not be set
    //     'N3'         // Top left coordinate of the worksheet range where
    // );


    $sheet->getColumnDimension('N')->setAutoSize(true);

  }

  private function subjectResults(){
    $subjects =  $this->statistics->hundredStats->subjectResults;

    krsort($subjects);
    foreach($subjects as $key => $subject){
      $this->makeSubjectCandidates($subject, $key);
    };

  }

  private function makeSubjectCandidates($subject, $key)
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $key);
    $color = '';
    switch($subject->level) {
      case 'GCSE':
        $color = '50C878'; break;
    }
    $worksheet->getTabColor()->setRGB($color);
    $spreadsheet->addSheet($worksheet, 0);


    //sheet title
    $sheet = $spreadsheet->getSheetByName($key);

    $sheet->setCellValue('A1', $subject->subjectName . ' (' . $subject->boardName . ')');
    $sheet->mergeCells('A1:D1');
    $sheet->getRowDimension('1')->setRowHeight(50);


    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $worksheet->getStyle('A1:M300')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $styleArray = [
      'font' => [
          'size' => 10
      ]
    ];
    $sheet->getStyle('A1:M300')->applyFromArray($styleArray);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['Number', 'Name', 'UCI', 'Title', 'Module', 'Result', 'Grade'];

    $data = array();
    $data[] = $fields;
    usort($subject->results, array($this, "compareNames"));

    foreach($subject->results as $result){
      $space = $result->mark > 0 ? '/' : '';
      $data[] = [
        $result->txtCandidateNumber,
        $result->txtInitialedName,
        $result->txtCandidateCode,
        $result->title,
        $result->moduleCode,
        $result->grade,
        '',
        ltrim($result->mark, '0') . $space . ltrim($result->total, '0')

      ];
    }

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );


    $styleArray = [
      'font' => [
          'bold' => true
      ]
    ];
    $sheet->getStyle('A1:Z1')->applyFromArray($styleArray);

    foreach (range('A','Z') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
  }

  private function makeHouseCandidates($house)
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $house->txtHouseCode);
    $color = '';
    switch($house->genderType) {
      case 'mixed':
        $color = '50C878'; break;
      case 'boys':
        $color = '0D98BA'; break;
      case 'girls':
        $color = 'FF6600'; break;
    }
    $worksheet->getTabColor()->setRGB($color);
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName($house->txtHouseCode);

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $worksheet->getStyle('A1:M300')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $styleArray = [
      'font' => [
          'size' => 10
      ]
    ];
    $sheet->getStyle('A1:M300')->applyFromArray($styleArray);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['Surname', 'Forenames', 'Candidate #', 'UCI', 'Level', 'Code', 'Title', 'Result', 'Mark'];

    $data = array();
    $data[] = $fields;
    usort($house->students, array($this, "compareNames"));

    foreach($house->students as $student){
      $data[] = [
        $student->txtSurname,
        $student->txtForename,
        $student->txtCandidateNumber,
        $student->txtCandidateCode,
      ];
      foreach($student->results as $result){
        $space = $result->mark > 0 ? '/' : '';
        $data[] = [
          '',
          '',
          '',
          '',
          $result->level,
          $result->moduleCode,
          $result->txtSubjectName,
          $result->grade,
          ltrim($result->mark, '0') . $space . ltrim($result->total, '0')
        ];
      }
    }

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    );


    $styleArray = [
      'font' => [
          'bold' => true
      ]
    ];
    $sheet->getStyle('A1:Z1')->applyFromArray($styleArray);

    foreach (range('A','Z') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
  }

  //create the sheet which lists the hundred stats for each subject
  private function makeHouseSummary($hasLetters = true)
  {

    $spreadsheet = $this->spreadsheet;
    $name = 'Houses';
    $worksheet = new Worksheet($spreadsheet, $name );
    $worksheet->getTabColor()->setRGB('FFCC00');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName($name);
    $sheet->getDefaultColumnDimension()->setWidth(4);

    $sheet->setCellValue('A1', 'House Summary');
    $sheet->mergeCells('A1:I1');
    $sheet->getRowDimension('1')->setRowHeight(50);
    $sheet->getRowDimension('5')->setRowHeight(60);

    $data = array();
    //generate array to be placed in spreadsheet
    $fields = ['', '#', 'Rank', 'GA', 'Passes', '9', '8', '7', '6', '5', '4', '3', '2', '1', 'U'];
    if ($hasLetters) $fields = array_merge($fields, ['A', 'B', 'C', 'D', 'E']);
    $data[] = $fields;
    $data[] = []; //blank row
    $count = 0;
    foreach($this->statistics->hundredStats->houseResults as $s){
      $g = $s->gradeCounts;
      $sum = $s->summaryData;

      $count++;
      $values = [ $s->txtHouseCode,
                  $s->resultCount,
                  $s->position,
                  $sum['gradeAverage'],
                  $s->passes,
                  $g['#9'],
                  $g['#8'],
                  $g['#7'],
                  $g['#6'],
                  $g['#5'],
                  $g['#4'],
                  $g['#3'],
                  $g['#2'],
                  $g['#1'],
                  $g['U']
                ];
      if ($hasLetters) $values = array_merge($values, [
        $g['A*'],
        $g['A'],
        $g['B'],
        $g['C'],
        $g['D'],
        $g['E']
      ]);
      $data[] = $values;
    }
     $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A5'         // Top left coordinate of the worksheet range where
    );

    //styling

    $styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];
    $count = $count + 6;
    //border

    $sheet->getStyle('B5:E'. $count)->applyFromArray($styleArray);
    $sheet->getStyle('F5:O'. $count)->applyFromArray($styleArray);
    if ($hasLetters) $sheet->getStyle('P5:T'. $count)->applyFromArray($styleArray);

    $sheet->getStyle('E4:N4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('O4:Z4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


    $sheet->getColumnDimension('A')->setWidth(7);
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
    $sheet->getStyle('A4:AA5')->applyFromArray($styleArray);


    // rotate headers
    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'textRotation' => 90,
        'shrinkToFit' => false
      ]
    ];
    $sheet->getStyle('C5:E5')->applyFromArray($styleArray);


    //put key at the bottom
    // $bottomRow = count($this->statistics->hundredStats->subjectResults) + 3;
    $key = array();


  }

  //create the sheet which lists the hundred stats for each subject
  private function makeLetterSubjects()
  {


    $data = array();
    $year = $this->year;
    $lastYear =  $this->year - 1;
    $lastYear2 =  $this->year - 2;
    //generate array to be placed in spreadsheet
    $fields = ['Subject', 'Board', 'Entries', 'A*', 'A', 'B', 'C', 'D', 'E', 'U', '%A*', '%A*A','%A*AB', '%Pass', "Grd Avg ($year)", "Grd Avg ($lastYear)", "Grd Avg ($lastYear2)", '# Boys', '# Girls', 'Grd Avg Boys', 'Grd Avg Girls'];
    $data[] = $fields;
    $data[] = []; //blank row

    ksort($this->statistics->hundredStats->subjectResults);

    $count = 4;
    $hasSubjects = false;
    foreach($this->statistics->hundredStats->subjectResults as $s){
      if ($s->isNumeric) continue;
      $hasSubjects = true;
      $count++;
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
                  $sum['historyKeys']['y_' . $lastYear]['gradeAverage'] ?? '',
                  $sum['historyKeys']['y_' . $lastYear2]['gradeAverage'] ?? '',
                  $sum['boysCount'],
                  $sum['girlsCount'],
                  $sum['pointsAvgBoys'],
                  $sum['pointsAvgGirls']
                ];
      $data[] = $values;
    }

    if (!$hasSubjects) return;

    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Subjects (A-E)');
    $worksheet->getTabColor()->setRGB('FF0000');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Subjects (A-E)');
    $sheet->getDefaultColumnDimension()->setWidth(15);
    $sheet->setCellValue('A1', 'GCSE (A-E) Results 20' . $this->session['year']);
    $sheet->mergeCells('A1:G1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );


    $styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];
    //border
    $sheet->getStyle('A3:C' .$count)->applyFromArray($styleArray);
    $sheet->getStyle('K3:N'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('R3:S'.$count)->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'rotation' => 90,
      'startColor' => [
          'argb' => 'FFA0A0A0',
      ],
      'endColor' => [
          'argb' => 'FFA0A0A0',
      ],
    ];


    $sheet->getStyle('D3:J'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('O3:Q'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('T3:U'.$count)->applyFromArray($styleArray);

    $count++;
    //make totals and averages
    $lastRow = $count - 1;
    $dataRow = $count;
    $sheet->setCellValueByColumnAndRow(3, $dataRow, "=sum(C5:C$lastRow)");
    $sheet->setCellValueByColumnAndRow(4, $dataRow, "=sum(D5:D$lastRow)");
    $sheet->setCellValueByColumnAndRow(5, $dataRow, "=sum(E5:E$lastRow)");
    $sheet->setCellValueByColumnAndRow(6, $dataRow, "=sum(F5:F$lastRow)");
    $sheet->setCellValueByColumnAndRow(7, $dataRow, "=sum(G5:G$lastRow)");
    $sheet->setCellValueByColumnAndRow(8, $dataRow, "=sum(H5:H$lastRow)");
    $sheet->setCellValueByColumnAndRow(9, $dataRow, "=sum(I5:I$lastRow)");
    $sheet->setCellValueByColumnAndRow(10, $dataRow, "=sum(J5:J$lastRow)");

    $sheet->setCellValueByColumnAndRow(11, $dataRow, "=round(100*sum(D5:D$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(12, $dataRow, "=round(100*sum(D5:E$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(13, $dataRow, "=round(100*sum(D5:F$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(14, $dataRow, "=round(100*sum(D5:I$lastRow)/C$dataRow,1)");

    $sheet->setCellValueByColumnAndRow(15, $dataRow, "=round(average(O5:O$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(16, $dataRow, "=round(average(P5:P$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(17, $dataRow, "=round(average(Q5:Q$lastRow),1)");

    $sheet->setCellValueByColumnAndRow(18, $dataRow, "=sum(R5:R$lastRow)");
    $sheet->setCellValueByColumnAndRow(19, $dataRow, "=sum(S5:S$lastRow)");

    $sheet->setCellValueByColumnAndRow(20, $dataRow, "=round(average(T5:T$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(21, $dataRow, "=round(average(U5:U$lastRow),1)");


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
    $sheet->getStyle('A1:A50')->applyFromArray($styleArray);
    $sheet->getStyle('A3:AA3')->applyFromArray($styleArray);
    $sheet->getStyle('A3:AA3')->applyFromArray($styleArray);
    $sheet->getStyle("A$dataRow:AA$dataRow")->applyFromArray($styleArray);


    foreach (range('A','Z') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    // foreach (range('L','Z') as $col) {
    //   $sheet->getColumnDimension($col)->setWidth(4);
    // }
    $sheet->getColumnDimension('AA')->setWidth(4);

    $sheet->getRowDimension('3')->setRowHeight(95);

    // rotate headers
    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'textRotation' => 90,
        'shrinkToFit' => true
      ]
    ];
    $sheet->getStyle('K3:Z3')->applyFromArray($styleArray);

    //grade key
    $count = $count + 2;
    $sheet->setCellValue("A$count" , $this->keyL);
    $sheet->mergeCells("A$count:O$count");

  }

  private function makeNumericSubjects()
  {

    $data = array();
    $year = $this->year;
    $lastYear =  $this->year - 1;
    $lastYear2 =  $this->year - 2;
    //generate array to be placed in spreadsheet
    $fields = ['Subject', 'Board', 'Entries', '9', '8', '7', '6', '5', '4', '3', '2', '1', 'U', '%9', '%9-8', '%9-7','%9-6', '%9-5', '%9-4', '%9-3', '%9-2', '%9-1', "Grd Avg ($year)", "Grd Avg ($lastYear)", "Grd Avg ($lastYear2)", '# Boys', '# Girls', 'Grd Avg Boys', 'Grd Avg Girls'];
    $data[] = $fields;
    $data[] = []; //blank row

    ksort($this->statistics->hundredStats->subjectResults);

    $count = 4;
    $hasSubjects = false;
    foreach($this->statistics->hundredStats->subjectResults as $s){
      if (!$s->isNumeric) continue;
      $hasSubjects = true;
      $count++;
      $sum = $s->summaryData;
      $g = $s->gradeCounts;
      $values = [ $s->subjectName,
                  $s->boardName,
                  $sum['candidateCount'],
                  $g['#9'],
                  $g['#8'],
                  $g['#7'],
                  $g['#6'],
                  $g['#5'],
                  $g['#4'],
                  $g['#3'],
                  $g['#2'],
                  $g['#1'],
                  $g['U'],
                  $sum['%9'],
                  $sum['%98'],
                  $sum['%97'],
                  $sum['%96'],
                  $sum['%95'],
                  $sum['%94'],
                  $sum['%93'],
                  $sum['%92'],
                  $sum['%91'],
                  $sum['gradeAverage'],
                  $sum['historyKeys']['y_' . $lastYear]['gradeAverage'] ?? '',
                  $sum['historyKeys']['y_' . $lastYear2]['gradeAverage'] ?? '',
                  $sum['boysCount'],
                  $sum['girlsCount'],
                  $sum['pointsAvgBoys'],
                  $sum['pointsAvgGirls']
                ];
      $data[] = $values;
    }

    if (!$hasSubjects) return;

    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Subjects (9-1)');
    $worksheet->getTabColor()->setRGB('FF0000');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Subjects (9-1)');
    $sheet->getDefaultColumnDimension()->setWidth(15);
    $sheet->setCellValue('A1', 'GCSE (9-1) Results 20' . $this->session['year']);
    $sheet->mergeCells('A1:D1');
    $sheet->getRowDimension('1')->setRowHeight(50);
    $sheet->getRowDimension('3')->setRowHeight(80);

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );


    $styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];
    //border
    $sheet->getStyle('A3:C' .$count)->applyFromArray($styleArray);
    $sheet->getStyle('N3:W'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('Z3:AA'.$count)->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'rotation' => 90,
      'startColor' => [
          'argb' => 'FFA0A0A0',
      ],
      'endColor' => [
          'argb' => 'FFA0A0A0',
      ],
    ];


    $sheet->getStyle('D3:M'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('W3:Y'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('AB3:AC'.$count)->applyFromArray($styleArray);

    $count++;
    //make totals and averages
    $lastRow = $count - 1;
    $dataRow = $count;
    $sheet->setCellValueByColumnAndRow(3, $dataRow, "=sum(C5:C$lastRow)");
    $sheet->setCellValueByColumnAndRow(4, $dataRow, "=sum(D5:D$lastRow)");
    $sheet->setCellValueByColumnAndRow(5, $dataRow, "=sum(E5:E$lastRow)");
    $sheet->setCellValueByColumnAndRow(6, $dataRow, "=sum(F5:F$lastRow)");
    $sheet->setCellValueByColumnAndRow(7, $dataRow, "=sum(G5:G$lastRow)");
    $sheet->setCellValueByColumnAndRow(8, $dataRow, "=sum(H5:H$lastRow)");
    $sheet->setCellValueByColumnAndRow(9, $dataRow, "=sum(I5:I$lastRow)");
    $sheet->setCellValueByColumnAndRow(10, $dataRow, "=sum(J5:J$lastRow)");
    $sheet->setCellValueByColumnAndRow(11, $dataRow, "=sum(K5:K$lastRow)");
    $sheet->setCellValueByColumnAndRow(12, $dataRow, "=sum(L5:L$lastRow)");
    $sheet->setCellValueByColumnAndRow(13, $dataRow, "=sum(M5:M$lastRow)");

    $sheet->setCellValueByColumnAndRow(14, $dataRow, "=round(100*sum(D5:D$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(15, $dataRow, "=round(100*sum(D5:E$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(16, $dataRow, "=round(100*sum(D5:F$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(17, $dataRow, "=round(100*sum(D5:G$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(18, $dataRow, "=round(100*sum(D5:L$lastRow)/C$dataRow,1)");

    $sheet->setCellValueByColumnAndRow(19, $dataRow, "=round(average(S5:S$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(20, $dataRow, "=round(average(T5:T$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(21, $dataRow, "=round(average(U5:U$lastRow),1)");

    $sheet->setCellValueByColumnAndRow(22, $dataRow, "=sum(V5:V$lastRow)");
    $sheet->setCellValueByColumnAndRow(23, $dataRow, "=sum(W5:W$lastRow)");

    $sheet->setCellValueByColumnAndRow(24, $dataRow, "=round(average(X5:X$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(25, $dataRow, "=round(average(Y5:Y$lastRow),1)");


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
    $sheet->getStyle('A1:A50')->applyFromArray($styleArray);
    $sheet->getStyle('A3:AA3')->applyFromArray($styleArray);
    $sheet->getStyle('A3:AA3')->applyFromArray($styleArray);
    $sheet->getStyle("A$dataRow:AA$dataRow")->applyFromArray($styleArray);


    foreach (range('A','Z') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    // foreach (range('L','Z') as $col) {
    //   $sheet->getColumnDimension($col)->setWidth(4);
    // }
    $sheet->getColumnDimension('AA')->setWidth(4);

    $sheet->getRowDimension('3')->setRowHeight(95);

    // rotate headers
    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'textRotation' => 90,
        'shrinkToFit' => true
      ]
    ];
    $sheet->getStyle('N3:AD3')->applyFromArray($styleArray);

    //grade key
    $count = $count + 2;
    $sheet->setCellValue("A$count" , $this->keyN);
    $sheet->mergeCells("A$count:O$count");

  }


  private function makeOverview()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Stats');
    $worksheet->getTabColor()->setRGB('008B00');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Stats');

    $sheet->setCellValue('A1', 'GCSE Results Misc Stats 20' . $this->session['year']);
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


  private function makeStudentsSheet(string $title, $yearStats, $isSSS = false, $detailed = false)
  {
    if(!$yearStats) return;

    $subjectsOnly = [];

    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $title);
    $color = $isSSS ? '7F7FFF': '0000FF';
    $worksheet->getTabColor()->setRGB($color);
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName($title);
    $subjects = $yearStats->subjectNames;

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $styleArray = [
      'font' => [
          'size' => 8
      ]
    ];
    $sheet->getStyle('A1:BZ300')->applyFromArray($styleArray);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['Name', 'Gender', 'House', 'Yr', "#", "Grade. Avg"];
    $usedSubjects = [];
    $columnIndex = 3;

    $subjects = [];
    $students = $yearStats->allStudents;
    //get relevant students and subjects
    foreach($yearStats->allStudents as $student){
      foreach($student->results as $result) {
          $subjects[$result->subjectCode] = $result->txtSubjectName;
      }
    }

    $data = array();

    //load students
    usort($students, array($this, "compareNames"));

    ksort($subjects);
    foreach($subjects as $key => $subject){
      $fields[] = $subject;
      $subjectsOnly[$key] = $subject;
    }

    if ($detailed) {
      $fields = array_merge($fields, [
                              '#1',
                              '#2',
                              '#3',
                              '#4',
                              '#5',
                              '#6',
                              '#7',
                              '#8',
                              '#9',
                              '#9-8',
                              '#9-7',
                              '#9-6',
                              '#9-5',
                              '#9-4',
                              '#9-3',
                              '#9-2',
                              '#9-1',
                              'A*',
                              'A',
                              'B',
                              'C',
                              'D',
                              'E',
                              '#9-8, A*',
                              '#9-7, A*-A',
                              '#9-5, A*-B',
                              '#9-4, A*-C',
                              '#9-3, A*-D',
                              '#9-1, A*-E'
                            ]);
    }

    $data[] = $fields;
    $data[] = []; //blank row for filters

    foreach($students as $student){
      $d = [  $student->txtInitialedName,
              $student->txtGender,
              $student->txtHouseCode,
              $student->NCYear,
              $student->resultCount,
              $student->gradeAverage
            ];
      $count = 0;
      $points = 0;
      foreach($subjects as $key => $subject){
          if(isset($student->{$key})){
            $count++;
            $points += $student->subjects[$key]->points ?? 0;
            $d[] = $isSSS ? $student->subjects[$key]->surplus : $student->{$key};
          } else {
            $d[] = null;
          }
      }
      $d[4] = $count;
      $d[5] = $count == 0 ? 0 : round($points / $count, 1);

      $g = $student->gradeCounts;
      if ($detailed){
        $d = array_merge($d, [
            $g['#1'],
            $g['#2'],
            $g['#3'],
            $g['#4'],
            $g['#5'],
            $g['#6'],
            $g['#7'],
            $g['#8'],
            $g['#9'],
            $g['#9'] + $g['#8'],
            $g['#9'] + $g['#8'] + $g['#7'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['#2'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['#2'] + $g['#1'],
            $g['A*'],
            $g['A'],
            $g['B'],
            $g['C'],
            $g['D'],
            $g['E'],
            $g['#9'] + $g['#8'] + $g['A*'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['A*'] + $g['A'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['A*'] + $g['A'] + $g['B'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['A*'] + $g['A'] + $g['B'] + + $g['C'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['A*'] + $g['A'] + $g['B'] + $g['C'] + $g['D'],
            $g['#9'] + $g['#8'] + $g['#7'] + $g['#6'] + $g['#5'] + $g['#4'] + $g['#3'] + $g['#2'] + $g['#1'] + $g['A*'] + $g['A'] + $g['B'] + $g['C'] + $g['D'] + $g['E'],
        ]);
      }
      $data[] = $d;
    }

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    );


    $d = [];
    foreach($subjectsOnly as $key => $subject){
          $d[] = $key;
    }
    $lastRow = count($students) + 2;
    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($fields));
    $sheet->setAutoFilter("A2:$col".$lastRow);
    $lastRow++;
    $sheet->fromArray(
        $d,  // The data to set
        NULL,        // Array values with this value will not be set
        'G' . $lastRow        // Top left coordinate of the worksheet range where
    );

    //make totals and averages
    $lastRow = count($students) + 3;
    $dataRow = $lastRow + 1;

    // $sheet->setCellValueByColumnAndRow(5, $dataRow, "=count(A2:A$lastRow)");
    $sheet->setCellValueByColumnAndRow(6, $dataRow, "=Round(Average(F2:F$lastRow),2)");

    $subjectCount = 7; //first column containing a subject
    foreach($subjects as $subject){
        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($subjectCount);
        if ($isSSS) {
          $sheet->setCellValueByColumnAndRow($subjectCount, $dataRow, "=round(average(".$col."2:$col$lastRow),2)");
        } else {
            $sheet->setCellValueByColumnAndRow($subjectCount, $dataRow, "=countA(".$col."2:$col$lastRow)");
        }
        $subjectCount++;
    }

    for ($x = 0; $x <= 28; $x++) {
      $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($subjectCount + $x);
      if ($detailed) $sheet->setCellValueByColumnAndRow($subjectCount + $x, $dataRow, "=sum(".$col."2:$col$lastRow)");
    }


    $sheet->getRowDimension('1')->setRowHeight(100);
    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $sheet->getStyle('A1:E1')->applyFromArray($styleArray);
    $sheet->getStyle("A$dataRow:BZ$dataRow")->applyFromArray($styleArray);

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
    // $sheet->freezePane('G' . $count);

    //set filters
    // $spreadsheet->getActiveSheet()->setAutoFilter('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow());

  }


  private function compareNames($a, $b){
     return strcmp($a->txtInitialedName, $b->txtInitialedName);
  }



  private function getPointsFromGrade($grade)
  {
    $result = new \Exams\Tools\GCSE\Result();
    return $result->processGrade($grade);
  }

  private function makeCombinedSubjects()
  {

    $data = array();
    $year = $this->year;
    $lastYear =  $this->year - 1;
    $lastYear2 =  $this->year - 2;
    //generate array to be placed in spreadsheet

    //first row
    $data[] = [
      '',
      'A* Equivalent',
      '',
      '',
      'A Equivalent',
      '',
      'B Equivalent',
      '',
      '',
      'C Equivalent',
      '',
      'D Equivalent',
      '',
      'E Equivalent',
      ''
    ];
    //second row
    $data[] = [
      'New Points:',
      9,
      8.5,
      8,
      7,
      7,
      6,
      5.5,
      5,
      4,
      4,
      3,
      3,
      2
    ];
    $data[] = [
      'Raw Grade',
      9,
      'A*',
      8,
      'A',
      7,
      6,
      'B',
      5,
      'C',
      4,
      'D',
      3,
      'E',
      'Total'
    ];
    $data[] = []; //blank row

    ksort($this->statistics->hundredStats->subjectResults);

    $count = 6;
    $hasSubjects = false;
    foreach($this->statistics->hundredStats->subjectResults as $s){

      $sum = $s->summaryData;
      $g = $s->gradeCounts;
      $values = [
        $s->subjectName,
        $g['#9'],
        $g['A*'],
        $g['#8'],
        $g['A'],
        $g['#7'],
        $g['#6'],
        $g['B'],
        $g['#5'],
        $g['C'],
        $g['#4'],
        $g['D'],
        $g['#3'],
        $g['E'],
        "=SUM(B$count:N$count)"
      ];
      $count++;
      $data[] = $values;
    }


    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Subjects (Combined)');
    $worksheet->getTabColor()->setRGB('FF0000');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Subjects (Combined)');
    $sheet->getDefaultColumnDimension()->setWidth(15);
    $sheet->setCellValue('A1', 'GCSE Results (Combined Data) 20' . $this->session['year']);
    $sheet->mergeCells('A1:G1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );

    $sheet->mergeCells('B3:D3');
    $sheet->mergeCells('E3:F3');
    $sheet->mergeCells('G3:I3');
    $sheet->mergeCells('J3:K3');
    $sheet->mergeCells('L3:M3');
    $sheet->mergeCells('N3:O3');

    $styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];
    //border
    $sheet->getStyle('E3:F' .$count)->applyFromArray($styleArray);
    $sheet->getStyle('J3:K'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('N3:O'.$count)->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'rotation' => 90,
      'startColor' => [
          'argb' => 'FFA0A0A0',
      ],
      'endColor' => [
          'argb' => 'FFA0A0A0',
      ],
    ];


    $sheet->getStyle('B3:D'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('G3:I'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('L3:M'.$count)->applyFromArray($styleArray);

    $sheet->getRowDimension('3')->setRowHeight(80);

    $count++;
    //make totals and averages
    $lastRow = $count - 1;
    $dataRow = $count;
    $sheet->setCellValueByColumnAndRow(2, $dataRow, "=sum(B7:B$lastRow)");
    $sheet->setCellValueByColumnAndRow(3, $dataRow, "=sum(C7:C$lastRow)");
    $sheet->setCellValueByColumnAndRow(4, $dataRow, "=sum(D7:D$lastRow)");
    $sheet->setCellValueByColumnAndRow(5, $dataRow, "=sum(E7:E$lastRow)");
    $sheet->setCellValueByColumnAndRow(6, $dataRow, "=sum(F7:F$lastRow)");
    $sheet->setCellValueByColumnAndRow(7, $dataRow, "=sum(G7:G$lastRow)");
    $sheet->setCellValueByColumnAndRow(8, $dataRow, "=sum(H7:H$lastRow)");
    $sheet->setCellValueByColumnAndRow(9, $dataRow, "=sum(I7:I$lastRow)");
    $sheet->setCellValueByColumnAndRow(10, $dataRow, "=sum(J7:J$lastRow)");
    $sheet->setCellValueByColumnAndRow(11, $dataRow, "=sum(K7:K$lastRow)");
    $sheet->setCellValueByColumnAndRow(12, $dataRow, "=sum(L7:L$lastRow)");
    $sheet->setCellValueByColumnAndRow(13, $dataRow, "=sum(M7:M$lastRow)");
    $sheet->setCellValueByColumnAndRow(14, $dataRow, "=sum(N7:N$lastRow)");
    //



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
    $dataRow = $count+1;
    $sheet->getStyle('A1:A50')->applyFromArray($styleArray);
    $sheet->getStyle('A3:O5')->applyFromArray($styleArray);
    $sheet->getStyle("A$dataRow:O$dataRow")->applyFromArray($styleArray);
    $sheet->getStyle("A$dataRow:AA$dataRow")->applyFromArray($styleArray);

    foreach (range('A','Z') as $col) {
      $sheet->getColumnDimension($col)->setWidth(8);;
    }
    // foreach (range('L','Z') as $col) {
    //   $sheet->getColumnDimension($col)->setWidth(4);
    // }
    $sheet->getColumnDimension('AA')->setWidth(4);

    $sheet->getRowDimension('3')->setRowHeight(95);

    $styleArray = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        ]
    ];

    $sheet->getStyle('B4:O'.$dataRow)->applyFromArray($styleArray);

    $styleArray = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ]
    ];

    $sheet->getStyle('B3:O3')->applyFromArray($styleArray);



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
