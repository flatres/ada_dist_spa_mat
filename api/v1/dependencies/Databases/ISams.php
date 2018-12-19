<?php
namespace Dependency\Databases;


class ISams extends \Dependency\MSSql
{

  public function __construct() {

    // $ip = "192.168.2.165";
    $ip="192.168.2.164";
    $db = "iSAMS";
    //$database = "dbo";
    $user = "mcnode";
    $pwd = "MM0ndcol1s.03";

    $this->connect($ip, $db,  $user, $pwd);

  }



}

 ?>
