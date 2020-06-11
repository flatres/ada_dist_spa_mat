<?php
namespace Auth;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/sen', function(){
    $this->get('/report/all', '\SEN\Reports:allGet');

})->add("Authenticate");
