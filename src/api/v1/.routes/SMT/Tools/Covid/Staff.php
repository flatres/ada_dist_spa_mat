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
      $staff = $this->ada->select('usr_details', 'id, login, email, lastname, firstname, prename', 'disabled = ? AND id=? ORDER BY lastname ASC', [0, $id]);
      if (isset($staff[0])) {
        $staff = (object)$staff[0];
        $staff->answers = $this->adaModules->select('covid_answers_staff', '*', 'user_id = ? ORDER BY date DESC LIMIT 7', [$id]);
      }
      return $staff;
    }

    public function sendTodayEmails() {

      // if (date('N') == 7) return; //dont run on a Sundays

      $status = $this->getStatus();
      if ($status == 0) return false;

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
        $email = new \Utilities\Email\Emails\Covid\CovidStaff($s->email, $s->prename, $s->hash);
        $this->adaModules->insert('covid_answers_staff', 'user_id, hash, date', [$s->id, $s->hash, $today]);
        // break;
      }
      //1893
      $this->adaModules->update('covid_answers_staff', 'isHealthy=?, hasAnswered=?, isNotInWork=?', 'user_id=?', [0, 1, 1, 1893]);

      return true;
    }

    public function sendHODSEmails() {

      // if (date('N') == 7) return; //dont run on a Sundays

      $status = $this->getStatus();
      if ($status == 0) return false;
      $today = date("Y-m-d", time());
      $emails = [];
      $hods = $this->adaModules->query('SELECT count(*) as count, hod_user_id as id FROM covid_hod_subscriptions GROUP BY hod_user_id', []);
      foreach($hods as $h) {
        $subs = $this->adaModules->select('covid_hod_subscriptions', 'user_id', 'hod_user_id=?', [$h['id']]);
        $hod = new \Entities\People\User($this->ada, $h['id']);
        $alertNames = [];
        $notAnsweredNames = [];
        $notInWorkNames = [];

        foreach($subs as $s) {
          $user = new \Entities\People\User($this->ada, $s['user_id']);
          $answer = $this->adaModules->select('covid_answers_staff', '*', 'user_id = ? AND date=?', [$s['user_id'], $today])[0] ?? null;
          if (!$answer) continue;
          if ($answer['hasAnswered'] == 0) {
            $notAnsweredNames[] = $user->displayName;
            continue;
          }
          if ($answer['isNotInWork'] == 1) {
            $notInWorkNames[] = $user->displayName;
            continue;
          }
          if ($answer['isHealthy'] == 0) {
            $alertNames[] = $user->displayName;
          }
        }
        $email = new \Utilities\Email\Emails\Covid\CovidHODS($hod->email, $hod->firstName, $alertNames, $notAnsweredNames, $notInWorkNames);
        $emails[] = [
          'hod' => $hod->displayName,
          'subs' => $subs,
  				'alert' => $alertNames,
  				'notAnswered' => $notAnsweredNames,
  				'notInWorkNames' => $notInWorkNames
  			];
      }

      return $emails;
    }

    public function changeStatus($isActive)
    {
      $this->adaModules->update('covid_control', 'value=?', 'field=?', [$isActive, 'staffOn']);
    }

    public function getStatus()
    {
      return (int)$this->adaModules->select('covid_control', 'value', 'field=?', ['staffOn'])[0]['value'] ?? 0;
    }
}
