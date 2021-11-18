<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/controllers_common/general.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/core/class_route.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/core/class_user.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/core/class_notification.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/core/class_db.php";

// setup fo DB
MyDB::$HOST = "localhost";
MyDB::$DB = "test";
MyDB::$USER = "root";
MyDB::$PASS = "";

// setup for routers
$router = new Router();
$route = new Route('api/user.get', 'UserController', 'userGet');
$router->registerRoute("api/user.get", $route);

$route = new Route('api/user.update', 'UserController', 'userUpdate');
$router->registerRoute("api/user.update", $route);

$route = new Route('api/notifications.get', 'NotificationsController', 'notificationsGet');
$router->registerRoute("api/notifications.get", $route);

$route = new Route('api/notifications.read', 'NotificationsController', 'readAllNotifications');
$router->registerRoute("api/notifications.read", $route);


$response = $router->run();
if ($response) {
    die($response);
}

die("404 - api/index.php");