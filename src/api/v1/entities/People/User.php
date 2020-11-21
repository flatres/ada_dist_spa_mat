<?php

/**
 * Description

 * Usage:

 */
//Class for Ada System User aka: Staff
namespace Entities\People;

class User
{

    private $sql;

    public $id, $firstName, $lastName, $email, $boardingHouse, $displayName;
    public $misFamilyId, $title;
    public $fullName, $name;
    public $misId, $login;
    public $classes = [];

    public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
    {
       // $this->sql= $ada ?? new \Dependency\Databases\Ada();
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
       if ($id) $this->byId($id);
       return $this;

    }

    public function displayName(int $id = null)
    {
      $id = $id ? $id : $this->id;
      $s = $this->sql->select(
        'usr_details',
        'id, firstname, lastname',
        'id=?',
        array($id));

      $name = $s[0]['lastname'] . ', ' . $s[0]['firstname'] ?? '';
      $this->displayName = $name;
      return $name;
    }

    public function byMISId($id)
    {
      $d = $this->sql->select('usr_details', 'id', 'mis_id=?', [$id]);
      if($d) $this->byId($d[0]['id']);
      return $this;
    }

    public function byId(int $id)
    {
      $s = $this->sql->select(
        'usr_details',
        'id, login, firstname, lastname, prename, email, title, mis_id',
        'id=?',
        [$id]);

      if (isset($s[0])) {
        $s = $s[0];
        $this->id = $id;
        $this->preName = $s['prename'];
        $this->title = $s['title'];
        $this->login = strtoupper($s['login']);
        $this->firstName = $s['firstname'];
        $this->lastName = $s['lastname'];
        $this->fullName = $s['firstname'] . ' ' . $s['lastname'];
        $this->name = $this->fullName;
        $this->misId = $s['mis_id'];
        $this->email = $s['email'];
      } else {

      }

      $this->displayName();
      return $this;
    }

    public function getClasses () {
      $classes = $this->sql->select('sch_class_teachers', 'classId', 'userId=?', [$this->id]);
      foreach ($classes as $c) $this->classes[] = new \Entities\Academic\AdaClass($this->sql, $c['classId']);
      return $this->classes;
    }

}
