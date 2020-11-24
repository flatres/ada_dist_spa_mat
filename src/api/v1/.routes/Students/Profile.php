<?php

/**
 * Description

 * Usage:

 */
namespace Students;

class Profile
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
      $this->container = $container;
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;

    }

// ROUTE -----------------------------------------------------------------------------
    public function subjectsGet($request, $response, $args)
    {
      $id = $args['id'];
      $this->isams = $this->container->isams;
      $student = new \Entities\People\iSamsStudent($this->isams);
      $student->byAdaId($id);
      $student->getSubjects();

      return emit($response, $student);
    }

    public function exgardeGet($request, $response, $args)
    {
      $id = $args['id'];
      $exgarde = $this->container->exgarde;
      $student = new \Entities\People\Student($this->ada, $id);
      $events = [];
      $exg = $this->adaModules->select('watch_exgarde_students', 'exgarde_id', 'student_id=?', [$id]);
      if (isset($exg[0])) {
        $exgId = $exg[0]['exgarde_id'];
        $events = $exgarde->getPerson($exgId);
      }
      // $student->byAdaId($id);
      // $student->getSubjects();
      $data = ['student' => $student, 'events' => $events, 'exgId' => $exgId ?? null, 'id' => $id ?? null];
      return emit($response, $data);
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
