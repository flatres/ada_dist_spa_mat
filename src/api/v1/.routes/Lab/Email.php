<?php

/**
 * Description

 * Usage:

 */
namespace Lab;

// define('ZMQ_SERVER', getenv("ZMQ_SERVER"));

class Email
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------

    public function emailPost($request, $response, $args)
    {
      $data = $request->getParsedBody();
      $postmark = new \Utilities\Postmark\Postmark($data['to'], $data['subject'], "Lab", true);
      $content = $postmark->template('Lab.Email', array("body"=>$data['body']));
      $postmark->send("Ada Lab", $content);
      return emit($response, $data);
    }

}
