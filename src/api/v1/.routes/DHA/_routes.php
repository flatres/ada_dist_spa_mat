<?php
namespace DHA;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/dha', function(){

    // metrics
    $this->get('/years', '\DHA\Years:yearsGet');
    $this->get('/access/year/{year}', '\DHA\Access:yearAccessGet');
    $this->get('/wyaps/year/{year}', '\DHA\Wyaps:yearWyapsGet');
    $this->get('/wyaps/statistics/meg/year/{year}', '\DHA\Wyaps:statisticsMEGGet');
    $this->get('/wyaps/statistics/tag/year/{year}', '\DHA\Wyaps:statisticsTAGGet');
    $this->get('/wyaps/statistics/simulate/year/{year}', '\DHA\Wyaps:statisticsSimulate');

    $this->post('/tags/upload/initial', '\DHA\Tags:initialUploadPost');
    $this->post('/tags/upload/final/{id}/{code}/{year}', '\DHA\Tags:finalUploadPost');
    $this->get('/tags/overview/year/{year}', '\DHA\Tags:overviewGet');
    $this->put('/tags/flag/{id}/{flag}', '\DHA\Tags:toggleFlagPut');
    $this->put('/tags/checked/{id}/{flag}', '\DHA\Tags:toggleCheckedPut');

    $this->get('/ucas/pupils', '\DHA\Ucas:pupilsGet');

    $this->get('/baseline/alis/{year}', '\DHA\Baseline:alisGet');
    $this->get('/baseline/midyis/{year}', '\DHA\Baseline:midyisGet');

})->add("Authenticate");
