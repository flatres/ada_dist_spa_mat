<?php
namespace Entities\People;

class Staff
{
  public $boarding = array();
  public $tutees = array();
  public $details = array();
  private $sql;

  public function __construct(\Dependency\Mysql $mySql, $id = null)
  {
    $this->sql= $mySql;
  }



  public function new(\Entities\People\iSamsStaff $s)
  {
    $d = $this->sql->select('usr_types', 'id', 'name=?', array('staff'));
    if(!isset($d[0])) return false;

    $type = $d[0]['id'];
    $dets = $s->details;
    $this->id = $this->sql->insert(
      'usr_details',
      'usr_type, mis_id, login, firstname, lastname',
      array(
        $type,
        $s->userCode,
        $dets['Initials'],
        $dets['Firstname'],
        $dets['Surname']
      )
    );

    //set the role to basic if it exists
    $d = $this->sql->select('acs_roles', 'id', 'name=?', array('basic'));
    if(isset($d[0])){
      $this->sql->insert('acs_roles_users', 'user_id, role_id', array($this->id, $d[0]['id']));
    }
    return $this->id;
  }

}

 ?>
