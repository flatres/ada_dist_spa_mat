<?php
namespace Auth;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/smt', function(){

    $this->get('/watch/chapel/attendance/{date}', '\SMT\Chapel:attendanceGet');
    $this->post('/watch/chapel/attendance/{date}', '\SMT\Chapel:emailsPost');
    $this->get('/watch/privs/{date}', '\SMT\Privs:privsGet');

    $this->get('/covid/students', '\SMT\Covid:studentsGet');
    $this->get('/covid/staff', '\SMT\Covid:staffGet');
    $this->put('/covid/control/switch', '\SMT\Covid:controlSwitchPut');
    $this->post('/covid/students', '\SMT\Covid:studentEmailsPost');
    $this->post('/covid/staff', '\SMT\Covid:staffEmailsPost');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
