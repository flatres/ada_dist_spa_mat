<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Access;

class Structure
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
      $this->sql = $container->mysql;
    }

    //retrives roles along with names of those with that role
    public function module_DELETE($request, $response, $args)
    {
      $sql = $this->sql;
      $id = $args['id'];
      $sql->delete('acs_reg_modules', 'id=?', array($id));
      $data = array();
      return emit($response, $data);
    }

    //retrives roles along with names of those with that role
    public function moduleIcon_POST($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $id = $data['id'];
      $icon = $data['icon'];
      $sql->update('acs_reg_modules', 'icon=?', 'id=?', array($icon, $id));
      return emit($response, $data);
    }

    //retrives roles along with names of those with that role
    public function moduleName_POST($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $id = $data['id'];
      $name = strtolower($data['name']);
      $sql->update('acs_reg_modules', 'name=?, api_route=?', 'id=?', array($name, $name, $id));
      return emit($response, $data);
    }

    public function moduleColor_POST($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $id = $data['id'];
      $color = $data['color'];
      $sql->update('acs_reg_modules', 'color=?', 'id=?', array($color, $id));
      return emit($response, $data);
    }

    //retrives roles along with names of those with that role
    public function module_POST($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $name = $data['name'];
      $sql->insert('acs_reg_modules', 'name, api_route', array($name, $name));
      return emit($response, $data);
    }

    //retrives roles along with names of those with that role
    public function page_POST($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $moduleId = $data['moduleId'];
      $moduleName = $sql->select('acs_reg_modules', 'name', 'id=?', array($moduleId))[0]['name'];
      $name = strtolower($data['name']);
      $sql->insert('acs_reg_pages', 'module_id, name, api_route', array($moduleId, $name, $moduleName . '/' . $name));
      return emit($response, $data);
    }

    //retrives roles along with names of those with that role
    public function page_PUT($request, $response, $args)
    {
      $sql = $this->sql;
      $data = $request->getParsedBody();
      $id = $data['id'];
      $moduleId = $sql->select('acs_reg_pages', 'module_id', 'id=?', array($id))[0]['module_id'];
      $moduleName = $sql->select('acs_reg_modules', 'name', 'id=?', array($moduleId))[0]['name'];
      $name = strtolower($data['name']);
      $sql->update('acs_reg_pages', 'name=?, api_route=?', 'id=?', array($name, $moduleName . '/' . $name, $id));
      return emit($response, $data);
    }

    public function page_DELETE($request, $response, $args)
    {
      $sql = $this->sql;
      $id = $args['id'];
      $sql->delete('acs_reg_pages', 'id=?', array($id));
      return emit($response, $data);
    }
}
