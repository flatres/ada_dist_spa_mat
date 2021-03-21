<?php

/**
 * Description

 * Usage:

 */
namespace Aux;

class Covid
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
    }

    // returns the coach corresponding to the unique ID so that the supervisor can register
    public function staffDetailsGet($request, $response, $args)
    {
      $hash = $args['hash'];
      $d = (object)$this->adaModules->select('covid_answers_staff', 'user_id, date, hasAnswered', 'hash=?', [$hash])[0] ?? null;
      if (!$d) return emit($response, []);
      $staff = new \Entities\People\User($this->ada, $d->user_id);
      $d->prename = $staff->preName;
      $d->lastName = $staff->lastName;
      $d->datePretty = date("D M j", strtotime($d->date));

      return emit($response, $d);
    }

    public function studentDetailsGet($request, $response, $args)
    {
      $hash = $args['hash'];
      $d = (object)$this->adaModules->select('covid_answers_students', 'student_id, date, hasAnswered', 'hash=?', [$hash])[0] ?? null;
      if (!$d) return emit($response, []);
      $student = new \Entities\People\Student($this->ada, $d->student_id);
      $d->prename = $student->preName;
      $d->lastName = $student->lastName;
      $d->datePretty = date("D M j", strtotime($d->date));

      return emit($response, $d);
    }

    public function studentResponsePost($request, $response, $args)
    {
      $res = $request->getParsedBody();
      $hasAnswered = true;
      $isNotInWork = $res['isNotInWork'] ? 1 : 0;
      $isHealthy = $res['isHealthy'] ? 1 : 0;
      $this->adaModules->update(
        'covid_answers_students',
        'hasAnswered=?, isHealthy=?, isNotInWork=?',
        'hash=?',
        [$hasAnswered, $isHealthy, $isNotInWork, $res['hash']]);

      return emit($response, 'success');
    }

    public function staffResponsePost($request, $response, $args)
    {
      $res = $request->getParsedBody();
      $hasAnswered = true;
      $isNotInWork = $res['isNotInWork'] ? 1 : 0;
      $isHealthy = $res['isHealthy'] ? 1 : 0;
      $hasTakenTest = $res['hasTakenTest'] ? 1 : 0;
      if ($hasTakenTest === 0) {
        $hasLoggedTest = null;
        $testWasNegative = null;
      } else {
        $hasLoggedTest = $res['hasLoggedTest'] ? 1 : 0;
        $testWasNegative = $res['testWasNegative'] ? 1 : 0;
      }
      $this->adaModules->update(
        'covid_answers_staff',
        'hasAnswered=?, isHealthy=?, isNotInWork=?, hasTakenTest = ?, hasLoggedTest = ?, testWasNegative = ?',
        'hash=?',
        [$hasAnswered, $isHealthy, $isNotInWork, $hasTakenTest, $hasLoggedTest, $testWasNegative, $res['hash']]);

      return emit($response, 'success');
    }

}
