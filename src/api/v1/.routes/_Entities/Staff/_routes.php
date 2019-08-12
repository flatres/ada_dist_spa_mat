<?php
namespace Entities\Staff;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/staff', function(){

    $this->get('/lists/details', '\Entities\Staff\Lists:detailsGet');

});
// ->add("Authenticate");
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
