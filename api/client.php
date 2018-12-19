<?php

require dirname(__DIR__) . '/api/vendor/autoload.php';


use Thruway\ClientSession;
use Thruway\Connection;

$onClose = function ($msg) {
    echo $msg;
};

$connection = new Connection(
    [
        "realm"   => 'realm1',
        "onClose" => $onClose,
        "url"     => 'ws://127.0.0.1:9090',
    ]
);

$connection->on(
    'open',
    function (ClientSession $session) {

        // 1) subscribe to a topic
        $onevent = function ($args) {
            // echo "Event {$args[0]}\n";
            print_r($args);
        };
        $session->subscribe('poo', $onevent);

    }

);

$connection->open();
