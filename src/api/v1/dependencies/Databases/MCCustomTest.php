<?php

// namespace MicroStats;
namespace Dependency\Databases;

class MCCustomTest extends \Dependency\MSSql
{
  public function __construct()
  {
    $ip="192.168.2.166";
    $db = "mccustom";
    //$database = "dbo";
    $user = "mcnode";
    $pwd = "MM0ndcol1s.03";

    $this->connect($ip, $db,  $user, $pwd);
  }
}
