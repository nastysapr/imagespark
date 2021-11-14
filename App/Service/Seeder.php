<?php

namespace App\Service;

use GlobIterator;
use PDO;
use PDOException;

class Seeder
{
    protected PDO $dbh;
    public string $directory = __DIR__ . '/../Database/seeds/';

    public function __construct()
    {
        $connectParams = new Connect();
        try {
            $this->dbh = new PDO($connectParams->dsn, $connectParams->user, $connectParams->password);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

//цикл, сколько создать записей
    public function seed(string $table, int $count): void
    {
        $className = ucfirst($table) . 'TableSeeder';
        require $this->directory . $className . '.php';
        (new $className())->seed($count);
    }
}