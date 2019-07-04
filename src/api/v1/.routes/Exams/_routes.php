<?php
namespace Exams;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/exams', function(){

    $this->get('/sessions', '\Exams\Results:getSessions');
    $this->get('/gcse/results/{sessionId}', '\Exams\Results:getGCSEResults');
    $this->get('/cache/gcse/results/{sessionId}', '\Exams\Results:getCachedGCSEResults');
    $this->get('/alevel/results/{sessionId}', '\Exams\Results:getALevelResults');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
