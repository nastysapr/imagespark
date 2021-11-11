<?php
namespace App\Service;

use GlobIterator;
use PDO;
use PDOException;

class Seeder
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
    }
//цикл, сколько создать записей
    public function seed(): void
    {
        $directory = __DIR__ . '/../Database/seeds/';
        $iterator = new GlobIterator($directory . '*');

        foreach ($iterator as $fileName) {
            $className = str_replace('.php', '', $fileName->getFilename());
            (new $className())->seed();
        }
    }
}