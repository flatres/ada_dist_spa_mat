<?php

/**
 * Description

 * Usage:

 */
namespace SMT\Tools\Covid;

class Test
{
    private $ada, $adaModules;
    public $students = [];
    public $dates;


    public function __construct()
    {

    }

    public function sendEmail() {
      $email = new \Utilities\Email\Emails\Covid\CovidStudents('sdf@marlboroughcollege.org', 'Tester', '1234');
      return true;
    }
}
