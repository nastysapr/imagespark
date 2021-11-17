<?php
namespace App\Models;

use App\Service\Authorization;

class Model
{
    public static $driver;
    public array $attributes = [];
    public string $table;

    public function __construct()
    {
        if (!self::$driver) {
            $config = require __DIR__ . '/../../config/db_config.php';
            self::$driver = new $config['driver']();
        }
    }

    /**
     * Магический метод получения значения свойства объекта
     */
    public function __get($property)
    {
        return $this->attributes[$property] ?? null;
    }

    /**
     * Магический метод назначения свойства объекта
     */
    public function __set($name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Возвращает url-адрес объекта
     */
    public function getUrl(): string
    {
        return '/' . $this->table . '/' . $this->id;
    }

    /**
     * Cохраняет объект в базу, возвращает id
     */
    public function save(): int
    {
        if (get_called_class() === 'App\Models\User' && !$this->role) {
            $this->role = Authorization::ROLE_USER;
        }

        return self::$driver->save($this);
    }

    /**
     * Удаляет объект из базы
     */
    public function delete(): void
    {
        self::$driver->deleteRecord($this);
    }

    /**
     * Возвращает массив объектов заданной размерности ($limit) из базы,
     * начиная с определенного смещения $offset. Если параметры не заданы, возвращает все записи
     */
    public function findAll(int $offset = 0, int $limit = 0, string $filter = null, array $column = null): array
    {
        return self::$driver->findAll($this->table, get_called_class(), $filter, $column, $offset, $limit);
    }

    /**
     * Возвращает сущность из базы
     */
    public function findByPK(int $id): ?object
    {
        return self::$driver->findRecordByPK($this->table, get_called_class(), $id);
    }

    /**
     * Возвращает количество объектов
     */
    public function count(): int
    {
        $filter = '';
        if (get_called_class() === 'App\Models\News') {
            $filter = 'date';
        }

        return self::$driver->count($this->table, $filter);
    }

    /**
     * Для новостей и статей возвращает свойство text
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Для новостей и статей возвращает короткий текст для анонса
     */
    public function getShortText(int $length = 100): ?string
    {
        if (strlen($this->getText()) > $length) {
            $substring = substr($this->getText(), 0, $length + 1);

            if (preg_match('/[a-zа-яёЁA-ZА-Я0-9]$/u', $substring)) {
                $length = strripos($substring, ' ');
            }

            return substr($substring, 0, $length) . '...';
        }

        return $this->getText();
    }

    /**
     * Для статей и новостей администратор может изменять автора,
     * а для пользователя это поле заполняется автоматически
     */
    public function getAuthor(): string
    {
        $auth = new Authorization();

        if ($auth->isAdmin() && $this->author) {
            return '"' . htmlspecialchars($this->author) . '"';
        }

        if ($auth->check()) {
            return '"' . $auth->greetings() . '" readonly';
        }
        return '';
    }

    /**
     * Для статей и новостей администратор может изменять поле Дата,
     * а для пользователя в это поле устанавливается значение текущей даты
     */
    public function setDate(): string
    {
        $auth = new Authorization();

        if ($auth->isAdmin() && $this->date) {
            return '"' . htmlspecialchars($this->date) . '"';
        }

        if ($auth->check()) {
            return '"' . date("Y-m-d") . '" readonly';
        }

        return '';
    }
}
