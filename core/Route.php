<?php

namespace Core;

class Route
{
    private array $routes;


    public function __construct(array $routes)
    {
        $this->setRoutes($routes);
        $this->run();
    }

    /**
     * @param array $routes
     */
    public function setRoutes(array $routes): void
    {
        $newRoutes[] = [];
        foreach ($routes as $route) {
            $routeArray = explode(".", $route[1]);
            $newRoute = [$route[0], $routeArray[0], $routeArray[1]];
            $newRoutes[] = $newRoute;
        }
        $this->routes = $newRoutes;
    }

    private function getRequest() : object
    {
        $obj = new \stdClass();
        foreach ($_GET as $key => $value)
        {
            $obj->get->$key = $value;
        }
        foreach ($_POST as $key => $value)
        {
            $obj->post->$key = $value;
        }
        return  $obj;
    }
    private function run()
    {
        $url = $this->getUrl();
        $urlArray = explode("/", $url);
        $found = false;
        foreach ($this->routes as $route){
            $routeArray = explode("/", @$route[0]);
            $params = array();
            for ($i=0; $i < count($routeArray); $i++){
                if((strpos($routeArray[$i], "{") !== false) && (count($urlArray) == count($routeArray))){
                    $routeArray[$i] = $urlArray[$i];
                    $params[] = $urlArray[$i];
                }
                $route[0] = implode($routeArray, "/");
            }


            if($url == $route[0]) {
                $found = true;
                $controller = $route[1];
                $action = $route[2];
                break;
            }
        }

        if($found){
            $instance = Container::newController($controller);
            $instance->$action($params, $this->getRequest());
        }else{
            if(file_exists(__DIR__ . "/../app/Views/404.phtml"))
                require_once __DIR__ . "/../app/Views/404.phtml";
            else
                echo "sorry, page not found";
        }
    }

    private function getUrl() : string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}