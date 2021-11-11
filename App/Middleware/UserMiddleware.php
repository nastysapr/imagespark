<?php
namespace App\Middleware;

use App\Service\Authorization;
use Exception;

class UserMiddleware
{
    public function __invoke(callable $callback)
    {
        $auth = new Authorization();

        if ($auth->check()) {
            return $callback();
        }
        throw new Exception();
    }
}

