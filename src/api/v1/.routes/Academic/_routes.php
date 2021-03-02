<?php
namespace Academic;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/academic', function(){

    $this->get('/prizes', '\Academic\Prizes:prizesGet');
    $this->get('/census', '\Academic\Census:censusGet');
    $this->get('/alis/registration', '\Academic\Alis:alisRegistrationGet');
    $this->post('/alis/upload/{isFromTest}', '\Academic\Alis:alisGCSEUploadPost');
    $this->post('/midyis/upload', '\Academic\Midyis:midyisUploadPost');
    $this->get('/covid', '\Academic\Covid:covidGet');

    $this->get('/ucas/grades', '\Academic\Ucas:ucasGradesGet');

    $this->get('/almanac/history', '\Academic\Almanac:almanacHistoryGet');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
