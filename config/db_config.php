<?php
return [
    /** ['mysql', files] */
    'driver' => 'mysql',
    'mysql' => [
        'dsn' => 'mysql:dbname=ImageSpark;host=127.0.0.1;port=3306',
        'user' => 'dev',
        'password' => '123123'
    ],
    'paths' => [
        'root' => __DIR__ . '/../',
        'storage' => 'storage/',
        'views' => 'App/Views/',
    ],
];