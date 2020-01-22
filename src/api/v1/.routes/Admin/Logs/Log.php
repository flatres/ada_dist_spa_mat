<?php

/**
 * Description

 * Usage:

 */
namespace Admin\Logs;

define('OS_ENV', getenv("OS_ENV"));

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

    $now = date('c');

    return ['messages' => $log, 'resources' => $resources, 'time' => $now, 'OS_ENV' => OS_ENV];
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
    switch (OS_ENV) {
      case 'OSX' :
        $cpuIdle = round($this->getStringBetween($top, 'sys, ', '% idle'));
        $memTotal = 16;
        $memFree = 16 - round($this->getStringBetween($top, 'PhysMem: ', 'G'));
        break;
      case 'UBUNTU' :
        $memString = $this->getStringBetween($top, 'KiB Mem', '/cache');
        $cpuIdle = round(str_replace(" ", "", $this->getStringBetween($top, 'ni, ', 'id')));
        $memTotal = round(str_replace(" ", "", $this->getStringBetween($memString, ':', 'total')));
        $memFree = round(str_replace(" ", "", $this->getStringBetween($memString, 'total,', 'free')));
        break;
    }
    return [
      'cpuIdle' => $cpuIdle,
      'memTotal'  => $memTotal,
      'memFree' => $memFree
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
