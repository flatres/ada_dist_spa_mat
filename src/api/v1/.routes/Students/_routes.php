<?php
namespace Lists\Students;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/students', function(){

    // $this->get('/list', '\Students\Lists:fullList_GET');
    $this->get('/names', '\Students\Lists:names_GET');
    $this->get('/portal/names', '\Students\Lists:portalNames_GET');
    // $this->get('/lists/tags/all', '\Lists\Students\Lists:tags_GET');
    // $this->get('/lists/houses/all', '\Lists\Students\Lists:houses_GET');

    $this->get('/details/{id}', '\Students\Student:details_GET');

});
// ->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
