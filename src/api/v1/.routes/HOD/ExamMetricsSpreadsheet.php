<?php

//settings array = [
//   'title' => default ''
//   'filename' => //or autogenerate
//   'path' => eg 'exams/gcse/' default: spreadsheets
//   'sheetTitle' => default Sheet1
//   'sheetColor'
// ]

// $columns = [
//   [
//     'field' => 'name',
//     'label' =>  'Name'
//   ]
// ]

// =IF(LEN(Profile!C$21)>0,IF(F4<Profile!C$21,"9",IF(F4<Profile!C$22,"8",IF(F4<Profile!C$23,"7",IF(F4<Profile!C$24,"6",IF(F4<Profile!C$25,"5",IF(F4<Profile!C$26,"4",IF(F4<Profile!C$27,"3",IF(F4<Profile!C$28,"2",IF(F4<Profile!C$28,"1","U"))))))))), IF(LEN(Profile!E$22)>0,IF(F4<Profile!E$21,"A* ",IF(F4<Profile!E$22!,"A",IF(F4<Profile!E$23,"B",IF(F4<Profile!E$24,"C",IF(F4<Profile!E$25,"D",IF(F4<Profile!E$26,"E", "U")))))), IF(Profile!G$21>0,IF(F4<Profile!G$21,"D1",IF(F4<Profile!G$22,"D2",IF(F4<Profile!G$23,"D3",IF(F4<Profile!G$24,"M1",IF(F4<Profile!G$25,"M2",IF(F4<Profile!G$26,"M3",IF(F4<Profile!G$27,"P1",IF(F4<Profile!G$28,"P2",IF(F4<Profile!G$28,"P3","U"))))))))), "")))

// https://stackoverflow.com/questions/55133722/how-to-protect-individual-cell-using-phpspreadsheet

namespace HOD;

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet as Worksheet;

class ExamMetricsSpreadsheet
{
  public $url = '';
  public $filename = '';
  public $path;
  public $package = [];
  public $settings = [];
  public $subject;
  public $rowsWithNotes=[];
  public $hmNotes = [];
  public $grades = [];
  public $gradeBands = [];
  public $gradesForCounting = []; //so that I can add a tilde to A*

  public function __construct($subject)
  {
    $this->spreadsheet = new Spreadsheet();
    $this->subject = $subject;

    $filename = $subject->code . "_" . $subject->year . '_' . date('d-m-y_H-i-s',time());

    //delete the default sheet
    $sheetIndex = $this->spreadsheet->getIndex($this->spreadsheet->getSheetByName('Worksheet'));
    $this->spreadsheet->removeSheetByIndex($sheetIndex);

    $title = ';';

    $this->getGrades($subject);

    //set metadata
    $this->spreadsheet->getProperties()
                      ->setCreator("ADA")
                      ->setLastModifiedBy("ADA")
                      ->setTitle($title);

    $sheetTitle = 'Students';
    $color = $settings['sheetColor'] ?? null;

    $this->generateStudentSheet($subject);
    $this->generateProfileSheet($subject);
    //
    //
    // //generate file path and save sheet

    $path = 'spreadsheets/';
    $filename = $filename . '.xlsx';

    $filepath = FILESTORE_PATH . "$path$filename";
    $url = FILESTORE_URL . "$path$filename";

    $this->writer = new Xlsx($this->spreadsheet);
    $this->writer->save($filepath);

    $this->url = $url;
    $this->filename = $filename;
    $this->path = $filepath;

    $this->package = [
      'file' => $filename,
      'url'  => $url
    ];

    return $this;
  }

