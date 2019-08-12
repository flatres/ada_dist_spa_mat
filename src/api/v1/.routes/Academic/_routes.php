<?php
namespace Academic;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/academic', function(){

    $this->get('/prizes', '\Academic\Prizes:prizesGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
