<?php

/**
 * Description

 * Usage:

 */
 // https://stackoverflow.com/questions/1375501/how-do-i-throttle-my-sites-api-users
namespace Admin\Jobs;

class Job
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
      $this->ada = $container->ada;
    }

    //retrives roles along with names of those with that role
    public function scriptsGet($request, $response, $args)
    {
      $paths = [];
      $paths = array_merge($paths, glob(dirname(__FILE__). '/../../../tasks/scripts/*.php'));
      $paths = array_merge($paths, glob(dirname(__FILE__). '/../../../tasks/scripts/*/*.php'));
      $paths = array_merge($paths,  glob(dirname(__FILE__). '/../../../tasks/scripts/*/*/*.php'));

      $scripts = [];
      foreach($paths as $p) {
        $split = explode("/", $p);
        $file = $split[count($split) -1];
        $scripts[] = [
          'file'  => $file,
          'path' => str_replace($file, '', $p)
        ];
      }
      // $scripts = sortObjects((object)$scripts, 'file');
      return emit($response, $scripts);
    }

    public function statusGet($request, $response, $args)
    {
      $unix = $args['unix'];
      $status = $this->ada->select('auto_status', 'unix, last_updated', 'id=?', [1])[0] ?? [];
      $unixNew = $status['unix'] ?? 0;
      $status['serverOn'] = $unixNew > $unix;
      return emit($response, $status);
    }

    public function jobPost($request, $response, $args)
    {
      $job = $request->getParsedBody();
      $job['id'] = $this->ada->insertObject('auto_jobs', $job);
      return emit($response, $job);
    }

    public function jobPut($request, $response, $args)
    {
      $job = $request->getParsedBody();
      $job['id'] = $this->ada->UpdateObject('auto_jobs', $job);
      return emit($response, $job);
    }

    public function jobDelete($request, $response, $args)
    {
      $id = $args['id'];
      $this->ada->delete('auto_jobs', 'id=?', [$id]);
      return emit($response, $id);
    }

    public function jobsGet($request, $response, $args)
    {
      $jobs = $this->ada->select('auto_jobs', '*', 'id>?', [0]);
      return emit($response, $jobs);
    }

}
