<?php
/** Front controller */

/**
 * Autoloader
 */
require "../vendor/autoload.php";

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/** Routing */

$router = new Core\Router();


// Add the routes
$router->add('', ['controller' => 'Boards', 'action' => 'index']);
$router->add('{controller}/{id:\d+}');
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('{controller}/{id:\d+}/{action}/{aid:\d+}');

//$router->add('admin/{controller}/', ['namespace' => 'Admin', 'action'=>'index']);


$router->dispatch($_SERVER['QUERY_STRING']);
