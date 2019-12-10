<?php
namespace Lists\Users;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/users', function(){

    // $this->get('/list', '\Users\Lists:fullList_GET');
    $this->get('/names', '\Users\Lists:names_GET');
    // $this->get('/lists/tags/all', '\Lists\Users\Lists:tags_GET');
    // $this->get('/lists/houses/all', '\Lists\Users\Lists:houses_GET');

    $this->get('/details/{id}', '\Users\User:details_GET');

});
// ->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
