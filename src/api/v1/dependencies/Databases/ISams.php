<?php
namespace Dependency\Databases;

define('ISAMS_IP', getenv("ISAMS_IP"));
define('ISAMS_DB', getenv("ISAMS_DB"));
define('ISAMS_USER', getenv("ISAMS_USER"));
define('ISAMS_PWD', getenv("ISAMS_PWD"));

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
