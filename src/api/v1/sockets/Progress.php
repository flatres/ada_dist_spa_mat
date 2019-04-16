<?php

/**
 * Description

 * Usage:

 */
namespace Sockets;

use \ZMQContext;
use \ZMQ;

class Progress
{
    protected $container;
    public $lineIndex = 0;

    public function __construct($auth, $progressId = '')
    {
       $this->auth = $auth;
       $this->progressId = $progressId;
       $this->progress = 0;
       $this->isComplete = false;
       $this->lastPublishTime = 0;

    }

    //progress should be between 0 and 1
    public function publish (float $progress)
    {
      if ($progress >= 1) {
        $progress = 1;
        $this->isComplete = true;
        $this->send(100);
      }
      if ($progress < 0) {
        $progress = 0;
        $this->isComplete = false;
      }

      //only pubish every second to avoid madness
      if (time() - $this->lastPublishTime > 1){
          $this->send($progress * 100);
      }
    }

    private function send (float $progress)
    {
      $rounded = round($progress);
      $entryData = array(
                           'progress'    => $rounded,
                           'when'       => time(),
                           'progressId' => $this->progressId,
                           'isComplete' => $this->isComplete,
                           'socketId'   => 'progress',
                           'auth'       => $this->auth //auth is required so that it is send to the correct Socket
                        );

       // This is our new stuff
       $context = new \ZMQContext();
       // $context = new \React\ZMQ\Context();
       try{
         $socket = $context->getSocket(\ZMQ::SOCKET_PUSH);
         $socket->connect("tcp://127.0.0.1:5555");

       }catch(\ZMQSocketException $e){

         echo "An error occured\n";
        echo "{$e->getMessage()}\n";

       }
       $socket->send(json_encode($entryData));
       $this->lastPublishTime = time();
    }
}
