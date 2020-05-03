<?php

function emit($response, $data){

  // foreach((array)$data as &$d){
  //   if (is_object($d)){
  //       if (property_exists($d, 'conn')) $d = null;
  //   }
  //   if (is_array($d)) {
  //     foreach ($d as &$e) {
  //       if (is_object($e)){
  //           if (property_exists($e, 'conn')) $e = null;
  //       }
  //       if (is_object($d)){
  //           if (property_exists($d, 'conn')) $d = null;
  //       }
  //     }
  //   }
  // }
  // return;

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
