<?php
//An isams user can be either a member of staff or parent. Their details are held in TbsiSAMSManagerUsers

namespace Entities\People;

class iSamsUser
{
  public $id;
  public $title, $firstName, $lastName, $fullName, $userName;
  public $email, $userCode, $userType, $passwordHash;

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
      'TbliSAMSManagerUsersID=?', [$id]);

    if(isset($d[0])){
      saveToObject($d[0], $this);
    }
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


}

?>
