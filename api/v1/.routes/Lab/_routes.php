<?php
namespace Lab;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/lab', function(){

    $this->get('/crud/basic', '\Lab\Crud:basicGet');
    $this->get('/crud/cars', '\Lab\Crud:carsGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
