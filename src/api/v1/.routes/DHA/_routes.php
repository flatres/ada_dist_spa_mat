<?php
namespace DHA;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/dha', function(){

    // metrics
    $this->get('/years', '\DHA\Years:yearsGet');
    $this->get('/access/year/{year}', '\DHA\Access:yearAccessGet');
    $this->get('/wyaps/year/{year}', '\DHA\Wyaps:yearWyapsGet');

})->add("Authenticate");
