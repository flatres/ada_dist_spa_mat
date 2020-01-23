<?php
namespace Dependency\Databases;

define('ISAMS_IP', getenv("ISAMS_IP"));
define('ISAMS_DB', getenv("ISAMS_DB"));
define('ISAMS_USER', getenv("ISAMS_USER"));
define('ISAMS_PWD', getenv("ISAMS_PWD"));

class ISams extends \Dependency\MSSql
{
  public function __construct() {

    $ip="192.168.2.164";
    $db = "iSAMS";
    $user = "mcnode";
    $pwd = "MM0ndcol1s.03";
    $this->connect($ip, $db,  $user, $pwd);

  }
}

 ?>
