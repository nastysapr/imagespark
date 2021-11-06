<?php
//TODO:
//hash password

/**
 * Модель пользователя
 */
class User extends Model
{
    public string $folder = 'users';
    public string $table = 'users';
    public string $login;
    public string $password;
    public string $email;
    public string $birthday;
    public string $full_name;
    public string $description = '';
    public string $role = Authorization::ROLE_USER;

    /**
     * Возвращает массив объектов пользователей
     */
    public function all(): array
    {
        if ($this->dbh) {
            $sql = 'SELECT * FROM ' . $this->table;
            $sth = $this->dbh->prepare($sql);
            $sth->execute();

            return $sth->fetchAll(PDO::FETCH_CLASS, get_called_class());
        }

        $directory = $this->getDirectoryPath();
        $iterator = new FilesystemIterator($directory, FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS);
        $users = [];

        foreach ($iterator as $file) {
            $id = (int)$file->getFilename();
            $user = new User($id);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Сверяет введенные учетные данные с хранящимися в базе,
     * возвращает объект в случае успеха
     */
    public function findAndAuth(array $loginData): ?User
    {
        $users = (new User)->all();
        foreach ($users as $user) {
            if ($user->login === $loginData['login'] && password_verify($loginData['password'], $user->password)) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Возвращает роль пользователя
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Возвращает атрибуты пользователя из свойств объекта
     */
    public function getAttributesFromObject(): array
    {
        $attributes = [];
        $attributes['login'] = $this->login;
        $attributes['password'] = password_hash($this->password, PASSWORD_BCRYPT);
        $attributes['email'] = $this->email;
        $attributes['birthday'] = $this->birthday;
        $attributes['full_name'] = $this->full_name;
        $attributes['description'] = $this->description;
        $attributes['role'] = $this->role;

        return $attributes;
    }
}


