<?php
namespace Auth;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/smt', function(){

    $this->get('/watch/chapel/attendance/{date}', '\SMT\Chapel:attendanceGet');
    $this->post('/watch/chapel/attendance/{date}', '\SMT\Chapel:emailsPost');
    $this->get('/watch/privs/{date}', '\SMT\Privs:privsGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
