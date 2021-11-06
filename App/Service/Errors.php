<?php

class Errors
{
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function notFound()
    {
        $this->view->notFound();
        exit();
    }

    public function forbidden()
    {
        $this->view->forbidden();
        exit();
    }

    public function methodNotAllowed()
    {
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
        exit();
    }
}