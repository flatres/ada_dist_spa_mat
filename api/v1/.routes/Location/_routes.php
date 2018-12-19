<?php
namespace Location;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/location', function(){

    $this->get('/chapel/attendance/{date}', '\Location\Chapel\Chapel:chapelAttendance_GET');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
