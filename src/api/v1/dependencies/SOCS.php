<?php
namespace Dependency;

define('SOCS_KEY', getenv("SOCS_KEY"));
define('SOCS_ID', getenv("SOCS_ID"));

use \PDO;
// "https://www.schoolssports.com/school/xml/mso-sport.ashx?ID=178&key=CD3A8825-7D17-4B89-B7F1-4575CD3AEE17&data=fixtures" //ste
// "https://www.schoolssports.com/school/xml/mso-sport.ashx?ID=178&key=CD3A8825-7D17-4B89-B7F1-4575CD3AEE17data=fixtures"


class SOCS {

    private $curlObject;
    private $urlBase = "https://www.schoolssports.com/school/xml/";
    private $keyBase = '';

		public function __construct($transactionId = 0) {

      $this->keyBase = ".ashx?ID=" . SOCS_ID . '&key=' . SOCS_KEY . '&';

   	}

    private function execute($feedFile, $fields)
    {
      $fieldsString = '';
      foreach($fields as $key=>$value) {
        if(strlen($value) > 0) $fieldsString .= $key . '=' . $value . '&';
      }
      $fieldsString = rtrim($fieldsString, '&');
      $url = $this->urlBase . $feedFile . $this->keyBase . $fieldsString;
      // echo $url . PHP_EOL;
      $xml = simplexml_load_file($url);

      return json_decode(json_encode($xml), TRUE);  //convert xml to array

    }

    public function getTeams(){
      $fields = [
        'data'  => 'teams'
      ];
      return $this->execute('mso-sport', $fields)['team'];
    }
// &startdate=1 Sep 2017&enddate=1 Oct 2017
    public function getDetailedFixtures() {
      $fields = [
        'data'  => 'fixtures',
        'startdate' => '1 Sep 2014',
        'enddate' => '1 Sep 2021'

      ];
      // return $this->execute('fixturecalendar', $fields);
      return $this->execute('mso-sport', $fields)['fixture'];
    }

}

?>
