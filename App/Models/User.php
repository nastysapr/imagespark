<?php
//TODO:
//hash password

/**
 * Модель пользователя
 */
class User extends Model
{
    public string $table = 'users';

    /**
     * Сверяет введенные учетные данные с хранящимися в базе,
     * возвращает объект в случае успеха
     */
    public function findAndAuth(array $loginData): ?User
    {
        $users = (new User)->findAll();

        foreach ($users as $user) {
            var_dump($user);
            if ($user->login === $loginData['login'] && password_verify($loginData['password'], $user->password)) {
                return $user;
            }
        }

        return null;
    }
}


