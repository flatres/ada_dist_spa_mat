<?php
namespace Entities\Students\Tools;

class iSamsPupil
{
  public $firstName, $lastName, $fullName, $initials, $gender, $dob, $enrolmentNVYear, $enrolmentSchoolYear, $boarding;
  private $sql;

  public function __construct($msSql, $txtSchoolID = null)
  {
    $this->sql= $msSql;
    if($txtSchoolID) $this->basicInformation($txtSchoolID);
  }

  public function basicInformation($txtSchoolID)
  {
    $this->txtSchoolID = $txtSchoolID;

    $d = $this->sql->select(  'TblPupilManagementPupils',
                                        'txtSchoolID, txtForename, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear',
                                        'txtSchoolID=?', array($txtSchoolID));

    if(isset($d[0])){
      $d = $d[0];
      $this->firstName = $d['txtForename'];
      $this->lastName = $d['txtSurname'];
      $this->fullName = $d['txtFullName'];
      $this->initials = $d['txtInitials'];
      // $this->familyID = $d['txtFamily'];
      $this->gender = $d['txtGender'];
      $this->dob = $d['txtDOB'];
      $this->enrolmentNCYear = $d['intEnrolmentNCYear'];
      $this->enrolmentSchoolYear = $d['intEnrolmentSchoolYear'];
      $this->boarding = $d['txtBoardingHouse'];
    }
  }

  public function family()
  {
    $d = $this->sql->select('TblPupilManagementAddressLink', 'intAddressID', 'txtSchoolID=?', array($this->txtSchoolID));
    if(isset($d[0])){
      $address = $this->sql->select(  'TblPupilManagementAddresses',
                                      'txtLabelSalutation, txtEmail1, txtEmail2',
                                      'TblPupilManagementAddressesID=?',
                                      [$d[0]['intAddressID']]);
    }
    return $address[0];
  }

}

 ?>
