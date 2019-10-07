<?php
namespace Portal;

use Slim\Http\Request;
use Slim\Http\Response;



$app->group('/portal', function(){

    $this->get('/students/{id}', '\Portal\Family:studentsGet');
    $this->get('/transport/taxis/locations/pickup', '\Transport\TbsExtTaxisAdmin:pickupLocationsGet');

    // AIRPORTS
    $this->get('/transport/taxis/locations/airports', '\Transport\TbsExtTaxisAdmin:airportsGet');
  
    // STATIONS
    $this->get('/transport/taxis/locations/stations', '\Transport\TbsExtTaxisAdmin:stationsGet');
  
    // COMPANIES
    $this->get('/transport/taxis/companies', '\Transport\TbsExtTaxisAdmin:companiesGet');
    // $this->get('/transport', '\Auth\TestClass:testGet');

});

