<?php

class AdminMiddleware extends UserMiddleware
{
    public function __invoke(callable $callback)
    {
        $auth = new Authorization();

        if ($auth->isAdmin()) {
            return parent::__invoke($callback);
        }

        throw new Exception();
    }
}

