<?php
namespace Exams;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/data/exams', function(){

    $this->get('/sessions', '\Data\Exams\Results:getSessions');
    $this->get('/gcse/results/{sessionId}', '\Data\Exams\Results:getGCSEResults');
    $this->get('/alevel/results/{sessionId}', '\Data\Exams\Results:getALevelResults');

})->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