  private function getGrades($subject){

    if ($subject->year < 12) {

      $this->grades = [9, 8, 7, 6, 5, 4, 3, 2, 1, 'U'];
      $this->gradesForCounting = $this->grades;
      $this->gradeBands = ['9', '9-8', '9-7', '9-6', '9-5', '9-4', '9-3', '9-2', '9-1'];

      return;

    }
    foreach($subject->students as $s) {
      if ($s->aLevelMockGrade == 'D1' || $s->aLevelMockGrade == 'D3' || $s->aLevelMockGrade == 'M1' || $s->aLevelMockGrade == 'M2') { //that should catch all
        $isPreU = true;
        $this->grades = ['D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3', 'U'];
        $this->gradeBands = ['D1', 'D1-D2', 'D1-D3', 'D1-M1', 'D1-M2', 'D1-M3', 'D1-P1', 'D1-P2', 'D1-P3', 'U' ];
        $this->gradesForCounting = $this->grades;
        return;
      }
    }

    //must be A Level
    $this->grades = ['A*', 'A', 'B', 'C', 'D', 'E', 'U'];
    $this->gradeBands = ['A*', 'A*-A', 'A*-B', 'A*-C', 'A*-D', 'A*-E'];
    $this->gradesForCounting = ['A~*', 'A', 'B', 'C', 'D', 'E', 'U'];
  }


  private function generateStudentSheet($subject)
  {
    $title = 'Students';
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $title);
    $color = '50C878';
    $worksheet->getTabColor()->setRGB($color);
    $spreadsheet->addSheet($worksheet, 0);

