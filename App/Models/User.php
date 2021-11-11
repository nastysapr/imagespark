<?php
namespace App\Models;

/**
 * Модель пользователя
 */
class User extends Model
{
    public string $table = 'users';
    public array $breadcrumbs = ['/users' => 'Список пользователей'];
    public string $genitive = 'пользователя';

    /**
     * Сверяет введенные учетные данные с хранящимися в базе,
     * возвращает объект в случае успеха
     */
    public function findAndAuth(array $loginData): ?User
    {
        $users = (new User)->findAll();

        foreach ($users as $user) {
            if ($user->login === $loginData['login'] && password_verify($loginData['password'], $user->password)) {
                return $user;
            }
        }

        return null;
    }
}


