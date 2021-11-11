<?php

use App\Service\Router;

require_once '../App/Autoloader.php';
require_once 'helper.php';

Autoloader::register();

session_start();

new Router();

//(new \App\Service\Migration())->migrate();

