<?php
namespace App\Service;

class Request
{
    public function get($key, $defaultValue = ''): int
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        return $defaultValue;
    }

    public function isGET(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }

        return false;
    }

    public function getFormData(): array
    {
        if (isset($_POST)) {
            return $_POST;
        }

        return [];
    }


}