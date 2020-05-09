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
  public $rowsWithNotes=[6,7,12];

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

    $this->generateProfileSheet($subject);
    $this->generateStudentSheet($subject);
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
    $sheetData[] = ['Weightings:','','','','','','','','','','','',1,'',1,'',1,'','',1,'','',1, '', 1, '', 1, '', 1, '', 1, '', 1];
    $sheetData[] = [
      'Name',
      'Class',
      'Sch. #',
      'M/F',
      'HM Note',
      'Final Grade',
      'Final Rank',
      'Weighted Rank',
      'WRA',
       $subject->maxMLOCount > 1 ? 'Min MLO' : 'MLO',
       $subject->maxMLOCount > 1 ? 'Max MLO' : '',
      'Midyis',
      'Midyis Rank',
      'Alis',
      'Alis Rank',
      'GCSE Mock Grade',
      'GCSE Mock %',
      'Rank',
      'Mock Gpa',
      'GCSE Grade',
      'GCSE Gpa',
      'IGDR',
      'U6 Mock',
      '%',
      'Rank',
      'New Metric 1',
      'New Rank 1',
      'New Metric 2',
      'New Rank 2',
      'New Metric 3',
      'New Rank 3',
      'New Metric 4',
      'New Rank 4',
    ];
    //blank row for filter buttons
    $sheetData[] = [];
    $i = 4;
    foreach ($subject->students as $s) {
      if (strlen($s->hmNote) > 1) $this->rowsWithNotes[] = $i;
      $row = [
        $s->displayName,
        \str_replace('(FM)', '', $s->classCode),
        $s->schoolNumber,
        $s->gender,
        $s->hmNote,
        '',
        '',
        '',
        '=RANK(J'. $i . ',J4:$J$200, 1)',
        '=ROUND((N' . $i . '*N$1+P' . $i . '*P$1+S' . $i . '*S$1+W' . $i . '*W$1+Z' . $i . '*Z$1+AB' . $i . '*AB$1+AD' . $i . '*AD$1+AF' . $i . '*AF$1+AH' . $i . '*AH$1 +AJ' . $i . '*AJ$1)/COUNTA(N' . $i . ',P' . $i . ',S' . $i . ',W' . $i . ',Z' . $i . ',AB' . $i . ',AD' . $i . ',AF' . $i . ',AH' . $i . ',AJ' . $i . '),2)',
        $s->mlo0 ?? $s->mlo1 ?? '',
        $s->mlo1 ?? '',
        $s->midyisPrediction,
        $s->midyisCohortRank,
        $s->alisTestPrediction,
        $s->alisCohortRank,
        $s->gcseMockGrade ?? '',
        $s->gcseMockPercentage ?? '',
        $s->gcseMockCohortRank ?? '',
        $s->gcseMockGpa ?? '',
        $s->gcseGrade ?? '',
        $s->gcseGpa ?? '',
        $s->igdr ?? '',
        $s->aLevelMockGrade ?? '',
        $s->aLevelMockPercentage ?? '',
        $s->aLevelMockCohortRank ?? ''
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

  private function setMainStyle(&$sheet){

    $sheet->mergeCells('A1:K1');
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
    $sheet->getStyle('D2:ZZ2')->applyFromArray($styleArray);

    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $sheet->getStyle('A1:C2')->applyFromArray($styleArray);

    //hide score
    $sheet->getRowDimension('1')->setRowHeight(50);
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

     // borders
     $styleArray = [
     'borders' => [
         'outline' => [
             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                 'color' => ['argb' => '00000000'],
             ],
         ],
     ];
     $sheet->getStyle('F2:G200')->applyFromArray($styleArray);

     //background colors
     $styleArray = [
     'borders' => [
         'left' => [
             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => ['argb' => '00000000'],
             ],
         'right' => [
             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              'color' => ['argb' => '00000000'],
             ]
         ]
     ];

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
     $sheet->getStyle('F2:G200')->applyFromArray($styleArray);

     // gray alternate background
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FFECECEC',
       ],
       'endColor' => [
           'argb' => 'FFECECEC',
       ],
     ];
     $sheet->getStyle('J2:K200')->applyFromArray($styleArray);
     $sheet->getStyle('N2:O200')->applyFromArray($styleArray);
     $sheet->getStyle('R2:T200')->applyFromArray($styleArray);
     $sheet->getStyle('R2:T200')->applyFromArray($styleArray);
     $sheet->getStyle('X2:Y200')->applyFromArray($styleArray);
     $sheet->getStyle('AB2:AC200')->applyFromArray($styleArray);
     $sheet->getStyle('AF2:AG200')->applyFromArray($styleArray);

     //rows with hm notes. Box goes red
     $styleArrat = [];
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FFFF0000',
       ],
       'endColor' => [
           'argb' => 'FFFF0000',
       ],
     ];
     foreach($this->rowsWithNotes as $r) $sheet->getStyle("E$r:E$r")->applyFromArray($styleArray);

     $styleArray = [];
     //blank where weightings wont go
     // gray alternate background
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FF000000',
       ],
       'endColor' => [
           'argb' => 'FF000000',
       ],
     ];
     $sheet->getStyle('L1:L1')->applyFromArray($styleArray);
     $sheet->getStyle('N1:N1')->applyFromArray($styleArray);
     $sheet->getStyle('P1:P1')->applyFromArray($styleArray);
     $sheet->getStyle('R1:S1')->applyFromArray($styleArray);
     $sheet->getStyle('U1:V1')->applyFromArray($styleArray);
     $sheet->getStyle('X1:X1')->applyFromArray($styleArray);
     $sheet->getStyle('Z1:Z1')->applyFromArray($styleArray);
     $sheet->getStyle('AB1:AB1')->applyFromArray($styleArray);
     $sheet->getStyle('AD1:AD1')->applyFromArray($styleArray);
     $sheet->getStyle('AF1:AF1')->applyFromArray($styleArray);



     if ($this->subject->year < 12) {
       //hide some columns if GCSE
       $sheet->getColumnDimension('K')->setVisible(false);
       $sheet->getColumnDimension('M')->setVisible(false);
       $sheet->getColumnDimension('O')->setVisible(false);
       $sheet->getColumnDimension('P')->setVisible(false);
       $sheet->getColumnDimension('Q')->setVisible(false);
       $sheet->getColumnDimension('R')->setVisible(false);
       $sheet->getColumnDimension('S')->setVisible(false);
       $sheet->getColumnDimension('T')->setVisible(false);
       $sheet->getColumnDimension('U')->setVisible(false);
       $sheet->getColumnDimension('V')->setVisible(false);
       $sheet->getColumnDimension('W')->setVisible(false);
     }

     //filters
     $sheet->setAutoFilter('A3:AG200');

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
    $sheetData[] = ['', 'All', '' , 'Boys', '', 'Girls'];
    $sheetData[] = ['Grades', '#', '%', '#', '%', '#', '%'];
    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "A*") > 0, "A*", IF(COUNTIF(K$4:L$300, "D1") > 0, "D1", IF(COUNTIF(K$4:L$300, 9),9,"") ))',
      '=IF(LEN($B4) = 0,"", COUNTIF(Students!G$4:G$444,$B4))',
      '=IF(LEN($B4) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B4) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B4) = 0,"", COUNTIF(K$4:K$300,$B4))',
      '=IF(LEN($B4) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B4) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B4) = 0,"", COUNTIF(L$4:L$300,$B4))',
      '=IF(LEN($B4) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B4) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "A") > 0, "A", IF(COUNTIF(K$4:L$300, "D2") > 0, "D2", IF(COUNTIF(K$4:L$300, 8),8,"") ))',
      '=IF(LEN($B5) = 0,"", COUNTIF(Students!G$4:G$444,$B5))',
      '=IF(LEN($B5) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B5) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B5) = 0,"", COUNTIF(K$4:K$300,$B5))',
      '=IF(LEN($B5) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B5) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B5) = 0,"", COUNTIF(L$4:L$300,$B5))',
      '=IF(LEN($B5) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B5) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "B") > 0, "B", IF(COUNTIF(K$4:L$300, "D3") > 0, "D3", IF(COUNTIF(K$4:L$300, 7),7,"") ))',
      '=IF(LEN($B6) = 0,"", COUNTIF(Students!G$4:G$444,$B6))',
      '=IF(LEN($B6) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B6) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B6) = 0,"", COUNTIF(K$4:K$300,$B6))',
      '=IF(LEN($B6) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B6) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B6) = 0,"", COUNTIF(L$4:L$300,$B6))',
      '=IF(LEN($B6) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B6) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "C") > 0, "C", IF(COUNTIF(K$4:L$300, "M1") > 0, "M1", IF(COUNTIF(K$4:L$300, 6),6,"") ))',
      '=IF(LEN($B7) = 0,"", COUNTIF(Students!G$4:G$444,$B7))',
      '=IF(LEN($B7) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B7) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B7) = 0,"", COUNTIF(K$4:K$300,$B7))',
      '=IF(LEN($B7) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B7) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B7) = 0,"", COUNTIF(L$4:L$300,$B7))',
      '=IF(LEN($B7) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B7) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "D") > 0, "D", IF(COUNTIF(K$4:L$300, "M2") > 0, "M2", IF(COUNTIF(K$4:L$300, 5),5,"") ))',
      '=IF(LEN($B8) = 0,"", COUNTIF(Students!G$4:G$444,$B8))',
      '=IF(LEN($B8) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B8) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B8) = 0,"", COUNTIF(K$4:K$300,$B8))',
      '=IF(LEN($B8) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B8) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B8) = 0,"", COUNTIF(L$4:L$300,$B8))',
      '=IF(LEN($B8) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B8) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];

    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "E") > 0, "E", IF(COUNTIF(K$4:L$300, "M3") > 0, "M3", IF(COUNTIF(K$4:L$300, 4),4,"") ))',
      '=IF(LEN($B9) = 0,"", COUNTIF(Students!G$4:G$444,$B9))',
      '=IF(LEN($B9) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B9) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B9) = 0,"", COUNTIF(K$4:K$300,$B9))',
      '=IF(LEN($B9) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B9) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B9) = 0,"", COUNTIF(L$4:L$300,$B9))',
      '=IF(LEN($B9) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B9) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "P1") > 0, "P1", IF(COUNTIF(K$4:L$300, 3),3,""))',
      '=IF(LEN($B10) = 0,"", COUNTIF(Students!G$4:G$444,$B10))',
      '=IF(LEN($B10) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B10) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B10) = 0,"", COUNTIF(K$4:K$300,$B10))',
      '=IF(LEN($B10) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B10) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B10) = 0,"", COUNTIF(L$4:L$300,$B10))',
      '=IF(LEN($B10) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B10) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "P2") > 0, "P2", IF(COUNTIF(K$4:L$300, 2),2,""))',
      '=IF(LEN($B11) = 0,"", COUNTIF(Students!G$4:G$444,$B11))',
      '=IF(LEN($B11) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B11) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B11) = 0,"", COUNTIF(K$4:K$300,$B11))',
      '=IF(LEN($B11) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B11) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B11) = 0,"", COUNTIF(L$4:L$300,$B11))',
      '=IF(LEN($B11) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B11) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "P3") > 0, "P3", IF(COUNTIF(K$4:L$300, 1),1,""))',
      '=IF(LEN($B12) = 0,"", COUNTIF(Students!G$4:G$444,$B12))',
      '=IF(LEN($B12) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B12) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B12) = 0,"", COUNTIF(K$4:K$300,$B12))',
      '=IF(LEN($B12) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B12) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B12) = 0,"", COUNTIF(L$4:L$300,$B12))',
      '=IF(LEN($B12) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B12) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];
    //
    $sheetData[] = [
      '=IF(COUNTIF(K$4:L$300, "U") > 0, "U", "")',
      '=IF(LEN($B13) = 0,"", COUNTIF(Students!G$4:G$444,$B13))',
      '=IF(LEN($B13) = 0,"", ROUND(100 * COUNTIF(Students!G$4:G$444, $B13) / COUNTA(Students!G$4:G$444),1))',
      '=IF(LEN($B13) = 0,"", COUNTIF(K$4:K$300,$B13))',
      '=IF(LEN($B13) = 0,"", ROUND(100 * COUNTIF(K$4:K$300, $B13) / COUNTIF(Students!D$4:D$444,"M"),1))',
      '=IF(LEN($B13) = 0,"", COUNTIF(L$4:L$300,$B13))',
      '=IF(LEN($B13) = 0,"", ROUND(100 * COUNTIF(L$4:L$300, $B13) / COUNTIF(Students!D$4:D$444,"F"),1))'
    ];


    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'B2'         // Top left coordinate of the worksheet range where
    );


    $sheetData = [];
    $sheetData[] = ['Boys', 'Girls'];
    $i=4;
    foreach($subject->students as $s){
      $sheetData[] = [
        '=IF(Students!D' . $i .' = "M", Students!G' . $i .', "")',
        '=IF(Students!D' . $i .' = "F", Students!G' . $i .', "")'
      ];
      $i++;
    }

    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'K3'         // Top left coordinate of the worksheet range where
    );

    $this->setProfileStyle($sheet);

  }

  private function setProfileStyle($sheet){
    $styleArray = [];

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FFC7EA46',
      ],
      'endColor' => [
          'argb' => 'FFC7EA46',
      ],
    ];
    $sheet->getStyle('B3:B13')->applyFromArray($styleArray);
    $sheet->getStyle('B2:D3')->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FF92B7FE',
      ],
      'endColor' => [
          'argb' => 'FF92B7FE',
      ],
    ];
    $sheet->getStyle('E2:F3')->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FFDF5286',
      ],
      'endColor' => [
          'argb' => 'FFDF5286',
      ],
    ];
    $sheet->getStyle('G2:H3')->applyFromArray($styleArray);

    $sheet->mergeCells('C2:D2');
    $sheet->mergeCells('E2:F2');
    $sheet->mergeCells('G2:H2');

    $sheet->getColumnDimension('K')->setVisible(false);
    $sheet->getColumnDimension('L')->setVisible(false);

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
