<?php

/**
 * Description

 * Usage:

 */
namespace HM;

class House
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       // $this->adaModules = $container->adaModules;
       // $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------
    public function listGet($request, $response, $args)
    {

      $d = $this->ada->select('sch_houses', 'id, name, code', 'id>?', [0]);
      // $all = new \Entities\Houses\All();
      return emit($response, $d);
    }

    public function notesGet($request, $response, $args)
    {
      $houseId = $args['house'];
      $house = new \Entities\Houses\House($this->ada, $houseId);
      $house->getStudents();
      // $all = new \Entities\Houses\All();
      foreach ($house->students as &$s) {
        $s->getHmNote();
      }
      return emit($response, $house->students);
    }

    public function notePost($request, $response)
    {
      $data = $request->getParsedBody();
      $student = new \Entities\People\Student($this->ada, $data['id']);
      $student->setHmNote($data['hmNote']);

      return emit($response, true);
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

}
