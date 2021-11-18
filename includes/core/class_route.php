<?php

class Route {
    public $url;
    public $controller;
    public $action;

    function __construct(string $url, string $controller, string $action){
        $this->url = $url;
        $this->controller = $controller;
        $this->action = $action;
    }
}


class Router {
    private $routeList = [];

    /**
     * Here we will register our routes
     * @param string $url
     * @param Route $route
     */
    public function registerRoute(string $url, Route $route){
        $this->routeList[] = $route;
    }


    /**
     * This function will route user to the right controller and method
     * @return mixed
     */
    public function run(){

        $currentRoutePath = explode('?', $_SERVER["REQUEST_URI"])[0];
        foreach ($this->routeList as $route) {

            if ("/".$route->url == $currentRoutePath) {
                $controllerNameClass = $route->controller;
                $actionName = $route->action;
                $controller = new $controllerNameClass();
                return $controller->$actionName();
            }
        }

        die("404 - my router error");
    }
}
