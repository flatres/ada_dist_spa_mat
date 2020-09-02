<?php

/**
 * Description

 * Usage:

 */
namespace Covid;

class Covid
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
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
      $staff = (new \SMT\Tools\Covid\Students())->sendTodayEmails();
      return emit($response, $staff);
    }

    public function hodsStaffPost($request, $response, $args)
    {
      $id = $args['id'];
      global $userId;
      $check = $this->adaModules->select('covid_hod_subscriptions', 'id', 'hod_user_id=? AND user_id=?', [$userId, $id])[0] ?? null;
      if (!$check) {
        $this->adaModules->insert('covid_hod_subscriptions', 'hod_user_id, user_id', [$userId, $id]);
      }
      return emit($response, true);
    }
    public function hodsStaffDelete($request, $response, $args)
    {
      global $userId;
      $id = $args['id'];
      $this->adaModules->delete('covid_hod_subscriptions', 'hod_user_id=? AND user_id=?', [$userId, $id]);
      return emit($response, true);
    }

    public function hodsStaffGet($request, $response, $args)
    {
      global $userId;
      $staff = (new \SMT\Tools\Covid\Staff())->getByUser($userId);
      return emit($response, $staff);
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
