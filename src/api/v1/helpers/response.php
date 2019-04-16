<?php

function emit($response, $data){

  $packagedResponse = $response->withJson($data, 200);
  return $packagedResponse;

}
// 400 = bad request
function emitError($response,$code, $message){

  $data = ['error'=>true, 'message' => $message];

  $packagedResponse = $response->withJson($data, $code);

  return $packagedResponse;

}

?>
