<?php
namespace Home;

use Slim\Http\Request;
use Slim\Http\Response;
$app->get('/home/almanac', '\Home\Almanac:almanacGet');
$app->group('/home', function(){
  $this->get('/absences/all', '\Home\Absences:allAbsencesGet');
  $this->get('/absences/subjects', '\Home\Absences:subjectAbsencesGet');
})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
