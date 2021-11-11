<?php
namespace App\Service;

class Config
{
    private static $instance;
    private $data;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function get(): self
    {
        if (!self::$instance) {
            self::$instance = new static();
            self::$instance->data = require '../config/config.php';
        }
        return self::$instance;
    }

    public function value(string $accessor)
    {
        $keys = explode('.', $accessor);
        $data = self::$instance->data;

        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data = $data[$key];
            }
        }

        if ($data === self::$instance->data){
            return null;
        }

        return $data;
    }
}