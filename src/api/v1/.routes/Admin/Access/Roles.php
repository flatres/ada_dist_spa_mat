<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Access;

class Roles
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
      $this->sql = $container->mysql;
    }

    //retrives roles along with names of those with that role
    public function roles_GET($request, $response, $args)
    {
      $sql = $this->sql;
      $data = array();
      $roles = $sql->select('acs_roles', 'id, name', '1=1 ORDER BY name ASC', array());
      foreach($roles as &$role){
        $count = $sql->query("Select COUNT(*) as count FROM acs_roles_users WHERE role_id = ?", array($role['id']))[0]['count'];
        $role['count'] = $count;
      }
      return emit($response, $roles);
    }

    public function rolePages_GET($request, $response, $args)
    {
      $sql = $this->sql;
      $data = array();
      $pages = $sql->select('acs_roles_pages', 'page_id', 'role_id=?', array($args['id']));
      foreach($pages as $page){
        $data[] = $page['page_id'];
      }
      return emit($response, $data);
    }

    public function roleUsers_GET($request, $response, $args)
    {
      $sql = $this->sql;
      $data = array();
      $users = $sql->select('acs_roles_users', 'user_id as id', 'role_id=?', array($args['id']));
      foreach($users as &$user){
        $user = array_merge($sql->select('usr_details', 'firstName, lastName', 'id=?', array($user['id']))[0], $user);
      }
      return emit($response, $users);
    }

    public function allUsers_GET($request, $response, $args)
    {
      $sql = $this->sql;
      $users = $sql->select('usr_details', 'id, firstName, lastName', 'id>?', array(0));
      return emit($response, $users);
    }

    // $page is an array of page ids
    public function rolePages_PUT($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $id = $data['id']; //roleId
      $pages = $data['pages'];

      $sql->delete('acs_roles_pages', 'role_id=?', array($id));
      foreach($pages as $page){
        $moduleId = $sql->select('acs_reg_pages', 'module_id', 'id=?', array($page))[0]['module_id'];
        $sql->insert('acs_roles_pages', 'page_id, role_id, module_id', array($page, $id, $moduleId));
      }
      $this->sendRoleUpdate($id);

      return emit($response, $data);
    }

    public function role_POST($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $name = strtolower($data['name']);

      $data['id'] = $sql->insert('acs_roles', 'name', array($name));

      return emit($response, $data);
    }

    public function role_PUT($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $name = strtolower($data['name']);

      $data['id'] = $sql->update('acs_roles', 'name = ?', 'id = ?', array($name, $data['id']));

      return emit($response, $data);
    }

    public function roleUser_POST($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $roleId = $data['roleId'];
      $userId = $data['userId'];

      $id = $sql->insert('acs_roles_users', 'user_id, role_id', array($userId, $roleId));

      return emit($response, array('id'=>$id));
    }

    public function roleUser_DELETE($request, $response, $args)
    {
      $sql = $this->sql;
      $roleId = $args['roleId'];
      $userId = $args['userId'];

      $id = $sql->delete('acs_roles_users', 'user_id=? AND role_id=?', array($userId, $roleId));

      return emit($response, array('id'=>$id));
    }

    public function role_DELETE($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $id = $args['id'];

      $sql->delete('acs_roles', 'id=?', array($id));

      return emit($response, $data);
    }

    public function moduleTree_GET($request, $response, $args)
    {
      $sql = $this->sql;
      $data = array();
      $modules = $sql->select('acs_reg_modules', 'id, name, icon, color', '1=1 ORDER BY name ASC', array());
      foreach($modules as $module){
        $d = array( 'id'      => 'm_' .$module['id'],
                    'moduleId'      => $module['id'],
                    'name'    => $module['name'],
                    'color'   => $module['color'],
                    'icon'    => $module['icon'],
                    'module'  => true,
                    'children'=> array());
        $pages = $sql->select('acs_reg_pages', 'id, name', 'module_id = ? ORDER BY name ASC', array($module['id']));
        foreach($pages as $page){
          $pageData = array( 'id'  => $page['id'],
                                    'name'=> $page['name'],
                                    'roles' => array());
          //get the roles using this
          $roles = $sql->select('acs_roles_pages', 'role_id', 'page_id=?', array($page['id']));
          foreach($roles as $role){
            $name = $sql->select('acs_roles', 'name', 'id=?', array($role['role_id']));
            $name = isset($name[0]) ? $name[0]['name'] : '';
            $pageData['roles'][] = array('id' => $role['role_id'], 'name' => $name);
          }

          $d['children'][] = $pageData;

        }
        $data[] = $d;
      }

      return emit($response, $data);
    }

    private function sendRoleUpdate($roleId)
    {
      $sql = $this->sql;
      $permissions = new \Auth\Tools\PermissionsRetriever($sql);
      $data =  $permissions->getRolePermissions($roleId);
      $socket = new \Sockets\Role($roleId);
      $socket->publish($data);
    }
}
