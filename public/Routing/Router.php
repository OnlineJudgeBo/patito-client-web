<?php
class Router
{
    private $routeGroups = [];
    private $currentGroupPrefix = '';

    public function group($prefix, $callback)
    {
        $this->currentGroupPrefix = $prefix;
        call_user_func($callback, $this);
        $this->currentGroupPrefix = '';
    }

    public function addRoute($method, $uri, $callback)
    {
        $uri = $this->currentGroupPrefix . $uri;
        $this->routeGroups[$method][$uri] = $callback;
    }

    public function get($uri, $callback)
    {
        $this->addRoute('GET', $uri, $callback);
    }

    public function post($uri, $callback)
    {
        $this->addRoute('POST', $uri, $callback);
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        if (isset($this->routeGroups[$method][$uri])) {
            call_user_func($this->routeGroups[$method][$uri]);
            return;
        }

        header('Location: https://jv.umsa.bo/404.php');
    }
}
