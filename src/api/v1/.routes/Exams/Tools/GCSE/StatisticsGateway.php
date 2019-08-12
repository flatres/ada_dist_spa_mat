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

namespace Exams\Tools\GCSE;

class StatisticsGateway
{
  public $allResults = array();
  public $hundredResults = array();
  public $removeResults = array();
  public $shellResults = array();
  public $years = array();
  public $hundredStats;
  public $removeStats;
  public $shellStats;
  public $spreadsheet;

  private $sql;
  private $console;

  public function __construct(\Dependency\Databases\ISams $sql, \Sockets\Console $console)
  {
     $this->sql= $sql;
     $this->console = $console; //for caching student data
     $this->console->publish("Building statistics");

  }

  public function makeStatistics(array $session, array $results, \Exams\Tools\Cache $cache)
  {
    // return $this;
    $this->allResults = $results;
    //extract result for each year
    foreach($results as $result){
      switch($result['NCYear']){
        case 11:  $this->hundredResults[] = $result; break;
        case 10:  $this->removeResults[] = $result; break;
        case 9:   $this->shellResults[] = $result; break;
      }
    }
    //make stats for each year with results
    if(count($this->hundredResults) > 0){
      $this->console->publish('Hundred statistics', 1);
      $this->hundredStats = new \Exams\Tools\GCSE\Statistics($this->sql, $this->console);
      $this->hundredStats->makeStatistics($session, $this->hundredResults, $cache);
      $this->years[] = array('label' => 'Hundred', 'value' => 11);
    }
    //make stats for each year with results
    if(count($this->removeResults) > 0){
      $this->console->publish('Remove statistics', 1);
      $this->removeStats = new \Exams\Tools\GCSE\Statistics($this->sql, $this->console);
      $this->removeStats->makeStatistics($session, $this->removeResults, $cache);
      $this->years[] = array('label' => 'Remove', 'value' => 10);
    }else{
      $this->console->publish('No remove results found', 2);
    }
    //make stats for each year with results
    if(count($this->shellResults) > 0){
      $this->console->publish('Shell statistics', 1);
      $this->shellStats = new \Exams\Tools\GCSE\Statistics($this->sql, $this->console, $cache);
      $this->shellStats->makeStatistics($session, $this->shellResults);
      $this->years[] = array('label' => 'Shell', 'value' => 9);
    } else {
      $this->console->publish('No shell results found', 2);
    }

    $this->spreadsheet = new SpreadsheetRenderer($session, $this->console, $this);
    $this->cemSpreadsheet = new CemSpreadsheetRenderer($session, $this->console, $this);
    return $this;
  }

}
