<?php

use Crunz\Schedule;
$path = __DIR__;
echo $path;
$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . ' basic.php');
$task
    ->in($path)
    ->everyMinute()
    ->description('Copying the project directory')
    ->appendOutputTo($path . '/logs/basic.log');;

return $schedule;
