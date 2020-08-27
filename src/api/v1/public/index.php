<?php
$userId = null;

$showErrors = true;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

const FILESTORE_PATH = __DIR__ . '/filestore/';
const FILESTORE_URL = 'filestore/';



if($showErrors){
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  set_error_handler(function($severity, $message, $file, $line) {
      if (error_reporting() & $severity) {
          throw new ErrorException($message, 0, $severity, $file, $line);
      }
  });
} else {
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
}
require __DIR__ . '/../../vendor/autoload.php';

session_start();

// load environmental variables
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__FILE__). '/../');
$dotenv->load();

// echo dirname(__FILE__);
// var_dump($_ENV);

// Instantiate the app
$settings = require __DIR__ . '/../settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../dependencies.php';

// Register middleware
require __DIR__ . '/../middleware.php';


//load helpers
foreach (glob(dirname(__FILE__). '/../helpers/*.php') as $filename)
{
    include $filename;
}

// Register routes
foreach (glob(dirname(__FILE__). '/../.routes/*/_routes.php') as $filename)
{
    include $filename;
}
foreach (glob(dirname(__FILE__). '/../.routes/*/*/_routes.php') as $filename)
{
    include $filename;
}



// Run app
$app->run();
