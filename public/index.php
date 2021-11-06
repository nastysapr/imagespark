<?php
function dd($var)
{
    var_dump($var);
    exit;
}

require_once '../App/Autoloader.php';

Autoloader::register();

session_start();

new Router();


