<?php
namespace Entities\People;

class iSamsStudent
{
  public $firstName, $lastName, $fullName, $displayName, $initials, $gender, $dob, $enrolmentNVYear, $enrolmentSchoolYear, $NCYear, $boardingHouse, $mobile;
  public $familyId, $year, $formId;
  public $id, $adaId;
  public $contacts = [];
  public $portalUserCodes = [];
  // public $subjects = [];
  public $sets=[];

  private $sql;

  public function __construct(\Dependency\Databases\isams $msSql, string $id = null) //must be cast as string as isams id can start with 0
  {
    $this->sql= $msSql;
    if($id) $this->byId($id);
  }

  public function byId($id)
  {
    $this->id = $id;

    $d = $this->sql->select(
      'TblPupilManagementPupils',
      'intNCYear, txtSchoolID, intFamily, txtForename, txtSurname, txtForm, txtFullName, txtInitials, txtGender, txtDOB, intEnrolmentNCYear, txtBoardingHouse, txtLeavingBoardingHouse, intEnrolmentSchoolYear, txtMobileNumber',
      'txtSchoolID=?', [$id]);

    if(isset($d[0])){
      $d = $d[0];
      $this->firstName = $d['txtForename'];
      $this->lastName = $d['txtSurname'];
      $this->lastName = $d['txtSurname'];
      $this->fullName = $d['txtFullName'];
      $this->displayName = $d['txtSurname'] . ", " . $d['txtForename'];
      $this->initials = $d['txtInitials'];
      $this->familyId = $d['intFamily'];
      $this->formId = $d['txtForm'];
      $this->mobile = $d['txtMobileNumber'];
      // $this->familyID = $d['txtFamily'];
      $this->gender = $d['txtGender'];
      $this->dob = $d['txtDOB'];
      $this->enrolmentNCYear = $d['intEnrolmentNCYear'];
      $this->enrolmentSchoolYear = $d['intEnrolmentSchoolYear'];
      $this->boardingHouse = $d['txtBoardingHouse'];
      $this->NCYear = $d['intNCYear'];
      $this->year = $this->makeYear($d['intNCYear']);
      $this->getAdaId();
    }
  }

  private function makeYear($intYear) {
    switch ($intYear) {
      case 9: return 'Shell';
      case 10: return 'Remove';
      case 11: return 'Hundred';
      case 12: return 'L6';
      case 13: return 'U6';
    }
    return "";
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
    return $this;
  }

  public function byAdaId($id)
  {
    $ada = new \Dependency\Databases\Ada();
    $d = $ada->select('stu_details', 'mis_id', 'id=?', [$id]);
    $misId = $d[0]['mis_id'] ?? false;
    $this->byId($misId);
    return $this;
  }

  public function getContacts()
  {
    if (!$this->familyId) return [];
    $d = $this->sql->select('TblPupilManagementFamily', 'intMotherID, intFatherID', 'TblPupilManagementFamilyID=?', [$this->familyId] );
    $contacts = [];
    if(isset($d[0])){
      $motherId = $d[0]['intMotherID'];
      $fatherId = $d[0]['intFatherID'];
      $d= $this->sql->select(  'TblPupilManagementAddresses',
                                        'txtLetterSalutation as letterSalutation,
                                        txtEmail1 as email1,
                                        txtEmail2 as email2,
                                        txtRelationType as relationship,
                                        txtContactsTitle as title1,
                                        txtContactsForename as forename1,
                                        txtContactsSurname as surname1,
                                        txtSecondaryTitle as title2,
                                        txtSecondaryForename as forename2,
                                        txtSecondarySurname as surname2',
                                        'TblPupilManagementAddressesID=?',
                                        [$motherId]
                                        )[0];

      if ($d['forename1']) {
        $contacts[] = [
          'title'           => $d['title1'],
          'relationship'    => $d['relationship'],
          'firstName'       => $d['forename1'],
          'lastName'        => $d['surname1'],
          'email'           => $d['email1'],
          'letterSalulation'=> $d['letterSalutation'],
          'portalUserInfo' => $this->hasPortalAccess($d['email1'])
        ];
      }
      if ($d['forename2']) {
        $contacts[] = [
          'title'           => $d['title2'],
          'relationship'    => $d['relationship'],
          'firstName'       => $d['forename2'],
          'lastName'        => $d['surname2'],
          'email'           => $d['email2'],
          'letterSalulation'=> $d['letterSalutation'],
          'portalUserInfo' => $this->hasPortalAccess($d['email2'])
        ];
      }

      if($motherId !== $fatherId && $fatherId) {
        $d = $this->sql->select(  'TblPupilManagementAddresses',
                                          'txtLetterSalutation as letterSalutation,
                                          txtEmail1 as email1,
                                          txtEmail2 as email2,
                                          txtRelationType as relationship,
                                          txtContactsTitle as title1,
                                          txtContactsForename as forename1,
                                          txtContactsSurname as surname1,
                                          txtSecondaryTitle as title2,
                                          txtSecondaryForename as forename2,
                                          txtSecondarySurname as surname2',
                                          'TblPupilManagementAddressesID=?',
                                          [$fatherId]
                                          )[0];
        if ($d['forename1']) {
          $contacts[] = [
            'title'           => $d['title1'],
            'relationship'    => $d['relationship'],
            'firstName'       => $d['forename1'],
            'lastName'        => $d['surname1'],
            'email'           => $d['email1'],
            'letterSalulation'=> $d['letterSalutation'],
            'portalUserInfo' => $this->hasPortalAccess($d['email1'])
          ];
        }
        if ($d['forename2']) {
          $contacts[] = [
            'title'             => $d['title2'],
            'relationship'      => $d['relationship'],
            'firstName'         => $d['forename2'],
            'lastName'          => $d['surname2'],
            'email'             => $d['email2'],
            'letterSalulation'  => $d['letterSalutation'],
            'portalUserInfo' => $this->hasPortalAccess($d['email2'])
          ];
        }
      }
    }
    $this->contacts = $contacts;
    return $contacts;
  }

  private function hasPortalAccess($email) {
    if(!$email || strlen($email) === 0) return false;
    $d = $this->sql->select('TbliSAMSManagerUsers', 'TbliSAMSManagerUsersID, txtEmailAddress, txtUserCode', 'txtEmailAddress=?', [$email]);
    if (isset($d[0])) {
      $this->portalUserCodes[] = $d[0]['txtUserCode'];
      return [
        'userCode'  => $d[0]['txtUserCode'],
        'userId'    => $d[0]['TbliSAMSManagerUsersID']
      ];
    } else {
      return false;
    }
  }

  public function getSets() {
    // normal sets
    $sets = $this->sql->select( 'TblTeachingManagerSetLists', 'intSetID', 'txtSchoolID=?', [$this->id]);
    $this->sets = [];
    foreach ($sets as $set) {
      $this->sets[] = new \Entities\Academic\iSamsSet($this->sql, $set['intSetID']);
    }
    // now forms
    $forms = $this->sql->select('TblTeachingManagerSubjectForms', 'TblTeachingManagerSubjectFormsID as id', 'txtForm=?', [$this->formId]);
    // var_dump($forms);
    foreach ($forms as $form) {
      $this->sets[] = new \Entities\Academic\iSamsForm($this->sql, $form['id']);
    }
  }

  public function getSubjects() {
    $this->getSets();
  }
}

?>
