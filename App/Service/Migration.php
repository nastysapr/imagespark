<?php

namespace App\Service;

use App\Models\MysqlDriver;
use GlobIterator;
use PDO;
use PDOException;

class Migration
{
    protected PDO $dbh;
    public string $directory = __DIR__ . '/../Database/migrations/';

    public function __construct()
    {
        $connectParams = new Connect();
        try {
            $this->dbh = new PDO($connectParams->dsn, $connectParams->user, $connectParams->password);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }

        $this->isMigrationsTableExist();
    }

    public function isMigrationsTableExist()
    {
        $sql = "SHOW TABLES FROM ImageSpark";
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $tables = $sth->fetchAll(PDO::FETCH_COLUMN, 'Tables_in_ImageSpark');

        if (!in_array('migrations_table', $tables)) {
            $sql = "CREATE TABLE migrations_table(
                id int AUTO_INCREMENT,
                migration varchar(255) NOT NULL,
                PRIMARY KEY (id))";
            $this->execute($sql);
        }
    }

    /**
     * Создает класс для миграции
     */
    public function create(string $fileName): void
    {
        $className = $this->getClassName($fileName);

        $iterator = new GlobIterator($this->directory . '*');
        $ordinal = iterator_count($iterator) + 1;
        $fileName = 'm' . str_pad($ordinal, 3, 0, STR_PAD_LEFT) . '_' . $fileName;

        $template = file_get_contents(__DIR__ . '/../../config/migrationTemplate.php');
        $template = str_replace('Name', $className, $template);
        file_put_contents($this->directory . $fileName . '.php', $template);
    }

    /**
     * Возвращает название класса по имени миграции
     */
    public function getClassName(string $fileName): string
    {
        $className = explode('_', $fileName);
        $className = array_map((function ($item) {
            return ucfirst($item);
        }), $className);
        return implode('', $className);
    }

    /**
     * Вызывает исполнение непроведенных ранее миграций
     */
    public function migrate(): void
    {
        $iterator = new GlobIterator($this->directory . '*');

        foreach ($iterator as $fileName) {
            $migrationName = str_replace('.php', '', $fileName->getFilename());

            include $this->directory . $fileName->getFilename();

            $migrationName = str_replace('.php', '', $fileName->getFilename());
            $className = preg_split('/m[\d]*_/', $migrationName);
            $className = $this->getClassName(end($className));

            $sql = "SELECT * FROM migrations_table WHERE migration = :migration";
            $sth = $this->dbh->prepare($sql);
            $sth->execute(["migration" => $migrationName]);

            if (!$sth->fetch()) {
                if (!$this->execute((new $className())->up())) {
                    return;
                }

                $sql = "INSERT INTO migrations_table SET migration = :migration";
                $this->execute($sql, ["migration" => $migrationName]);
            }
        }
    }

    /**
     * Откатывает миграции (все\количество)
     */
    public function rollback(string $params): void
    {
        $sql = "DELETE FROM migrations_table WHERE migration = :migration";

        if ($params === 'all') {
            $params = (new MysqlDriver())->count('migrations_table', '');
            $this->execute($sql);
        }

        if (!is_numeric($params)) {
            echo "Error! Unexpected parameter";
            return;
        }

        if ($params > (new MysqlDriver())->count('migrations_table', '')) {
            echo "Error! Unexpected parameter";
            return;
        }

        for ($i = $params; $i > 0; $i--) {
            $migration = $this->getLastMigration();

            include $this->directory . $migration . '.php';

            $className = preg_split('/m[\d]*_/', $migration);
            $className = $this->getClassName(end($className));

            $this->execute((new $className())->down());
            $this->execute($sql, ["migration" => $migration]);
        }
    }

    /**
     * Выполняет sql-запрос
     */
    public function execute(string $sql, array $params = null): bool
    {
        $sth = $this->dbh->prepare($sql);

        return $sth->execute($params);
    }

    /**
     * Возвращает название последней миграции
     */
    public function getLastMigration(): string
    {
        $sql = "SELECT * FROM migrations_table ORDER BY ID DESC LIMIT 1";
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $migration = $sth->fetch();
        return $migration['migration'];
    }
}