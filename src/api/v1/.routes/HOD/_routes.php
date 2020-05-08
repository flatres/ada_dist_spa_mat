<?php
namespace HOD;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/hod', function(){

    $this->get('/years/{subject}', '\HOD\Years:yearsGet');
    $this->get('/years/{subject}/exams/{year}', '\HOD\Years:examsGet');
    $this->get('/years/{subject}/examclasses/{year}/{exam}', '\HOD\Years:examClassesGet');

    $this->get('/{subject}/metrics/year/{year}', '\HOD\Metrics:yearGet');
    $this->get('/{subject}/metrics/class/{class}', '\HOD\Metrics:classGet');
    $this->get('/{subject}/metrics/year/{year}/MLO/{exam}', '\HOD\Metrics:yearMLOGet');
    $this->get('/{subject}/metrics/year/{year}/metrics/{exam}', '\HOD\Metrics:yearMetricsGet');
    $this->get('/{subject}/metrics/year/{year}/metrics/{exam}/spreadsheet', '\HOD\Metrics:yearMetricsSpreadSheetGet');
    $this->get('/{subject}/metrics/year/{year}/history/{exam}', '\HOD\Metrics:yearHistoryGet');

})->add("Authenticate");
