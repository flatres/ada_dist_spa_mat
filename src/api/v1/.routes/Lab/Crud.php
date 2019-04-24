<?php

/**
 * Description

 * Usage:

 */
namespace Lab;

class Crud
{
    protected $container;

    private $cars = array('Ford', 'Audi', 'Skoda', 'VW', 'Volvo', 'Kia');

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       // $this->msSql = $container->msSql;
    }

// ROUTE -----------------------------------------------------------------------------
    public function basicGet($request, $response, $args)
    {
      $data = array();

      $cars = $this->cars;

      for ($x = 0; $x <= 1000; $x++) {
        $item = array();
        $item['id'] = $x+1;
        $item['name'] = $this->randomString();
        $item['car'] = rand(0, count($cars) - 1);
        $item['birthday'] = rand(1990, 2005). '/' . rand(1,12) . '/' . rand(1,28) ;
        $item['time'] = rand(0,23) . ':' . rand(0,59);
        $item['notes'] = '';
        for ($y = 0; $y <= 100; $y++) {
          $item['notes'] .= $this->randomString() . ' ';
        }
        $item['isActive'] = $x==2 ? true : false;
        $item['unread'] = rand(0,100) < 10 ? true : false;
        $data[] = $item;
      }
      return emit($response, $data);
    }

    public function carsGet($request, $response, $args)
    {
      $options = array();
      $x = 0;
      foreach($this->cars as $car){
        $options[] = array('label' => $car, 'value' => $x);
        $x++;
      }

      return emit($response, $options);
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

}
