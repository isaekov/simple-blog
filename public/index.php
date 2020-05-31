<?php
//function dd($var) {
//    echo '<pre style="margin-left: 100px; margin-top: 60px; background:#000; color: #00fe00; font-weight:bold; font-size: 14px">' . print_r($var, true) . '</pre>';
//}

function dd($var) {
    echo '<pre>' . print_r($var, true) . '</pre>';
}

use src\controller\Resolver;
use System\router\Router;


include_once "../vendor/autoload.php";


//$pdo = new PDO("mysql:host=localhost;dbname=java", "root", "6", [
//    PDO::ATTR_PERSISTENT => true
//]);

//for ($i = 0; $i < 1000000; $i++) {
//    $pdo->exec("INSERT INTO `tet` (`addr`) VALUES ($i)");
//}



ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$router = new Router();
$router->run();



