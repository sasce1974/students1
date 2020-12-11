<?php
$router = new Core\Router();


// Add the routes
$router->add('', ['controller' => 'Boards', 'action' => 'index']);
$router->add('{controller}', ['action' => 'getAll']);
$router->add('{controller}/{id:\d+}');
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('{controller}/{id:\d+}/{action}/{aid:\d+}');


$router->dispatch($_SERVER['QUERY_STRING']);