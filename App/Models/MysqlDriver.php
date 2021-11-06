<?php

class MysqlDriver implements DriverInterface
{
    private $dbh;
    private static $instance;

    public function __construct()
    {
        $connectParams = new Connect();

        try {
            $this->dbh = new PDO($connectParams->dsn, $connectParams->user, $connectParams->password);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnect();
        }
        return self::$instance->dbh;
    }

    /**
     * Поиск записи по идентификатору
     */
    public function findRecordByPK(string $table, string $model, int $id): ?object
    {
        $sql = "SELECT * FROM " . $table . " WHERE id = " . $id;
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchObject($model);
        if ($result) {
            return $result;
        }
        return null;
    }

    /**
     * Возвращает из базы записи в зависимости от параметров
     */
    public function findAll(string $table, string $model, string $filter = '', int $offset = 0, int $limit = 0): array
    {
        $sql = "SELECT * FROM " . $table;

        if ($filter) {
            $sql .= " WHERE CURDATE() - date <= 1 ";
        }

        if ($limit) {
            $sql .= " LIMIT " . $limit . " OFFSET " . ($offset - 1) * $limit;
        }

        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, $model);
    }

    /**
     * Подсчет записей в таблице
     */
    public function count(string $table): int
    {
        $sql = "SELECT COUNT(*) FROM " . $table;
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        return $sth->fetchColumn();
    }

    /**
     * Сохраняет объект в базе
     */
    public function save(object $record): int
    {
        $sql = "SHOW COLUMNS FROM " . $record->table;
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $colomns = $sth->fetchAll(PDO::FETCH_COLUMN);
        unset($colomns[array_search('id',$colomns)]);

        $primaryKey = '';

        if ($record->id) {
            $sql = "UPDATE ";
            $primaryKey = " WHERE id = " . $record->id;
        } else {
            $sql = "INSERT INTO ";
        }

        $sql .= $record->table . " SET ";

        foreach ($colomns as $colomn) {
            $sql .= $colomn . " = '" . $record->$colomn . "', ";
        }

        $sql = rtrim($sql, ", ");
        $sql .= $primaryKey;

        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        if (!$record->id) {
            return $this->dbh->lastInsertId();
        }

        return $record->id;
    }

    /**
     * Удаляет запись из базы
     */
    public function deleteRecord(object $record)
    {
        $sql = "DELETE FROM " . $record->table . " WHERE id = " . $record->id;
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
    }
}