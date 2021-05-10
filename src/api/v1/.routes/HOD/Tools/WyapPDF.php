<?php
namespace HOD\Tools;
use Fpdf\Fpdf;

class WyapPDF
{
  public $subject, $wyaps;
  public $package;
  public $primaryWyaps, $secondaryWyaps;
  public $dataEndColumn;
  public $isPreU;

  public function __construct($subject, $wyaps)
  {
     $this->subject = $subject;
     $this->wyaps = $wyaps;
     $this->generate();
  }

  public function generate() {
    $pdf = new FPDF();
    $subject = $this->subject;

    $this->getGrades($subject);
    $primary = $this->primaryWyaps();
    $data = [];
    foreach($primary as $w) {
      $data[] = [$w->name, ' '];
    }
    if (!$this->isPreU) {
      $secondary = $this->secondaryWyaps();
      foreach($secondary as $w) {
        $data[] = [$w->name, ' '];
      }
    }

    foreach ($this->subject->students as $s) {
      $pdf->AddPage();
      $pdf->SetY(25);
      $pdf->Image('https://www.marlboroughcollege.org/wp-content/uploads/2020/07/wp-staff-feature-logo-969x650.jpg',64,17,80);
      $pdf->SetFont('Times','B',20);

      $pdf->SetY(75);
      $pdf->Cell(0,10,$s->displayName, 0, 1, 'C');
      $pdf->Cell(0,10,$s->schoolNumber, 0, 1, 'C');
      $pdf->SetFont('Times','',18);
      $pdf->Cell(0,10,$subject->name, 0, 1, 'C');

      $pdf->SetFont('Times','',16);
      $pdf->Cell(0,10,$s->classCode, 0, 1, 'C');

      $pdf->SetFont('Times','',13);

      $pdf->Ln();
      $header = array('Assessment Point', 'Substitution');
      $this->table($pdf, $header,$data);

      $pdf->Ln();
      $pdf->Ln();
      $pdf->Cell(0,10,"    ", 0, 1, 'C');

      $text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
      $pdf->MultiCell(0, 8, $text);

      $pdf->Ln();
      $pdf->Ln();

      $pdf->SetFont('Times','B',16);
      $pdf->Cell(0,10,'Signature:    __________________________________________', 0, 1, 'L');
      $pdf->Ln();
      $pdf->Cell(0,10,'Print Name: __________________________________________', 0, 1, 'L');

      break;

    }
      // $s->displayName,
      // str_replace('(FM)', '', $s->classCode),
      // $s->schoolNumber,

    $path = 'hod/';
    $filename = 'TEST.pdf';
    $filepath = FILESTORE_PATH . "$path$filename";

    $pdf->Output("F", $filepath);

    $url = FILESTORE_URL . "$path$filename";

    $this->package = [
      'file' => $filename,
      'url'  => $url
    ];

  }

  // Better table
private function table(&$pdf, $header, $data)
{
    // Column widths
    $w = array(90,0);
    // Header
    $pdf->SetFont('Times','B',13);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'L');
        $pdf->Ln();
    // Data
    $pdf->SetFont('Times','',13);
    foreach($data as $row)
    {
        $pdf->Cell($w[0],7,$row[0],'LRT');
        $pdf->Cell($w[1],7,$row[1],'LRT');
        $pdf->Ln();
    }
    // Closing line
    $pdf->Cell(0,0,'','T');
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


}
