<?php

/**
 * Description

 * Usage:

 */
namespace HM;

class Bandwidth
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->baseURL = 'http://msp.marlboroughcollege.org/apis/v1/api/';
       $this->auth = '7f3f9530-129f-4a65-8391-9f7e13c7e944';
    
    }

// ROUTE -----------------------------------------------------------------------------
    public function allHousesGet($request, $response, $args)
    {
      $client = new \GuzzleHttp\Client(['verify' => false]);
      $days = 2;
      // $days = $args['days'];
      // $url = $this->baseURL . 'pupilBW/All/All/2';
      $url = $this->baseURL . 'pupilBW/All/All/' . $days;
      
      $res = $client->request('GET', $url, [
        'headers' => [
            'Accept' => 'application/json',
            'Auth_Token' => $this->auth
        ]
      ]);
      $status =  $res->getStatusCode();           // 200
      // echo $res->getHeader('content-type'); // 'application/json; charset=utf8'
      // echo $res->getBody();                 // {"type":"User"...'
      // var_dump($res->json());
      //   var_export($res->json());
      $body = $res->getBody()->getContents();
      $body = json_decode($body);
      $data = [
        'url' => $url,
        'status' => $status,
        'body'  => $body
      ];
      return emit($response, $data);
    }

    // public function ROUTEPost($request, $response)
    // {
    //   $data = $request->getParsedBody();
    //   $data['id'] = $this->adaModules->insertObject('TABLE', $data);
    //   return emit($response, $data);
    // }
    //
    // public function ROUTELocationsPut($request, $response)
    // {
    //   $data = $request->getParsedBody();
    //   return emit($response, $this->adaModules->updateObject('TABLE', $data, 'id'));
    // }
    //
    // public function ROUTEDelete($request, $response, $args)
    // {
    //   return emit($response, $this->adaModules->delete('TABLE', 'id=?', array($args['id'])));
    // }

}
