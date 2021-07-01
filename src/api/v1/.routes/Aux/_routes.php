<?php
namespace Aux;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/aux', function(){

    $this->get('/bookings/coach/register/{uniqueId}', '\Aux\Bookings:coachGet');
    $this->put('/bookings/coach/register', '\Aux\Bookings:registerPut');
    $this->post('/bookings/coach/booking', '\Aux\Bookings:bookingPost');
    $this->get('/bookings/coach/houses/{registerId}', '\Aux\Bookings:housesGet');
    $this->get('/bookings/coach/students/{registerId}', '\Aux\Bookings:studentsGet');

    $this->get('/period/{date}/{time}', '\Admin\School\Calendar:periodGet');

    $this->get('/covid/staff/{hash}', '\Aux\Covid:staffDetailsGet');
    $this->get('/covid/student/{hash}', '\Aux\Covid:studentDetailsGet');

    $this->post('/covid/student', '\Aux\Covid:studentResponsePost');
    $this->post('/covid/staff', '\Aux\Covid:staffResponsePost');

    $this->get('/socs/absences/{apiKey}', '\Aux\SOCS:absencesGet');

    $this->post('/pupils/login', '\Aux\Pupils:loginPost');

});
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
