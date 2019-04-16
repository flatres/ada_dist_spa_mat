<?php
namespace Location;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/location', function(){

    $this->get('/chapel/attendance/{date}', '\Location\Chapel\Chapel:chapelAttendance_GET');

    $this->get('/system/areas', '\Location\System:areasGET');
    $this->get('/system/areas/{id}', '\Location\System:areaGET');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
