<?php

require __DIR__ . '/../../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__FILE__). '/../');
$dotenv->load();

// Fetch data from the API
$sql = new \Dependency\Databases\Ada();
$tasks = $sql->select('auto_jobs', '*', 'active=?', [1]);

$sch = new \Crunz\Schedule();

foreach ($tasks as $task) {

    $task = (object) $task;
    $path = $task->run_in . $task->script;

    if (!file_exists($path)) continue;

    (new \Utilities\Tasks\TaskBuilder(
        $task,
        $sch->run(PHP_BINARY . ' ' . $path, [$task->id])
    ))
    ->generate();
}

$sql->update('auto_status', 'unix=?', 'id=1', [time()]);

return $sch;
