<?php
namespace Portal;

use Slim\Http\Request;
use Slim\Http\Response;



$app->group('/portal', function(){

    $this->get('/students/{id}', '\Portal\Family:studentsGet');
    $this->get('/user/code/{userCode}', '\Portal\User:byCodeGet');

});

