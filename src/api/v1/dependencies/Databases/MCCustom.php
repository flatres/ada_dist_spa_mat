<?php
namespace Dependency\Databases;

define('MCCUSTOM_IP', $_SERVER["MCCUSTOM_IP"]);
define('MCCUSTOM_DB', $_SERVER["MCCUSTOM_DB"]);
define('MCCUSTOM_USER', $_SERVER["MCCUSTOM_USER"]);
define('MCCUSTOM_PWD', $_SERVER["MCCUSTOM_PWD"]);

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
