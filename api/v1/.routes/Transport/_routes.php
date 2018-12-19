<?php
namespace Transport;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/transport', function(){

    $this->get('/tbs/sessions', '\Transport\TbsExplorer:sessionsGet');
    $this->get('/tbs/session/{id}', '\Transport\TbsExplorer:sessionGet');
    $this->post('/tbs/bookings/cancel', '\Transport\TbsExplorer:bookingsCancelPost');
    $this->put('/tbs/bookings/changebusout', '\Transport\TbsExplorer:changeBusOutPut');
    $this->put('/tbs/bookings/changebusreturn', '\Transport\TbsExplorer:changeBusReturnPut');
    $this->get('/tbs/bookings/bussummaries/{id}', '\Transport\TbsExplorer:busSummariesGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
