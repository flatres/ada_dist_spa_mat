<?php
namespace Tools;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/tools', function(){

    $this->post('/crud/sheet', '\Tools\CrudSheet:crudSheetPost');

});

// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
