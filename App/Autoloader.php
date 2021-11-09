<?php

class Autoloader
{
    protected static array $paths = [
       __DIR__ . '/../App/',
        __DIR__ . '/../App/Controllers/',
        __DIR__ . '/../App/Models/',
        __DIR__ . '/../App/Service/',
        __DIR__ . '/../App/Middleware/',
        __DIR__ . '/../App/database/migrations/',
    ];

    public static function register()
    {
        spl_autoload_register(function ($class) {
            $fileName = $class . '.php';
            foreach (self::$paths as $path) {
                if (file_exists($path . $fileName)) {
                    require_once($path . $fileName);
                }
            }
        });
    }
}
