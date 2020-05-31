<?php
/**
 * @var $collector RouteCollector
 */

//use FastRoute\RouteCollector;
use FastRoute\RouteCollector;
use src\controller\AdminController;
use src\controller\AuthController;
use src\controller\PostController;

$collector->get("/", [PostController::class, "posts"]);
$collector->get("/post/{id:[0-9]+}", [PostController::class, "post"]);
$collector->post("/post/like", [PostController::class, "like"]);
$collector->post("/post/dis-like", [PostController::class, "disLike"]);
$collector->get("/category/{name}", [PostController::class, "category"]);




$collector->addGroup("/admin", function (RouteCollector $routeCollector) {
    $routeCollector->post("/group", [AdminController::class, "groupNameAction"]);
    $routeCollector->post("/filter-group", [AdminController::class, "filterGroup"]);
    $routeCollector->post("/save-post", [AdminController::class, "save"]);
    $routeCollector->get("/lists", [AdminController::class, "listPosts"]);
    $routeCollector->addRoute(["GET", "POST"], "/admin", [AdminController::class, "index"]);
    $routeCollector->addRoute(["GET", "POST"], "/creator", [AdminController::class, "creator"]);
    $routeCollector->post("/user-setting", [AdminController::class, "settingAction"]);
    $routeCollector->post("/add-post", [AdminController::class, "addPostAction"]);
    $routeCollector->post("/delete-meta/{id:[0-9]+}/{postId:[0-9]+}", [AdminController::class, "deleteMeta"]);
    $routeCollector->get("/post/{id:[0-9]+}", [AdminController::class, "post"]);
    $routeCollector->get("/edit/{id:[0-9]+}", [AdminController::class, "edit"]);
    $routeCollector->addRoute(["GET", "POST"], "/reg123", [AuthController::class, "registration"]);
    $routeCollector->addRoute(["GET", "POST"],"/login", [AuthController::class, "login"]);
    $routeCollector->addRoute(["GET", "POST"],"/logOut", [AuthController::class, "logout"]);

});



