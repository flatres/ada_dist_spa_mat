<?php
namespace Lists;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/lists', function(){

    $this->get('/staff', '\Lists\Staff:staffGet');

});
// ->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
