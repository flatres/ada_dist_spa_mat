<?php
namespace Aux;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/aux', function(){

    $this->get('/bookings/coach/register/{uniqueId}', '\Aux\Bookings:coachGet');
    $this->put('/bookings/coach/register', '\Aux\Bookings:registerPut');
    $this->get('/period/{date}/{time}', '\Admin\School\Calendar:periodGet');

    $this->get('/covid/staff/{hash}', '\Aux\Covid:staffDetailsGet');
    $this->get('/covid/student/{hash}', '\Aux\Covid:studentDetailsGet');

    $this->post('/covid/student', '\Aux\Covid:studentResponsePost');
    $this->post('/covid/staff', '\Aux\Covid:staffResponsePost');

});
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
