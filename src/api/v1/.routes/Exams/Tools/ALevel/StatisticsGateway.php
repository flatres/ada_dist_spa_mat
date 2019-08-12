<?php

/**
* Description

* Usage:

*/
// A Result Object
// grade:"A"
// id:"39550"
// intEnrolmentNCYear:"12"
// subjectCode:"GG"
// txtBoardingHouse:"Littlefield"
// txtForename:"Lucy"
// txtFullName:"Lucy Goodman"
// txtGender:"F"
// txtHouseCode:"LI"
// txtInitialedName:"Goodman, L C"
// txtInitials:"L C"
// txtLeavingBoardingHouse:"Littlefield"
// txtModuleCode:"2031"
// txtOptionTitle:"GCE GEOGRAPHY ADV"
// txtQualification:"GCE"
// txtSchoolID:"111234705547"
// txtSurname:"Goodman"

namespace Exams\Tools\ALevel;

class StatisticsGateway
{
  public $results = array();
  public $years = array();
  public $data;
  public $spreadsheet;
  public $moduleResults;

  private $sql;
  private $console;

  public function __construct(\Dependency\Databases\ISams $sql, \Sockets\Console $console, array $moduleResults)
  {
     $this->sql= $sql;
     $this->console = $console; //for caching student data
     $this->console->publish("Building statistics");
     $this->moduleResults = $moduleResults;

  }

  public function makeStatistics(array $session, array &$results, \Exams\Tools\Cache $cache)
  {
    $this->results = $results;
    $this->data = new \Exams\Tools\ALevel\Statistics($this->sql, $this->console, $this->moduleResults);
    $this->data->makeStatistics($session, $this->results, $cache);
    unset($this->results);
    unset($this->moduleResults);
    $this->spreadsheet = new SpreadsheetRenderer($session, $this->console, $this);
    // $this->cemSpreadsheet = new CemSpreadsheetRenderer($session, $this->console, $this);
    return $this;
  }

}
