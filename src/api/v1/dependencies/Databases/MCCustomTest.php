<?php
namespace Dependency\Databases;

define('MCCUSTOM_TEST_IP', $_ENV["MCCUSTOM_TEST_IP"]);
define('MCCUSTOM_TEST_DB', $_ENV["MCCUSTOM_TEST_DB"]);
define('MCCUSTOM_TEST_USER', $_ENV["MCCUSTOM_TEST_USER"]);
define('MCCUSTOM_TEST_PWD', $_ENV["MCCUSTOM_TEST_PWD"]);

class MCCustomTest extends \Dependency\MSSql
{
  public function __construct()
  {
    $ip = MCCUSTOM_TEST_IP;
    $db = MCCUSTOM_TEST_DB;
    $user = MCCUSTOM_TEST_USER;
    $pwd = MCCUSTOM_TEST_PWD;

    $this->connect($ip, $db,  $user, $pwd);
  }
}
