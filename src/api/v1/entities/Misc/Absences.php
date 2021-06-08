<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Misc;

class Absences
{
    public $misc = [];
    public $trips = [];
    public $sani = [];
    public $extras = []; // music / LS / Language
    public $now, $today;

    private $allFetched = false;

    public function __construct(bool $fetchPupilDetails = false)
    {
       $this->sql= new \Dependency\Databases\MCCustom();
       $date = new \DateTime();
       $this->now = $date->format('Y-m-d H:i:s');
       $this->today = $date->format('Y-m-d');
       return $this;
    }

    public function all() {
      $this->getMisc();
      $this->getPrivs();
      $this->getExtraLessons();
      $this->getSani();
      $this->getTrips();
      $this->allFetched = true;
      return \array_merge($this->misc, $this->trips, $this->extras, $this->sani);
    }

    public function getSani() {
      $saniFinal = [];

      //get list of current pupils
      $ada = new \Dependency\Databases\Ada();
      $data = $ada->select('stu_details', 'mis_id', 'disabled=?', [0]);
      $students = [];
      foreach($data as $d) $students['s_' . $d['mis_id']] = true;
      $now = $this->today;
      $sani = $this->sql->select(
        'TblSani',
        'txtSchoolID as studentId, txtEntryType as entryType, dteStart as start, dteFinish as finish',
        '((dteStart < ? AND dteFinish > ?) OR (dteStart < ? AND dteFinish is NULL)) AND intDeleted=? AND txtEntryType <> ?',
        [$now, $now, $now, 0, 'app']
      );
      foreach($sani as &$s) {
        if (!isset($students['s_' . $s['studentId']])) continue;
        $reason = 'Sani';
        switch ($s['entryType']) {
          case 'home': $reason = 'Medical: At Home'; break;
          case 'ld': $reason = 'Medical: Lying Down'; break;
          case 'house': $reason = 'Medical: Lying Down In House'; break;
          case 'sani': $reason = 'Medical: In Sani'; break;
          case 'og': $reason = 'Medical: Off Games'; break;
          default: $reason = 'Medical';
        }
        $s['reason'] = $reason;
        $s['type'] = 'medical';
        unset($s['entryType']);
        $saniFinal[] = $s;
      }
      $this->sani = $saniFinal;
      return $this;
    }

    public function getMisc() {
      $now = $this->now;
      $this->misc = $this->sql->select(
        'TblAbsenceMiscell',
        'TblAbsenceMiscellID as id, txtSchoolID as studentId, dteStart as start, dteFinish as finish, txtReason as reason',
        'dteStart < ? AND dteFinish > ?',
        [$now, $now]
      );
      foreach($this->misc as &$m) $m['type'] = 'misc';
      return $this;
    }

    public function getTrips() {
      $now = $this->now;
      $trips = [];
      $tripData = $this->sql->select(
        'TblTrips',
        'TblTripsID as id, title, departdatetime as start, returndatetime as finish',
        'departdatetime  < ? AND returndatetime > ?',
        [$now, $now]
      );
      foreach($tripData as $t) {
        $students = $this->sql->select('TblTripMembers', 'txtSchoolID as studentId', 'intTripID=?', [$t['id']]);
        foreach($students as $s) {
          $trips[] = [
            'studentId' => $s['studentId'],
            'start' => $t['start'],
            'finish'  => $t['finish'],
            'reason' => 'Trip: ' . $t['title'],
            'type'  => 'trip'
          ];
        }
      }
      $this->trips = $trips;
      return $this;
    }

    public function getExtraLessons() {
      $lessonTypes = $this->lessonTypes();
      $now = $this->now;
      $lessons = $this->sql->select(
        'TblXLHistory',
        'txtSchoolID as studentId, intLesson as lessonId, dteDayTime as start',
        'dteDayTime > ?',
        [$now]
      );
      $extras = [];
      foreach ($lessons as $l) {
        $key = 'l_' . $l['lessonId'];
        if (!isset($lessonTypes[$key])) continue;

        $duration = $lessonTypes[$key]['duration'];
        $startUnix = \strtotime($l['start']);
        $date = new \DateTime();
        $date->setTimestamp($startUnix + $duration * 60);
        $type = null;
        switch ($lessonTypes[$key]['dept']) {
          case 'Music' : $reason = 'Music Lesson'; $type='music';  break;
          case 'LS' : $reason = 'Learning Support'; $type='ls'; break;
          case 'Tennis' : $reason = 'Tennis Lesson'; $type='tennis'; break;
          case 'ML' : $reason = 'Language Lesson'; $type='language'; break;
          default: $reason = 'Unknown Reason';
        }

        $extras[] = [
          'studentId' => $l['studentId'],
          'start' => $l['start'],
          'end' => $date->format('Y-m-d H:i:s'),
          'reason' => $reason,
          'type' => $type
        ];
      }
      $this->extras = $extras;
      return $this;
    }

    public function getPrivs() {
      $this->privs = $this->sql->select(
        'TblPrivs',
        'TblPrivsID, txtSchoolID, dtePrivDate',
        'dtePrivDate > ? ORDER BY TblPrivsID DESC',
        [$this->today]);
      foreach($this->privs as &$p) $p['type'] = 'misc';
      return $this;
    }

    private function lessonTypes() {
      $lessons = $this->sql->select(
        'TblXLLessons',
        'TblXLLessonsID as id, txtDept as dept, intLessonDuration as duration',
        'TblXLLessonsID > ?',
        [0]);
      $data = [];
      foreach($lessons as $l) {
        $data['l_' . $l['id']] = [
          'dept' => $l['dept'],
          'duration' => $l['duration']
        ];
      }
      return $data;
    }

}
