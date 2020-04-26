<?php
// <!-- class to process and return pupil academic subject lists -->

namespace Entities\Academic;

class iSamsSubject
{
    public $id;
    public $name, $code;
    public $sets, $students=[];
    private $ada, $isams;

    public function __construct(\Dependency\Databases\isams $msSql, $id = null)
    {
       // $this->sql= $ada ?? new \Dependency\Databases\Ada();
       // $this->ada = new \Dependency\Databases\Ada();
       $this->isams = $msSql;

       if ($id) $this->byId($id);
       return $this;
    }

    public function byId(int $id)
    {
      $this->id = (int)$id;
      $subject = $this->isams->select(
        'TblTeachingManagerSubjects',
        'TblTeachingManagerSubjectsID as id, txtSubjectName, txtSubjectCode',
        'TblTeachingManagerSubjectsID = ?',
        [$id]);

      if (!isset($subject[0])) return $this;

      $subject = $subject[0];
      $this->name = $subject['txtSubjectName'];
      $this->code = $subject['txtSubjectCode'];
      return $this;
    }

    public function byAdaId($id) {
      $ada = new \Dependency\Databases\Ada();
      $d = $ada->select('sch_subjects', 'misId', 'id=?', [$id]);
      if (isset($d[0])) {
        $this->byId($d[0]['misId']);
      }
      return $this;
    }

    public function students($year = null)
    {
      $this->students = [];
      if ($year) {
        $sets = $this->isams->select(
          'TblTeachingManagerSets',
          'TblTeachingManagerSetsID as id, intSubject, txtSetCode',
          'intSubject=? AND intYear = ?', [$this->id, $year]);
      } else {
        $sets = $this->isams->select(
          'TblTeachingManagerSets',
          'TblTeachingManagerSetsID as id, intSubject, txtSetCode',
          'intSubject=?', [$this->id]);
      }
      $this->sets = $sets;
      foreach ($this->sets as &$set) {
        $studentIds = $this->isams->select('TblTeachingManagerSetLists', 'txtSchoolID', 'intSetID=?', [$set['id']]);
        foreach($studentIds as $setStudent) {
          $student = new \Entities\People\Student();
          $student->byMISId($setStudent['txtSchoolID']);
          $student->set = $set['txtSetCode'];
          $this->students[] = $student;
        }
      }
    }

    public function studentsByYear($year) {
      $students = $this->students($year);
      return $students;
    }



}
