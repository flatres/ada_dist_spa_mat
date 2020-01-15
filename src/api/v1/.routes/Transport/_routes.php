<?php
namespace Transport;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/transport', function(){

// SESSIONS --------------------------------------------------------------------------------------------------------
    $this->get('/sessions', '\Transport\TbsExtSessions:sessionsGet');
    $this->get('/sessions/{id}', '\Transport\TbsExtSessions:sessionGet');
    $this->get('/sessions/deadlines/{dateOut}', '\Transport\TbsExtSessions:deadlinesGet');
    $this->delete('/sessions/{id}', '\Transport\TbsExtSessions:sessionDelete');
    $this->put('/sessions', '\Transport\TbsExtSessions:sessionPut');
    $this->post('/sessions', '\Transport\TbsExtSessions:sessionPost');
    $this->post('/sessions/activate/{id}', '\Transport\TbsExtSessions:sessionActivate');
    $this->post('/sessions/activate/checklist/{id}/{isActive}', '\Transport\TbsExtSessions:sessionActivateFromCheckist');
    $this->post('/sessions/{id}/selfservice/{isOn}', '\Transport\TbsExtSessions:sessionSelfServicePost');

// COACHES --------------------------------------------------------------------------------------------------------

    // COACHES
    // $this->get('/coaches', '\Transport\Coaches:coachesGet');
    // $this->get('/coaches/bookings/{sessionID}', '\Transport\Coaches:bookingsGet');
    // $this->post('/coaches/bookings/cancel', '\Transport\Coaches:bookingsCancelPost');
    // $this->put('/coaches/bookings/changebusout', '\Transport\Coaches:changeBusOutPut');
    // $this->put('/coaches/bookings/changebusreturn', '\Transport\Coaches:changeBusReturnPut');
    // $this->get('/coaches/bookings/bussummaries/{id}', '\Transport\Coaches:busSummariesGet');
    $this->post('/coaches/bookings', '\Transport\TbsExtCoachesBookings:bookingPost');
    $this->put('/coaches/bookings', '\Transport\TbsExtCoachesBookings:bookingPut');
    $this->get('/coaches/bookings/{session}', '\Transport\TbsExtCoachesBookings:allBookingsGet');
    $this->get('/coaches/checklist/{session}', '\Transport\TbsExtCoachesBookings:checklistGet');
    $this->get('/coaches/newbookings/{session}', '\Transport\TbsExtCoachesBookings:bookingsNewCountGet');
    $this->delete('/coaches/bookings/{id}', '\Transport\TbsExtCoachesBookings:bookingDelete');
    $this->delete('/coaches/bookings/decline/{id}', '\Transport\TbsExtCoachesBookings:bookingDecline');
    $this->put('/coaches/confirm', '\Transport\TbsExtCoachesBookings:coachConfirmPut');
    $this->put('/coaches/assignment', '\Transport\TbsExtCoachesBookings:coachAssigmentPut');

    //ROUTES
    $this->get('/coaches/routes/{sessionId}', '\Transport\TbsExtRoutes:routesGet');
    $this->get('/coaches/route/{id}', '\Transport\TbsExtRoutes:routeGet');
    $this->post('/coaches/route', '\Transport\TbsExtRoutes:routePost');
    $this->put('/coaches/route', '\Transport\TbsExtRoutes:routePut');
    $this->delete('/coaches/route/{id}', '\Transport\TbsExtRoutes:routeDelete');
    $this->post('/coaches/copy/{from}/{to}', '\Transport\TbsExtRoutes:copyRoutesPost');

    //ROUTE COACHES
    $this->post('/coaches/coach', '\Transport\TbsExtRoutes:coachPost');
    $this->post('/coaches/coach/register/email/{id}', '\Transport\TbsExtRoutes:coachRegisterEmailPost');
    $this->post('/coaches/registers/email/{sessionId}', '\Transport\TbsExtRoutes:sendAllRegistersPost');
    //
    $this->put('/coaches/coach', '\Transport\TbsExtRoutes:coachPut');
    $this->delete('/coaches/coach/{id}', '\Transport\TbsExtRoutes:coachDelete');
    $this->put('/coaches/coach/supervisor/{coachId}/{supervisorId}', '\Transport\TbsExtRoutes:supervisorPut');

    //ROUTE COACH STOPS
    $this->get('/coaches/stops/{sessionId}', '\Transport\TbsExtRoutes:stopsGet');
    $this->post('/coaches/stop', '\Transport\TbsExtRoutes:stopPost');
    $this->put('/coaches/stop', '\Transport\TbsExtRoutes:stopPut');
    $this->delete('/coaches/stop/{id}', '\Transport\TbsExtRoutes:stopDelete');

    $this->put('/coaches/coach/stop', '\Transport\TbsExtRoutes:coachStopPut');


// TAXIS --------------------------------------------------------------------------------------------------------

    $this->get('/taxis/bookings/{session}', '\Transport\TbsExtTaxisBookings:allBookingsGet');
    $this->get('/taxis/bookings/{session}/{type}', '\Transport\TbsExtTaxisBookings:bookingsGet');
    $this->get('/taxis/newbookings/{session}', '\Transport\TbsExtTaxisBookings:bookingsNewCountGet');
    $this->get('/taxis/companies/bookings/{session}', '\Transport\TbsExtTaxisBookings:bookingsByCompanyGet');
    // $this->get('/taxis/bookings/{session}/{id}', '\Transport\TbsExtTaxisBookings:bookingGet');
    $this->post('/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPost');

    $this->post('/taxis/summary', '\Transport\TbsExtTaxisBookings:summaryPost');
    $this->get('/taxis/summary/{sessionId}/{taxiId}', '\Transport\TbsExtTaxisBookings:summaryGET');

    $this->put('/taxis/bookings', '\Transport\TbsExtTaxisBookings:bookingPut');
    $this->put('/taxis/assignment', '\Transport\TbsExtTaxisBookings:taxiAssigmentPut');
    $this->put('/taxis/confirm', '\Transport\TbsExtTaxisBookings:taxiConfirmPut');
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
