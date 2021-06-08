<?php

/**
 * Description

 * Usage:

 */
namespace Aux;

class SOCS
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       // $this->ada = $container->ada;
       // $this->adaModules = $container->adaModules;
    }

    public function absencesGet($request, $response, $args)
    {
      $key = $args['apiKey'];
      if ($key !== 'cce8472b265bd23686a7ff721d4af910') return emitError($response, 401, "Unauthorized");
      $absences = (new \Entities\Misc\Absences())->all();
      foreach($absences as &$a) {
        // unset($a['id']);
        unset($a['submittedAt']);
      }
      // $response->write('<html></html>');
      // $response = $response->withHeader('Content-Type', 'text/xml');
      // // $response->headers()->set('Content-Type', 'text/xml');
      //
      // // return $app -> render('rss.xml', array('articles' => $articles));
      return emit($response, $absences);
      // return json_encode($absences);
    }

}
