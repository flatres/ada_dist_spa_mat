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
      $email = new \Utilities\Email\Email('flatres@gmail.com', $data['subject']);
      $fields = [];
      foreach($data['fields'] as $field) {
        $fields[$field['label']] = $field['value'];
      }
      $content = $email->template($data['template'], $fields);
      
      $res = $email->send($content);
      
      return emit($response, $res);
    }
    
    public function templatesGet($request, $response, $args)
    {
      $email = new \Utilities\Email\Email();
      $files = $email->listTemplates();
      return emit($response, $files);
    }

}
