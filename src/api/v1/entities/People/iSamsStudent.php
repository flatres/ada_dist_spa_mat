<?php
namespace Entities\People;

class iSamsStudent
{
  public $firstName, $lastName, $fullName, $initials, $gender, $dob, $enrolmentNVYear, $enrolmentSchoolYear, $boardingHouse;
  public $familyId;
  public $adaId;
  private $sql;

  public function __construct(\Dependency\Databases\MCCustom $msSql, (int) $id = null)
  {
    $this->sql= $msSql;
    if($id) $this->byId($id);
  }

  public function byId($id)
  {
    $this->id = $txtSchoolID;
    $d = $this->sql->select(
      'TblPupilManagementPupils',
      'txtSchoolID, intFamily, txtForename, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear',
      'txtSchoolID=?', [$id]);

    if(isset($d[0])){
      $d = $d[0];
      $this->firstName = $d['txtForename'];
      $this->lastName = $d['txtSurname'];
      $this->fullName = $d['txtFullName'];
      $this->initials = $d['txtInitials'];
      $this->familyId = $d['intFamily'];
      // $this->familyID = $d['txtFamily'];
      $this->gender = $d['txtGender'];
      $this->dob = $d['txtDOB'];
      $this->enrolmentNCYear = $d['intEnrolmentNCYear'];
      $this->enrolmentSchoolYear = $d['intEnrolmentSchoolYear'];
      $this->boardingHouse = $d['txtBoardingHouse'];
      
      $this->getAdaId();
    }
  }

  private function getAdaId()
  {
    $ada = new \Dependency\Databases\Ada();
    $d = $ada->select('stu_details', 'id', 'mis_id=?', [$this->id]);
    $this->adaId = $d[0]['id'] ?? false;
    return $this->adaId;
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
