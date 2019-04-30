<?php
namespace Lab;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/lab', function(){

    $this->get('/crud/basic', '\Lab\Crud:basicGet');
    $this->get('/crud/cars', '\Lab\Crud:carsGet');

    $this->get('/sockets/zmq', '\Lab\Console:zmqGet');
    $this->post('/sockets/console', '\Lab\Console:consolePost');
    $this->post('/sockets/notify', '\Lab\Console:notifyPost');
    $this->get('/sockets/table', '\Lab\Console:tableGet');
    $this->get('/sockets/cars', '\Lab\Console:carsGet');
    $this->get('/sockets/table/{id}', '\Lab\Console:tableSingleGet');
    $this->post('/sockets/table', '\Lab\Console:tablePost');
    $this->put('/sockets/table', '\Lab\Console:tablePut');
    $this->put('/sockets/favorite', '\Lab\Console:favoritePut');
    $this->delete('/sockets/table/{id}', '\Lab\Console:tableDelete');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
