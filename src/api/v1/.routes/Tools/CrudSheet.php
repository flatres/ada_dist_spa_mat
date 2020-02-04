<?php

/**
 * Description

 * Usage:

 */
namespace Tools;

class CrudSheet
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
    }

    public function crudSheetPost($request, $response)
    {
      $package = $request->getParsedBody();
      //convert any options to their actual values
      $columns = $package['columns'];
      $data = $package['data'];
      foreach($columns as $column) {
        if (!isset($column['type'])) continue;
        if ($column['type'] == 'options') {
          $field = $column['field'];
          $options = $column['options'];
          $index = [];
          foreach($options as $option) {
            $index['i_' . $option['value']] = $option['label'];
          }
          foreach($data as &$d) {

            $d[$field] = $d[$field] ? $index['i_'. $d[$field]] : null;
          }
          unset($d);
        }
      }

      $sheet = new \Utilities\Spreadsheet\SingleSheet($columns, $data);

      return emit($response, $sheet->package);
    }



}
