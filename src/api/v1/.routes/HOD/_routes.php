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

    $this->get('/{subject}/metrics/year/{year}/metrics/{exam}/pdf/class', '\HOD\Metrics:pdfByClassGet');
    $this->get('/{subject}/metrics/year/{year}/metrics/{exam}/pdf/name', '\HOD\Metrics:pdfByNameGet');

    // wyaps
    $this->get('/{subject}/wyaps/{year}/{exam}', '\HOD\Wyaps:wyapsGet');
    $this->post('/{subject}/wyaps/{year}/{exam}', '\HOD\Wyaps:wyapPost');
    $this->put('/{subject}/wyaps/{id}', '\HOD\Wyaps:wyapPut');
    $this->delete('/{subject}/wyaps/{id}', '\HOD\Wyaps:wyapDelete');
    $this->get('/{subject}/wyaps/{id}', '\HOD\Wyaps:wyapsResultsGet');

    $this->get('/{subject}/wyaps/grades/boundaries/{id}', '\HOD\Wyaps:wyapsBoundariesGet');
    $this->put('/{subject}/wyaps/grades/boundaries/{id}', '\HOD\Wyaps:wyapsBoundariesPut');
    $this->put('/{subject}/wyaps/grades/boundaries/profile/{id}', '\HOD\Wyaps:wyapsBoundariesProfilePut');
    $this->get('/{subject}/wyaps/grades/{year}/gradesets', '\HOD\Wyaps:gradeSetsGet');
    $this->put('/{subject}/wyaps/{id}/gradeset/{gradeSetId}', '\HOD\Wyaps:wyapGradeSetPut');

    // meetings
    $this->get('/meetings/{subject}/{year}/{exam}', '\HOD\Meetings:meetingClassesGet');
    $this->get('/meetings/download/{subject}/{year}/{exam}', '\HOD\Meetings:meetingClassesDownloadGet');
    $this->post('/meetings/{subject}/{exam}/{studentId}/{userId}/{classId}', '\HOD\Meetings:meetingPost');
    $this->get('/meetings/all/{year}', '\HOD\Meetings:meetingsAllGet');
    $this->get('/meetings/schoolscloud/{year}', '\HOD\Meetings:meetingsSchoolsCloudGet');
    $this->get('/meetings/schoolscloudfair/{year}', '\HOD\Meetings:meetingsSchoolsCloudFairGet');
    $this->post('/meetings/counts', '\HOD\Meetings:meetingsCountsPost');

    //science
    $this->get('/science/tags/spreadsheet', '\HOD\Science:tagsSpreadsheetGet');

})->add("Authenticate");
