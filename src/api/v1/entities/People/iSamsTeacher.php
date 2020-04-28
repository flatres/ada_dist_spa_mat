<?php
//An isams user can be either a member of staff or parent. Their details are held in TbsiSAMSManagerUsers

namespace Entities\People;

class iSamsTeacher
{
  public $id;
  public $title, $firstName, $lastName, $fullName, $userName;
  public $email, $userCode, $userType, $passwordHash;
  public $sets = [];

  private $sql;

  public function __construct(\Dependency\Databases\isams $msSql, int $id = null)
  {
    $this->sql= $msSql;
    if($id) $this->byId($id);
  }

  public function byId($id)
  {
    $this->id = $id;
    $d = $this->sql->select(
      'TbliSAMSManagerUsers',
      'txtUsername as userName, txtUserCode as userCode, txtTitle as title, txtFirstname as firstName, txtSurname as lastName, txtFullName as fullName, txtEmailAddress as email, txtPassword as passwordHash, txtUserType as userType',
      'intPersonID=?', [$id]);

    if(isset($d[0])){
      saveToObject($d[0], $this);
    }
    return $this;
  }

  public function byAdaId($id)
  {
    $ada = new \Dependency\Databases\Ada();
    $d = $ada->select('usr_details', 'mis_id', 'id=?', [$id]);
    $misId = $d[0]['mis_id'] ?? false;
    $this->byId($misId);
    return $this;
  }

  public function byUserCode($userCode)
  {
    $d = $this->sql->select(
      'TbliSAMSManagerUsers',
      'TbliSAMSManagerUsersID',
      'txtUserCode=?',
      [$userCode]
    );
    if (isset($d[0])) {
      $this->byId($d[0]['TbliSAMSManagerUsersID']);
    }

    return $this;
  }

  public function getSets() {
    // search for sets as primary teacher
    $d = $this->sql->select(
      'TblTeachingManagerSets',
      'TblTeachingManagerSetsID as id',
      'txtTeacher=?', [$this->userCode]);

    foreach($d as $set) {
      $s  = new \Entities\Academic\iSamsSet($this->sql, $set['id']);
      $this->sets[] = $s;
      if ($s->furtherMathsOtherSet) $this->sets[] = $s->furtherMathsOtherSet;
    }

    // secondary teacher
    $d = $this->sql->select(
      'TblTeachingManagerSetAssociatedTeachers',
      'intSetID as id',
      'txtTeacher=?', [$this->userCode]);

    foreach($d as $set) {
      $s  = new \Entities\Academic\iSamsSet($this->sql, $set['id']);
      $this->sets[] = $s;
      if ($s->furtherMathsOtherSet) $this->sets[] = $s->furtherMathsOtherSet;
    }

    // form teachers
    $d = $this->sql->select(
      'TblTeachingManagerSubjectForms',
      'TblTeachingManagerSubjectFormsID as id',
      'txtTeacher=?', [$this->userCode]);

    foreach($d as $set) {
      $this->sets[] = new \Entities\Academic\iSamsForm($this->sql, $set['id']);
    }
    $this->sets = sortObjects($this->sets, 'NCYear', "DESC");
    return $this->sets;
  }


}

?>
