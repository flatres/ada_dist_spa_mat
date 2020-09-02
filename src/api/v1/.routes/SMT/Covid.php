<?php

/**
 * Description

 * Usage:

 */
namespace SMT;

class Covid
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
    }

// ROUTE -----------------------------------------------------------------------------
    public function staffGet($request, $response, $args)
    {
      $staff = (new \SMT\Tools\Covid\Staff())->getAll();
      return emit($response, $staff);
    }

    public function studentsGet($request, $response, $args)
    {
      $staff = (new \SMT\Tools\Covid\Students())->getAll();
      return emit($response, $staff);
    }

    public function houseStudentsGet($request, $response, $args)
    {
      $id = $args['id'];
      $students = (new \SMT\Tools\Covid\Students())->getHouse($id);
      return emit($response, $students);
    }

    public function studentEmailsPost($request, $response, $args)
    {
      $students = (new \SMT\Tools\Covid\Students())->sendTodayEmails();
      return emit($response, $students);
    }

    public function staffEmailsPost($request, $response, $args)
    {
      $staff = (new \SMT\Tools\Covid\Staff())->sendTodayEmails();
      return emit($response, $staff);
    }

    public function housesEmailsPost($request, $response, $args)
    {
      // $staff = (new \SMT\Tools\Covid\Staff())->sendTodayEmails();
      return emit($response, true);
    }

    public function hodsEmailsPost($request, $response, $args)
    {
      $hods = (new \SMT\Tools\Covid\Staff())->sendHODSEmails();
      return emit($response, $hods);
    }

    public function studentsStatusPut($request, $response)
    {
      $data = $request->getParsedBody();
      $isActive = $data['active'];
      $status = (new \SMT\Tools\Covid\Students())->changeStatus($isActive);
      return emit($response, $data);
    }

    public function staffStatusPut($request, $response)
    {
      $data = $request->getParsedBody();
      $isActive = $data['active'];
      $status = (new \SMT\Tools\Covid\Staff())->changeStatus($isActive);
      return emit($response, $data);
    }

    public function statusGet($request, $response)
    {
      $data = $request->getParsedBody();
      $staffStatus = (new \SMT\Tools\Covid\Staff())->getStatus();
      $studentsStatus = (new \SMT\Tools\Covid\Students())->getStatus();
      $data = [
        'staff' => ['active' => $staffStatus],
        'students' => ['active' => $studentsStatus]
      ];
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
