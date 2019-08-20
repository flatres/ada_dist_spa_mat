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
  private $year;
  private $keyA = 'For Grade Average A*=12, A = 10, B = 8, C = 6, D = 4, E = 2';
  private $keyP = 'For UCAS tariff A*=56, A=48, B=40, C=32, D=24, E=16';
  private $keyAPreU = 'For Grade Average D1=12, D2=12, D3=11, M1=9, M2=8, M3=7, P1=5, P2=4, P3=3';
  private $keyPPreU = 'For new UCAS tariff D1=56, D2=56, D3=52, M1=44, M2=40, M3=36, P1=28, P2=24, P3=20';

  public function __construct($filename, $title, $type,  array $session, \Sockets\Console $console, \Exams\Tools\Alevel\StatisticsGateway $statistics)
  {
    // return;
     // $this->sql= $sql;
    $this->console = $console;
    $this->console->publish("Generating basic spreadsheet");
    $this->statistics = $statistics;
    $this->session = $session;
    $this->filename = $filename;



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

    //generate the data sheets

    $this->year = (int)$session['year'];

    switch($type){
      case 'detailed':
        $this->makeGenderComparison();
        $this->makeHouseSummary('boys');
        $this->makeHouseSummary('girls');
        $this->makeHouseSummary('all');
        $this->makeALevelRange('candidateCount', 'Candidates');
        $this->makeALevelRange('ucasAverage', 'UCAS Points');
        $this->makeALevelRange('pointsAvg', 'GA');
        $this->makeALevelRange('%ABCDEs', '%Pass');
        $this->makeALevelRange('%ABs', '%AB Grades');
        $this->makeALevelRange('%Astar');
        // $this->subjectResults(true);
        $this->makeEPQ(12);
        $this->makeEPQ(13);
        $this->makePreUSubjects();
        $this->makeALevelSubjects();
        // string $title, $yearStats, $isYear13 = true, $isSSS = false, $isAS = false
        $this->makeStudentsSheet('AS Results', $this->statistics->data, true, false, true);
        $this->makeStudentsSheet('Other Years', $this->statistics->data, false);
        $this->makeStudentsSheet('U6 Results SSS', $this->statistics->data, true, true);
        $this->makeStudentsSheet('U6 Results', $this->statistics->data);
        $this->makeOverview();

        break;
      case 'sss':
        $this->makeStudentsSheet('U6 Results SSS', $this->statistics->data, true, true);
        $this->makeStudentsSheet('U6 Results', $this->statistics->data);
        break;
      case 'results':
        $this->makeEPQ(12);
        $this->makeEPQ(13);
        $this->makePreUSubjects();
        $this->makeALevelSubjects();
        $this->makeStudentsSheet('Other Years', $this->statistics->data, false);
        // $this->makeStudentsSheet('U6 Results SSS', $this->statistics->data, true, true);
        $this->makeStudentsSheet('U6 Results', $this->statistics->data);
        $this->makeOverview();
        break;
      case 'houseresults':
        $houses = $this->statistics->data->houseResults;
        krsort($houses);
        foreach($houses as $house){
          $this->makeHouseCandidates($house);
        };
        break;
      case 'subjectresults':
        $this->subjectResults();
        break;

    }

    // $this->makeHouseSheets();
    //



    //generate file path and save sheet

    $fileName = $filename . '_' . $session['month'] . $session['year'] . '_' . date('d-m-y_H-i-s',time()) . '.xlsx';
    $filePath = FILESTORE_PATH . "exams/alevel/$fileName";
    $url = FILESTORE_URL . "exams/alevel/$fileName";

    $this->writer = new Xlsx($this->spreadsheet);
    $this->writer->save($filePath);

    $this->path = $url;
    $this->filename = $fileName;

    //get rid

    $this->spreadsheet = null;

    return $this;
  }

  private function subjectResults($ASLevel = false){
    $subjects = [];

    if ($ASLevel){
      $subjectAS = $this->statistics->data->subjectResults['AS'];
      foreach($subjectAS as $key => $s){
        $subjects[$key . ' (AS)'] = $s;
      }
    } else {

      $subjectA = $this->statistics->data->subjectResults['A'];
      foreach($subjectA as $key => $s){
        $subjects[$key . ' (A)'] = $s;
      }
      $subjectAS = $this->statistics->data->subjectResults['AS'];
      foreach($subjectAS as $key => $s){
        $subjects[$key . ' (AS)'] = $s;
      }
      $subjectP = $this->statistics->data->subjectResults['PreU'];
      foreach($subjectP as $key => $s){
        $subjects[$key . ' (PU)'] = $s;
      }
      $subjectE = $this->statistics->data->subjectResults['EPQ'];
      foreach($subjectE as $key => $s){
        $subjects['EPQ'] = $s;
      }
    }

    krsort($subjects);
    foreach($subjects as $key => $subject){
      $this->makeSubjectCandidates($subject, $key);
    };

  }
  private function makeEPQ($year)
  {
    $spreadsheet = $this->spreadsheet;
    $name = $year == '13' ? 'EPQ U6' : 'EPQ L6';
    $worksheet = new Worksheet($spreadsheet, $name);
    $color = '50C878';
    $worksheet->getTabColor()->setRGB($color);
    $spreadsheet->addSheet($worksheet, 0);


    //sheet title
    $sheet = $spreadsheet->getSheetByName($name);

    $sheet->setCellValue('A1', $name);
    $sheet->mergeCells('A1:D1');
    $sheet->getRowDimension('1')->setRowHeight(50);


    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    $worksheet->getStyle('A1:M1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $styleArray = [
      'font' => [
          'size' => 10
      ]
    ];
    $sheet->getStyle('A1:M1000')->applyFromArray($styleArray);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['Name', 'Year', 'House', 'Result'];

    $data = array();
    $data[] = $fields;
    $epq =  $this->statistics->data->subjectResults['EPQ']['EPQ']->results;
    usort($epq, array($this, "compareNames"));

    foreach($epq as $result){
      if ($result->NCYear == $year){
        $data[] = [
          $result->txtInitialedName,
          $result->NCYear,
          $result->txtHouseCode,
          $result->grade
        ];
      }
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

    foreach (range('A','D') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
  }

  private function makeSubjectCandidates($subject, $key)
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $key);
    $color = '';
    switch($subject->level) {
      case 'A':
        $color = '50C878'; break;
      case 'EPQ':
        $color = '0D98BA'; break;
      case 'AS':
        $color = 'FF6600'; break;
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
    $fields = ['Number', 'Name', 'UCI', 'Title', 'Module', 'Result', 'Grade', 'Mark'];

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

    foreach($subject->modules as $module)
    {
      $data[] = [];
      foreach($module->results as $result){
        $space = $result['mark'] > 0 ? '/' : '';
        $data[] = [
          '',
          $result['txtInitialedName'] ?? 'error',
          '',
          $result['txtOptionTitle'],
          $result['txtModuleCode'],
          '',
          $result['grade'],
          ltrim($result['mark'], '0') . $space . ltrim($result['total'], '0')

        ];
      }
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
    $fields = ['Surname', 'Forenames', 'Candidate #', 'UCI', 'Level', 'Code', 'Title', 'Result', 'Mark', 'Grade'];

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
      foreach($student->modules as $subject){
        foreach ($subject as $result){
          $space = $result['mark'] > 0 ? '/' : '';
          $data[] = [
            '',
            '',
            '',
            '',
            $result['level'],
            $result['txtModuleCode'],
            $result['txtOptionTitle'],
            '',
            ltrim($result['mark'], '0') . $space . ltrim($result['total'], '0'),
            $result['grade'],
          ];
        }
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

  private function makeOverview()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Overview');
    $worksheet->getTabColor()->setRGB('008B00');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Overview');

    $sheet->setCellValue('A1', 'U6 Results Overview 20' . $this->session['year']);
    $sheet->mergeCells('A1:F1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(4);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['A Level', '%', 'Pre U', '%', 'Combined %'];

    $data = array();
    $data[] = $fields;
    $data = array_merge($data, $this->statistics->data->summaryData['ranges'] );

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
    );

    $fields = ['Grade', '#Boys', '#Girls', '#Total'];
    $data = [$fields];

    foreach($this->statistics->data->summaryData['gradeCounts'] as $key => $grade)
    {
      $data[] = [
        $key,
        $grade['boys'],
        $grade['girls'],
        $grade['all']
      ];
    }

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'H3'         // Top left coordinate of the worksheet range where
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

    /// isc stats

    //candidate counts;
    $students = $this->statistics->data->allStudents;
    $countA = 0;
    $countAB = 0;
    $countAG = 0;
    $countP = 0;
    $countPB = 0;
    $countPG = 0;
    $threeOrMoreAstar = 0;
    $threeOrMoreAstarEquiv = 0;
    foreach($students as $student){
      if($student->NCYear !== 13) continue;
      $AstarCount = 0;
      $AstarEquivCount = 0;
      $isAL = false;
      $isALG = false;
      $isALB = false;
      $isPU = false;
      $isPUG = false;
      $isPUB = false;
      foreach($student->results as $result){

        if($result->level === 'A') {
          $isAL = true;
          if($result->txtGender==='M') $isALB = true;
          if($result->txtGender==='F') $isALG = true;
          if($result->grade === 'A*') {
            $AstarCount++;
            $AstarEquivCount++;
          }
        }
        if($result->level === 'PreU') {
          $isPU = true;
          if($result->txtGender==='M') $isPUB = true;
          if($result->txtGender==='F') $isPUG = true;
          if($result->grade === 'D1' || $result->grade === 'D2') {
            $AstarEquivCount++;
          }
        }
      }
      if ($AstarCount > 2) $threeOrMoreAstar++;
      if ($AstarEquivCount > 2) $threeOrMoreAstarEquiv++;
      if ($isALB) $countAB++;
      if ($isALG) $countAG++;
      if ($isAL) $countA++;
      if ($isPUB) $countPB++;
      if ($isPUG) $countPG++;
      if ($isPU) $countP++;

    }

    $countAAB = 0;
    $test = [];
    //calculate how many have AAB or better (or equivalent). Take three best results and the qualify if the score is 26 or more
    foreach($students as $student){
      //sort results by points
      if($student->NCYear !== 13) continue;
      //get only A and PRU results
      $results = [];
      $count = 0;
      $avg = 0;
      foreach($student->results as $result){
        if($result->level === 'A' || $result->level === 'PreU') {
          $avg = $avg + $result->points;
          $count++;
          $results[] = $result;
        }
      }
      if($count == 0) continue;
      $avg1 = round($avg / $count);
      $avg = round($avg / $count,2);

      usort($results, array($this, "compareResults"));

      $first = $results[0]->points ?? 0;
      $second = $results[1]->points ?? 0;
      $third = $results[2]->points ?? 0;
      $total = $first + $second + $third;
      if ($total > 25) {
        $countAAB++;
      }
    }


    $data = [];
    $data[] = ['# A Level Candidates Boys', $countAB];
    $data[] = ['# A Level Candidates Girls', $countAG];
    $data[] = ['# A Level Candidates', $countA];
    $data[] = ['> 2 A*', $threeOrMoreAstar];
    $data[] = ['# Pre U Level Candidates Boys', $countPB];
    $data[] = ['# Pre U  Level Candidates Girls', $countPG];
    $data[] = ['# Pre U Level Candidates', $countP];
    $data[] = ['>2 A* Equiv (D1, D2)', $threeOrMoreAstarEquiv];
    $data[] = ['>AAB or Equiv', $countAAB];

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'N3'         // Top left coordinate of the worksheet range where
    );


    $sheet->getColumnDimension('N')->setAutoSize(true);

  }

  private function compareResults($a, $b){
     return $a->points < $b->points;
  }

  private function makeGenderComparison()
  {
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, 'Gender & Intake');
    $worksheet->getTabColor()->setRGB('FFA500');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName('Gender & Intake');

    $sheet->setCellValue('A1', 'Gender / Intake Comparison 20' . $this->session['year']);
    $sheet->mergeCells('A1:L1');
    $sheet->getRowDimension('1')->setRowHeight(50);
    $sheet->getRowDimension('3')->setRowHeight(110);

    //column widths
    $sheet->getDefaultColumnDimension()->setWidth(6);
    $sheet->getColumnDimension('A')->setAutoSize(true);

    //make columns
    $subjectColumnIndex = array();
    $fields = ['', 'A*', 'A', 'B', 'C', 'D', 'E', 'U', 'D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3', '', '%A* equiv', '%AA* equiv', '%Pass equiv', 'Grade Avg', 'UCAS Avg'];

    $data = array();
    $data[] = $fields;
    $data = array_merge($data, $this->statistics->data->summaryData['gender']);

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

    $styleArray = [
      'font' => [
          'bold' => true
      ]
    ];

    $sheet->getStyle('A3:W3')->applyFromArray($styleArray);
    $sheet->getStyle('A3:A12')->applyFromArray($styleArray);

    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'textRotation' => 90,
        'shrinkToFit' => false
      ]
    ];
    $sheet->getStyle('R3:W3')->applyFromArray($styleArray);

    // foreach (range('A','L') as $col) {
    //   $sheet->getColumnDimension($col)->setAutoSize(true);
    // }
  }

  private function makeStudentsSheet(string $title, $yearStats, $isYear13 = true, $isSSS = false, $isAS = false)
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

    $styleArray = [
      'font' => [
          'size' => 8
      ]
    ];
    $sheet->getStyle('A1:AU300')->applyFromArray($styleArray);

    //can't get the dupication to work.
    // if ($isSSS) {  //set colour less than 0 to red
    //   $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    //   $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    //   $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
    //   $conditional1->addCondition('0');
    //   $conditional1->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
    //   $conditional1->getStyle()->getFont()->setBold(false);
    //
    //   $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    //   $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    //   $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHANOREQUAL);
    //   $conditional2->addCondition('0');
    //   $conditional2->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
    //   $conditional2->getStyle()->getFont()->setBold(false);
    //
    //   $conditionalStyles = $sheet->getStyle('G2')->getConditionalStyles();
    //   $conditionalStyles[] = $conditional1;
    //   $conditionalStyles[] = $conditional2;
    //
    //   $sheet->getStyle('G2')->setConditionalStyles($conditionalStyles);
    //
    //   $worksheet->duplicateStyle($sheet->getStyle('G2'),'H3');
    //
    // }

    //make columns
    $subjectColumnIndex = array();
    $fields = ['Name', 'Gender', 'House', 'Yr', "#", "Grade. Avg"];
    $usedSubjects = [];
    $columnIndex = 3;

    $level1 = $isAS ? 'AS' : 'A';
    $level2 = $isAS ? 'AS' : 'PreU';

    $subjects = [];
    $students = [];
    //get relevant students and subjects
    foreach($yearStats->allStudents as $student){
      // $gA = 0l
      if ($isYear13 && $student->NCYear !== 13 ) continue;
      if (!$isYear13 && $student->NCYear === 13) continue;
      $hasAS = false;
      $hasA2 = false;
      foreach($student->results as $result) {
        if ($result->level === 'A' || $result->level == 'PreU' || $result->level === 'EarlyA' || $result->level === 'EarlyP' || $result->level === 'LateA' || $result->level === 'LateP') {
          $hasA2 = true;
          if (!$isAS) $subjects[$result->subjectCode] = $result->txtSubjectName;
        }
        if ($result->level === 'AS') {
          $hasAS = true;
          if ($isAS) $subjects[$result->subjectCode] = $result->txtSubjectName;
        }
      }
      if($isAS && $hasAS) $students[] = $student;
      if(!$isAS && $hasA2) $students[] = $student;
    }

    $data = array();

    //load students
    usort($students, array($this, "compareNames"));

    ksort($subjects);
    foreach($subjects as $key => $subject){
      $fields[] = $subject;
    }
    $data[] = $fields;

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
            if ($isAS && $student->subjects[$key]->level !== 'AS') continue;
            if (!$isAS && $student->subjects[$key]->level !== 'A' && $student->subjects[$key]->level !== 'PreU' && $student->subjects[$key]->level !== 'EarlyA' && $student->subjects[$key]->level !== 'EarlyP') continue;

            $count++;
            $points += $student->subjects[$key]->points;
            $d[] = $isSSS ? $student->subjects[$key]->surplus : $student->{$key};
          } else {
            $d[] = null;
          }
      }
      $d[4] = $count;
      $d[5] = $count == 0 ? 0 : round($points / $count, 1);
      $data[] = $d;
    }

    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    );

    //make totals and averages
    $lastRow = count($students) + 1;
    $dataRow = $lastRow + 1;
    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(6, $dataRow, "=Round(Average(F2:F$lastRow),2)");

    $subjectCount = 7; //first column containing a subject
    foreach($subjects as $subject){
        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($subjectCount);
        if ($isSSS) {
          $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($subjectCount, $dataRow, "=round(average(".$col."2:$col$lastRow),2)");
        } else {
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($subjectCount, $dataRow, "=countA(".$col."2:$col$lastRow)");
        }
        $subjectCount++;
    }

    $sheet->getRowDimension('1')->setRowHeight(100);
    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $sheet->getStyle('A1:E1')->applyFromArray($styleArray);
    $sheet->getStyle("A$dataRow:AZ$dataRow")->applyFromArray($styleArray);

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

  //create the sheet which lists the hundred stats for each subject
  private function makeHouseSummary($gender)
  {
    $spreadsheet = $this->spreadsheet;
    $name = 'Houses - ' . $gender;
    $worksheet = new Worksheet($spreadsheet, $name );
    $worksheet->getTabColor()->setRGB('FFCC00');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName($name);
    $sheet->getDefaultColumnDimension()->setWidth(4);

    $sheet->setCellValue('A1', 'House Summary - ' . $gender);
    $sheet->mergeCells('A1:I1');
    $sheet->getRowDimension('1')->setRowHeight(50);
    $sheet->getRowDimension('5')->setRowHeight(60);

    $sheet->setCellValue('E4', 'A Level');
    $sheet->mergeCells('E4:N4');

    $sheet->setCellValue('O4', 'Pre U');
    $sheet->mergeCells('O4:Z4');

    $data = array();
    //generate array to be placed in spreadsheet
    $fields = ['', '#', 'Rank', 'GA', 'Rank', 'GA', 'Passes', 'A*', 'A', 'B', 'C', 'D', 'E', 'U', 'Rank', 'GA', 'Passes', 'D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3'];
    $data[] = $fields;
    $data[] = []; //blank row
    $count = 0;
    foreach($this->statistics->data->houseResults as $s){
      $g = $s->gradeCounts;
      $sum = $s->summaryData;
      $a = $sum['data']['A'];
      $p = $sum['data']['PreU'];
      $aGC = $a['all'][$gender]['gradeCounts'];
      $pGC = $p['all'][$gender]['gradeCounts'];

      if ($a['all'][$gender]['results'] + $p['all'][$gender]['results'] == 0) continue;
      $count++;
      $values = [ $s->txtHouseCode,
                  $a['all'][$gender]['results'],
                  $sum['data']['U6']['all'][$gender]['position'],
                  $sum['data']['U6']['all'][$gender]['pointsAvg'],
                  $a['all'][$gender]['position'],
                  $a['all'][$gender]['pointsAvg'],
                  // $a['NL6'][$gender]['pointsAvg'],
                  $a['all'][$gender]['passes'],
                  $aGC['A*'],
                  $aGC['A'],
                  $aGC['B'],
                  $aGC['C'],
                  $aGC['D'],
                  $aGC['E'],
                  $aGC['U'],
                  $p['all'][$gender]['position'],
                  $p['all'][$gender]['pointsAvg'],
                  // $p['NL6'][$gender]['pointsAvg'],
                  $p['all'][$gender]['passes'],
                  $pGC['D1'],
                  $pGC['D2'],
                  $pGC['D3'],
                  $pGC['M1'],
                  $pGC['M2'],
                  $pGC['M3'],
                  $pGC['P1'],
                  $pGC['P2'],
                  $pGC['P3']
                ];
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
    $sheet->getStyle('E4:N4')->applyFromArray($styleArray);
    $sheet->getStyle('O4:Z4')->applyFromArray($styleArray);

    $sheet->getStyle('B5:D'. $count)->applyFromArray($styleArray);
    $sheet->getStyle('E5:N'. $count)->applyFromArray($styleArray);
    $sheet->getStyle('O5:Z'. $count)->applyFromArray($styleArray);

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

    // foreach (range('A','AA') as $col) {
    //   $sheet->getColumnDimension($col)->setAutoSize(true);
    // }

    // foreach (range('B','G') as $col) {
    //   $sheet->getColumnDimension($col)->setWidth(18);
    // }
    // foreach (range('O','R') as $col) {
    //   $sheet->getColumnDimension($col)->setWidth(18);
    // }
    //
    // //grades
    // foreach (range('F','N') as $col) {
    //   $sheet->getColumnDimension($col)->setWidth(3);
    // }
    // foreach (range('S','AA') as $col) {
    //   $sheet->getColumnDimension($col)->setWidth(3);
    // }
    // $sheet->getColumnDimension('AA')->setWidth(4);

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
    $sheet->getStyle('C5:G5')->applyFromArray($styleArray);
    $sheet->getStyle('O5:Q5')->applyFromArray($styleArray);

    //put key at the bottom
    // $bottomRow = count($this->statistics->hundredStats->subjectResults) + 3;
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
    $year = $this->year;
    $lastYear =  $this->year - 1;
    $lastYear2 =  $this->year - 2;
    //generate array to be placed in spreadsheet
    $fields = ['Subject', 'Board', 'Entries', 'A*', 'A', 'B', 'C', 'D', 'E', 'U', '%A*', '%A*A','%A*AB', '%Pass', "Grd Avg ($year)", "Grd Avg ($lastYear)", "Grd Avg ($lastYear2)", "UCAS Avg ($year)", "UCAS Avg ($lastYear)", "UCAS Avg ($lastYear2)", '# Boys', '# Girls', 'Grd Avg Boys', 'Grd Avg Girls', 'UCAS Avg Boys', 'UCAS Avg Girls'];
    $data[] = $fields;
    $data[] = []; //blank row

    ksort($this->statistics->data->subjectResults['A']);

    $count = 4;
    foreach($this->statistics->data->subjectResults['A'] as $s){
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
                  $sum['ucasAverage'],
                  $sum['historyKeys']['y_' . $lastYear]['ucasAverage'] ?? '',
                  $sum['historyKeys']['y_' . $lastYear2]['ucasAverage'] ?? '',
                  $sum['boysCount'],
                  $sum['girlsCount'],
                  $sum['pointsAvgBoys'],
                  $sum['pointsAvgGirls'],
                  $sum['ucasAvgBoys'],
                  $sum['ucasAvgGirls']
                ];
      $data[] = $values;
    }
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
    $sheet->getStyle('R3:T'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('W3:X'.$count)->applyFromArray($styleArray);

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
    $sheet->getStyle('U3:V'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('Y3:Z'.$count)->applyFromArray($styleArray);

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
    $sheet->setCellValueByColumnAndRow(18, $dataRow, "=round(average(R5:R$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(19, $dataRow, "=round(average(S5:S$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(20, $dataRow, "=round(average(T5:T$lastRow),1)");

    $sheet->setCellValueByColumnAndRow(21, $dataRow, "=sum(U5:U$lastRow)");
    $sheet->setCellValueByColumnAndRow(22, $dataRow, "=sum(V5:V$lastRow)");

    $sheet->setCellValueByColumnAndRow(23, $dataRow, "=round(average(W5:W$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(24, $dataRow, "=round(average(X5:X$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(25, $dataRow, "=round(average(Y5:Y$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(26, $dataRow, "=round(average(Z5:Z$lastRow),1)");

    //totals at bottom of sheet
    $t = $this->statistics->data->summaryData['totals']['A'];
    $totals = [
      $t['entries'],
      $t['A*'],
      $t['A'],
      $t['B'],
      $t['C'],
      $t['D'],
      $t['E'],
      $t['U'],
      $t['%A*'],
      $t['%A*A'],
      $t['%AB'],
      $t['%Pass']
    ];
    $count++;
    // $sheet->fromArray(
    //     $totals,  // The data to set
    //     NULL,        // Array values with this value will not be set
    //     "C$count"         // Top left coordinate of the worksheet range where
    // );


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
    $sheet->setCellValue("A$count" , $this->keyA);
    $sheet->mergeCells("A$count:O$count");

    $count = $count + 1;
    $sheet->setCellValue("A$count", $this->keyP);
    $sheet->mergeCells("A$count:O$count");
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
    $sheet->setCellValue('A1', 'Pre U Results 20' . $this->session['year']);
    $sheet->mergeCells('A1:D1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    $data = array();
    $year = $this->year;
    $lastYear =  $this->year - 1;
    $lastYear2 =  $this->year - 2;
    //generate array to be placed in spreadsheet
    $fields = ['Subject', 'Board', 'Entries', 'D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3', 'U', '%D', '%M', '%P', "Grd Avg ($year)", "Grd Avg ($lastYear)", "Grd Avg ($lastYear2)", "UCAS Avg ($year)", "UCAS Avg ($lastYear)", "UCAS Avg ($lastYear2)",  '# Boys', '# Girls', 'Grd Avg Boys', 'Grd Avg Girls', 'UCAS Avg Boys', 'UCAS Avg Girls'];
    $data[] = $fields;
    $data[] = []; //blank row
    $count = 4;

    ksort($this->statistics->data->subjectResults['PreU']);

    foreach($this->statistics->data->subjectResults['PreU'] as $s){
      $count++;
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
                  $sum['%D'],
                  $sum['%M'],
                  $sum['%P'],
                  $sum['gradeAverage'],
                  $sum['historyKeys']['y_' . $lastYear]['gradeAverage'] ?? '',
                  $sum['historyKeys']['y_' . $lastYear2]['gradeAverage'] ?? '',
                  $sum['ucasAverage'],
                  $sum['historyKeys']['y_' . $lastYear]['ucasAverage'] ?? '',
                  $sum['historyKeys']['y_' . $lastYear2]['ucasAverage'] ?? '',
                  $sum['boysCount'],
                  $sum['girlsCount'],
                  $sum['pointsAvgBoys'],
                  $sum['pointsAvgGirls'],
                  $sum['ucasAvgBoys'],
                  $sum['ucasAvgGirls']
                ];
      $data[] = $values;
    }
    $sheet->fromArray(
        $data,  // The data to set
        NULL,        // Array values with this value will not be set
        'A3'         // Top left coordinate of the worksheet range where
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
    //border
    $sheet->getStyle('A3:C' .$count)->applyFromArray($styleArray);
    $sheet->getStyle('N3:P'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('T3:V'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('Y3:Z'.$count)->applyFromArray($styleArray);

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
    $sheet->getStyle('Q3:S'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('W3:X'.$count)->applyFromArray($styleArray);
    $sheet->getStyle('AA3:AB'.$count)->applyFromArray($styleArray);

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

    $sheet->setCellValueByColumnAndRow(14, $dataRow, "=round(100*sum(D5:F$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(15, $dataRow, "=round(100*sum(D5:I$lastRow)/C$dataRow,1)");
    $sheet->setCellValueByColumnAndRow(16, $dataRow, "=round(100*sum(D5:L$lastRow)/C$dataRow,1)");

    $sheet->setCellValueByColumnAndRow(17, $dataRow, "=round(average(Q5:Q$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(18, $dataRow, "=round(average(R5:R$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(19, $dataRow, "=round(average(S5:S$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(20, $dataRow, "=round(average(T5:T$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(21, $dataRow, "=round(average(U5:U$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(22, $dataRow, "=round(average(V5:V$lastRow),1)");

    $sheet->setCellValueByColumnAndRow(23, $dataRow, "=sum(W5:W$lastRow)");
    $sheet->setCellValueByColumnAndRow(24, $dataRow, "=sum(X5:X$lastRow)");

    $sheet->setCellValueByColumnAndRow(25, $dataRow, "=round(average(Y5:Y$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(26, $dataRow, "=round(average(Z5:Z$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(27, $dataRow, "=round(average(AA5:AA$lastRow),1)");
    $sheet->setCellValueByColumnAndRow(28, $dataRow, "=round(average(AB5:AB$lastRow),1)");

    //totals at bottom of sheet
    $t = $this->statistics->data->summaryData['totals']['PreU'];
    $totals = [
      $t['entries'],
      $t['D1'],
      $t['D2'],
      $t['D3'],
      $t['M1'],
      $t['M2'],
      $t['M3'],
      $t['P1'],
      $t['P2'],
      $t['P3'],
      $t['U'],
      $t['%D'],
      $t['%M'],
      $t['%P']
    ];
    $count++;
    // $sheet->fromArray(
    //     $totals,  // The data to set
    //     NULL,        // Array values with this value will not be set
    //     "C$count"         // Top left coordinate of the worksheet range where
    // );
    $count++;

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
    $sheet->getStyle("A$dataRow:AB$dataRow")->applyFromArray($styleArray);

    foreach (range('A','L') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    foreach (range('L','Z') as $col) {
      $sheet->getColumnDimension($col)->setWidth(4);
    }
    $sheet->getColumnDimension('AA')->setWidth(4);

    $sheet->getRowDimension('3')->setRowHeight(95);

    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'textRotation' => 90,
        'shrinkToFit' => true
      ]
    ];
    $sheet->getStyle('Q3:AB3')->applyFromArray($styleArray);

    //grade key
    $count = $count + 2;
    $sheet->setCellValue("A$count", $this->keyAPreU);
    $sheet->mergeCells("A$count:O$count");

    $count = $count + 1;
    $sheet->setCellValue("A$count", $this->keyPPreU);
    $sheet->mergeCells("A$count:O$count");

  }

  private function getPointsFromGrade($grade)
  {
    $result = new \Exams\Tools\GCSE\Result();
    return $result->processGrade($grade);
  }

  //create the sheet which lists the hundred stats for each subject
  private function makeALevelRange($key, $title = null)
  {

    $title = $title ? $title : $key . ' Grades';
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, "$title");
    $worksheet->getTabColor()->setRGB('ffc6d0');
    $spreadsheet->addSheet($worksheet, 0);

    //sheet title
    $sheet = $spreadsheet->getSheetByName("$title");
    $sheet->getDefaultColumnDimension()->setWidth(15);
    $sheet->setCellValue('A1', "$key (no Pre U)");
    $sheet->mergeCells('A1:G1');
    $sheet->getRowDimension('1')->setRowHeight(50);

    $data = array();
    $year = $this->year;
    $lastYear =  $this->year - 1;
    //generate array to be placed in spreadsheet
    $fields = ['Subject', 'Board'];

    //choose a random subject to get years availiable
    foreach($this->statistics->data->subjectResults['A']['MA']->summaryData['history'] as $year) {
      $fields[] = $year['year'];
    }

    $data[] = $fields;
    $data[] = []; //blank row

    ksort($this->statistics->data->subjectResults['A']);

    foreach($this->statistics->data->subjectResults['A'] as $s){

      $values = [ $s->subjectName,
                  $s->boardName
                ];
      $i = 0;
      foreach($s->summaryData['history'] as $year) {
          if ($i === 0) {
              $star = false;
              $board = $year['board'];
          }
          //record with a start whether or not the board has changed.
          if ($i > 1) {
            if ($year['board'] !== $board){
              $star = $star ? false : '*';
              $board = $year['board'];
            }
          }
          $i++;
          $values[] = $year[$key] . $star;
      }

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

    foreach (range('A','Z') as $col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    foreach (range('L','Z') as $col) {
      $sheet->getColumnDimension($col)->setWidth(4);
    }
    $sheet->getColumnDimension('AA')->setWidth(4);

    $sheet->getRowDimension('3')->setRowHeight(95);

    // // rotate headers
    // $styleArray = [
    //   'font' => [
    //       'bold' => true,
    //   ],
    //   'alignment' => [
    //     'textRotation' => 90,
    //     'shrinkToFit' => true
    //   ]
    // ];
    // $sheet->getStyle('K3:R3')->applyFromArray($styleArray);

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
