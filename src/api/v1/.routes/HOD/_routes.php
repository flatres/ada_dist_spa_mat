<?php
namespace HOD;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/hod', function(){

    $this->get('/years/{subject}', '\HOD\Years:yearsGet');
    $this->get('/years/{subject}/exams/{year}', '\HOD\Years:examsGet');
    $this->get('/{subject}/metrics/year/{year}', '\HOD\Metrics:yearGet');
    $this->get('/{subject}/metrics/year/{year}/MLO/{exam}', '\HOD\Metrics:yearMLOGet');

})->add("Authenticate");
