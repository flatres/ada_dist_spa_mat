<?php
namespace Entities\People;

class iSamsStudent
{
  public $firstName, $lastName, $fullName, $initials, $gender, $dob, $enrolmentNVYear, $enrolmentSchoolYear, $boardingHouse;
  public $familyId;
  public $adaId;
  public $contacts = [
    'mother' => [],
    'father' => []
  ];
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
      'txtSchoolID, intFamily, txtForename, txtSurname, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear, txtMobileNumber',
      'txtSchoolID=?', [$id]);

    if(isset($d[0])){
      $d = $d[0];
      $this->firstName = $d['txtForename'];
      $this->lastName = $d['txtSurname'];
      $this->fullName = $d['txtFullName'];
      $this->initials = $d['txtInitials'];
      $this->familyId = $d['intFamily'];
      $this->mobile = $d['txtMobileNumber'];
      // $this->familyID = $d['txtFamily'];
      $this->gender = $d['txtGender'];
      $this->dob = $d['txtDOB'];
      $this->enrolmentNCYear = $d['intEnrolmentNCYear'];
      $this->enrolmentSchoolYear = $d['intEnrolmentSchoolYear'];
      $this->boardingHouse = $d['txtBoardingHouse'];

      $this->getAdaId();
    }
  }

  //returns an array of \Entities\People\isamsParent s accociated with this pupil's family Id
  public function family()
  {

  }

  private function getAdaId()
  {
    $ada = new \Dependency\Databases\Ada();
    $d = $ada->select('stu_details', 'id', 'mis_id=?', [$this->id]);
    $this->adaId = $d[0]['id'] ?? false;
    return $this->adaId;
  }

  public function contacts()
  {
    if (!$this->familyId) return;
    $d = $this->sql->select('TblPupilManagementFamily', 'intMotherID, intFatherID', 'TblPupilManagementFamilyID=?', [$this->familyId] );
    if(isset($d[0])){
      $motherId = $d[0]['intMotherID'];
      $fatherId = $d[0]['intFatherID'];
      $address = $this->sql->select(  'TblPupilManagementAddresses',
                                      'txtLetterSalutation as letterSalulation,
                                      txtEmail1 as email1,
                                      txtEmail2 as email2,
                                      txtRelationType as relationship,
                                      txtContactsTitle as title1',
                                      'txtContactsForename as forename1',
                                      'txtContactsSurname as surname1',
                                      'txtSecondaryTitle as title2',
                                      'txtSecondaryForename as forename2',
                                      'txtSecondarySurname as surname2',
                                      'TblPupilManagementAddressesID=?',
                                      [$d[0]['intAddressID']])[0];
                                      
    }
    return $address[0];
  }
}

?>
