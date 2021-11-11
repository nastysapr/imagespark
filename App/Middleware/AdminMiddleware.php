<?php
namespace App\Middleware;

use App\Service\Authorization;
use Exception;
use App\Middleware\UserMiddleware;

class AdminMiddleware extends UserMiddleware
{
    public function __invoke(callable $callback)
    {
        $auth = new Authorization();
//два объекта авторизации
        if ($auth->isAdmin()) {
            return parent::__invoke($callback);
        }

        throw new Exception();
    }
}

