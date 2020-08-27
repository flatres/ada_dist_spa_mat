<?php
namespace Dependency\Databases;

define('ISAMS_IP', $_SERVER["ISAMS_IP"]);
define('ISAMS_DB', $_SERVER["ISAMS_DB"]);
define('ISAMS_USER', $_SERVER["ISAMS_USER"]);
define('ISAMS_PWD', $_SERVER["ISAMS_PWD"]);

class ISams extends \Dependency\MSSql
{
  public function __construct() {

    $ip = ISAMS_IP;
    $db = ISAMS_DB;
    $user = ISAMS_USER;
    $pwd = ISAMS_PWD;
    $this->connect($ip, $db,  $user, $pwd);
  }
}

 ?>
