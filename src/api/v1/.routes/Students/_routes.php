<?php
namespace Students;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/students', function(){

    $this->get('/list', '\Students\Lists:fullList_GET');
    $this->get('/names', '\Students\Lists:names_GET');
    $this->get('/tags', '\Students\Lists:tags_GET');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
