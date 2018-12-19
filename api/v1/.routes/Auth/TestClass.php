<?php
namespace Auth;

class TestClass
{

    protected $container;

    public function __construct(\Slim\Container $container) {

       $this->container = $container;

       $this->db =  $container->mysql;
    }

    public function testGet($request, $response, $args) {

      $data = array('message'=>'it worked');
      return emit($response, $data);
    }
}

 ?>
