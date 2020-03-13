<?php
namespace Aux;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/aux', function(){

    $this->get('/bookings/coach/register/{uniqueId}', '\Aux\Bookings:coachGet');
    $this->put('/bookings/coach/register', '\Aux\Bookings:registerPut');
    $this->get('/period/{date}/{time}', '\Admin\School\Calendar:periodGet');

});
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
