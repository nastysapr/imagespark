<?php

/**
 * Класс валидации данных, заполняемых пользователем посредством форм
 */
class Validate
{
    public function user(array $data): array
    {
        $errors = [];
        if (!preg_match("/^[A-Za-z0-9]+$/", $data['login'])) {
            $errors['login'] = true;
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

    public function article(array $data): array
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
        return $this->article($data);
    }
}