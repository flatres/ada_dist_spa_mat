<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams;

class Level
{
    public $id, $code;

    public function __construct(\Dependency\Databases\Ada $ada = null)
    {
       $this->ada= $ada ?? new \Dependency\Databases\Ada();
       $this->adaData= new \Dependency\Databases\AdaData();
       return $this;
    }

    public function byId($id) {
      $this->id = $id;
      $level = $this->adaData->select('exams_levels', 'code', 'id=?', [$id]);
      if ($level) {
        $this->code = $level[0]['code'];
      }
      return $this;
    }

    public function byCode($code) {
      if (!$code) return $this;
      $level = $this->adaData->select('exams_levels', 'id', 'code=?', [$code]);
      if ($level) {
        $id = $level[0]['id'];
      } else {
        $id = $this->adaData->insert('exams_levels', 'code', [$code]);
      }
      $this->byId($id);
      return $this;
    }

}
