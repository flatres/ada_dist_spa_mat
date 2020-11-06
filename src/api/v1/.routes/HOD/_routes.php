<?php
namespace HOD;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/hod', function(){

    // metrics
    $this->get('/years/{subject}', '\HOD\Years:yearsGet');
    $this->get('/years/{subject}/exams/{year}', '\HOD\Years:examsGet');
    $this->get('/years/{subject}/examclasses/{year}/{exam}', '\HOD\Years:examClassesGet');

    $this->get('/{subject}/metrics/year/{year}', '\HOD\Metrics:yearGet');
    $this->get('/{subject}/metrics/class/{class}', '\HOD\Metrics:classGet');
    $this->get('/{subject}/metrics/year/{year}/MLO/{exam}', '\HOD\Metrics:yearMLOGet');
    $this->get('/{subject}/metrics/year/{year}/metrics/{exam}', '\HOD\Metrics:yearMetricsGet');
    $this->get('/{subject}/metrics/year/{year}/metrics/{exam}/spreadsheet', '\HOD\Metrics:yearMetricsSpreadSheetGet');
    $this->get('/{subject}/metrics/year/{year}/history/{exam}', '\HOD\Metrics:yearHistoryGet');

    // wyaps
    $this->get('/{subject}/wyaps/{year}/{exam}', '\HOD\Wyaps:wyapsGet');
    $this->post('/{subject}/wyaps/{year}/{exam}', '\HOD\Wyaps:wyapPost');
    $this->put('/{subject}/wyaps/{id}', '\HOD\Wyaps:wyapPut');
    $this->delete('/{subject}/wyaps/{id}', '\HOD\Wyaps:wyapDelete');
    $this->get('/{subject}/wyaps/{id}', '\HOD\Wyaps:wyapsResultsGet');

    // meetings
    $this->get('/meetings/{subject}/{year}/{exam}', '\HOD\Meetings:meetingClassesGet');
    $this->get('/meetings/download/{subject}/{year}/{exam}', '\HOD\Meetings:meetingClassesDownloadGet');
    $this->post('/meetings/{subject}/{exam}/{studentId}/{userId}/{classId}', '\HOD\Meetings:meetingPost');
    $this->get('/meetings/all/{year}', '\HOD\Meetings:meetingsAllGet');
    $this->get('/meetings/schoolscloud/{year}', '\HOD\Meetings:meetingsSchoolsCloudGet');
    $this->post('/meetings/counts', '\HOD\Meetings:meetingsCountsPost');

})->add("Authenticate");
