<?php
namespace Auth;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/smt', function(){

    $this->get('/watch/chapel/attendance/{date}', '\SMT\Chapel:attendanceGet');
    $this->post('/watch/chapel/attendance/{date}', '\SMT\Chapel:emailsPost');
    $this->get('/watch/privs/{date}', '\SMT\Privs:privsGet');

    $this->get('/covid/students', '\SMT\Covid:studentsGet');
    // $this->get('/covid/house/{id}', '\SMT\Covid:houseStudentsGet');

    $this->get('/covid/staff', '\SMT\Covid:staffGet');
    $this->get('/covid/staff/watchers', '\SMT\Covid:staffWatchersGet');

    $this->post('/covid/students', '\SMT\Covid:studentEmailsPost');
    $this->post('/covid/staff', '\SMT\Covid:staffEmailsPost');

    $this->post('/covid/summaries/hods', '\SMT\Covid:hodsEmailsPost');
    $this->post('/covid/summaries/houses', '\SMT\Covid:housesEmailsPost');

    $this->put('/covid/students/status', '\SMT\Covid:studentsStatusPut');
    $this->put('/covid/staff/status', '\SMT\Covid:staffStatusPut');
    $this->get('/covid/status', '\SMT\Covid:statusGet');

})->add("Authenticate");

$app->get('/smt/covid/house/{id}', '\SMT\Covid:houseStudentsGet');
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
