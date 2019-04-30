<?php

/**
 * Description

 * Usage:

 */
namespace Lab;

define('ZMQ_SERVER', getenv("ZMQ_SERVER"));

class Console
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

// ROUTE -----------------------------------------------------------------------------

    // return data for the test table
    public function tableGet($request, $response, $args)
    {
      $data = $this->adaModules->select('lab_sockets_data', '*', 'id > 0', array());
      return emit($response, $data);
    }

    public function carsGet($request, $response, $args)
    {
      $data = $this->adaModules->select('lab_sockets_cars', '*', 'id > 0', array());
      return emit($response, $data);
    }

    public function tablePost($request, $response, $args)
    {
      $data = $request->getParsedBody();
      $data['id'] = $this->adaModules->insertObject('lab_sockets_data', $data);
      return emit($response, $data);
    }

    public function tablePut($request, $response, $args)
    {
      $data = $request->getParsedBody();
      $data = $this->adaModules->updateObject('lab_sockets_data', $data, 'id');
      return emit($response, $data );
    }

    public function tableDelete($request, $response, $args)
    {
      $data = $this->adaModules->delete('lab_sockets_data', 'id = ?', array(args['id']));
      return emit($response, $data );
    }

    // Get Address of ZMQ Server
    public function ZMQGet($request, $response, $args)
    {
      return emit($response, ZMQ_SERVER );
    }

    // send a notification to self
    public function notifyPost($request, $response)
    {
      $data = $request->getParsedBody();
      $auth = $request->getAttribute('auth');

      $notify = new \Sockets\Notify($auth);
      $notify->publish($data['message']);

      return emit($response, $data);
    }

    public function consolePost($request, $response)
    {
      $data = $request->getParsedBody();
      $auth = $request->getAttribute('auth');

      $console = new \Sockets\Console($auth);
      $console->publish('Let\'s Go');

      $progress = new \Sockets\Progress($auth, 'Lab.Sockets.Console');
      $notify = new \Sockets\Notify($auth);

      $notify->publish("Let's go");

      $p = 0;
      $l = 20;
      for ($x = 0; $x <= 0.5 * $l; $x++) {
        sleep(1);
        $p = $p + 1 / $l;
        $progress->publish($p);
        $string = $this->randomString(4) . ' ' . $this->randomString(6);
        $console->publish($string);
      }

      $console->error('This is what an error looks like');

      $console->publish('This is replace mode:');
      $console->publish('-');

      for ($x = 0; $x <= 0.5 * $l; $x++) {
        sleep(1);
        $p = $p + 1 / $l;
        $progress->publish($p);
        $string = "Number $x";
        $console->replace($string);
      }


      $progress->publish(1);

      $data['message'] = 'Complete';

      $notify->publish("Finished!");

      return emit($response, $data);
    }

    private function randomString($length = 6)
    {
        $length = rand(4,10);
        $string     = '';
        $vowels     = array("a","e","i","o","u");
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );
        // Seed it
        srand((double) microtime() * 1000000);
        $max = $length/2;
        for ($i = 1; $i <= $max; $i++)
        {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }
        return $string;
    }
    // public function ROUTEGet($request, $response, $args)
    // {
    //   return emit($response, $this->adaModules->select('TABLE', '*'));
    // }
    //
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
