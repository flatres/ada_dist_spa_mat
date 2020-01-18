<?php
namespace Academic;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/academic', function(){

    $this->get('/prizes', '\Academic\Prizes:prizesGet');
    $this->get('/alis/registration', '\Academic\Alis:alisRegistrationGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
