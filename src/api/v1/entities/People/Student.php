<?php

/**
 * Description

 * Usage:

 */
namespace Entities\People;

class Student
{

    private $sql;

    public $id, $firstName, $lastName, $email, $boardingHouse, $gender, $displayName;
    public $misFamilyId;
    public $dob;
    public $boardingHouseSafe; //has spaces replaced with _ for use in array keys
    public $preName, $fullName, $fullPreName, $name, $NCYear, $schoolNumber;
    public $misId;
    public $metrics = [];
    public $examData = [];
    public $classes = [];
    public $hmNote = '';
    public $boardingHouseId, $boardingHouseCode;
    public $isDisabled; //if account is disabled. i.e they have left

    public function __construct(\Dependency\Databases\Ada $ada = null, $id = null)
    {
       // $this->sql= $ada ?? new \Dependency\Databases\Ada();
       $this->sql= $ada ?? new \Dependency\Databases\Ada();
       if ($id) $this->byId($id);
       return $this;
    }

    public function basic() {
      return [
        'student_id'        => $this->id,
        'firstName' => $this->firstName,
        'preName'   => $this->preName,
        'lastName'  => $this->lastName,
        'name'      => $this->displayName,
        'NCYear'    => $this->NCYear,
        'gender'    => $this->gender,
        'dob'       => $this->dob,
        'email'     => $this->email,
        'schoolNumber'    => $this->schoolNumber,
        'boarding'  => $this->boardingHouseCode,
        'isDisabled' => $this->isDisabled
      ];
    }

    public function displayName(int $id = null)
    {
      $id = $id ? $id : $this->id;
      $s = $this->sql->select(
        'stu_details',
        'id, firstname, lastname',
        'id=?',
        array($id));

      $name = $s[0]['lastname'] . ', ' . $s[0]['firstname'] ?? '';
      $this->displayName = $name;
      return $name;
    }

    public function byMISId($id)
    {
      $d = $this->sql->select('stu_details', 'id', 'mis_id=?', [$id]);
      if($d) {
          $this->byId($d[0]['id']);
          return $this;
      }
      return $this;
    }

    public function bySchoolNumber($number)
    {
      $email = $number . "@marlboroughcollege.org";
      $d = $this->sql->select('stu_details', 'id', 'email=?', [$email]);
      if($d) {
          $this->byId($d[0]['id']);
          return $this;
      }
      return $this;
    }

    public function byId(int $id)
    {
      $student = $this->sql->select(
        'stu_details',
        'id, firstname, lastname, prename, email, boardingHouse, boardingHouseId, gender, mis_id, mis_family_id, NCYear, dob, disabled',
        'id=?',
        [$id]);

      if (isset($student[0])) {
        $student = $student[0];
        $bh = new \Entities\Houses\House($this->sql, $student['boardingHouseId']);
        $this->boardingHouseCode = $bh->code;
        $this->boardingHouseId = $bh->id;

        $this->id = $id;
        $this->preName = $student['prename'];
        $this->firstName = $student['firstname'];
        $this->lastName = $student['lastname'];
        $this->fullName = $student['firstname'] . ' ' . $student['lastname'];
        $this->name = $this->fullName;
        $this->fullPreName = $student['prename'] . ' ' . $student['lastname'];
        $this->misId = $student['mis_id'];
        $this->misFamilyId = $student['mis_family_id'];

        $this->email = $student['email'];
        $this->boardingHouse = $student['boardingHouse'];
        $this->boardingHouseSafe = str_replace(" ", '_', $student['boardingHouse']);
        $this->gender = $student['gender'];
        $this->NCYear = $student['NCYear'];
        $this->schoolNumber = explode('@', $this->email)[0] ?? null;
        $dob = strtotime($student['dob']);
        $this->dob = date('d/m/Y', $dob);
        $this->isDisabled = $student['disabled'] == 1 ? true : false;
      } else {
        return null;
      }

      $this->displayName();
      return $this;
    }

    public function getClasses() {
      $classes = $this->sql->select('sch_class_students', 'classId, subjectId', 'studentId=?', [$this->id]);
      foreach($classes as $class) {
        $this->classes[] = new \Entities\Academic\adaClass($this->sql, $class['classId']);
      }
      return $this->classes;
    }

    public function getClassesBySubject($subjectId) {
      $classes = $this->sql->select('sch_class_students', 'classId, subjectId', 'studentId=? AND subjectId = ?', [$this->id, $subjectId]);
      $subjectClasses = [];
      foreach($classes as $class) {
        $subjectClasses[] = new \Entities\Academic\adaClass($this->sql, $class['classId']);
      }
      $this->classes = $subjectClasses;
      return $subjectClasses;
    }

    public function getClassesByExam($examId) {
      $exam = $this->sql->select('sch_subjects_exams', 'subjectId', 'id=?', [$examId]);
      $classes = [];
      if (isset($exam[0])){
        $subjectClasses = $this->getClassesBySubject($exam[0]['subjectId']);
        foreach($subjectClasses as $c){
          $classExam = $this->sql->select('sch_class_exams', 'id', 'classId=? and examId=?', [$c->id, $examId]);
          if (isset($classExam[0])) {
            $classes[] = $c;
          }
        }
      }
      return $classes;
    }

    public function getExamsData($subjectId = false) {
      if ($subjectId) {
        $classes = $this->getClassesBySubject($subjectId);
      } else {
        $classes = count($this->classes) === 0 ? $this->getClasses : $this->classes;
      }

      $mlo = [];
      foreach($classes as $c) {
        $mlo[] = $c->getStudentMLO($studentId);
      }
    }

    public function setHmNote($note){
      $adaData = new \Dependency\Databases\AdaData();
      $d = $adaData->select('notes_hm_students', 'id, note', 'studentId=?', [$this->id]);
      if ($d){
        $id = $d[0]['id'];
        $adaData->update('notes_hm_students', 'note=?', 'id=?', [$note, $id]);
      }{
        $adaData->insert('notes_hm_students', 'note, studentId', [$note, $this->id]);
      }
      return true;
    }

    public function getHmNote(){

      $adaData = new \Dependency\Databases\AdaData();
      $d = $adaData->select('notes_hm_students', 'id, note', 'studentId=?', [$this->id]);
      if ($d) {
        $this->hmNote = $d[0]['note'];
        return $this->hmNote;
      }
      return null;
    }

    public function sanitizeNames(){

    }
}
