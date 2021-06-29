<?php
//An isams user can be either a member of staff or parent. Their details are held in TbsiSAMSManagerUsers

namespace Entities\People;

class iSamsStudentContact
{
  public $id;
  public $title, $firstName, $lastName, $letterSalutation;
  public $email, $relationship;

  private $sql;

  public function __construct(\Dependency\Databases\isams $msSql, int $id = null)
  {
    $this->sql= $msSql;
    if($id) $this->byId($id);
  }

  public function byEmail($email)
  {
    $this->email = $email;
    $d = $this->sql->select(  'TblPupilManagementAddresses',
                                          'txtLetterSalutation as letterSalutation,
                                          txtEmail1 as email,
                                          txtRelationType as relationship,
                                          txtContactsTitle as title,
                                          txtContactsForename as firstName,
                                          txtContactsSurname as lastName',
                                          'txtEmail1=?',
                                          [$email]
  );
    if(isset($d[0])){
      saveToObject($d[0], $this);
      return $this;
    }

    $d = $this->sql->select(  'TblPupilManagementAddresses',
                                          'txtLetterSalutation as letterSalutation,
                                          txtEmail2 as email,
                                          txtRelationType as relationship,
                                          txtSecondaryTitle as title,
                                          txtSecondaryForename as firstName,
                                          txtSecondarySurname as lastName',
                                          'txtEmail2=?',
                                          [$email]
  );
    if(isset($d[0])){
      saveToObject($d[0], $this);
    }

    return $this;
  }

}
