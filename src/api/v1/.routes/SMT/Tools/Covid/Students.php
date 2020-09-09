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

    public function getHouse($houseId) {
      $house = new \Entities\Houses\House($this->ada, $houseId);
      $house->getStudents();
      foreach($house->students as $s) $this->students[] = $this->getStudent($s->id);
      return $this;
    }

    public function getStudent(int $id) {
      $students = (object)$this->ada->select('stu_details', 'id, lastname, firstname, prename, boardingHouse, NCYear, email', 'disabled = ? AND id=? ORDER BY lastname ASC', [0, $id])[0] ?? null;
      $students->answers = $this->adaModules->select('covid_answers_students', '*', 'student_id = ? ORDER BY date DESC LIMIT 7', [$id]);
      return $students;
    }

    public function sendTodayEmails() {

      $status = $this->getStatus();
      if ($status == 0) return false;

      $this->getAll();
      $today = date("Y-m-d", time());
      $pendingStudents = [];
      foreach($this->students as $s) {
        $exists = $this->adaModules->select('covid_answers_students', 'id', 'student_id=? AND date=?', [$s->id, $today])[0] ?? null;
        // echo 'y';
        if (!$exists) {
          $hash = bin2hex(random_bytes(32));
          $s->hash = $hash;
          $pendingStudents[] = $s;
        }
      }
      foreach($pendingStudents as $s) {
        $email = new \Utilities\Email\Emails\Covid\CovidStudents($s->email, $s->prename, $s->hash);
        $this->adaModules->insert('covid_answers_students', 'student_id, hash, date', [$s->id, $s->hash, $today]);
      }
      return true;
    }

    public function sendHMEmails() {

      $status = $this->getStatus();
      if ($status == 0) return false;
      $today = date("Y-m-d", time());
      $emails = [];
      $hms = $this->ada->select('sch_houses', 'id, code', 'id>?', [0]);
      foreach($hms as $h) {
        $subs = $this->adaModules->select('covid_hod_subscriptions', 'user_id', 'hod_user_id=?', [$h['id']]);
        $house = new \Entities\Houses\House($this->ada, $h['id']);
        $house->getStudents();

        $alertNames = [];
        $notAnsweredNames = [];
        $notInWorkNames = [];

        foreach($house->students as $s) {
          $answer = $this->adaModules->select('covid_answers_students', '*', 'student_id = ? AND date=?', [$s->id, $today])[0] ?? null;
          if (!$answer) continue;
          if ($answer['hasAnswered'] == 0) {
            $notAnsweredNames[] = $s->displayName;
            continue;
          }
          if ($answer['isHealthy'] == 0) {
            $alertNames[] = $s->displayName;
          }
        }
        // $email = new \Utilities\Email\Emails\Covid\CovidHMS("HM" . $h['code'] . '@marlboroughcollege.org', 'HM', $alertNames, $notAnsweredNames);
        $emails[] = [
          'hs' => $h['code'],
          'subs' => $subs,
  				'alert' => $alertNames,
  				'notAnswered' => $notAnsweredNames
  			];
      }

      return $emails;
    }


    public function changeStatus($isActive)
    {
      $this->adaModules->update('covid_control', 'value=?', 'field=?', [$isActive, 'studentsOn']);
    }

    public function getStatus()
    {
      return (int)$this->adaModules->select('covid_control', 'value', 'field=?', ['studentsOn'])[0]['value'] ?? 0;
    }
}
