<?php

/**
 * Description

 * Usage:

 */
namespace SMT\Tools\Covid;

class Staff
{
    private $ada, $adaModules;
    public $staff = [];
    public $dates;


    public function __construct()
    {
       $this->ada = new \Dependency\Databases\Ada();
       $this->adaModules = new \Dependency\Databases\AdaModules();
       $this->dates = $this->adaModules->query('Select count(*) as count, date FROM covid_answers_staff GROUP BY date ORDER BY date DESC LIMIT 7', []);
       foreach ($this->dates as &$d) $d['prettyDate'] = date("j/n", strtotime($d['date']));
       return $this;
    }

    public function getAll() {
      $staff = $this->ada->select('usr_details', 'id', 'disabled = ? ORDER BY lastname ASC', [0]);
      foreach($staff as $s) $this->staff[] = $this->getStaff($s['id']);
      return $this;
    }

    public function getByUser($userId) {
      $staff = $this->adaModules->select('covid_hod_subscriptions', 'user_id', 'hod_user_id = ?', [$userId]);
      foreach($staff as $s) $this->staff[] = $this->getStaff($s['user_id']);
      return $this;
    }

    public function getStaff(int $id) {
      $staff = (object)$this->ada->select('usr_details', 'id, login, email, lastname, firstname, prename', 'disabled = ? AND id=? ORDER BY lastname ASC', [0, $id])[0] ?? null;
      $staff->answers = $this->adaModules->select('covid_answers_staff', '*', 'user_id = ? ORDER BY date DESC LIMIT 7', [$id]);
      return $staff;
    }

    public function sendTodayEmails() {

      $this->getAll();
      $today = date("Y-m-d", time());
      $pendingStaff = [];
      foreach($this->staff as $s) {
        $exists = $this->adaModules->select('covid_answers_staff', 'id', 'user_id=? AND date=?', [$s->id, $today])[0] ?? null;
        // echo 'y';
        if (!$exists) {
          $hash = bin2hex(random_bytes(32));
          $s->hash = $hash;
          $pendingStaff[] = $s;
        }
      }
      foreach($pendingStaff as $s) {
        $email = new \Utilities\Email\Emails\Covid\CovidStaff($s->email, $s->prename, $hash);
        $this->adaModules->insert('covid_answers_staff', 'user_id, hash, date', [$s->id, $hash, $today]);
        break;
      }
    }
}
