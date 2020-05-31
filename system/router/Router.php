<?php

namespace System\router;

use Delight\Auth\Auth;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use src\controller\PostController;
use src\entity\Login;
use function FastRoute\simpleDispatcher;

class Router
{

    public static \DI\Container $container;

    public Auth $auth;

    public function __construct()
    {
        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions(require __DIR__ . "/../config.php");

        try {
            self::$container = $builder->build();
            self::$container->set('db',function () {
                return new \PDO("mysql:host=localhost;dbname=java;charset=utf8", "root", "6");
            });
        } catch (\Exception $e) {
            $e->getCode();
        }

        $this->auth = new Auth(self::$container->get("db"));
    }

   public function run()
   {
       $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            $this->route($r);
       });

       $httpMethod = $_SERVER['REQUEST_METHOD'];
       $uri = $_SERVER['REQUEST_URI'];
       if (false !== $pos = strpos($uri, '?')) {
           $uri = substr($uri, 0, $pos);
       }
       $uri = rawurldecode($uri);
       $login = new Login();
       $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
       if (isset($routeInfo[1])) {
           $parameter = isset($routeInfo[2]["id"]) ? $routeInfo[2]["id"]: "";
           $login->writeHack($routeInfo[1][1], $uri, $httpMethod, $parameter, $_SERVER["REMOTE_ADDR"]);
       }
       switch ($routeInfo[0]) {
           case Dispatcher::NOT_FOUND:
               $handler[0] = "src\controller\ErrorController";
               $handler[1] = "notFound";
               self::$container->call($handler);
               break;
           case Dispatcher::METHOD_NOT_ALLOWED:
               $handler[0] = "src\controller\ErrorController";
               $handler[1] = "serverError";
               self::$container->call($handler);
               break;
           case Dispatcher::FOUND:
               $handler = $routeInfo[1];
               $vars = $routeInfo[2];

             if (!$this->auth->check()) {
                 if ($handler[0] === "src\controller\AdminController" || $handler[0] === "src\controller\AuthController") {
                     if ($handler[1] !== "registration") {
                         $handler[0] = "src\controller\AuthController";
                         $handler[1] = "login";
                     }
                 }
             }

               self::$container->call($handler, $vars);
               break;
       }
   }
   private function route(RouteCollector $collector) {
       include_once __DIR__ . "/../../src/route.php";
   }
}