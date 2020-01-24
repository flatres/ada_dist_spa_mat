<?php

namespace Dependency\Databases;

define('ISAMS_TEST_IP', getenv("ISAMS_TEST_IP"));
define('ISAMS_TEST_DB', getenv("ISAMS_TEST_DB"));
define('ISAMS_TEST_USER', getenv("ISAMS_TEST_USER"));
define('ISAMS_TEST_PWD', getenv("ISAMS_TEST_PWD"));

class ISams_Test extends \Dependency\MSSql
{
  public function __construct() {

    $ip = ISAMS_TEST_IP;
    $db = ISAMS_TEST_DB;
    $user = ISAMS_TEST_USER;
    $pwd = ISAMS_TEST_PWD;
    $this->connect($ip, $db,  $user, $pwd);
  }
}
