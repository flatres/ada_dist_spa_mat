<?php

namespace Exams\Tools\ALevel;

class Module
{
    public $results = array();

    public function __construct($result)
    {
      $this->moduleCode = $result['txtModuleCode'];
      $this->subjectCode = $result['subjectCode'];
      $this->title = $result['txtOptionTitle'];
      $this->total = (int)$result['total'];
      $this->results[] = $result;
    }

    public function setResult(array $result)
    {
      $this->results[] = $result;
    }
    
    public function sortResults()
    {
      if (count($this->results) > 0)  usort($this->results ,'self::sort');
    }
    
    private static function sort($a, $b)
    {
      if (!isset($a["txtSurname"]) || !isset($b["txtSurname"])) return true;
      return strcmp($a["txtSurname"], $b["txtSurname"]);
    }

}
