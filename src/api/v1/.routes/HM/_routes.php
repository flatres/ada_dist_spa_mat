<?php
namespace HM;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/hm', function(){

    $this->get('/houses', '\HM\House:listGet');
    $this->get('/{house}/notes', '\HM\House:notesGet');
    $this->post('/{house}/notes', '\HM\House:notePost');
    $this->get('/bandwidth', '\HM\Bandwidth:allHousesGet');
    $this->get('/bandwidthAll/{days}', '\HM\Bandwidth:allHousesGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
