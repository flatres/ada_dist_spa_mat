<?php
namespace Covid;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/covid', function(){

  $this->get('/staff', '\Covid\Covid:hodsStaffGet');
  $this->delete('/staff/{id}', '\Covid\Covid:hodsStaffDelete');
  $this->post('/staff/{id}', '\Covid\Covid:hodsStaffPost');
  $this->post('/responses', '\Covid\Covid:staffResponsesGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
