<?php

function emit($response, $data){

  $packagedResponse = $response->withJson($data, 200, JSON_INVALID_UTF8_IGNORE);
  return $packagedResponse;

}

function emitRaw($response, $data){

  $packagedResponse = $response->getBody()->write(serialize($data));
  return $packagedResponse;

}

// 400 = bad request
function emitError($response, $code, $message){

  $data = ['error'=>true, 'message' => $message];

  $packagedResponse = $response->withJson($data, $code);

  return $packagedResponse;

}

?>
