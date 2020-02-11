<?php
// <!-- class to process and return pupil academic subject lists -->

namespace Entities\Academic;

class iSamsSubject
{
    public $id;
    public $name, $code;
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

}
