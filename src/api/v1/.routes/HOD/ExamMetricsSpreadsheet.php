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

  public function __construct($subject)
  {
    $this->spreadsheet = new Spreadsheet();
    $this->subject = $subject;

    $filename = $subject->code . "_" . $subject->year . '_' . date('d-m-y_H-i-s',time());

    //delete the default sheet
    $sheetIndex = $this->spreadsheet->getIndex($this->spreadsheet->getSheetByName('Worksheet'));
    $this->spreadsheet->removeSheetByIndex($sheetIndex);

    $title = ';';

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
      'Provisional CAG',
      'Provisional SCR',
      'Weighted Rank Average',
      'U6 Mock Score',
      'Rank',
      'GCSE GPA Uplift',
      'Rank',
      'GCSE Grade',
      'Rank',
      'GCSE Mock Results',
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
        $s->gcseGrade ?? '',
        $s->gcseGradeCohortRank ?? '',
        $s->gcseMockPercentage ?? '',
        $s->gcseMockCohortRank ?? '',
        $s->alisTestBaseline ?? '',
        $s->alisCohortRank ?? '',
        $s->midyisBaseline,
        $s->midyisCohortRank,
        '',
        '=IF(LEN(AD'.$i.') > 0,RANK(AE'. $i . ',AE$5:$AE$200),0)',
        '',
        '=IF(LEN(AF'.$i.') > 0,RANK(AG'. $i . ',AG$5:$AG$200),0)',
        '',
        '=IF(LEN(AH'.$i.') > 0,RANK(AI'. $i . ',AI$5:$AI$200),0)'
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
    $original = '=IF(LEN(Profile!D$4)>0,IF(N5<Profile!D$4,"9",IF(N5<Profile!D$5,"8",IF(N5<Profile!D$6,"7",IF(N5<Profile!D$7,"6",IF(N5<Profile!D$8,"5",IF(N5<Profile!D$9,"4",IF(N5<Profile!D$10,"3",IF(N5<Profile!D$11,"2",IF(N5<Profile!D$12,"1","U"))))))))), IF(LEN(Profile!G$4)>0,IF(N5<Profile!G$4,"A* ",IF(N5<Profile!G$5,"A",IF(N5<Profile!G$6,"B",IF(N5<Profile!G$7,"C",IF(N5<Profile!G$8,"D",IF(N5<Profile!G$9,"E", "U")))))), IF(Profile!J$4>0,IF(N5<Profile!J$4,"D1",IF(N5<Profile!J$5,"D2",IF(N5<Profile!J$6,"D3",IF(N5<Profile!J$7,"M1",IF(N5<Profile!J$8,"M2",IF(N5<Profile!J$9,"M3",IF(N5<Profile!J$10,"P1",IF(N5<Profile!J$11,"P2",IF(N5<Profile!J$12,"P3","U"))))))))), "")))';
    return str_replace('(N5<', "(P$i<", $original);
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
    $sheet->getRowDimension('3')->setRowHeight(110);
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
       $sheet->getColumnDimension('L')->setVisible(false);
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
    $sheetData[] = ['', 'All', '' , 'Boys', '', 'Girls'];
    $sheetData[] = ['Grades', '#', '%', '#', '%', '#', '%'];
    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "A*") > 0, "A*", IF(COUNTIF(V$4:W$300, "D1") > 0, "D1", IF(COUNTIF(V$4:W$300, 9),9,"") ))',
      '=IF(LEN($M4) = 0,"", COUNTIF(Students!M$5:M$445,$M4))',
      '=IF(LEN($M4) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M4) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M4) = 0,"", COUNTIF(V$4:V$300,$M4))',
      '=IF(LEN($M4) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M4) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M4) = 0,"", COUNTIF(W$4:W$300,$M4))',
      '=IF(LEN($M4) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M4) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "A") > 0, "A", IF(COUNTIF(V$4:W$300, "D2") > 0, "D2", IF(COUNTIF(V$4:W$300, 8),8,"") ))',
      '=IF(LEN($M5) = 0,"", COUNTIF(Students!M$5:M$445,$M5))',
      '=IF(LEN($M5) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M5) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M5) = 0,"", COUNTIF(V$4:V$300,$M5))',
      '=IF(LEN($M5) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M5) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M5) = 0,"", COUNTIF(W$4:W$300,$M5))',
      '=IF(LEN($M5) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M5) / COUNTIF(Students!D$5:D$445,"F"),1))'

    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "B") > 0, "B", IF(COUNTIF(V$4:W$300, "D3") > 0, "D3", IF(COUNTIF(V$4:W$300, 7),7,"") ))',
      '=IF(LEN($M6) = 0,"", COUNTIF(Students!M$5:M$445,$M6))',
      '=IF(LEN($M6) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M6) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M6) = 0,"", COUNTIF(V$4:V$300,$M6))',
      '=IF(LEN($M6) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M6) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M6) = 0,"", COUNTIF(W$4:W$300,$M6))',
      '=IF(LEN($M6) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M6) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "C") > 0, "C", IF(COUNTIF(V$4:W$300, "M1") > 0, "M1", IF(COUNTIF(V$4:W$300, 6),6,"") ))',
      '=IF(LEN($M7) = 0,"", COUNTIF(Students!M$5:M$445,$M7))',
      '=IF(LEN($M7) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M7) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M7) = 0,"", COUNTIF(V$4:V$300,$M7))',
      '=IF(LEN($M7) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M7) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M7) = 0,"", COUNTIF(W$4:W$300,$M7))',
      '=IF(LEN($M7) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M7) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "D") > 0, "D", IF(COUNTIF(V$4:W$300, "M2") > 0, "M2", IF(COUNTIF(V$4:W$300, 5),5,"") ))',
      '=IF(LEN($M8) = 0,"", COUNTIF(Students!M$5:M$445,$M8))',
      '=IF(LEN($M8) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M8) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M8) = 0,"", COUNTIF(V$4:V$300,$M8))',
      '=IF(LEN($M8) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M8) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M8) = 0,"", COUNTIF(W$4:W$300,$M8))',
      '=IF(LEN($M8) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M8) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "E") > 0, "E", IF(COUNTIF(V$4:W$300, "M3") > 0, "M3", IF(COUNTIF(V$4:W$300, 4),4,"") ))',
      '=IF(LEN($M9) = 0,"", COUNTIF(Students!M$5:M$445,$M9))',
      '=IF(LEN($M9) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M9) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M9) = 0,"", COUNTIF(V$4:V$300,$M9))',
      '=IF(LEN($M9) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M9) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M9) = 0,"", COUNTIF(W$4:W$300,$M9))',
      '=IF(LEN($M9) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M9) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "P1") > 0, "P1", IF(COUNTIF(V$4:W$300, 3),3,""))',
      '=IF(LEN($M10) = 0,"", COUNTIF(Students!M$5:M$445,$M10))',
      '=IF(LEN($M10) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M10) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M10) = 0,"", COUNTIF(V$4:V$300,$M10))',
      '=IF(LEN($M10) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M10) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M10) = 0,"", COUNTIF(W$4:W$300,$M10))',
      '=IF(LEN($M10) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M10) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "P2") > 0, "P2", IF(COUNTIF(V$4:W$300, 2),2,""))',
      '=IF(LEN($M11) = 0,"", COUNTIF(Students!M$5:M$445,$M11))',
      '=IF(LEN($M11) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M11) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M11) = 0,"", COUNTIF(V$4:V$300,$M11))',
      '=IF(LEN($M11) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M11) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M11) = 0,"", COUNTIF(W$4:W$300,$M11))',
      '=IF(LEN($M11) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M11) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "P3") > 0, "P3", IF(COUNTIF(V$4:W$300, 1),1,""))',
      '=IF(LEN($M12) = 0,"", COUNTIF(Students!M$5:M$445,$M12))',
      '=IF(LEN($M12) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M12) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M12) = 0,"", COUNTIF(V$4:V$300,$M12))',
      '=IF(LEN($M12) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M12) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M12) = 0,"", COUNTIF(W$4:W$300,$M12))',
      '=IF(LEN($M12) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M12) / COUNTIF(Students!D$5:D$445,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(V$4:W$300, "U") > 0, "U", "")',
      '=IF(LEN($M13) = 0,"", COUNTIF(Students!M$5:M$445,$M13))',
      '=IF(LEN($M13) = 0,"", ROUND(100 * COUNTIF(Students!M$5:M$445, $M13) / COUNTA(Students!M$5:M$445),1))',
      '=IF(LEN($M13) = 0,"", COUNTIF(V$4:V$300,$M13))',
      '=IF(LEN($M13) = 0,"", ROUND(100 * COUNTIF(V$4:V$300, $M13) / COUNTIF(Students!D$5:D$445,"M"),1))',
      '=IF(LEN($M13) = 0,"", COUNTIF(W$4:W$300,$M13))',
      '=IF(LEN($M13) = 0,"", ROUND(100 * COUNTIF(W$4:W$300, $M13) / COUNTIF(Students!D$5:D$445,"F"),1))'



    ];


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
    $cag[] = ['Band', 'Grade', 'Count', 'Band', 'Grade', 'Count', 'Band', 'Grade', 'Count'];
    $cag[] = ['.9', 9, '', 'A*', 'A', '', 'D1', 'D1'];
    $cag[] = ['9-8', 8, '', 'A*-A', 'A', '', 'D1-D2', 'D1'];
    $cag[] = ['9-7', 7, '', 'A*-B', 'B', '', 'D1-D3', 'D2'];
    $cag[] = ['9-6', 6, '', 'A*-C', 'C', '', 'D1-M1', 'D3'];
    $cag[] = ['9-5', 5, '', 'A*-D', 'D', '', 'D1-M2', 'M1'];
    $cag[] = ['9-4', 4, '', 'A*-E', 'E', '', 'D1-M3', 'M2'];
    $cag[] = ['9-3', 3, '', '', '', '', 'D1-P1', 'P1'];
    $cag[] = ['9-2', 2, '', '', '', '', 'D1-P2', 'P2'];
    $cag[] = ['9-1', 1, '', '', '', '', 'D1-P3', 'P3'];

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
    $sheet->getStyle('B2:C12')->applyFromArray($styleArray);
    $sheet->getStyle('D2:J3')->applyFromArray($styleArray);
    $sheet->getStyle('E4:F9')->applyFromArray($styleArray);
    $sheet->getStyle('H4:I12')->applyFromArray($styleArray);

    $sheet->getColumnDimension('C')->setVisible(false);
    $sheet->getColumnDimension('F')->setVisible(false);
    $sheet->getColumnDimension('I')->setVisible(false);

    $sheet->mergeCells('B2:J2');
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

    $sheet->mergeCells('N2:O2');
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
    $sheet->getStyle('B1:B13')->applyFromArray($styleArray);
    $sheet->getStyle('C2:H3')->applyFromArray($styleArray);

  }

}
