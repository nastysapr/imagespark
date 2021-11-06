<?php

class Model
{
    private string $path = '';
    protected ?PDO $dbh = null;
    public string $folder;
    public string $table;
    public ?int $id;

    public function __construct(int $id = null)
    {
        $config = require __DIR__ . '/../../config/db_config.php';
        if ($config['driver'] === 'mysql') {
            $this->dbh = DatabaseConnect::getInstance();
        } else {
            $this->path = $config['paths']['root'] . $config['paths']['storage'];
        }

        if (is_numeric($id)) {
            $this->id = $id;

            $attributes = $this->getAttributesFromDB($id);

            if ($attributes) {
                foreach ($attributes as $attribute => $value) {
                    $this->$attribute = $value;
                }
            } else {
                (new Errors())->notFound();
            }
        }
    }

    /**
     * Получить аттрибуты сущности из базы
     */
    public function getAttributesFromDB($id): ?array
    {
        if ($this->dbh) {
            $sql = "SELECT * FROM " . $this->table . " WHERE id = " . $id;
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            $result = $sth->fetch();
            if ($result) {
                return $result;
            }
            return null;
        }

        $directory = $this->getDirectoryPath();
        $filename = $directory . str_pad($id, 7, 0, STR_PAD_LEFT);

        if (!file_exists($filename)) {
            (new Errors())->notFound();
        }

        return unserialize(file_get_contents($filename));
    }

    /**
     * Магический метод получения значения свойства объекта
     */
    public function __get($property): ?array
    {
        return $this->$property ?? null;
    }

    /**
     * Магический метод назначения свойства объекта
     */
    public function __set($name, $value): void
    {
        $this->$name = $value;
    }

    /**
     * Возвращает url-адрес объекта
     */
    public function getUrl(): string
    {
        return '/' . $this->folder . '/' . $this->id;
    }

    /**
     * Cохраняет объект в базу
     */
    public function save(): void
    {
        $primaryKey = "";
        if ($this->dbh) {
            if (!$this->id) {
                $sql = "INSERT INTO ";
            } else {
                $sql = "UPDATE ";
                $primaryKey = " WHERE id = " . $this->id;
            }

            $sql .= $this->table . " SET ";

            foreach ($this->getAttributesFromObject() as $attribute => $value) {
                $sql .= $attribute . " = '" . $value . "', ";
            }

            $sql = rtrim($sql, ", ");
            $sql .= $primaryKey;

            $sth = $this->dbh->prepare($sql);
            $sth->execute();

            if (!$this->id) {
                $this->id = $this->dbh->lastInsertId();
            }

            return;
        }

        $filename = str_pad($this->id, 7, 0, STR_PAD_LEFT);
        $file = $this->getDirectoryPath() . $filename;
        $data = serialize($this->getAttributesFromObject());
        file_put_contents($file, $data, 0);
    }

    /**
     * Вычислить числовой идентификатор для объекта
     */
    public function calcId(): ?int
    {
        if ($this->dbh) {
            return null;
        }

        $directory = $this->getDirectoryPath();
        $iterator = new GlobIterator($directory . '*');
        $countFiles = iterator_count($iterator);

        $iterator->seek($countFiles - 1);

        $id = (int)$iterator->getFilename();

        return ++$id;
    }

    /**
     * Удаляет объект из базы
     */
    public function delete(): bool
    {
        if ($this->dbh) {
            $sql = "DELETE FROM " . $this->table . " WHERE id = " .$this->id;
            $sth = $this->dbh->prepare($sql);
            return $sth->execute();
        }

        $filename = str_pad($this->id, 7, 0, STR_PAD_LEFT);
        return unlink($this->getDirectoryPath() . $filename);
    }

    /**
     * Возвращает путь до директории, в которой хранится файл
     */
    public function getDirectoryPath(): string
    {
        return $this->path . $this->folder . '/';
    }

    /**
     * Возвращает массив объектов заданной размерности ($limit) из базы,
     * начиная с определенного смещения $offset
     */
    public function getGroup(int $offset, int $limit): array
    {
        if ($this->dbh) {
            $sql = "SELECT * FROM " . $this->table;

            if ($this->table === 'news') {
                $sql .= " WHERE CURDATE() - date <= 1";
            }

            $sql .= " LIMIT " . $limit . " OFFSET " . ($offset - 1 ) * $limit;

            $sth = $this->dbh->prepare($sql);
            $sth->execute();

            return $sth->fetchAll(PDO::FETCH_CLASS, get_called_class());
        }

        $directory = $this->getDirectoryPath();
        $iterator = new GlobIterator($directory . '*');

        $entitiesList = [];
        if ($offset > 1) {
            $iterator->seek(($offset - 1) * $limit);
        }

        if ($this->folder === 'news') {
            for ($count = 0; $count < $limit;) {
                if (!$iterator->valid()) {
                    break;
                }

                $id = (int)$iterator->getFilename();
                $news = new News($id);
                $dateDiff = date_diff(new DateTime(), DateTime::createFromFormat('Y-m-d', $news->date));

                if ($dateDiff->days <= 1) {
                    $entitiesList[] = $news;
                    $count++;
                }

                $iterator->next();
            }

            return $entitiesList;
        }

        for ($i = 0; $i < $limit; $i++) {
            if (!$iterator->valid()) {
                break;
            }

            $id = (int)$iterator->getFilename();
            $article = new Articles($id);
            $entitiesList[] = $article;
            $iterator->next();
        }

        return $entitiesList;
    }

    /**
     * Возвращает количество объектов
     */
    public function getCount(): int
    {
        if ($this->dbh){
            $sql = "SELECT COUNT(*) FROM " . $this->table;
            $sth = $this->dbh->prepare($sql);
            $sth->execute();
            return $sth->fetchColumn();
        }

        $storage = $this->getDirectoryPath();
        $iterator = new FilesystemIterator($storage, FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS);

        return iterator_count($iterator);
    }

    /**
     * Устанавливает значения свойств объектов News и Articles (User переопределен в модели)
     */
    public function getAttributesFromObject(): array
    {
        $attributes = [];
        $attributes['title'] = $this->title;
        $attributes['date'] = $this->date;
        $attributes['author'] = $this->author;
        $attributes['text'] = $this->text;

        return $attributes;
    }

    /**
     * Для новостей и статей возвращает свойство text
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Для новостей и статей возвращает короткий текст для анонса
     */
    public function getShortText(int $length = 100): string
    {
        if (strlen($this->text) > $length) {
            $substring = substr($this->text, 0, $length + 1);

            if (preg_match('/[a-zа-яёЁA-ZА-Я0-9]$/u', $substring)) {
                $length = strripos($substring, ' ');
            }

            return substr($substring, 0, $length) . '...';
        }

        return $this->text;
    }

    /**
     * Для статей и новостей администратор может изменять автора,
     * а для пользователя это поле заполняется автоматически
     */
    public function getAuthor(): string
    {
        $auth = new Authorization();

        if ($auth->isAdmin() && isset($this->author)) {
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

        if ($auth->isAdmin() && isset($this->author)) {
            return '"' . htmlspecialchars($this->data) . '"';
        }

        if ($auth->check()) {
            return '"' . date("Y-m-d") . '" readonly';
        }

        return '';
    }
}
