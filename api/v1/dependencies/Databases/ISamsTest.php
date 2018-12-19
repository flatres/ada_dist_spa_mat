<?php

namespace Dependency\Databases;

class ISamsTest extends \Dependency\MSSql
{
  public function __construct() {
    $ip="192.168.2.166";
    $db = "iSAMS_Test";
    $user = "mcnode";
    $pwd = "MM0ndcol1s.03";

    $this->connect($ip, $db,  $user, $pwd);
  }
}
