<?php

/**
 * Description

 * Usage:

 */
namespace HOD;

class Wyaps
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

    public function wyapsGet($request, $response, $args)
    {
      $auth = $request->getAttribute('auth');
      $this->progress = new \Sockets\Progress($auth, 'hod.wyaps', 'Thinking... ');
      $pg = $this->progress;

      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $wyaps = (new \Entities\Academic\Subject($this->ada, $subjectId))->getWYAPsByExam($year, $examId);
      // $classes = (new \Entities\Academic\Subject($this->ada, $subjectId))->getStudentsByExam($)
      return emit($response, $wyaps);
    }

    public function wyapPost($request, $response, $args) {
      $subjectId = $args['subject'];
      $year = $args['year'];
      $examId = $args['exam'];
      $data = $request->getParsedBody();
      $wyap = new \Entities\Metrics\WYAP();
      $wyap->create($subjectId, $examId, $year, $data['name'], $data['marks']);
      return emit($response, $wyap);
    }

    public function wyapPut($request, $response, $args) {
      $data = $request->getParsedBody();

      $wyap = new \Entities\Metrics\WYAP($args['id']);
      $wyap->edit($data['name'], $data['marks'], $data['results']);
    }

    public function wyapDelete($request, $response, $args) {
      $id = $args['id'];
      $wyap = new \Entities\Metrics\WYAP($id);
      $wyap->delete($id);
    }

    public function wyapsResultsGet($request, $response, $args)
    {
      $id = $args['id'];

      $wyap = new \Entities\Metrics\WYAP($id);
      // $classes = (new \Entities\Academic\Subject($this->ada, $subjectId))->getStudentsByExam($)
      return emit($response, $wyap->results());
    }

}
