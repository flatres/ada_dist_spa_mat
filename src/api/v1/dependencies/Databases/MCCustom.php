<?php
namespace Dependency\Databases;

define('MCCUSTOM_IP', getenv("MCCUSTOM_IP"));
define('MCCUSTOM_DB', getenv("MCCUSTOM_DB"));
define('MCCUSTOM_USER', getenv("MCCUSTOM_USER"));
define('MCCUSTOM_PWD', getenv("MCCUSTOM_PWD"));

class MCCustom extends \Dependency\MSSql
{
  public function __construct() {

    $ip = MCCUSTOM_IP;
    $db = MCCUSTOM_DB;
    $user = MCCUSTOM_USER;
    $pwd = MCCUSTOM_PWD;

    $this->connect($ip, $db,  $user, $pwd);
  }
}

?>
