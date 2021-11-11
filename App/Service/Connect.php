<?php
namespace App\Service;

class Connect
{
    public string $dsn;
    public string $user;
    public string $password;

    public function __construct()
    {
        $connectParams = require __DIR__ . '/../../config/db_config.php';

        foreach ($connectParams['mysql'] as $param => $value) {
            $this->$param = $value;
        }
    }

}