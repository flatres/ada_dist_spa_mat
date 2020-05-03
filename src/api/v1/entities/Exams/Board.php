<?php

/**
 * Description

 * Usage:

 */
namespace Entities\Exams;

class Board
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
      $board = $this->adaData->select('exams_boards', 'code', 'id=?', [$id]);
      if ($board) {
        $this->code = $board[0]['code'];
      }
      return $this;
    }

    public function byCode($code) {
      if (!$code) return $this;
      $board = $this->adaData->select('exams_boards', 'id', 'code=?', [$code]);
      if ($board) {
        $id = $board[0]['id'];
      } else {
        $id = $this->adaData->insert('exams_boards', 'code', [$code]);
      }
      $this->byId($id);
      return $this;
    }

}
