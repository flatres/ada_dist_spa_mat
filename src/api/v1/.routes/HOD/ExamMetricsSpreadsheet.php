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
  public $rowsWithAccessArrangements=[];
  public $accessArrangements=[];
  public $hmNotes = [];
  public $grades = [];
  public $gradeBands = [];
  public $gradesForCounting = []; //so that I can add a tilde to A*
  public $wyaps;
  public $primaryWyaps, $secondaryWyaps;
  public $dataEndColumn;
  public $isPreU;

  public function __construct($subject, $wyaps)
  {
    $this->spreadsheet = new Spreadsheet();
    $this->subject = $subject;
    $this->wyaps = $wyaps;

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

    $this->generateQASheet($subject);
    $this->generateStudentSheet($subject);

    $this->setProtections();
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

    $isPreU = substr($subject->bands[0], 0, 1) == 'D' ? true : false;
    $this->isPreU = $subject->isPreU;
    if ($subject->name == 'Italian') $this->isPreU = false; //has gone back to A level
    if ($subject->name == 'Russian') $this->isPreU = true;
    if ($subject->name == 'Spanish') $this->isPreU = true;
    if ($subject->name == 'French') $this->isPreU = true;
    if ($subject->name == 'Chinese') $this->isPreU = true;
    if ($subject->name == 'German') $this->isPreU = true;
    if ($subject->name == 'History') $this->isPreU = true;

    if ($this->isPreU) {
      $this->grades = ['D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3', 'U'];
      $this->gradeBands = ['D1', 'D1-D2', 'D1-D3', 'D1-M1', 'D1-M2', 'D1-M3', 'D1-P1', 'D1-P2', 'D1-P3', 'U' ];
      $this->gradesForCounting = $this->grades;
      return;
    }
    // foreach($subject->students as $s) {
    //   if ($s->aLevelMockGrade == 'D1' || $s->aLevelMockGrade == 'D3' || $s->aLevelMockGrade == 'M1' || $s->aLevelMockGrade == 'M2') { //that should catch all
    //     $isPreU = true;
    //     $this->grades = ['D1', 'D2', 'D3', 'M1', 'M2', 'M3', 'P1', 'P2', 'P3', 'U'];
    //     $this->gradeBands = ['D1', 'D1-D2', 'D1-D3', 'D1-M1', 'D1-M2', 'D1-M3', 'D1-P1', 'D1-P2', 'D1-P3', 'U' ];
    //     $this->gradesForCounting = $this->grades;
    //     return;
    //   }
    // }

    //must be A Level
    $this->grades = ['A*', 'A', 'B', 'C', 'D', 'E', 'U'];
    $this->gradeBands = ['A*', 'A*-A', 'A*-B', 'A*-C', 'A*-D', 'A*-E'];
    $this->gradesForCounting = ['A~*', 'A', 'B', 'C', 'D', 'E', 'U'];
  }


  private function primaryWyaps() {
    $wyaps = [];
    $SACount = 1;
    foreach($this->wyaps as &$w) {
      if ($w->type == 'Summer Assessment') {
        $w->shortName = 'SA' . $SACount;
        $wyaps[] = $w;
        $SACount++;
      }
    }
    unset($w);
    foreach($this->wyaps as &$w) {
      if ($w->type == 'NEA') {
        $w->shortName = 'NEA';
        $wyaps[] = $w;
      }
    }
    unset($w);
    $weight = count($wyaps) > 0 ? 1 / count($wyaps) : 0;
    foreach($this->wyaps as &$w) $w->weight = $weight;
    unset($w);

    if ($this->isPreU) {
      $wyaps[] = (object)['name'=> '', 'shortName' => 'ADD 1', 'weight' => 1, 'hasGrades' => true, 'marks' => 100];
      $wyaps[] = (object)['name'=> '', 'shortName' => 'ADD 2', 'weight' => 1, 'hasGrades' => true, 'marks' => 100];
      $wyaps[] = (object)['name'=> '', 'shortName' => 'ADD 3', 'weight' => 1, 'hasGrades' => true, 'marks' => 100];
      $wyaps[] = (object)['name'=> '', 'shortName' => 'ADD 4', 'weight' => 1, 'hasGrades' => true, 'marks' => 100];
    }

    $this->primaryWyaps = $wyaps;
    return $this->primaryWyaps;
  }

  private function secondaryWyaps() {
    $wyaps = [];
    $WCount = 1;
    foreach($this->wyaps as &$w) {
      if ($w->type == 'Internal Assessment') {
          $w->shortName = 'WYAP ' . $WCount;
          $wyaps[] = $w;
          $WCount++;
      }
    }
    unset($w);
    $weight = count($wyaps) > 0 ? 1 / count($wyaps) : 0;
    foreach($this->wyaps as &$w) $w->weight = $weight;
    unset($w);
    if ($this->isPreU) $wyaps = [];
    $this->secondaryWyaps = $wyaps;
    return $this->secondaryWyaps;
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
    $gcseMockWeight = $subject->year > 11 ? 0 : 1;
    $gpaUpliftWeight = $subject->year > 11 ? 1 : 0;
    $row1 = [$subject->name,'','','','','','','','Weightings:', '', '', ''];
    $code = "{$subject->code}/{$subject->id}/{$subject->examId}";
    $row2 = [$code, '','','','','','','','','', '', ''];
    $row3 = [
      'Name',
      'Class',
      'Sch. #',
      'M/F',
      'FINAL GRADE',
      '',
      'Moderated Evidence Grade',
      'Moderated Evidence Total (%)',
      'Primary Percentile',
      'Secondary Percentile',
      'WYAP Evidence Total (%)',
      ''
    ];

    $column = 13;
    //add primary wyaps
    $primary = $this->primaryWyaps();
    foreach($primary as &$p) {
      $p->startColumn = $column;
      $p->markColumn = $column;
      $p->pctColumn = $column + 1;
      $p->endColumn = $p->hasGrades ? $column + 2 : $column + 1;
      $column = $p->endColumn + 1; //for the next one

      $row1[] = '';
      $row1[] = $p->weight;

      if ($p->hasGrades) $row1[] = '';

      $row2[] = $p->shortName;
      $row2[] = '';
      if ($p->hasGrades) $row2[] = '';

      $row3[] = $p->marks;
      $row3[] = '%';
      if ($p->hasGrades) $row3[] = 'Grade';
    }
    unset($p);
    // leave a gap
    $column++;
    $row1[] = '';
    $row2[] = '';
    $row3[] = '';

    $secondary = $this->secondaryWyaps();
    foreach($secondary as &$s) {
      $s->startColumn = $column;
      $s->markColumn = $column;
      $s->pctColumn = $column + 1;
      $s->endColumn = $s->hasGrades ? $column + 2 : $column + 1;
      $column = $s->endColumn + 1; //for the next one

      $row1[] = '';
      $row1[] = $s->weight;
      if ($s->hasGrades) $row1[] = '';

      $row2[] = $s->shortName;
      $row2[] = '';
      if ($s->hasGrades) $row2[] = '';

      $row3[] = $s->marks;
      $row3[] = '%';
      if ($s->hasGrades) $row3[] = 'Grade';
    }

    if(count($secondary) > 0) $row3[] = '';

    $row3[] = $subject->year < 12 ? 'Remove EOY %' : 'L6 EOY % ';

    $column = count($secondary) > 0 ? $column + 2 : $column + 1;

    unset($s);
    $this->dataEndColumn = $column + 1;
    $row3[] = '';
    $row3[] = 'Special Circ.';
    $row3[] = 'Explanation';
    $row3[] = '';
    $row3[] = 'Access Argmts';
    // $row3[] = '';
    $row3[] = '';
    $row3[] = 'Signature 1';
    $row3[] = 'Signature 2';
    $row3[] = '';
    $row3[] = 'Evidence Remarks';
    $row3[] = 'Rationale';

    $sheetData[]=$row1;
    $sheetData[]=$row2;
    $sheetData[]=$row3;

    //blank row for filter buttons
    $sheetData[] = [];
    $i = 5;
    $lastRow = $i+count($subject->students) -1;
    $primaryAggregateRange = 'H$' . $i . ':H$' . $lastRow;
    $secondaryAggregateRange = 'K$' . $i . ':K$' . $lastRow;
    foreach ($subject->students as $s) {

      $s->getHmNote();
      if (strlen($s->hmNote) > 1) {
        $this->rowsWithNotes[] = $i;
        $this->hmNotes['c_' . $i] = $s->hmNote;
      }

      $s->getAccessArrangements();
      if ($s->accessArrangements) {
        $this->rowsWithAccessArrangements[] = $i;
        $this->accessArrangements['c_' . $i] = $s->accessArrangements;
      }

      $row = [
        $s->displayName,
        str_replace('(FM)', '', $s->classCode),
        $s->schoolNumber,
        $s->gender,
        "",
        "",
        "",
        $this->aggregate($s, $primary, $i),
        "=PERCENTRANK.INC(" . $primaryAggregateRange . ",H{$i})",
        "=PERCENTRANK.INC(" . $secondaryAggregateRange . ",K{$i})",
        $this->aggregate($s, $secondary, $i),
        ""
      ];

      foreach($primary as $w) {
        if (!isset($w->id)) {
          $row[] = '';
        } else {
          $row[] = isset($s->{"wyap_" . $w->id ."_mark"}) ? $s->{"wyap_" . $w->id ."_mark"} : "";
        }
        // $row[] = $s->{"wyap_" . $p->id ."_pct"};
        $startCol = $this->columnLetter($w->startColumn);
        $row[] = "=round(100*{$startCol}{$i} / {$startCol}". '$' . "3, 1)";
        if ($w->hasGrades) {
          if (isset($w->id)) {
            $row[] = $s->{"wyap_" . $w->id ."_grade"};
          } else {
            //must be an ADD blank
            $row[] = '';
          }
        }
      }
      unset($w);
      $row[] = '';
      foreach($secondary as $w) {
        $row[] = $s->{"wyap_" . $w->id ."_mark"} ?? "";
        // $row[] = $s->{"wyap_" . $p->id ."_pct"};
        $startCol = $this->columnLetter($w->startColumn);
        $row[] = "=round(100*{$startCol}{$i} / {$startCol}". '$' . "3, 1)";
        if ($w->hasGrades) $row[] = $s->{"wyap_" . $w->id ."_grade"};

        $comment = $s->{"wyap_" . $w->id ."_comment"};
        if (strlen($comment) > 0) {
          $note = 'Comment: ' . $comment;
          $thisRow = count($sheetData) + 1;
          $cell = $this->columnLetter($w->startColumn) . $thisRow;
          $sheet->getComment($cell)->getText()->createTextRun($note);
          $sheet->getComment($cell)->setHeight("300px");
          $sheet->getComment($cell)->setWidth("200px");
        }


      }
      unset($w);

      if(count($secondary) > 0) $row[] = '';
      $row[] = $subject->year < 12 ? $s->removeEOYPercentage : $s->L6EOYPercentage;

      $row[] = "";

      // $row[] = strlen($s->hmNote) > 1 ? '!' :  '';

      $i++;
      $sheetData[] = $row;
    }

    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    );

    $sheet = $this->spreadsheet->getSheetByName('Students');
    $maxRow = count($this->subject->students)+4;
    $lastCol = $this->columnLetter($column + 11);
    $sheet->setAutoFilter('A4:' . $lastCol . $maxRow);

    $this->setMainStyle($sheet);
    $this->reset($sheet);

    // http://davidp.net/phpexcel-lock-cells/

  }

  private function aggregate($s, $wyaps, $i) {
    if (count($wyaps)===0) return '';
    $weightedSum = "";
    $sumOfWeights = "";
    $prefix = "";
    foreach($wyaps as $w) {
      $weightCell = $this->columnLetter($w->startColumn + 1) . '$1';
      $pctCell = $this->columnLetter($w->pctColumn) . $i;
      $weightedSum .= $prefix . $weightCell . "*" . $pctCell;
      $prefix = "+";
    }
    $startColumn = $this->columnLetter($wyaps[0]->startColumn);
    $endColumn = $this->columnLetter($wyaps[count($wyaps)-1]->endColumn);
    $sumOfWeights = 'SUMIFS($'.$startColumn.'$1:$'.$endColumn.'$1,'.$startColumn . $i .':'.$endColumn.$i.',">0")';
    return "=({$weightedSum})/({$sumOfWeights})";
  }

  private function reset($sheet) {
    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $sheet->getStyle('A1:A1')->applyFromArray($styleArray);
  }

  private function CAG($i) {
    $original = '=IF(P5<=Profile!E$4, Profile!C$4, IF(P5<=Profile!E$5, Profile!C$5, IF(P5<=Profile!E$6, Profile!C$6, IF(P5<=Profile!E$7, Profile!C$7, IF(P5<=Profile!E$8, Profile!C$8, IF(P5<=Profile!E$9, Profile!C$9, IF(P5<=Profile!E$10, Profile!C$10, IF(P5<=Profile!E$11, Profile!C$11, IF(P5<=Profile!E$12, Profile!C$12, IF(P5<=Profile!E$13, Profile!C$13, IF(P5<=Profile!E$14, Profile!C$14, "U" ) ) ) ) ))) ))))';
    return str_replace('P5', "P$i", $original);
  }

  private function setProtections() {

    return;

  }

  private function setMainStyle(&$sheet){

    if ($this->isPreU) {
      $sheet->getColumnDimension('J')->setVisible(false);
      $sheet->getColumnDimension('K')->setVisible(false);
    }

    $maxRow = count($this->subject->students)+4;

    //filters
    $sheet->mergeCells('I1:L1');

    //primary Wyaps
    foreach($this->primaryWyaps as $w) {
      $start = $this->columnLetter($w->startColumn);
      $end = $this->columnLetter($w->endColumn);
      $sheet->mergeCells("{$start}2:{$end}2");
      //Primary Data DARK GREEN
      $styleArray = [];
      $styleArray['fill'] = $this->primary1Fill;
      $sheet->getStyle("{$start}2:{$start}{$maxRow}")->applyFromArray($styleArray);

      //name as comment
      $remarkCell = "{$start}2";
      $sheet->getComment($remarkCell)->getText()->createTextRun($w->name);
      $sheet->getComment($remarkCell)->setHeight("300px");
      $sheet->getComment($remarkCell)->setWidth("200px");

      $styleArray['fill'] = $this->primary2Fill;
      $start = $this->columnLetter($w->startColumn + 1);
      $sheet->getStyle("{$start}2:{$start}{$maxRow}")->applyFromArray($styleArray);
      if ($w->hasGrades) {
        $start = $this->columnLetter($w->startColumn + 2);
        $sheet->getStyle("{$start}2:{$start}{$maxRow}")->applyFromArray($styleArray);
      }
    }

    //primary Wyaps
    foreach($this->secondaryWyaps as $w) {
      $start = $this->columnLetter($w->startColumn);
      $end = $this->columnLetter($w->endColumn);
      $sheet->mergeCells("{$start}2:{$end}2");
      //Primary Data DARK GREEN
      $styleArray = [];
      $styleArray['fill'] = $this->secondary1Fill;
      $sheet->getStyle("{$start}2:{$start}{$maxRow}")->applyFromArray($styleArray);

      //name as comment
      $remarkCell = "{$start}2";
      $sheet->getComment($remarkCell)->getText()->createTextRun($w->name);
      $sheet->getComment($remarkCell)->setHeight("300px");
      $sheet->getComment($remarkCell)->setWidth("200px");

      $styleArray['fill'] = $this->secondary2Fill;
      $start = $this->columnLetter($w->startColumn + 1);
      $sheet->getStyle("{$start}2:{$start}{$maxRow}")->applyFromArray($styleArray);
      if ($w->hasGrades) {
        $start = $this->columnLetter($w->startColumn + 2);
        $sheet->getStyle("{$start}2:{$start}{$maxRow}")->applyFromArray($styleArray);
      }
    }

    // $sheet->mergeCells('Q1:R1');
    // $sheet->mergeCells('F2:L2');
    // $sheet->mergeCells('Q2:AI2');

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
    $finalCol = $this->columnLetter($this->dataEndColumn + 8);
    $sheet->getStyle("D3:" . $finalCol . "3")->applyFromArray($styleArray);

    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $finalCol = $this->columnLetter($this->dataEndColumn + 9);
    $sheet->getStyle($finalCol . "2:" . $finalCol . $maxRow)->applyFromArray($styleArray);

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
    $sheet->getColumnDimension('Q')->setWidth(10);

    $width = 4.5;
    // foreach (range('F',$this->columnLetter($this->dataEndColumn + 8)) as $col) $sheet->getColumnDimension($col)->setWidth($width);
    foreach (range(6,$this->dataEndColumn + 8) as $col) $sheet->getColumnDimension($this->columnLetter($col))->setWidth($width);
    // $sheet->getColumnDimension('AA')->setWidth($width);
    // $sheet->getColumnDimension('AB')->setWidth($width);
    // $sheet->getColumnDimension('AC')->setWidth($width);
    // $sheet->getColumnDimension('AD')->setWidth($width);
    // $sheet->getColumnDimension('AE')->setWidth($width);
    // $sheet->getColumnDimension('AF')->setWidth($width);
    // $sheet->getColumnDimension('AG')->setWidth($width);
    // $sheet->getColumnDimension('AH')->setWidth($width);
    // $sheet->getColumnDimension('AI')->setWidth($width);
    // $sheet->getColumnDimension('AJ')->setWidth($width);
    // $sheet->getColumnDimension('AK')->setWidth($width);
    // $sheet->getColumnDimension('AL')->setWidth($width);
    // $sheet->getColumnDimension('AM')->setWidth($width);
    // $sheet->getColumnDimension('AN')->setWidth($width);
    // $sheet->getColumnDimension('AO')->setWidth($width);

    $remarkCol = $this->columnLetter($this->dataEndColumn + 9);
    $sheet->getColumnDimension($remarkCol)->setWidth(50);


     // borders
     $styleArray = [
     'borders' => [
         'outline' => [
             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                 'color' => ['argb' => '00000000'],
             ],
         ],
     ];
     $sheet->getStyle('E3:E' . $maxRow)->applyFromArray($styleArray);

     $styleArray = [];
     //final rank / grade / sigs / remarks
     $styleArray['fill'] = $this->finalFill;

     $sheet->getStyle('E3:E' .$maxRow)->applyFromArray($styleArray);
     $sheet->getStyle("{$this->columnLetter($this->dataEndColumn + 5)}3:{$this->columnLetter($this->dataEndColumn + 6)}" .$maxRow)->applyFromArray($styleArray);
     // $sheet->getStyle("{$this->columnLetter($this->dataEndColumn + 4)}3:{$this->columnLetter($this->dataEndColumn + 4)}" .$maxRow)->applyFromArray($styleArray);

     //provisional grade
     $styleArray = [];
     $styleArray['fill'] = $this->provisionalFill;

     $sheet->getStyle('G3:G' .$maxRow)->applyFromArray($styleArray);


     //Primary Aggregate
     $styleArray = [];
     $styleArray['fill'] = $this->primary1Fill;
     $sheet->getStyle('H3:H' . $maxRow)->applyFromArray($styleArray);

     //Primary Percentil
     $styleArray = [];
     $styleArray['fill'] = $this->primary2Fill;
     $sheet->getStyle('I3:I' . $maxRow)->applyFromArray($styleArray);

     // Secondary Percentil
     $styleArray = [];
     $styleArray['fill'] = $this->secondary2Fill;
     $sheet->getStyle('J3:J' . $maxRow)->applyFromArray($styleArray);

     // secondary Aggregate
     $styleArray = [];
     $styleArray['fill'] = $this->secondary1Fill;
     $sheet->getStyle('K3:K' . $maxRow)->applyFromArray($styleArray);

     // L6, Remove Exam
     $col= $this->columnLetter($this->dataEndColumn - 2);
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'ffc0cb',
       ],
       'endColor' => [
           'argb' => 'ffc0cb',
       ],
     ];
     $sheet->getStyle("{$col}3:{$col}" .$maxRow)->applyFromArray($styleArray);

     //rows with hm notes. Box goes re
     $col1 = $this->columnLetter($this->dataEndColumn);
     $col2 = $this->columnLetter($this->dataEndColumn + 1);

     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'ffdb99',
       ],
       'endColor' => [
           'argb' => 'ffdb99',
       ],
     ];

     $sheet->getStyle("{$col1}3:{$col2}" .$maxRow)->applyFromArray($styleArray);

     $styleArray = [];
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
       $sheet->getStyle("$col1$r:$col1$r")->applyFromArray($styleArray);
       $note = $this->hmNotes['c_' . $r];
       $sheet->getComment("$col1$r")->getText()->createTextRun($note);
       $sheet->getComment("$col1$r")->setHeight("300px");
       $sheet->getComment("$col1$r")->setWidth("200px");
     }

     //rows with access Arrangements
     $col1 = $this->columnLetter($this->dataEndColumn + 3);
     $col2 = $this->columnLetter($this->dataEndColumn + 4);

     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'ffdb99',
       ],
       'endColor' => [
           'argb' => 'ffdb99',
       ],
     ];

     $sheet->getStyle("{$col1}3:{$col1}" .$maxRow)->applyFromArray($styleArray);

     $styleArray = [];
     $styleArray['fill'] = [
       'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
       'startColor' => [
           'argb' => 'FFF9910A',
       ],
       'endColor' => [
           'argb' => 'FFF9910A',
       ],
     ];

     foreach($this->rowsWithAccessArrangements as $r) {
       $access = $this->accessArrangements['c_' . $r];
       $note = '';
       $sheet->getStyle("$col1$r:$col1$r")->applyFromArray($styleArray);
       foreach($access as $key => $a) {
         if ($key === 'hasAccess') continue;
         $key = str_replace('has', '', $key);
         $key = str_replace('extraTime', 'Extra Time', $key);
         $logic = $a;
         if ($a == 1) $logic = 'yes';
         if ($a == 0) $logic = 'no';

         if ($logic == 'no') continue;
         if ($a) $note .= $key . ': ' . $logic . "\n";
       }
       $sheet->getComment("$col1$r")->getText()->createTextRun($note);
       $sheet->getComment("$col1$r")->setHeight("300px");
       $sheet->getComment("$col1$r")->setWidth("200px");
     }


  }

  private function generateProfileData($subject, $sheet)
  {

    $sheetData = [];
    //first row
    $sheetData[] = ['Final Grade Profile'];
    $sheetData[] = ['All:', '=Sum(D17:D35)', '' , 'Boys', '', 'Girls'];
    $sheetData[] = ['Grades', '#', '%', '#', '%', '#', '%'];

    $i = 4 + 13;
    $pointsData = [];
    $pointsData[] = ['GPA', '=Round(SUM(T7:T30)/D15,2)'];
    $pointsData[] = [];
    $pointsData[] = ['Grade', 'Points', 'Total'];
    foreach($this->gradesForCounting as $g){
      $gSanitized = str_replace('~', '', $g);
      $points = $subject->year > 11 ? (new \Exams\Tools\ALevel\Result())->processGrade($gSanitized) : (new \Exams\Tools\GCSE\Result())->processGrade($gSanitized);
      $pRow = $i - 10;
      $pointsData[] = [$g, $points, '=S' . $pRow . "*D" . $i];
      $sheetData[] = [
        $g,
        str_replace('$C4', '$C'.$i, '=COUNTIF(Students!E$5:E$445,$C4)'),
        '= IF(D'.$i.'>0, ROUND(100 * COUNTIF(Students!E$5:E$445, $C'.$i.') / COUNTA(Students!E$5:E$445),1), 0)',
        '=COUNTIF(V$4:V$300,$C'.$i.')',
        '=ROUND(100 * COUNTIF(V$4:V$300, $C'.$i.') / COUNTIF(Students!D$5:D$445,"M"),1)',
        '=COUNTIF(W$4:W$300,$C'.$i.')',
        '=ROUND(100 * COUNTIF(W$4:W$300, $C'.$i.') / COUNTIF(Students!D$5:D$445,"F"),1)'
      ];
      $i++;
    }

    $sheet->fromArray(
        $pointsData,  // The data to set
        NULL,        // Array values with this value will not be set
        'R4'         // Top left coordinate of the worksheet range where
    );

    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'C14'         // Top left coordinate of the worksheet range where
    );


    $sheetData = [];
    $sheetData[] = ['Boys', 'Girls'];
    $i=5;
    foreach($subject->students as $s){
      $sheetData[] = [
        '=IF(Students!D' . $i .' = "M", Students!E' . $i .', "")',
        '=IF(Students!D' . $i .' = "F", Students!E' . $i .', "")'
      ];
      $i++;
    }

    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'V4'         // Top left coordinate of the worksheet range where
    );



    $this->setProfileStyle($sheet);

    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $sheet->getStyle('A1:AI3')->applyFromArray($styleArray);

    $this->reset($sheet);

  }



  private function setProfileStyle($sheet){

    $sheet->mergeCells('C14:E14');

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
    $count = 13 + count($this->grades)+3;
    $sheet->getStyle('C17:C' . $count)->applyFromArray($styleArray);
    $sheet->getStyle('C15:E16')->applyFromArray($styleArray);
    $sheet->getStyle('C14:E14')->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FF92B7FE',
      ],
      'endColor' => [
          'argb' => 'FF92B7FE',
      ],
    ];
    $sheet->getStyle('F15:G16')->applyFromArray($styleArray);

    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FFDF5286',
      ],
      'endColor' => [
          'argb' => 'FFDF5286',
      ],
    ];
    $sheet->getStyle('H15:I16')->applyFromArray($styleArray);

    $sheet->mergeCells('F15:G15');
    $sheet->mergeCells('H15:I15');

    $sheet->getColumnDimension('R')->setVisible(false);
    $sheet->getColumnDimension('S')->setVisible(false);
    $sheet->getColumnDimension('T')->setVisible(false);
    $sheet->getColumnDimension('K')->setVisible(false);
    $sheet->getColumnDimension('L')->setVisible(false);
    $sheet->getColumnDimension('V')->setVisible(false);
    $sheet->getColumnDimension('W')->setVisible(false);


    $styleArray = [
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
      'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '00000000']
            ]
      ]
    ];
    $sheet->getStyle('C14:E14')->applyFromArray($styleArray);

    $styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];


  }

  private function generateQASheet($subject){
    $title = 'Quality Assurance';
    $spreadsheet = $this->spreadsheet;
    $worksheet = new Worksheet($spreadsheet, $title);
    $spreadsheet->addSheet($worksheet, 0);

    $color = 'E05A00';
    $worksheet->getTabColor()->setRGB($color);

    // //sheet title
    $sheet = $spreadsheet->getSheetByName($title);

    $bands = $subject->bands;
    $history = $subject->bandedHistory;

    $sheetData = [];
    //first row
    //header row
    $header = [];
    $header[] = '%';
    foreach($bands as $b) $header[] = $b;
    $sheetData[] = $header;

    // final grade profile
    $thisYear = ['2021 Final'];
    //A level has an extra avg gcse column, which obs isnt a band
    $formula = '=Round(';
    $prefix = '';
    $startRow = 17;
    $maxCol = $subject->year < 12 ? count($bands) : count($bands) - 1;
    // -1 TO NOT INCLUDE GPA COLUMN
    for ($x = 0; $x < $maxCol - 1; $x++) {
      $row = $startRow + $x;
      $formula .= $prefix . "E" . $row;
      $prefix = '+';
      $thisYear[] = $formula . ', 0)';
    }
    $thisYear[] = '=S4';
    if ($subject->year > 11) $thisYear[] = $subject->gcseGPA;

    $sheetData[] = $thisYear;


    //data
    foreach($history as $h) {
      $row = [$h['year']];
      foreach($h['results'] as $r) $row[] = $r['pct'];
      $sheetData[] = $row;
    }

    $sheet->fromArray(
        $sheetData,  // The data to set
        NULL,        // Array values with this value will not be set
        'B2'         // Top left coordinate of the worksheet range where
    );

    $sheet->getColumnDimension('B')->setWidth(18);

    $styleArray = [
      'font' => [
          'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      ]
    ];
    // Ranking Backgrounds
    $styleArray['fill'] = [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
      'startColor' => [
          'argb' => 'FFf9b247',
      ],
      'endColor' => [
          'argb' => 'FFf9b247',
      ],
    ];

    $cols = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];

    $maxRow = count($subject->bandedHistory)+3;
    $maxCol = $cols[count($bands) + 3];

    $sheet->getStyle('B2:B' . $maxRow)->applyFromArray($styleArray);
    $sheet->getStyle('B2:'. $maxCol.'2')->applyFromArray($styleArray);

    $styleArray = [
      'font' => [
          'bold' => true,
      ]
    ];
    $sheet->getStyle('A1:AI3')->applyFromArray($styleArray);
    $this->reset($sheet);

    $this->generateProfileData($subject, $sheet);
  }

  // https://icesquare.com/wordpress/example-code-to-convert-a-number-to-excel-column-letter/
  private function columnLetter($c){
    $c = intval($c);
    if ($c <= 0) return '';
    $letter = '';
    while($c != 0){
      $p = ($c - 1) % 26;
      $c = intval(($c - $p) / 26);
      $letter = chr(65 + $p) . $letter;
    }
    return $letter;
  }

  private $primary1Fill = [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
    'startColor' => [
        'argb' => 'FF90EE90',
    ],
    'endColor' => [
        'argb' => 'FF90EE90',
    ],
  ];
  private $primary2Fill = [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
    'startColor' => [
          'argb' => 'FFD2F8D2',
      ],
      'endColor' => [
          'argb' => 'FFD2F8D2',
      ],
  ];

  private $secondary1Fill = [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
    'startColor' => [
        'argb' => 'FABD02',
    ],
    'endColor' => [
        'argb' => 'FABD02',
    ],
  ];

  private $secondary2Fill = [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
    'startColor' => [
        'argb' => 'FEEB75',
    ],
    'endColor' => [
        'argb' => 'FEEB75',
    ],
  ];

  private $finalFill = [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
    'startColor' => [
        'argb' => 'FF92D5E6',
    ],
    'endColor' => [
        'argb' => 'FF92D5E6',
    ],
  ];

  private $provisionalFill = [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
    'startColor' => [
        'argb' => 'FFE1C4FF',
    ],
    'endColor' => [
        'argb' => 'FF#E1C4FF',
    ],
  ];

}