    // //sheet title
    $sheet = $spreadsheet->getSheetByName($title);
    //
    $sheetData = [];
    //first row
    $sheetData[] = ['','','','','','','','','','','','','','Weightings:', 1,'',1,'',1,'',1,'',1, '', 1, '', 1, '', 1, '', 1];
    $sheetData[] = ['','','','','','','','','Contextual Data','','','','','', '','',"Ranked Data"];
    $sheetData[] = [
      'Name',
      'Class',
      'Sch. #',
      'M/F',
      'HM Note',
      'Midyis Prediction',
      'Alis Prediction',
      $subject->maxMLOCount > 1 ? 'Min MLO' : 'MLO',
      $subject->maxMLOCount > 1 ? 'Max MLO' : '',
      'GCSE Mock Grade',
      'GCSE Grade',
      'U6 Mock Grade',
      'FINAL GRADE',
      'FINAL RANK',
      'Provisional Grade',
      'Provisional Rank',
      'Weighted Rank Average',
      'U6 Mock Score',
      'Rank',
      'GCSE GPA Uplift',
      'Rank',
      'GCSE Avg.',
      'Rank',
      'GCSE Mock %',
      'Rank',
      'Alis Score',
      'Rank',
      'Midyis Score',
      'Rank',
      'Dept. Level Data',
      'Rank',
      'Dept. Level Data',
      'Rank',
      'Dept. Level Data',
      'Rank'
    ];
    //blank row for filter buttons
    $sheetData[] = [];
    $i = 5;
    foreach ($subject->students as $s) {
      $s->getHmNote();
      if (strlen($s->hmNote) > 1) {
        $this->rowsWithNotes[] = $i;
        $this->hmNotes['c_' . $i] = $s->hmNote;
      }
      $row = [
        $s->displayName,
        str_replace('(FM)', '', $s->classCode),
        $s->schoolNumber,
        $s->gender,
        strlen($s->hmNote) > 1 ? '!' :  '',
        $s->midyisPrediction,
        $s->alisTestPrediction,
        $s->mlo0 ?? $s->mlo1 ?? '',
        $s->mlo1 ?? '',
        $s->gcseMockGrade ?? '',
        $s->gcseGrade ?? '',
        $s->aLevelMockGrade ?? '',
        "",
        "",
        $this->CAG($i),
        '=RANK(Q'. $i . ',Q$5:$Q$200, 1)',
        $this->WRA($i),
        $s->aLevelMockPercentage ?? '',
        $s->aLevelMockCohortRank ?? '',
        $s->gcseDelta ?? '',
        $s->igdr ?? '',
        $s->alisGcseBaseline ?? '',
        '=IF(LEN(V'.$i.') > 0,RANK(V'. $i . ',V$5:$V$200),0)',
        $s->gcseMockPercentage ?? '',
        $s->gcseMockCohortRank ?? '',
        $s->alisTestBaseline ?? '',
        $s->alisCohortRank ?? '',
        $s->midyisBaseline,
        $s->midyisCohortRank,
        '',
        '=IF(LEN(AD'.$i.') > 0,RANK(AD'. $i . ',AD$5:$AD$200),0)',
        '',
        '=IF(LEN(AF'.$i.') > 0,RANK(AF'. $i . ',AG$5:$AF$200),0)',
        '',
        '=IF(LEN(AH'.$i.') > 0,RANK(AH'. $i . ',AH$5:$AH$200),0)'
      ];
      $i++;
      $sheetData[] = $row;
    }

    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    );

    $this->setMainStyle($sheet);


    // $styleArray = [
    //   'font' => [
    //       'bold' => true
    //       // 'size' => 18
    //   ]
    // ];
    // $sheet->getStyle('A1:AZ1')->applyFromArray($styleArray);
    //
    // foreach (range('A','AZ') as $col) {
    //   $sheet->getColumnDimension($col)->setAutoSize(true);
    // }

  }

  private function WRA($i) {
    $original = '=ROUND((S5*S$1+U5*U$1+W5*W$1+Y5*Y$1+AA5*AA$1+AC5*AC$1+AE5*AE$1+AG5*AG$1+AI5*AI$1)/COUNTA(S5,U5,W5,Y5,AA5,AC5,AE5,AG5,AI5),2)';
    return str_replace('5', $i, $original);
  }

  private function CAG($i) {
    $original = '=IF(P5<=Profile!E$4, Profile!C$4, IF(P5<=Profile!E$5, Profile!C$5, IF(P5<=Profile!E$6, Profile!C$6, IF(P5<=Profile!E$7, Profile!C$7, IF(P5<=Profile!E$8, Profile!C$8, IF(P5<=Profile!E$9, Profile!C$9, IF(P5<=Profile!E$10, Profile!C$10, IF(P5<=Profile!E$11, Profile!C$11, IF(P5<=Profile!E$12, Profile!C$12, IF(P5<=Profile!E$13, Profile!C$13, IF(P5<=Profile!E$14, Profile!C$14, "U" ) ) ) ) ))) ))))';
    return str_replace('P5', "P$i", $original);
  }

  private function setMainStyle(&$sheet){

    $maxRow = count($this->subject->students)+4;

    $sheet->mergeCells('N1:R1');
    $sheet->mergeCells('F2:L2');
    $sheet->mergeCells('Q2:AI2');

    // $sheet->getRowDimension('2')->setRowHeight(80);

    //top row rotation
    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'textRotation' => 90,
        'shrinkToFit' => false
      ]
    ];
    $sheet->getStyle('D3:ZZ3')->applyFromArray($styleArray);

    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $sheet->getStyle('A1:AI3')->applyFromArray($styleArray);
    $sheet->getStyle('A5:A'. $maxRow)->applyFromArray($styleArray);

    $sheet->getStyle('F2:L2')->applyFromArray($styleArray);
    $sheet->getStyle('Q2:AI2')->applyFromArray($styleArray);

    //hide score
    $sheet->getRowDimension('1')->setRowHeight(20);
    $sheet->getRowDimension('3')->setRowHeight(140);
    //widths
    $sheet->getColumnDimension('A')->setWidth(25);
    $sheet->getColumnDimension('B')->setWidth(13);
    $sheet->getColumnDimension('C')->setWidth(6);
    $sheet->getColumnDimension('D')->setWidth(3);
    $sheet->getColumnDimension('E')->setWidth(5);

    $width = 4;
    foreach (range('F','Z') as $col) $sheet->getColumnDimension($col)->setWidth($width);
    $sheet->getColumnDimension('AA')->setWidth($width);
    $sheet->getColumnDimension('AB')->setWidth($width);
    $sheet->getColumnDimension('AC')->setWidth($width);
    $sheet->getColumnDimension('AD')->setWidth($width);
    $sheet->getColumnDimension('AE')->setWidth($width);
    $sheet->getColumnDimension('AF')->setWidth($width);
    $sheet->getColumnDimension('AG')->setWidth($width);
    $sheet->getColumnDimension('AH')->setWidth($width);
    $sheet->getColumnDimension('AI')->setWidth($width);

     // borders
     $styleArray = [
     'borders' => [
         'outline' => [
             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                 'color' => ['argb' => '00000000'],
             ],
         ],
     ];
     $sheet->getStyle('M3:N' . $maxRow)->applyFromArray($styleArray);

     // //background colors
     // $styleArray = [
     // 'borders' => [
     //     'left' => [
     //         'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
     //          'color' => ['argb' => '00000000'],
     //         ],
     //     'right' => [
     //         'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
     //          'color' => ['argb' => '00000000'],
     //         ]
     //     ]
     // ];

     $styleArray = [];
     //final rank / grade
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FF92D5E6',
       ],
       'endColor' => [
           'argb' => 'FF92D5E6',
       ],
     ];
     $sheet->getStyle('M3:N' .$maxRow)->applyFromArray($styleArray);

     //provisional grade
     $styleArray = [];
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FFE1C4FF',
       ],
       'endColor' => [
           'argb' => 'FF#E1C4FF',
       ],
     ];
     $sheet->getStyle('O3:P' .$maxRow)->applyFromArray($styleArray);


     //WRA
     $styleArray = [];
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FF90EE90',
       ],
       'endColor' => [
           'argb' => 'FF90EE90',
       ],
     ];
     $sheet->getStyle('Q3:Q' . $maxRow)->applyFromArray($styleArray);

     $sheet->getStyle('S1:S1')->applyFromArray($styleArray);
     $sheet->getStyle('Q2:AI2')->applyFromArray($styleArray);
     $sheet->getStyle('U1:U1')->applyFromArray($styleArray);
     $sheet->getStyle('W1:W1')->applyFromArray($styleArray);
     $sheet->getStyle('Y1:Y1')->applyFromArray($styleArray);
     $sheet->getStyle('AA1:AA1')->applyFromArray($styleArray);
     $sheet->getStyle('AC1:AC1')->applyFromArray($styleArray);
     $sheet->getStyle('AE1:AE1')->applyFromArray($styleArray);
     $sheet->getStyle('AG1:AG1')->applyFromArray($styleArray);
     $sheet->getStyle('AI1:AI1')->applyFromArray($styleArray);

     // Ranking Backgrounds
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FFD2F8D2',
       ],
       'endColor' => [
           'argb' => 'FFD2F8D2',
       ],
     ];
     $sheet->getStyle('S3:S' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('U3:U' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('W3:W' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('Y3:Y' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('AA3:AA' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('AC3:AC' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('AE3:AE' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('AG3:AG' . $maxRow)->applyFromArray($styleArray);
     $sheet->getStyle('AI3:AI' . $maxRow)->applyFromArray($styleArray);

     //rows with hm notes. Box goes red
     $styleArrat = [];
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FFF9910A',
       ],
       'endColor' => [
           'argb' => 'FFF9910A',
       ],
     ];
     foreach($this->rowsWithNotes as $r) {
       $sheet->getStyle("E$r:E$r")->applyFromArray($styleArray);
       $note = $this->hmNotes['c_' . $r];
       $sheet->getComment("E$r")->getText()->createTextRun($note);
       $sheet->getComment("E$r")->setHeight("300px");
       $sheet->getComment("E$r")->setWidth("200px");
     }

     // $styleArray = [];
     // //blank where weightings wont go
     // // gray alternate background
     // $styleArray['fill'] = [
     //   'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
     //   'startColor' => [
     //       'argb' => 'FF000000',
     //   ],
     //   'endColor' => [
     //       'argb' => 'FF000000',
     //   ],
     // ];
     // $sheet->getStyle('L1:L1')->applyFromArray($styleArray);
     // $sheet->getStyle('N1:N1')->applyFromArray($styleArray);
     // $sheet->getStyle('P1:P1')->applyFromArray($styleArray);
     // $sheet->getStyle('R1:S1')->applyFromArray($styleArray);
     // $sheet->getStyle('U1:V1')->applyFromArray($styleArray);
     // $sheet->getStyle('X1:X1')->applyFromArray($styleArray);
     // $sheet->getStyle('Z1:Z1')->applyFromArray($styleArray);
     // $sheet->getStyle('AB1:AB1')->applyFromArray($styleArray);
     // $sheet->getStyle('AD1:AD1')->applyFromArray($styleArray);
     // $sheet->getStyle('AF1:AF1')->applyFromArray($styleArray);



     if ($this->subject->year < 12) {
       //hide some columns if GCSE
       $sheet->getColumnDimension('G')->setVisible(false);
       $sheet->getColumnDimension('I')->setVisible(false);
       $sheet->getColumnDimension('K')->setVisible(false);
       $sheet->getColumnDimension('L')->setVisible(false);
       $sheet->getColumnDimension('R')->setVisible(false);
       $sheet->getColumnDimension('S')->setVisible(false);
       $sheet->getColumnDimension('T')->setVisible(false);
       $sheet->getColumnDimension('U')->setVisible(false);
       $sheet->getColumnDimension('V')->setVisible(false);
       $sheet->getColumnDimension('W')->setVisible(false);
       $sheet->getColumnDimension('T')->setVisible(false);
       $sheet->getColumnDimension('U')->setVisible(false);
       $sheet->getColumnDimension('V')->setVisible(false);
       $sheet->getColumnDimension('W')->setVisible(false);
       $sheet->getColumnDimension('Z')->setVisible(false);
       $sheet->getColumnDimension('AA')->setVisible(false);
     } else {
       $sheet->getColumnDimension('F')->setVisible(false);
       $sheet->getColumnDimension('AB')->setVisible(false);
       $sheet->getColumnDimension('AC')->setVisible(false);
     }

     //filters
     $sheet->setAutoFilter('A4:AG' . $maxRow);

     return;

     //instructions
     $sheet->getComment('A1')->getText()->createTextRun('The weightings control how important each metric is in deciding the weighted ranking. A metric with twice the weighting will have twice the influence.');
     $sheet->getComment("A1")->setHeight("300px");
     $sheet->getComment("A1")->setWidth("200px");

     $sheet->getComment('H2')->getText()->createTextRun('Rank order according the the weighted score. A lower score means a higher rank.');
     $sheet->getComment("H2")->setHeight("300px");
     $sheet->getComment("H2")->setWidth("200px");

     $sheet->getComment('I2')->getText()->createTextRun('This average of each ranking multipled by the weighting for that metric.');
     $sheet->getComment("I2")->setHeight("300px");
     $sheet->getComment("I2")->setWidth("200px");

     $sheet->getComment('M2')->getText()->createTextRun('Midyis score ranked within this subject.');
     $sheet->getComment("M2")->setHeight("300px");
     $sheet->getComment("M2")->setWidth("200px");

     $sheet->getComment('O2')->getText()->createTextRun('Alis score ranked within this subject.');
     $sheet->getComment("O2")->setHeight("300px");
     $sheet->getComment("O2")->setWidth("200px");


     $sheet->getComment('S2')->getText()->createTextRun('The difference (delta) between the actual GSCE points scored and that scored in the mock.');
     $sheet->getComment("S2")->setHeight("300px");
     $sheet->getComment("S2")->setWidth("200px");

     $sheet->getComment('T2')->getText()->createTextRun('Interband GCSE Delta Rank: U6 Mock results are ordered by grade. Within each grade band, the pupils are ranked according to their GCSE delta i.e how much they improved from GCSE mock to actual. A bigger improvement is ranked higher. This may give an indication of how they are likely to improve between U6 mock and actual.');
     $sheet->getComment("T2")->setHeight("300px");
     $sheet->getComment("T2")->setWidth("200px");
  }

  private function generateProfileSheet($subject)
  {
    $title = 'Profile';
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $title);
    $spreadsheet->addSheet($worksheet, 0);

    // //sheet title
    $sheet = $spreadsheet->getSheetByName($title);

    $sheetData = [];
    //first row
    $sheetData[] = ['Final Grade Profile'];
    $sheetData[] = ['All:', count($subject->students), '' , 'Boys', '', 'Girls'];
    $sheetData[] = ['Grades', '#', '%', '#', '%', '#', '%'];

    $i = 4;
    foreach($this->gradesForCounting as $g){
      $sheetData[] = [
        $g,
        str_replace('$M4', '$M'.$i, '=COUNTIF(Students!M$5:M$445,$M4)'),
        '= IF(N'.$i.'>0, ROUND(100 * COUNTIF(Students!M$5:M$445, $M'.$i.') / COUNTA(Students!M$5:M$445),1), 0)',
        '=COUNTIF(V$4:V$300,$M'.$i.')',
        '=ROUND(100 * COUNTIF(V$4:V$300, $M'.$i.') / COUNTIF(Students!D$5:D$445,"M"),1)',
        '=COUNTIF(W$4:W$300,$M'.$i.')',
        '=ROUND(100 * COUNTIF(W$4:W$300, $M4) / COUNTIF(Students!D$5:D$445,"F"),1)'
      ];
      $i++;
    }


    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'M1'         // Top left coordinate of the worksheet range where
    );


    $sheetData = [];
    $sheetData[] = ['Boys', 'Girls'];
    $i=5;
    foreach($subject->students as $s){
      $sheetData[] = [
        '=IF(Students!D' . $i .' = "M", Students!M' . $i .', "")',
        '=IF(Students!D' . $i .' = "F", Students!M' . $i .', "")'
      ];
      $i++;
    }

    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'V4'         // Top left coordinate of the worksheet range where
    );


    //CAG Profile
    $cag = [];
    $cag[] = ['Cag Profile'];
    $cag[] = ['Band', 'Grade', '%', 'Count'];

    $i = 4;
    foreach($this->gradeBands as $b) {
      if ($i == 4) {
        $cell = '=IF(COUNT(D'.$i.')>0, ROUND(D'.$i.'*N$2/100,0), "")';
      } else {
        $j = $i - 1;
        $cell = '=IF(COUNT(D'.$i.')>0, ROUND(D'.$i.'*N$2/100,0) + E' . $j .', "")';
      }
      $cag[] = [
        $b,
        $this->grades[$i-4],
        0,
        $cell
      ];
      $i++;
    }
    $cag[] = ["", "U"]; //needed for correct display of last grades

    $sheet->fromArray(
        $cag,  // The data to set
        NULL,        // Array values with this value will not be set
        'B2'         // Top left coordinate of the worksheet range where
    );

    $this->setProfileStyle($sheet);

  }

  private function setProfileStyle($sheet){

    //Cag
    $styleArray = [
      'font' => [
          'bold' => true
          // 'size' => 18
      ]
    ];
    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FFE1C4FF',
      ],
      'endColor' => [
          'argb' => 'FF#E1C4FF',
      ]
    ];
    $count = count($this->grades) + 3;
    $sheet->getStyle('B2:C'. $count)->applyFromArray($styleArray);
    $sheet->getStyle('B2:D3')->applyFromArray($styleArray);

    $sheet->getColumnDimension('C')->setVisible(false);
    $sheet->getColumnDimension('E')->setVisible(false);
    $sheet->getColumnDimension('F')->setVisible(false);
    $sheet->getColumnDimension('G')->setVisible(false);
    $sheet->getColumnDimension('H')->setVisible(false);
    $sheet->getColumnDimension('I')->setVisible(false);

    $sheet->mergeCells('B2:D2');
    $sheet->mergeCells('M1:O1');


    $styleArray = [
      'font' => [
          'bold' => true
          // 'size' => 18
      ]
    ];

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FFC7EA46',
      ],
      'endColor' => [
          'argb' => 'FFC7EA46',
      ]
    ];
    $sheet->getStyle('M3:M13')->applyFromArray($styleArray);
    $sheet->getStyle('M2:O3')->applyFromArray($styleArray);
    $sheet->getStyle('M1:O1')->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FF92B7FE',
      ],
      'endColor' => [
          'argb' => 'FF92B7FE',
      ],
    ];
    $sheet->getStyle('P2:Q3')->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FFDF5286',
      ],
      'endColor' => [
          'argb' => 'FFDF5286',
      ],
    ];
    $sheet->getStyle('R2:S3')->applyFromArray($styleArray);

    $sheet->mergeCells('P2:Q2');
    $sheet->mergeCells('R2:S2');

    $sheet->getColumnDimension('V')->setVisible(false);
    $sheet->getColumnDimension('W')->setVisible(false);

    $styleArray = [
      'font' => [
          'bold' => true
          // 'size' => 18
      ]
    ];
    $sheet->getStyle('B2:C12')->applyFromArray($styleArray);

  }

}
