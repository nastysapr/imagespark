<?php
namespace App\Service;

use App\Controllers\MainController;

class Route
{
    public string $controller = MainController::class;
    public string $action = 'index';
    public array $methods = ['GET'];
    public string $middleware;

    public function __construct($controller = null, $action = null, $methods = null, $middleware = null)
    {
        if ($controller) {
            $this->controller = $controller;
        }

        if ($action) {
            $this->action = $action;
        }

        if ($methods) {
            $this->methods = $methods;
        }

        if ($middleware) {
            $this->middleware = $middleware;
        }
    }
}