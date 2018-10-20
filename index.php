<?php

session_start();

//autoload
spl_autoload_register(function ($class) {

    $require = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('App\\', '', $class)) . '.php';

    require_once($require);
});

//error handler for require
set_error_handler(function ($nbr, $message) {
    var_dump($message);
    die('The called Controller does not exist');
}, E_COMPILE_ERROR);

//router
if (isset($_GET['controller'])) {
    $controller = 'App\Controller\\' . $_GET['controller'] . 'Controller';
} else {
    $controller = 'App\Controller\\' . 'DefaultController';
}

if (isset($_GET['action'])) {
    $action = $_GET['action'] . 'Action';
} else {
    $action = 'DefaultAction';
}

$control = new $controller();
try {
    $response = $control->$action();
} catch (Error $error) {
    die('The called Action does not exist');
}
if ($response instanceof App\Response\View) {
    /** @var App\Response\View $response */
    echo $response->display();
} else {
    /** @var App\Response\Data $response */
    echo $response->getJson();
}
