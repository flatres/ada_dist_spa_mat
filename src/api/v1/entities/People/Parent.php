<?php
namespace Entities\People;

class iSamsParent
{

  public function __construct(\Dependency\Databases\MCCustom $msSql, (int) $id = null)
  {
    $this->sql= $msSql;
    if($id) $this->byId($id);
  }

  public function byId($id)
  {
    $d = $this->sql->select(
      'TblPupilManagementAddresses',
      'TblPupilManagementAddressesID,
      txtLetterSalutation, txtRelationType, txtContactsForename, txtContactsSurname, txtAddress1, txtAddress2, txtAddress3, txtTown',
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
