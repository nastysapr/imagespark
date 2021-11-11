<?php

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $fileName = __DIR__ . '/../' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            if (file_exists($fileName)) {
                require_once($fileName);
            }
        });
    }
}
