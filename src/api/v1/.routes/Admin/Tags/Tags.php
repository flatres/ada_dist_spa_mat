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
