<?php

/**
 * Отвечает за работу с сессией авторизованного пользователя
 */
class Authorization
{
    public const ROLE_ADMIN = 'admin'; //enum с 8.1
    public const ROLE_USER = 'user';

    /**
     * Проверяет, авторизован ли пользователь
     */
    public function check(): bool
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }

        return false;
    }

    /**
     * Возвращает параметры сессии пользователя
     */
    public function getSessionParams(): array
    {
        if (isset($_SESSION)) {
            return $_SESSION;
        }

        return [];
    }

    /**
     * Сохраняет идентификатор авторизованного пользователя
     */
    public function login(int $userID): void
    {
        $_SESSION['user_id'] = $userID;
    }

    /**
     * Удаляет идентификатор авторизованного пользователя
     */
    public function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
    }

    /**
     * Возвращает ФИО авторизованного пользователя
     */
    public function greetings(): string
    {
        $sessionParams = $this->getSessionParams();
        $user = (new User())->findByPK($sessionParams['user_id']);
        return $user->full_name;
    }

    /**
     * Возвращает идентификатор авторизованного пользователя
     */
    public function getUserId(): int
    {
        $sessionParams = $this->getSessionParams();
        return $sessionParams['user_id'];
    }

    /**
     * Проверяет права администратора у авторизованного пользователя
     */
    public function isAdmin(): bool
    {
        if ($this->check()) {
            $id = (new Authorization())->getUserId();
            $user = (new User)->findByPK($id);
            if ($user->role === $this::ROLE_ADMIN) {
                return true;
            }
        }

        return false;
    }
}