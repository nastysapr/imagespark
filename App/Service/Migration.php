<?php

class Migration
{
    protected PDO $dbh;

    public function __construct()
    {
        $connectParams = new Connect();
        try {
            $this->dbh = new PDO($connectParams->dsn, $connectParams->user, $connectParams->password);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }

        $sql = "SELECT 1 from migrations_table";
        if (!$this->execute($sql)) {
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
        $directory = __DIR__ . '/../database/migrations/';
        $iterator = new GlobIterator($directory . '*');
        $ordinal = iterator_count($iterator) + 1;
        $fileName = 'm' . str_pad($ordinal, 3, 0, STR_PAD_LEFT) . '_' . $fileName;

        $template = file_get_contents(__DIR__ . '/../../config/migrationTemplate.php');
        $template = str_replace('Name', $fileName, $template);
        file_put_contents($directory . $fileName . '.php', $template);
    }

    /**
     * Вызывает исполнение непроведенных ранее миграций
     */
    public function migrate()
    {
        $directory = __DIR__ . '/../database/migrations/';
        $iterator = new GlobIterator($directory . '*');

        foreach ($iterator as $fileName) {
            $migrationName = str_replace('.php', '', $fileName->getFilename());
            $sql = "SELECT * FROM migrations_table WHERE migration = :migration";
            $sth = $this->dbh->prepare($sql);
            $sth->execute(["migration" => $migrationName]);

            if (!$sth->fetch()) {
                if (!(new $migrationName())->up()) {
                    die;
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
            $params = (new MysqlDriver())->count('migrations_table');
            $this->execute($sql);
        }

        if (!is_numeric($params)) {
            echo "Error! Unexpected parameter";
            die;
        }

        for ($i = $params; $i > 0; $i--) {
            $migration = $this->getLastMigration();
            (new $migration())->down();
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