<?php
namespace Home;

use Slim\Http\Request;
use Slim\Http\Response;
$app->get('/home/almanac', '\Home\Almanac:almanacGet');
$app->group('/home', function(){
  $this->get('/classes', '\Home\Classes:classesGet');

  $this->get('/classes/wyaps/{classId}', '\Home\Classes:wyapsGet');
  $this->put('/classes/wyaps/{id}', '\HOD\Wyaps:wyapPut');
  $this->get('/classes/wyaps/results/{id}', '\HOD\Wyaps:wyapsResultsGet');

  $this->get('/classes/mlo/form/{id}', '\Home\Classes:formMLOGet');
  $this->get('/classes/mlo/set/{id}', '\Home\Classes:setMLOGet');
  $this->get('/classes/mlo/{classId}/{examId}', '\Home\Classes:MLOGet');
  $this->post('/classes/mlo', '\Home\Classes:MLOPost');

  $this->get('/absences/all', '\Home\Absences:allAbsencesGet');
  $this->get('/absences/subjects', '\Home\Absences:subjectAbsencesGet');
})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
