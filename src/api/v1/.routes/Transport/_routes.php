<?php
namespace Transport;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/transport', function(){

// SESSIONS --------------------------------------------------------------------------------------------------------
    $this->get('/sessions', '\Transport\TbsExtSessions:sessionsGet');
    $this->get('/sessions/{id}', '\Transport\TbsExtSessions:sessionGet');
    $this->delete('/sessions/{id}', '\Transport\TbsExtSessions:sessionDelete');
    $this->put('/sessions', '\Transport\TbsExtSessions:sessionPut');
    $this->post('/sessions', '\Transport\TbsExtSessions:sessionPost');
    $this->post('/sessions/activate/{id}', '\Transport\TbsExtSessions:sessionActivate');

// COACHES --------------------------------------------------------------------------------------------------------

    // COACHES
    $this->get('/coaches', '\Transport\Coaches:coachesGet');
    $this->get('/coaches/bookings/{sessionID}', '\Transport\Coaches:bookingsGet');
    $this->post('/coaches/bookings/cancel', '\Transport\Coaches:bookingsCancelPost');
    $this->put('/coaches/bookings/changebusout', '\Transport\Coaches:changeBusOutPut');
    $this->put('/coaches/bookings/changebusreturn', '\Transport\Coaches:changeBusReturnPut');
    // $this->get('/coaches/bookings/bussummaries/{id}', '\Transport\Coaches:busSummariesGet');

// TAXIS --------------------------------------------------------------------------------------------------------

    $this->get('/taxis/bookings/{session}/{type}', '\Transport\TbsExtTaxisBookings:bookingsGet');
    $this->get('/taxis/companies/bookings/{session}', '\Transport\TbsExtTaxisBookings:bookingsByCompanyGet');
    // $this->get('/taxis/bookings/{session}/{id}', '\Transport\TbsExtTaxisBookings:bookingGet');
    $this->post('/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPost');
    $this->put('/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPut');
    $this->put('/taxis/assignment', '\Transport\TbsExtTaxisBookings:taxiAssigmentPut');
    $this->delete('/taxis/bookings/{id}', '\Transport\TbsExtTaxisBookings:bookingDelete');

    // Pickup Locations
    $this->get('/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsGet');
    $this->get('/taxis/locations/pickup/{id}', '\Transport\TbsExtTaxisAdmin:pickupLocationGet');
    $this->post('/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsPost');
    $this->put('/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsPut');
    $this->delete('/taxis/locations/pickup/{id}', '\Transport\TbsExtTaxisAdmin:pickupLocationsDelete');

    // AIRPORTS
    $this->get('/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportsGet');
    $this->get('/taxis/locations/airports/{id}', '\Transport\TbsExtTaxisAdmin:airportGet');
    $this->post('/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportPost');
    $this->put('/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportPut');
    $this->delete('/taxis/locations/airports/{id}', '\Transport\TbsExtTaxisAdmin:airportDelete');

    // STATIONS
    $this->get('/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationsGet');
    $this->get('/taxis/locations/stations/{id}', '\Transport\TbsExtTaxisAdmin:stationGet');
    $this->post('/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationPost');
    $this->put('/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationPut');
    $this->delete('/taxis/locations/stations/{id}', '\Transport\TbsExtTaxisAdmin:stationDelete');

    // COMPANIES
    $this->get('/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesGet');
    $this->post('/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesPost');
    $this->put('/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesPut');
    $this->delete('/taxis/companies/{id}', '\Transport\TbsExtTaxisAdmin:companiesDelete');


})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
