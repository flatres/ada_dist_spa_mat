<?php

/**
 * Description

 * Usage:

 */
namespace SMT\Tools\Covid;

class Students
{
    private $ada, $adaModules;
    public $students = [];
    public $dates;


    public function __construct()
    {
       $this->ada = new \Dependency\Databases\Ada();
       $this->adaModules = new \Dependency\Databases\AdaModules();
       $this->dates = $this->adaModules->query('Select count(*) as count, date FROM covid_answers_students GROUP BY date ORDER BY date DESC LIMIT 7', []);
       foreach ($this->dates as &$d) $d['prettyDate'] = date("j/n", strtotime($d['date']));
       return $this;
    }

    public function getAll() {
      $students = $this->ada->select('stu_details', 'id', 'disabled = ? ORDER BY lastname ASC', [0]);
      foreach($students as $s) $this->students[] = $this->getStudent($s['id']);
      return $this;
    }

    public function getStudent(int $id) {
      $students = (object)$this->ada->select('stu_details', 'id, lastname, firstname, prename, boardingHouse, NCYear', 'disabled = ? AND id=? ORDER BY lastname ASC', [0, $id])[0] ?? null;
      $students->answers = $this->adaModules->select('covid_answers_students', '*', 'student_id = ? ORDER BY date DESC LIMIT 7', [$id]);
      return $students;
    }
}
