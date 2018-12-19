<?php

// require 'bootstrap.php';
  require dirname(__DIR__) . '/../api/vendor/autoload.php';

  use React\EventLoop\Factory;
  use React\ZMQ\Context;
  use Thruway\Logging\Logger;
  use Thruway\Peer\Client;
  use Thruway\Peer\Router;
  use Thruway\Transport\RatchetTransportProvider;

  $realm = "realm1";

  $loop   = Factory::create();

  $router = new Router($loop);

  $router->addInternalClient(new SocketServer\Client($realm, $loop));
  // https://hotexamples.com/examples/thruway.peer/Router/addInternalClient/php-router-addinternalclient-method-examples.html
  // http://voryx.net/creating-internal-client-thruway/

  $router->registerModule(new RatchetTransportProvider("127.0.0.1", 9090));

  $router->start();
