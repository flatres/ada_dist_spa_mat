<?php
namespace DHA;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/dha', function(){

    // metrics
    $this->get('/years', '\DHA\Years:yearsGet');
    $this->get('/access/year/{year}', '\DHA\Access:yearAccessGet');

})->add("Authenticate");
