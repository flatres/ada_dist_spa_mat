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
     ini_set('max_execution_time', 240);
     $this->sql= $sql;
     $this->console = $console; //for caching student data
     $this->console->publish("Building statistics");
     $this->moduleResults = $moduleResults;

  }

  public function makeStatistics(array $session, array &$results, \Exams\Tools\Cache $cache, $createSpreadsheets)
  {
    if (count($results) === 0) return;
    $this->results = $results;
    $this->data = new \Exams\Tools\ALevel\Statistics($this->sql, $this->console, $this->moduleResults);
    $this->data->makeStatistics($session, $this->results, $cache);
    unset($this->results);
    unset($this->moduleResults);

    if ($createSpreadsheets) {

      $this->spreadsheet = new SpreadsheetRenderer('Alevel_Detailed_Report', 'A Level Results', 'detailed', $session, $this->console, $this);

      $this->spreadsheetSSS = new SpreadsheetRenderer('Alevel_SSS', 'Subject Surplus Scores', 'sss', $session, $this->console, $this);

      $this->spreadsheetResults = new SpreadsheetRenderer('Alevel_Results', 'A Level Results', 'results', $session, $this->console, $this);

      $this->spreadsheetHouseCandidates = new SpreadsheetRenderer('House_Candidate_Results', 'House Candidate Results', 'houseresults', $session, $this->console, $this);

      $this->spreadsheetSubjectCandidates = new SpreadsheetRenderer('Subject_Candidate_Results', 'Subject Candidate Results', 'subjectresults', $session, $this->console, $this);

    }

    // $this->cemSpreadsheet = new CemSpreadsheetRenderer($session, $this->console, $this);
    return $this;
  }

}
