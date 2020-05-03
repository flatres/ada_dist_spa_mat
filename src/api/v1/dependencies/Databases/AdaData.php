<?php
namespace Dependency\Databases;


class AdaData extends \Dependency\MySql
{

  public function __construct() {

    $this->connect('ada_data');

  }

}

 ?>
