<?php
namespace Entities\People;

class iSamsStaff
{
  public $boarding = array();
  public $tutees = array();
  public $details = array();
  private $sql;

  public function __construct($msSql, $UserCode = null)
  {
    $this->sql= $msSql;
  }

  public function initials($txtInitials)
  {
    $this->initials = $txtInitials;
    $d = $this->sql->select(
      'TblStaff',
      'User_Code',
      'Initials=?', array($txtInitials));

    if (isset($d[0]))
    {
      $userCode = $d[0]['User_Code'];
      $this->userCode($userCode);
    }
  }

  public function userCode($userCode)
  {
    $this->userCode = $userCode;
    $this->basicInformation($userCode);
    $this->getTutees();
  }

  public function basicInformation($userCode)
  {
    $this->userCode = $userCode;

    $d = $this->sql->select(
      'TblStaff',
      'Initials, Title, Firstname, MiddleNames, Surname, PreName, Salutation, DOB, Sex, SchoolEmailAddress',
      'User_Code=?', array($userCode));

    $this->details = isset($d[0]) ? $d[0] : array();
  }

  public function getTutees()
  {
    $tutees = $this->sql->select(
      'TblPupilManagementPupils',
      'txtSchoolID',
      'txtTutor = ?',
      array($this->userCode)
    );
    foreach($tutees as $tutee)
    {
      $this->tutees[] = new \Entities\People\iSamsPupil($this->sql, $tutee['txtSchoolID']);
    }
  }

}

 ?>
