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
       $this->adaData = $container->adaData;
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
      $wyap->create($subjectId, $examId, $year, $data['name'], $data['marks'], $data['typeId']);
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

    public function wyapsBoundariesProfilePut($request, $response, $args)
    {
      $id = $args['id'];
      $boundaries = $request->getParsedBody();
      $wyap = new \Entities\Metrics\WYAP($id);
      // $classes = (new \Entities\Academic\Subject($this->ada, $subjectId))->getStudentsByExam($)
      return emit($response, $wyap->makeProfile($boundaries));
    }

    public function wyapsBoundariesPut($request, $response, $args)
    {
      $id = $args['id'];
      $boundaries = $request->getParsedBody();
      $wyap = new \Entities\Metrics\WYAP($id);
      // $classes = (new \Entities\Academic\Subject($this->ada, $subjectId))->getStudentsByExam($)
      return emit($response, $wyap->saveBoundaries($boundaries));
    }

    public function wyapsBoundariesGet($request, $response, $args) {
      $wyapId = $args['id'];

      $wyap = new \Entities\Metrics\WYAP($wyapId);
      // $classes = (new \Entities\Academic\Subject($this->ada, $subjectId))->getStudentsByExam($)
      return emit($response, $wyap->getBoundaries());

    }

    public function gradeSetsGet($request, $response, $args)
    {
      $year = $args['year'];
      $isLowerSchool = $year < 12 ? 1 : 0;
      $gs = $this->adaData->select('grade_sets', 'id, name', 'isActive = 1 AND isLowerSchool = ?', [$isLowerSchool]);
      $gradeSets = [];
      foreach($gs as $g) {
        $gradeSets[] = new \Entities\Academic\GradeSet($g['id']);
      }
      return emit($response, $gradeSets);
    }

    public function wyapGradeSetPut($request, $response, $args) {
      $wyapId = $args['id'];
      $gradeSetId = $args['gradeSetId'];

      $wyap = new \Entities\Metrics\WYAP($wyapId);
      $wyap->saveGradeSet($gradeSetId);
      // $classes = (new \Entities\Academic\Subject($this->ada, $subjectId))->getStudentsByExam($)
      return emit($response, $wyap->getBoundaries());
    }



}
