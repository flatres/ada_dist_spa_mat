<?php
namespace Home;

use Slim\Http\Request;
use Slim\Http\Response;
$app->get('/home/almanac', '\Home\Almanac:almanacGet');
$app->group('/home', function(){
// $this->get('/almanac', '\Home\Almanac:almanacGet');
    

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
