<?php
namespace Dependency\Databases;


class AdaModules extends \Dependency\MySql
{

  public function __construct() {

    $this->connect('ada_modules');

  }

}

 ?>
