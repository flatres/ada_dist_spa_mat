<?php
namespace HOD;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/hod', function(){

    $this->get('/{subject}/metrics/year/{year}', '\HOD\Metrics:yearGet');

})->add("Authenticate");
