<?php
namespace Portal\Transport;

use Slim\Http\Request;
use Slim\Http\Response;



$app->group('/portal/transport', function(){

    $this->get('/session', '\Portal\Transport\Bookings:activeSessionGet');
    $this->get('/bookings/{familyId}', '\Portal\Transport\Bookings:familyBookingsGet');
    $this->get('/bookings/user/{parentUserId}', '\Portal\Transport\Bookings:userBookingsGet');

    $this->get('/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsGet');

    // AIRPORTS
    $this->get('/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportsGet');

    // STATIONS
    $this->get('/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationsGet');

    // COMPANIES
    $this->get('/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesGet');
    // $this->get('/transport', '\Auth\TestClass:testGet');

    // $this->get('/taxis/bookings/{session}/{type}', '\Transport\TbsExtTaxisBookings:bookingsGet');

    $this->post('/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPost');
    $this->put('/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPut');
    $this->delete('/taxis/bookings/{id}', '\Transport\TbsExtTaxisBookings:bookingDelete');


    //COACHES
    $this->get('/coaches/stops/{sessionId}', '\Transport\TbsExtRoutes:stopsGet');
    $this->post('/coaches/bookings', '\Transport\TbsExtCoachesBookings:bookingPost');
    $this->post('/coaches/bookings/selfservice', '\Transport\TbsExtCoachesBookings:bookingSelfServicePost');
    $this->delete('/coaches/bookings/{id}', '\Transport\TbsExtCoachesBookings:bookingDelete');

});
