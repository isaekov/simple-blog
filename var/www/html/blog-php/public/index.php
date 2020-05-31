<?php
function dd($var) {
    echo '<pre style="margin-left: 100px; margin-top: 60px; background:#000; color: #00fe00; font-weight:bold; font-size: 14px">' . print_r($var, true) . '</pre>';
}

use src\controller\Resolver;
use System\router\Router;


include_once "../vendor/autoload.php";
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$router = new Router();
$router->run();



