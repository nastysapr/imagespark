<?php

namespace App\Models;

use App\Service\Connect;
use Exception;
use PDO;
use PDOException;

class MysqlDriver implements DriverInterface
{
    private PDO $dbh;

    public function __construct()
    {
        $connectParams = new Connect();
        try {
            $this->dbh = new PDO($connectParams->dsn, $connectParams->user, $connectParams->password);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * Поиск записи по идентификатору
     */
    public function findRecordByPK(string $table, string $model, int $id): ?object
    {
        $sql = "SELECT * FROM " . $table . " WHERE id = :id";
        $sth = $this->dbh->prepare($sql);
        $sth->execute(["id" => $id]);

        $result = $sth->fetchObject($model);
        if ($result) {
            return $result;
        }
        return null;
    }

    /**
     * Возвращает из базы записи в зависимости от параметров
     */
//    public function findAll(string $table, string $model, string $filter = null, string $column = null, int $offset = 0, int $limit = 0): array
    public function findAll(string $table, string $model, string $filter = null, array $column = null, int $offset = 0, int $limit = 0): array
    {
        $sql = "SELECT * FROM " . $table;
        $bindParams = [];

        if ($filter === 'date') {
            $sql .= " WHERE CURDATE() - date <= 1 ";
        } elseif (!empty($column)) {
            $sql .= " WHERE ";
            foreach ($column as $key => $value) {
                $sql .= $key . " = :" . $key . " AND ";
                $bindParams[$key] = $value;
            }
            $sql = rtrim($sql, " AND ");
        }

        if ($limit) {
            $sql .= " LIMIT " . $limit . " OFFSET " . ($offset - 1) * $limit;
        }
        $sth = $this->dbh->prepare($sql);

        $sth->execute($bindParams);

        return $sth->fetchAll(PDO::FETCH_CLASS, $model);
    }

    /**
     * Подсчет записей в таблице
     */
    public function count(string $table, string $filter): int
    {
        $sql = "SELECT COUNT(*) FROM " . $table;
        if ($filter) {
            $sql .= " WHERE CURDATE() - date <= 1 ";
        }
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        return $sth->fetchColumn();
    }

    /**
     * Сохраняет объект в базе
     */
    public function save(Model $record): int
    {
        $sql = "SHOW COLUMNS FROM " . $record->table;
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $colomns = $sth->fetchAll(PDO::FETCH_COLUMN);
        unset($colomns[array_search('id', $colomns)]);

        $primaryKey = '';

        try {
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute (PDO::ATTR_EMULATE_PREPARES, false);
            $this->dbh->beginTransaction();
            $bindParams = [];

            if ($record->id) {
                $sql = "UPDATE ";
                $primaryKey = " WHERE id = :id";
                $bindParams["id"] = $record->id;
            } else {
                $sql = "INSERT INTO ";
            }

            $sql .= $record->table . " SET ";

            foreach ($colomns as $column) {
                $sql .= $column . " = :" . $column . ", ";
                $bindParams[$column] = $record->$column;
            }


            $sql = rtrim($sql, ", ");
            $sql .= $primaryKey;

            $sth = $this->dbh->prepare($sql);
            $sth->execute($bindParams);

            if (!$record->id) {
                $record->id = $this->dbh->lastInsertId();
            }

            $this->dbh->commit();
        } catch (Exception $exception) {
            $this->dbh->rollBack();
            echo $exception->getMessage();
        }

        return $record->id;
    }

    /**
     * Удаляет запись из базы
     */
    public function deleteRecord(object $record)
    {
        try {
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->beginTransaction();

            $sql = "DELETE FROM " . $record->table . " WHERE id = :id";
            $sth = $this->dbh->prepare($sql);
            $sth->execute(["id" => $record->id]);
            $this->dbh->commit();
        } catch (Exception $exception) {
            $this->dbh->rollBack();
            echo $exception->getMessage();
        }
    }
}