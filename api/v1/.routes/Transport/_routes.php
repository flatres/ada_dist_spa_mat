<?php
namespace Transport;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/transport', function(){

// SESSIONS --------------------------------------------------------------------------------------------------------
    $this->get('/ext/sessions', '\Transport\TbsExtSessions:sessionsGet');
    $this->get('/ext/sessions/{id}', '\Transport\TbsExtSessions:sessionGet');
    $this->delete('/ext/sessions/{id}', '\Transport\TbsExtSessions:sessionDelete');
    $this->put('/ext/sessions', '\Transport\TbsExtSessions:sessionPut');
    $this->post('/ext/sessions', '\Transport\TbsExtSessions:sessionPost');
    $this->post('/ext/sessions/activate/{id}', '\Transport\TbsExtSessions:sessionActivate');

// COACHES --------------------------------------------------------------------------------------------------------

    // COACHES
    $this->get('/ext/coaches/session/{id}', '\Transport\TbsExtCoaches:sessionGet');
    $this->post('/ext/coaches/bookings/cancel', '\Transport\TbsExtCoaches:bookingsCancelPost');
    $this->put('/ext/coaches/bookings/changebusout', '\Transport\TbsExtCoaches:changeBusOutPut');
    $this->put('/ext/coaches/bookings/changebusreturn', '\Transport\TbsExtCoaches:changeBusReturnPut');
    $this->get('/ext/coaches/bookings/bussummaries/{id}', '\Transport\TbsExtCoaches:busSummariesGet');

// TAXIS --------------------------------------------------------------------------------------------------------

    $this->get('/ext/taxis/bookings/{session}', '\Transport\TbsExtTaxisBookings:bookingsGet');
    $this->get('/ext/taxis/bookings/{session}/{id}', '\Transport\TbsExtTaxisBookings:bookingGet');
    $this->post('/ext/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPost');
    $this->put('/ext/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPut');
    $this->delete('/ext/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingDelete');

    // Pickup Locations
    $this->get('/ext/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsGet');
    $this->get('/ext/taxis/locations/pickup/{id}', '\Transport\TbsExtTaxisAdmin:pickupLocationGet');
    $this->post('/ext/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsPost');
    $this->put('/ext/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsPut');
    $this->delete('/ext/taxis/locations/pickup/{id}', '\Transport\TbsExtTaxisAdmin:pickupLocationsDelete');

    // AIRPORTS
    $this->get('/ext/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportsGet');
    $this->get('/ext/taxis/locations/airports/{id}', '\Transport\TbsExtTaxisAdmin:airportGet');
    $this->post('/ext/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportPost');
    $this->put('/ext/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportPut');
    $this->delete('/ext/taxis/locations/airports/{id}', '\Transport\TbsExtTaxisAdmin:airportDelete');

    // STATIONS
    $this->get('/ext/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationsGet');
    $this->get('/ext/taxis/locations/stations/{id}', '\Transport\TbsExtTaxisAdmin:stationGet');
    $this->post('/ext/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationPost');
    $this->put('/ext/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationPut');
    $this->delete('/ext/taxis/locations/stations/{id}', '\Transport\TbsExtTaxisAdmin:stationDelete');

    // COMPANIES
    $this->get('/ext/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesGet');
    $this->post('/ext/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesPost');
    $this->put('/ext/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesPut');
    $this->delete('/ext/taxis/companies/{id}', '\Transport\TbsExtTaxisAdmin:companiesDelete');


})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
