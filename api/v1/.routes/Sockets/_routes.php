<?php
namespace Sockets;

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/sockets', function(){
    $this->get('/test', function (Request $request, Response $response, array $args) {
                                        $collection = (new \MongoDB\Client)->ada->mod_exams_gcse;

                                          $insertOneResult = $collection->insertOne(['_id' => 1, 'name' => 'Alice']);
                                          printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());

                                          var_dump($insertOneResult->getInsertedId());

                                  return $response;
                                });
})
// $app->get('/test', '\Auth\TestClass:testGet')->add(new \Authenticate);
?>
