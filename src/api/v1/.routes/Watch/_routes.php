<?php
namespace Watch;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/watch/exgarde', function(){

    // $this->get('/chapel/attendance/{date}', '\Location\Chapel\Chapel:chapelAttendance_GET');
    $this->get('/test', '\Watch\Exgarde:testGET');
    $this->get('/people', '\Watch\Exgarde:peopleGET');
    $this->get('/people/{id}', '\Watch\Exgarde:personGET');
    $this->get('/people/{id}/{date}', '\Watch\Exgarde:personByDateGET');
    $this->get('/areas', '\Watch\Exgarde:areasGET');
    $this->get('/areas/{id}', '\Watch\Exgarde:areaGET');
    $this->get('/locations', '\Watch\Exgarde:locationsGET');
    $this->get('/locations/{id}', '\Watch\Exgarde:locationGET');
    $this->get('/locations/{id}/{date}', '\Watch\Exgarde:locationByDateGET');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
