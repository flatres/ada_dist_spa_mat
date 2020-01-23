<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Tags;

class Tags
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
       $this->ada = $container->ada;
       $this->adaModules = $container->adaModules;
       // $this->msSql = $container->msSql;
    }

// // ROUTE -----------------------------------------------------------------------------
    public function globalTags_Get($request, $response, $args)
    {
      return emit($response, $this->ada->select('tag_tags', '*'));
    }

    public function studentsTree_Get($request, $response, $args){
      $userId = $args['id'];
      $allCats = new \Entities\Tags\AllCats($this->ada);

      return emit($response, $allCats->list($userId));
    }
    
    public function category_POST($request, $response)
    {
      $data = $request->getParsedBody();
      $cat = new \Entities\Tags\Category($this->ada);
      $name = $data['name'];
      if (strlen($name) == 0) return false;
      return emit($response, $cat->create($data['name']));
    }
    
    public function tag_POST($request, $response)
    {
      $data = $request->getParsedBody();
      $tag = new \Entities\Tags\Tag($this->ada);
      $name = $data['name'];
      $catName = $data['catName'];
      if (strlen($name) == 0) return false;
      return emit($response, $tag->create($catName, $name));
    }
    
    
//
//     public function ROUTEPost($request, $response)
//     {
//       $data = $request->getParsedBody();
//       $data['id'] = $this->adaModules->insertObject('TABLE', $data);
//       return emit($response, $data);
//     }
//
//     public function ROUTELocationsPut($request, $response)
//     {
//       $data = $request->getParsedBody();
//       return emit($response, $this->adaModules->updateObject('TABLE', $data, 'id'));
//     }
//
//     public function ROUTEDelete($request, $response, $args)
//     {
//       return emit($response, $this->adaModules->delete('TABLE', 'id=?', array($args['id'])));
//     }

}
