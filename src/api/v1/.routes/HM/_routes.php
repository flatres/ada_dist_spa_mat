<?php
namespace HM;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/hm', function(){

    $this->get('/houses', '\HM\House:listGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
