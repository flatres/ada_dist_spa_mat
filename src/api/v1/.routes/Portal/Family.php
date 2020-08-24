<?php

/**
 * Description

 * Usage:

 */
namespace Portal;

class Family
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       $this->isams = $container->isams;
    }

// ROUTE -----------------------------------------------------------------------------
// returns a list of students for the given familly ID
    public function studentsGet($request, $response, $args)
    {
      $familyId = $args['id'];
      $d = $this->ada->select('stu_details', 'id', 'mis_family_id=? AND disabled = 0 ORDER BY prename', [$familyId]);
      $students = [];
      foreach($d as $s){
        $students[] = new \Entities\People\Student($this->ada, $s['id']);
      }

      return emit($response, $students);
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
