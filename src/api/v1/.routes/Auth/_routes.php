<?php
namespace Auth;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/auth', function(){
    $this->post('/login', '\Auth\Login:login')->setName('Auth');;
    $this->get('/test', '\Auth\TestClass:testGet')->add("Authenticate");
    $this->post('/bug', '\Auth\Bug:report')->add("Authenticate");

})
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
