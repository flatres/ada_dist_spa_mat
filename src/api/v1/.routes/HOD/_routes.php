<?php
namespace HOD;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/hod', function(){

    $this->get('/years/{subject}', '\HOD\Years:yearsGet');
    $this->get('/{subject}/metrics/year/{year}', '\HOD\Metrics:yearGet');
    $this->get('/{subject}/metrics/year/{year}/MLO', '\HOD\Metrics:yearMLOGet');

})->add("Authenticate");
