<?php
namespace App\Service;

use App\Models\User;

/**
 * Класс валидации данных, заполняемых пользователем посредством форм
 */
class Validate
{
    public function users(array $data): array
    {
        $errors = [];
        $user = current((new User)->findAll(0,0, $data['login']));

        if (!empty($user) && $user->id !== $data['id']) {
            $errors['login'] = true;
        }

        if (!preg_match("/^[A-Za-z0-9]+$/", $data['login'])) {
            $errors['chars'] = true;
        }

        if (!preg_match("/^[A-Za-z0-9_\-.]+@[A-Za-z0-9_\-.]+.[A-Za-z]+$/", $data['email'])) {
            $errors['email'] = true;
        }

        if (strlen($data['password']) < 4 || $data['password'] !== $data['password_confirm']) {
            $errors['password'] = true;
        }

        if (!preg_match("/^[а-яА-Я\s.\-]+$/u", $data['full_name'])) {
            $errors['full_name'] = true;
        }

        if (date("Y-m-d") < $data['birthday']) {
            $errors['birthday'] = true;
        }

        return $errors;
    }

    public function articles(array $data): array
    {
        $errors = [];
        if (empty($data['title'])) {
            $errors['title'] = true;
        }

        if (empty($data['author'])) {
            $errors['author'] = true;
        }

        if (date("Y-m-d") < $data['date']) {
            $errors['date'] = true;
        }

        if (empty($data['text'])) {
            $errors['text'] = true;
        }

        return $errors;
    }

    public function news(array $data): array
    {
        return $this->articles($data);
    }
}