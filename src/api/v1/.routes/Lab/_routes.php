<?php
namespace Lab;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/lab', function(){

    $this->get('/crud/basic', '\Lab\Crud:basicGet');
    $this->get('/crud/cars', '\Lab\Crud:carsGet');

    $this->get('/sockets/zmq', '\Lab\Console:zmqGet');
    $this->post('/sockets/console', '\Lab\Console:consolePost');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
