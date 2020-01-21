<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Logs;

class Log
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
    }

    //retrives roles along with names of those with that role
    public function log_GET($request, $response, $args)
    {
      $handle = fopen(LOG_PATH, 'r');
      // https://stackoverflow.com/questions/2961618/how-to-read-only-5-last-line-of-the-text-file-in-php
        $data=array();
        $fp = fopen(LOG_PATH, "r");
        $count = 1000;
        while(!feof($fp))
        {
           $line = fgets($fp, 4096);
           array_push($data, $line);
           if (count($data)>$count)
               array_shift($data);
        }
        fclose($fp);

        $log = $this->processLog($data);

    return emit($response, $log);
  }

  private function processLog($data)
  {
    $log = [];
    foreach($data as $d){
      $timestamp = $this->getStringBetween($d, '[', ']');
      $level = $this->getStringBetween($d, 'ada.', ':');
      $id = $this->getStringBetween($d, '"uid":"', '"');
      $message = $this->getStringBetween($d, 'ada.', '{"uid":"');
      $l = [
        'id'      => $id,
        'time'    => $timestamp,
        'level'   => $level,
        'message' => $message
      ];
      $log[] = $l;
    }
    $resources = $this->getResources();

    return ['messages' => $log, 'resources' => $resources];
  }

  private function getStringBetween($string, $start, $end)
  {
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }
// https://stackoverflow.com/questions/15538687/using-php-to-stream-data-of-programs-such-as-htop
  private function getResources(){
    $top = shell_exec("top -l 1 -n 1");
    return [
      'cpuIdle' => round($this->getStringBetween($top, 'sys, ', '% idle'))
    ];
    // return system("top -n 1");
  }

  private function getServerMemoryUsage(){

    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    $memory_usage = $mem[2]/$mem[1]*100;

    return $memory_usage;
}

}
