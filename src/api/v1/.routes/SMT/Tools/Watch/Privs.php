<?php

namespace SMT\Tools\Watch;

Class Privs{
  
  public $students = [];
  public $count;
  private $ada, $sql_MCCustom, $sql_iSAMS;
  
  function __construct(\Dependency\Databases\Ada $ada, \Dependency\Databases\ISams $isams, \Dependency\Databases\MCCustom $mcCustom)
  {
    $this->debug = false;
    $this->ada = $ada;
    $this->sql_MCCustom = $mcCustom;
    $this->sql_iSAMS = $isams;
  }

  //returns an array of MIS Id's for this date
  function byDate($date)
  {
    //prelims
    $date = (string)$this->sql_MCCustom->dateFromUnix($date);
    // $this->getStudentBoarding();
    $d = $this->sql_MCCustom->select(
      'TblPrivs',
      'TblPrivsID, txtSchoolID, dtePrivDate',
      'dtePrivDate = ? ORDER BY TblPrivsID DESC',
      [$date]);
    
    $data = [];
    foreach($d as $row){
      $schoolId = $row['txtSchoolID'];
      $student = new \Entities\People\Student($this->ada);
      $student->byMISId($schoolId);
      $data[] = $student;
    }
    $this->students = $data;
    
    $allHouses = new \Entities\Houses\All($this->ada);
    $this->houses = $allHouses->sortStudents($this->students);
    
    $this->count = count($this->students);
    return $data;
  }

}
 ?>
