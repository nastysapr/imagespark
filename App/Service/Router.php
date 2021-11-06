<?php

class Router
{
    public array $routeProperties;
    public string $path;
    public array $routes;
    public string $requestMethod;

    public function __construct()
    {
        $parseUrl = parse_url($_SERVER['REQUEST_URI']);
        $this->path = $parseUrl['path'];
        $this->routes = Config::get()->value('routes');
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        try {
            $this->resolve();
        } catch (Exception $e) {
            (new Errors())->notFound();
        }
    }

    public function resolve()
    {
        foreach ($this->routes as $pattern => $route) {
            /** @var $route Route */
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

            if (preg_match($pattern, $this->path, $matches)) {
//                dd($matches);
                if (isset($route->methods) && !in_array($this->requestMethod, $route->methods)) {
                    continue;
                }

                if (isset($route->middleware)) {
                    $middleware = new $route->middleware();
                    $middleware($this->getAction($route, $matches));

                    return;
                }

                if (in_array($this->requestMethod, $route->methods)) {
                    $method = $route->action;
                    (new $route->controller($matches))->$method();
                } else {
                    (new Errors())->methodNotAllowed();
                }

                return;
            }
        }
    }

    public function getAction(Route $route, array $params): callable
    {
        $controller = $route->controller;
        $action = $route->action;
        return function () use ($controller, $action, $params) {
            (new $controller($params))->$action();
        };
    }
}