<?php
namespace Exams;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/exams', function(){

    $this->get('/sessions', '\Exams\Results:getSessions');
    $this->get('/gcse/results/{sessionId}', '\Exams\Results:getGCSEResults');
    $this->get('/cache/gcse/results/{sessionId}', '\Exams\Results:getCachedGCSEResults');
    $this->get('/cache/alevel/results/{sessionId}', '\Exams\Results:getCachedALevelResults');
    $this->get('/alevel/results/{sessionId}', '\Exams\Results:getALevelResults');

    $this->get('/admin/sessions', '\Exams\Admin:sessionsGet');
    $this->get('/admin/session/{id}', '\Exams\Admin:sessionGet');
    $this->get('/admin/files', '\Exams\Admin:filesGet');
    $this->get('/admin/subjects', '\Exams\Admin:subjectsGet');
    $this->delete('/admin/caches', '\Exams\Admin:cachesDelete');
    $this->delete('/admin/cache/{id}', '\Exams\Admin:cacheDelete');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
