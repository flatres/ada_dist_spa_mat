<?php
namespace Dependency\Databases;


class Ada extends \Dependency\MySql
{

  public function __construct() {

    $this->connect('ada');

  }

}

 ?>
