<?php

require __DIR__ . './../vendor/autoload.php';

session_start();
// var_dump('user session', $_SESSION);

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'App\Controllers\TaskController@index');
    $r->addRoute('GET', '/task/edit/{id:\d+}', 'App\Controllers\TaskController@edit');
    $r->addRoute('POST', '/task/store', 'App\Controllers\TaskController@store');
    $r->addRoute('POST', '/task/update/{id:\d+}', 'App\Controllers\TaskController@update');

    $r->addRoute('GET', '/login', 'App\Controllers\AuthController@login');
    $r->addRoute('GET', '/logout', 'App\Controllers\AuthController@logout');
    $r->addRoute('POST', '/authenticate', 'App\Controllers\AuthController@authenticate');
});


// var_dump($_POST['author_name']);
// var_dump($_GET);

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
// $uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
// if (false !== $pos = strpos($uri, '?')) {
//     $uri = substr($uri, 0, $pos);
// }
// $uri = rawurldecode($uri);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        // var_dump($vars);

        list($class, $method) = explode("@", $handler, 2);
        // ... call $handler with $vars
        call_user_func_array(array(new $class, $method), $vars);
        break;
}
