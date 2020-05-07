<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams\Internal;

class Session
{

    private $sql;

    public function __construct($id)
    {
       $this->adaData = new \Dependency\Databases\Ada();
       $this->isams = new \Dependency\Databases\Isams();
       if ($id) $this->byId($id);
       return $this;
    }

    public function $byId($id) {

    }

    public functio $byMISId($id) {
      $sessions = $this->isams->select(
        "TblInternalExamsSessions",
        'TblInternalExamsSessionsID as id, txtDescription as description',
        'TblInternalExamsSessionsID = 0 ORDER BY txtStartDate DESC',
        [$id]);
    }



}
