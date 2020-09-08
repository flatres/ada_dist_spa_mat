<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Sync;

class Staff
{
    protected $container;
    private $disabledCount = 0;
    private $updatedCount = 0;
    private $deletedCount = 0;
    private $newCount = 0;

    public function __construct(\Slim\Container $container)
    {
       $this->isams= $container->isams;
       $this->sql = $container->mysql;

    }

    public function misStaff_GET($request, $response, $args)
    {
      $data = $this->getStaffComparison();
      return emit($response, $data);
    }

    //ensure that ADA staff are up to date with iSams
    public function staffSync_POST($request, $response, $args)
    {
      $data = array('id'=>2);
      $auth = $request->getAttribute('auth');

      $console = new \Sockets\Console($auth);
      $this->progress = new \Sockets\Progress($auth, 'Admin/Sync/Staff');

      $console->publish('Pulling iSAMS staff...');
      $misStaff = $this->isams->select(  'TbliSAMSManagerUsers',
                                          'intPersonID as id, txtUsername, txtTitle, txtFirstname, txtSurname, txtPreName, txtEmailAddress, intGroupID, txtUserCode',
                                          '(txtStatus = ? OR txtStatus=?) AND (txtUserType = ? OR txtUserType=?) AND intPersonID > 0 ', array(0, 'Active', 'Staff', 'System User'));

      $console->publish('Got ' . count($misStaff), 1);

      // get ada staff to get comparison for syncing
      $console->publish('Pulling ADA staff');
      $adaStaff = $this->sql->select('usr_details', 'id, mis_id', 'ad_login=? ORDER BY lastname ASC', array(1));
      $console->publish('Got ' . count($adaStaff), 1);


      $console->publish('Syncing...');
      $allStaff = array();
      foreach($adaStaff as $staff)
      {
        $misId = $staff['mis_id'];
        $adaId = $staff['id'];
        $allStaff["s_$misId"] = array(
          'adaId'   => $adaId,
          'misId'   => $misId,
          'isNew'   => false,
          'disabled' => true
        );
      }
      //staff not appearing in the MIS will keep their disable flag (i.e not longer active)
      foreach($misStaff as $staff)
      {
        //so pre name isn't actually stored for staff here. You only see it in tblStaff
        // try to get prename here
        $staff['txtPreName'] = $this->isams->select('TblStaff', 'PreName', 'User_Code=?', [$staff['txtUserCode']])[0]['PreName'] ?? $staff['txtFirstname'];
        $misId = $staff['id'];
        if (isset($allStaff["s_$misId"])) //already exists, just needs updating
        {
          $allStaff["s_$misId"]['disabled'] = false;
          $allStaff["s_$misId"]['misData'] = $staff;
        } else {
          $allStaff["s_$misId"] = array(
            'adaId'   => null,
            'misId'   => $misId,
            'isNew'   => true,
            'disabled' => false,
            'misData' => $staff
          );
        }
      }
      $count = count($allStaff);
      $i = 1;
      //update
      foreach($allStaff as &$staff){
        $staff['error'] = $staff['isNew'] == true ? $this->newStaff($staff) : $this->updateStaff($staff);
        $this->progress->publish($i/$count);
        $i++;
      }
      $data = array(
        'count'   =>$count,
        'new'     =>$this->newCount,
        'updated' => $this->updatedCount,
        'disabled'=> $this->disabledCount
      );

      $console->publish("Done - new($this->newCount) - updated($this->updatedCount) - disabled($this->disabledCount)");

      return emit($response, $data);
    }

    private function newStaff($staff)
    {
      $d = $staff['misData'];
      // 'intPersonID as id, txtUsername, txtTitle, txtFirstname, txtSurname, txtPreName, txtEmailAddress, intGroupID, txtUserCode'
      $id = $this->sql->insert(
        'usr_details',
        'login, firstname, lastname, prename, title, email, mis_id, mis_group, mis_code',
        array(
          $d['txtUsername'],
          $d['txtFirstname'],
          $d['txtSurname'],
          $d['txtPreName'],
          $d['txtTitle'],
          $d['txtEmailAddress'],
          $d['id'],
          $d['intGroupID'],
          $d['txtUserCode']
        )
      );
      $this->setDefaultRole($id);
      // $tag = new \Entities\Tags\TagWriter($this->sql);
      // $tag->newByNames('House', $d['txtBoardingHouse'], $id);
      $this->newCount++;
    }

    private function setDefaultRole($adaID)
    {
      $sql = $this->sql;
      // check if there's a default role
      $d=$sql->single('acs_roles', 'id', 'isDefault=?', [true]);
      if ($d){
        $roleID = $d['id'];
        if (!$sql->exists('acs_roles_users', 'user_id=? AND role_id = ?', [$adaID, $roleID])){
          $sql->insert('acs_roles_users', 'user_id, role_id', [$adaID, $roleID]);
        }
      }
    }

    private function updateStaff($staff)
    {
      // echo 'jere';
      if ($staff['disabled'] == true)
      {
        $this->sql->update('usr_details', 'disabled=1', 'id=?', array($staff['adaId']));
        $this->deletedCount++;
      } else {
        $d = $staff['misData'];
        $this->sql->update(
          'usr_details',
          'login=?, firstname=?, lastname=?, prename=?, title=?, email=?, mis_id=?, mis_group=?, mis_code=?, disabled=?',
          'id=?',
          array(
            $d['txtUsername'],
            $d['txtFirstname'],
            $d['txtSurname'],
            $d['txtPreName'],
            $d['txtTitle'],
            $d['txtEmailAddress'],
            $d['id'],
            $d['intGroupID'],
            $d['txtUserCode'],
            0,
            $staff['adaId']
          )
        );
        $this->setDefaultRole($staff['adaId']);
        // $tag = new \Entities\Tags\TagWriter($this->sql);
        // $tag->newByNames('House', $d['txtBoardingHouse'], $staff['adaId']);
        $this->updatedCount++;
      }
    }

    private function getUserGroups()
    {
      $groups = $this->isams->select( 'TbliSAMSManagerUserGroups',
                                      'TbliSAMSManagerUserGroupsID as id, txtName',
                                      '1=1',
                                      array());
      $options = array();
      foreach($groups as $group){
        $options[] = [ 'label' => $group['txtName'], 'value' => $group['id'] ];
      }

      return $options;

    }

    private function getStaffComparison()
    {
      $data = array();
      $misStaff = $this->isams->select(  'TbliSAMSManagerUsers',
                                          'intPersonID as id, txtUsername, txtTitle, txtFirstname, txtSurname, txtPreName, txtEmailAddress, intGroupID, txtStatus, txtUserType',
                                          '(txtStatus = ? OR txtStatus=?) AND (txtUserType = ? OR txtUserType=?) AND intPersonID > 0 ', array(0, 'Active', 'Staff', 'SystemUser'));

      //get ada staff to get comparison for syncing
      $adaStaff = $this->sql->select('usr_details', 'id', 'ad_login=? ORDER BY lastname ASC', array(1));
      $data['misStaff'] = $misStaff;
      $data['groups'] = $this->getUserGroups();
      $stats = array();
      $stats['misStaffCount'] = count($misStaff);
      $stats['adaStaffCount']  = count($adaStaff);
      $data['stats'] = $stats;
      return $data;
    }


}
