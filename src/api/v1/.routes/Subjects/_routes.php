<?php
namespace Lists\Subjects;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/subjects', function(){

    $this->get('/names', '\Subjects\Lists:names_GET');

});
//
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
// $app->group('/students/profile', function(){
//     $this->get('/subjects/{id}', '\Students\Profile:subjectsGet');
// })->add("Authenticate");
