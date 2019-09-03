<?php

/**
 * Description

 * Usage:

 */
namespace Entities\People;

class Student
{

    private $sql;

    public $id, $firstName, $lastName, $email, $boardingHouse, $gender;

    public function __construct(\Dependency\Databases\Ada $ada = null)
    {
       $this->sql= $ada ?? new \Dependency\Databases\Ada();

    }



    public function displayName(int $id)
    {
      $s = $this->sql->select(
        'stu_details',
        'id, firstname, lastname',
        'id=?',
        array($id));

      return $s[0]['lastname'] . ', ' . $s[0]['firstname'] ?? '';
    }

    public function byId(int $id)
    {
      $student = $this->sql->select(
        'stu_details',
        'id, firstname, lastname, email, boardingHouse, gender',
        'id=?',
        [$id]);

      if (isset($student[0])) {
        $student = $student[0];
        $this->id = $id;
        $this->firstName = $student['firstname'];
        $this->lastName = $student['lastname'];
        $this->email = $student['email'];
        $this->boardingHouse = $student['boardingHouse'];
        $this->gender = $student['gender'];
      } else {

      }
      return $this;
    }
}
