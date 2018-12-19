<?php
namespace SocketServer;
// use Ratchet\ConnectionInterface;
// use Ratchet\Wamp\WampServerInterface;
use React\ZMQ\Context;

class Client extends \Thruway\Peer\Client{

     public function __construct($realm, $loop)
     {
         parent::__construct($realm, $loop);

        $this->on('open', function ($session) use ($loop) {
            $context = new Context($loop);
            $pull    = $context->getSocket(\ZMQ::SOCKET_PULL, 'console');
            $pull->bind('tcp://127.0.0.1:5555');

            // Handle incoming messages
            $pull->on('message', function ($entry) use ($session) {
                $entryData = json_decode($entry, true);
                $channel = null;
                $auth = $entryData['auth'];
                $socketId = $entryData['socketId'];
                switch($socketId){
                  case 'console':
                  case 'updater':
                  case 'progress':
                    $channel = $socketId . '_' . $auth;
                    break;
                  case 'role':
                    // echo 'role update - ('.$entryData['roleId'].')';
                    $channel = 'role_' . $entryData['roleId'];
                    break; 
                  default: return;
                }

                if (isset($entryData['auth'])) unset($entryData['auth']);
                echo 'Publishing to ' . $channel . PHP_EOL;
                $session->publish($channel, [$entryData]);

            });
        });
     }

     // https://stackoverflow.com/questions/50210887/thruway-manage-subscriptions
     // https://www.reddit.com/r/PHP/comments/4axpms/is_anyone_using_php_with_websockets/

    public function onSessionStart($session, $loop)
    {
        echo "--------------- Hello from Ada Internal Client ------------\n";

    }



}
