<?php
namespace Dependency;

define('SOCS_KEY', getenv("SOCS_KEY"));
define('SOCS_ID', getenv("SOCS_ID"));

use \PDO;

class SOCS {

    private $curlObject;
    private $urlBase = "https://www.schoolssports.com/school/xml/";
    private $keyBase = '';

		public function __construct($transactionId = 0) {

      $this->keyBase = ".ashx?ID=" . SOCS_ID . '&key=' . SOCS_KEY;

   	}

    private function execute($feedFile, $fields)
    {
      $fieldsString = '';
      foreach($fields as $key=>$value) {
        if(strlen($value) > 0) $fieldsString .= $key . '=' . $value . '&';
      }
      rtrim($fieldsString, '&');
      $url = $this->urlBase . $feedFile . $this->keyBase . $fieldsString;
      echo $url . PHP_EOL;
      $xml = simplexml_load_file($url);

      return $xml;
    }

    public function getTeams(){
      $fields = [
        'data'  => 'teams'
      ];
      return $this->execute('mso-sport', $fields);
    }

    public function getDetailedFixtures() {
      $fields = [
        'data'  => 'fixtures',
        'startdate' =>  '1 Jan 2020',
        'enddate' =>  '29 Feb 2020'
      ];
      // return $this->execute('fixturecalendar', $fields);
      return $this->execute('mso-sport', $fields);
    }

}

?>
