<?php
namespace Dependency\Databases;

define('ISAMS_IP', $_ENV["ISAMS_IP"]);
define('ISAMS_DB', $_ENV["ISAMS_DB"]);
define('ISAMS_USER', $_ENV["ISAMS_USER"]);
define('ISAMS_PWD', $_ENV["ISAMS_PWD"]);

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
